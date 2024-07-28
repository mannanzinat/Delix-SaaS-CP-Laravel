<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Client;
use App\Models\ClientStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{ 
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
 
    public function handleGoogleCallback()
    {
        DB::beginTransaction();
        try {
            $googleUser                     = Socialite::driver('google')->stateless()->user();
            $user                           = User::where('email', $googleUser->email)->first();
            $role                           ='';
            $role                           = DB::table('roles')->where('slug', 'Client-staff')->select('id', 'permissions')->first();
            $permissions                    = json_decode($role->permissions, true);


            if (!$user):
                $client                     = new Client();
                $client->first_name         = $googleUser->name;
                $client->save();
            endif;

            if (!$user):
                $user   = User::create([
                            'first_name'    => $googleUser->name,
                            'email'         => $googleUser->email,
                            'role_id'       => $role->id,
                            'user_type'     => 'client-staff',
                            'client_id'     => $client->id,
                            'permissions'   => $permissions,
                            'status'        => 1,
                            'password'      => \Hash::make(rand(100000,999999)),
                        ]);
            endif;

            if (!$user):
                $staff                       = new ClientStaff();
                $staff->user_id              = $user->id;
                $staff->client_id            = $client->id;
                // $staff->slug              = getSlug('clients', $client->company_name);
                $staff->save();
            endif;

            Auth::login($user, true);
            DB::commit();
            return redirect()->route('client.dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google.');
        }
    }

}

