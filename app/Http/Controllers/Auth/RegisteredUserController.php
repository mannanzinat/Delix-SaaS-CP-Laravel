<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\Models\Activation;
use App\Models\Client;
use App\Models\ClientStaff;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\EmailTemplateRepository;
use App\Services\TimeZoneService;
use App\Traits\SendMailTrait;
use App\Traits\SendNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    use SendMailTrait, SendNotification;

    protected $emailTemplate;

    public function __construct(EmailTemplateRepository $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    public function create()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                $url = RouteServiceProvider::ADMIN;
            } else {
                $url = RouteServiceProvider::CLIENT;
            }

            return redirect(url($url));
        }

        return view('backend.admin.auth.register');
    }

    public function store(SignUpRequest $request)
    {
        DB::beginTransaction();
        try {
            $role                         = DB::table('roles')->where('slug', 'Client-staff')->select('id', 'permissions')->first();
            $permissions                  = json_decode($role->permissions, true);

            $timeZoneService              = app(TimeZoneService::class)->execute($request);
            $client                       = new Client();
            $client->first_name           = $request->first_name;
            $client->last_name            = $request->last_name;
            $client->company_name         = $request->company_name;
            $client->domain               = $request->domain;
            $client->timezone             = $timeZoneService['timezone'] ?? setting('time_zone');
            $client->webhook_verify_token = Str::random(30);
            $client->api_key              = Str::random(30);
            $client->slug                 = getSlug('clients', $request['company_name']);
            $client->save();

            $user                         = new User();
            $user->password               = Hash::make($request->password);
            $user->first_name             = $request->first_name;
            $user->last_name              = $request->last_name;
            $user->role_id                = $role->id;
            $user->email                  = $request->email;
            $user->user_type              = 'client-staff';
            $user->client_id              = $client->id;
            $user->permissions            = $permissions;
            $user->status                 = 1;
            $user->save();

            $staff                        = new ClientStaff();
            $staff->user_id               = $user->id;
            $staff->client_id             = $client->id;
            $staff->slug                  = getSlug('clients', $client->company_name);
            $staff->save();
          
            $link                         = route('user.verified', $user->id);
            $template_data                = $this->emailTemplate->emailConfirmation();
            $data = [
                'confirmation_link'     => $link,
                'user'                  => $user,
                'subject'               => $template_data->subject ?? __('welcome'),
                'email_templates'       => $template_data,
                'template_title'        => 'Email Confirmation',
            ];

            try {
                $this->sendmail($request->email, 'emails.template_mail', $data);
            } catch (\Exception $e) {
                \Log::error('Error sending email: ' . $e->getMessage());
            }

            DB::commit();
            if ($request->ajax()) {
                return response()->json(['message' => __('registration_successful')]);
            } else {
                Toastr::success(__('registration_successful'));
            }

            return redirect()->route('login');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('something_went_wrong_please_try_again');

            return redirect()->back()->withErrors(['something_went_wrong_please_try_again']);
        }
    }

    public function emailConfirmation(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (setting('disable_email_confirmation') != 1) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $data['user_id'] = $user->id;
            $data['code']    = Str::random(32);
            $activation      = Activation::create($data);
            $data            = [
                'user'              => $user,
                'user_id'           => $user->id,
                'code'              => $activation->code,
                'confirmation_link' => url('/').'/activation/'.$request->email.'/'.$activation->code,
                'template_title'    => 'email_confirmation',
            ];
            $this->sendmail($request->email, 'emails.template_mail', $data);
            Toastr::success(__('user_register_hints'));

            return redirect()->route('login');
        } else {
            return redirect()->route('login');
        }
    }
}
