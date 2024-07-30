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
use App\Repositories\RegistrationRepository;
use App\Http\Requests\Auth\SocialiteSignUpRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{ 

    protected $repo;

    public function __construct(RegistrationRepository $repo)
    {
        $this->repo = $repo;
    }
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

            if (!$user):
                $user   = User::create([
                            'first_name'            => $googleUser->name,
                            'email'                 => $googleUser->email,
                            'email_verified_at'     => date('Y-m-d H:i:s'),
                        ]);
            endif;

            Auth::login($user, true);


            DB::commit();
            return redirect()->route('client.dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google.');
        }
    }

    public function create()
    {
        
        return view('backend.admin.auth.social_register');
    }

    public function store(SocialiteSignUpRequest $request)
    {
        $result                     = $this->repo->signUp($request);

        if ($result['success']):

            $message                = __('registration_successfuly');
            $route                  = route('whatsapp.verify', $result['user']['token']);

            if ($request->ajax()):
                return response()->json(['success' => true, 'message' => $message, 'route' => $route]);
            else:
                return redirect()->route('whatsapp.verify', $result['user']['token'])->with('success', $message);
            endif;
        else:
            if ($request->ajax()):
                return response()->json(['error' => $result['message']], 500);
            else:
                Toastr::error($result['message']);
                return redirect()->back()->withErrors([$result['message']]);
            endif;
        endif;
    }

}

