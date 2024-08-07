<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\OneSignalToken;
use App\Models\User;
use App\Models\Template;
use Carbon\Carbon;
use App\Traits\ImageTrait;
use App\Traits\SendMailTrait;
use App\Providers\RouteServiceProvider;
use App\Traits\DnsTrait;
use App\Traits\ServerTrait;
use App\Traits\WhatsAppTrait;
use Illuminate\Support\Str;
use App\Models\Domain;
use App\Models\Server;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    use ImageTrait, SendMailTrait, DnsTrait, ServerTrait, WhatsAppTrait;

    protected $emailTemplate;

    public function __construct(EmailTemplateRepository $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    public function index($data)
    {
        if (! arrayCheck('paginate', $data)) {
            $data['paginate'] = setting('pagination');
        }

        return User::paginate($data['paginate']);
    }

    public function store($data)
    {
        if (arrayCheck('image', $data)) {
            $data['image'] = $this->getImageWithRecommendedSize($data['image'], 260, 175);
        }
        $data['password'] = bcrypt($data['password']);

        return User::create($data);
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function totalUser()
    {
        return User::all()->whereNotIn('user_type', ['admin'])->count();
    }

    public function update($request, $id)
    {
        $user   = User::findOrFail($id);

        if (arrayCheck('image', $request)) {
            $requestImage      = $request['image'];
            $response          = $this->saveImage($requestImage, '_user_');
            $request['images'] = $response['images'];
        }
        if (arrayCheck('password', $request)) {
            $request['password'] = bcrypt($request['password']);
        }
        $user->update($request);

     

        if (auth()->user()->user_type == 'client-staff') {
            $client = Client::findOrFail($user->client_id);
            $client->update($request);
            $staff           = $user->client_staff;
            $request['slug'] = getSlug('clients', $user->name, 'slug', $staff->id);

            return $staff->update($request);
        }

    }

    public function destroy($id): int
    {
        return User::destroy($id);
    }

    public function findByEmail($mail)
    {
        return User::where('email', $mail)->first();
    }

    public function searchUsers($relation, $data): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return User::with($relation)->when(arrayCheck('search', $data), function ($query) use ($data) {
            $query->where('name', 'like', '%'.$data['search'].'%');
        })->when(arrayCheck('role_id', $data), function ($query) use ($data) {
            $query->where('role_id', $data['role_id']);
        })->when(arrayCheck('status', $data), function ($query) use ($data) {
            $query->where('status', $data['status'])->where('is_user_banned', 0)->where('is_deleted', 0);
        })->when(arrayCheck('ids', $data), function ($query) use ($data) {
            $query->whereIn('id', $data['ids']);
        })->when(arrayCheck('role_id', $data) && $data['role_id'] == 2, function ($query) use ($data) {
            $query->whereHas('instructor.organization', function ($query) use ($data) {
                $query->when(arrayCheck('organization_id', $data), function ($query) use ($data) {
                    $query->where('id', $data['organization_id']);
                });
            });
        })->when(arrayCheck('instructor_student', $data), function ($query) use ($data) {
            $query->whereHas('checkout', function ($query) use ($data) {
                $query->whereHas('enrolls', function ($query) use ($data) {
                    $query->whereIn('enrollable_id', $data['total_course'])->where('enrollable_type', Course::class);
                });
            });
        })->latest()->paginate($data['paginate']);
    }

    public function findUsers($data, $relation = [])
    {
        return User::with($relation)->when(arrayCheck('role_id', $data) && $data['role_id'] == 2, function ($query) {
            $query->where('role_id', 2)->whereHas('instructor.organization');
        })->when(arrayCheck('role_id', $data) && ! is_array($data['role_id']), function ($query) use ($data) {
            $query->where('role_id', $data['role_id']);
        })->when(arrayCheck('role_id', $data) && is_array($data['role_id']), function ($query) use ($data) {
            $query->whereIn('role_id', $data['role_id']);
        })->when(arrayCheck('q', $data), function ($query) use ($data) {
            $query->where(function ($query) use ($data) {
                $query->where('first_name', 'like', '%'.$data['q'].'%')->orWhere('last_name', 'like', '%'.$data['q'].'%')
                    ->orWhere('email', 'like', '%'.$data['q'].'%')->orWhere('phone', 'like', '%'.$data['q'].'%');
            });
        })->when(arrayCheck('ids', $data), function ($query) use ($data) {
            $query->whereIn('id', $data['ids']);
        })->when(arrayCheck('status', $data), function ($query) use ($data) {
            $query->where('status', $data['status'])->where('is_user_banned', 0)->where('is_deleted', 0);
        })->where('role_id', '!=', 1)->when(arrayCheck('take', $data), function ($query) use ($data) {
            $query->take($data['take']);
        })->when(arrayCheck('onesignal', $data), function ($query) {
            $query->where('is_onesignal_subscribed', 1);
        })->when(arrayCheck('organization_id', $data), function ($query) use ($data) {
            $query->whereHas('instructor', function ($query) use ($data) {
                $query->where('organization_id', $data['organization_id']);
            });
        })->get();
    }

    public function statusChange($request)
    {
        $id            = $request['id'];
        $status        = $request['status'];
        $staff         = User::findOrfail($id);
        $staff->status = $status;
        $staff->save();

        return true;
    }

    public function userVerified($token)
    {
        $staff = User::where('token', $token)->first();
        $data  = [
            'user'            => $staff,
            'email_templates' => $this->emailTemplate->welcomeMail(),
            'template_title'  => 'Welcome Email',
        ];


        if (! empty($staff->email_verified_at)) {
            $staff->email_verified_at = null;
            $staff->save();
            $data                     = [
                'status'  => true,
                'message' => __('verified_remove_successful'),
            ];

            return $data;
        } else {

            $staff->email_verified_at = date('Y-m-d H:i:s');
            $this->sendmail($staff->email, 'emails.template_mail', $data);
            $staff->save();
            $data                     = [
                'status'  => true,
                'message' => __('verified_this_successful'),
            ];

            return $data;
        }
    }

    public function userBan($id)
    {
        $staff = user::findOrfail($id);
        if ($staff->is_user_banned == 0) {
            $staff->is_user_banned = 1;
            $staff->save();
            $data                  = [
                'status'  => true,
                'message' => __('successfully_banned_this_person'),
            ];
        } else {
            $staff->is_user_banned = 0;
            $staff->save();
            $data                  = [
                'status'  => true,
                'message' => __('active_this_successful'),
            ];
        }

        return $data;
    }

    public function userDelete($id)
    {
        $staff = user::findOrfail($id);
        if ($staff->is_deleted == 0) {
            $staff->is_deleted = 1;
            $staff->save();
            $data              = [
                'status'  => true,
                'message' => __('delete_successful'),
            ];
        } else {
            $staff->is_deleted = 0;
            $staff->save();
            $data              = [
                'status'  => true,
                'message' => __('restore_successful'),
            ];
        }

        return $data;
    }

    public function oneSignalSubscription($data)
    {
        $current_id  = $data['current']['id'];
        $previous_id = $data['previous']['id'];
        $token       = OneSignalToken::where('subscription_id', $previous_id)->first();
        if ($token) {
            $token->update([
                'subscription_id' => $current_id,
                'token'           => $data['current']['token'],
            ]);
        } else {
            $if_exists_current = OneSignalToken::where('subscription_id', $current_id)->first();
            if (! $if_exists_current) {
                $token = OneSignalToken::create([
                    'client_id'       => auth()->user()->client_id,
                    'subscription_id' => $current_id,
                    'token'           => $data['current']['token'],
                ]);
            }
        }

        return $token;
    }

    public function sendWhatsappOtp($request)
    {
        try {
            $otp        = rand(100000, 999999);
            $user       = User::where('token', $request->token)->first();
            $response   = $this->sendWAOtp(
                $request->phone,
                $otp
            );

            if ($user) {
                $user->whatsapp_otp             = $otp;
                $user->whatsapp_otp_expired_at  = \Carbon\Carbon::now()->addMinute(5);
                $user->phone                    = $request->phone;
                $user->save();
                return ['success' => true, 'message' => __('otp_sent_successfully_check_your_whatsapp')];
            } else {
                return ['success' => false, 'message' => __('user_not_found')];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }





    public function confirmWhatsappOtp($request)
    {
        try {
            $user                   = User::where('token', $request->token)->first();

            if ($user->whatsapp_otp !== $request->otp):
                return [
                    'success'       => false,
                    'message'       => __('your_otp_is_incorrect')
                ];
            endif;

            $now                    = now();
            $formatted_present_time = $now->format('Y-m-d H:i:s');

            if($formatted_present_time>$user->whatsapp_otp_expired_at):
                return [
                    'success'       => false,
                    'message'       => __('your_session_is_expired'),
                ];
            endif;

            $user->whatsapp_verify_at = now();
            $user->save();


            if($user->email_verified_at !== null && $user->whatsapp_verify_at !== null):
                $client = Client::where('id', $user->client_id)->first();
                if($user):
                    $result = $this->dnsAdd($client->domain);
                    if (!$result['success']):
                        return ['success' => false, 'message' => $result['message']];
                    else:
                        $domain                             = new Domain;
                        $domain->script_deployed            = 1;
                        $domain->ssl_active                 = 1;
                        $server                             = Server::where('default', 1)->first();
                        $domain->client_id                  = $client->id;
                        $domain->server_id                  = $server->id;
                        $domain->sub_domain                 = $client->domain;
                        $uid                                = strtolower(Str::random(4));
                        $domain->sub_domain_user            = strtolower("delix-".$client->domain. $uid);
                        $domain->sub_domain_password        = Str::random(20);
                        $domain->sub_domain_db_name         = strtolower("db" . $uid . "db");
                        $domain->sub_domain_db_user         = strtolower("db" . $uid . "db");
                        $domain->sub_domain_db_password     = Str::random(20);
                        $uid                                = strtolower(Str::random(4));
                        $domain->custom_domain              = "";
                        $domain->custom_domain_user         = strtolower("delix-".$client->domain. $uid);
                        $domain->custom_domain_password     = Str::random(20);
                        $domain->custom_domain_db_name      = strtolower("db" . $uid . "db");
                        $domain->custom_domain_db_user      = strtolower("db" . $uid . "db");
                        $domain->custom_domain_db_password  = Str::random(20);
    

                        $domain_info['server_id']               = $server->id;
                        $domain_info['domain_name']             = $domain->sub_domain.'.delix.cloud';
                        $domain_info['site_user']               = $domain->sub_domain_user;
                        $domain_info['site_password']           = $domain->sub_domain_password;
                        $domain_info['database_name']           = $domain->sub_domain_db_name;
                        $domain_info['database_user']           = $domain->sub_domain_user;
                        $domain_info['database_password']       = $domain->sub_domain_password;
                        $domain_info['admin_key']               = strtolower(Str::random(24));
                        $domain_info['client_key']              = strtolower(Str::random(24));
                        $domain_info['database_password']       = $domain->sub_domain_password;
                        $domain_info['ssl_active']              = ($domain->ssl_active == 1) ? true:false;
                        $result                                 = $this->deployScript($domain_info);
                        if ($result['success']):
                            $domain->script_deployed            = 1;
                        endif;
                        $domain->save();
    
                        if (!$result['success']):
                            return ['success' => false, 'message' => $result['message']];
                        endif;
                    endif;
                endif;
            endif;

            Auth::login($user);

            return [
                'success' => true,
                'message' => __('otp_verified_successfully'),
                'url'     => route('client.dashboard') 
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' =>  $e->getMessage(),
                'error'   => $e->getMessage()
            ];
        }
    }



}
