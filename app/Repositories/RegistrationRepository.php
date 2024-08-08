<?php

namespace App\Repositories;
use App\Models\Activation;
use App\Models\Client;
use App\Models\Domain;
use App\Models\ClientStaff;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\TimeZoneService;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon;
class RegistrationRepository
{

    use SendNotification;

    public function signUp($request)
    {
        DB::beginTransaction();
        try {
            $role                       = DB::table('roles')->where('slug', 'Client-staff')->select('id', 'permissions')->first();
            if (!$role):
                throw new \Exception('Role not found');
            endif;

            $permissions                    = json_decode($role->permissions, true);
            $timeZoneService                = app(TimeZoneService::class)->execute($request);

            $client                         = new Client();
            $client->first_name             = $request->first_name;
            $client->last_name              = $request->last_name ?? '';
            $client->company_name           = $request->company_name;
            $client->domain                 = $request->domain;
            $client->timezone               = $timeZoneService['timezone'] ?? setting('time_zone');
            $client->webhook_verify_token   = Str::random(30);
            $client->api_key                = Str::random(30);
            $client->slug                   = getSlug('clients', $request['company_name']);
            $client->save();

            $user                           = User::firstOrNew(['email' => $request->email]);
            if($request->password):
                $user->password                 = Hash::make($request->password);
            endif;
            $user->first_name               = $request->first_name;
            $user->last_name                = $request->last_name ?? '';
            $user->role_id                  = $role->id;
            $user->email                    = $request->email;
            $user->user_type                = 'client-staff';
            $user->client_id                = $client->id;
            $user->permissions              = $permissions;
            $user->status                   = 1;
            $user->hear_about_delix         = $request->hear_about_delix ?? '';
            $user->token                    = Str::random(40);
            $user->token_expired_at         = \Carbon\Carbon::now()->addHours(1);
            $user->save();

            $staff                          = new ClientStaff();
            $staff->user_id                 = $user->id;
            $staff->client_id               = $client->id;
            $staff->slug                    = getSlug('clients', $client->company_name);
            $staff->save();

            DB::commit();

            return ['success' => true, 'user' => $user];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'something_went_wrong_please_try_again'];
        }
    }



}