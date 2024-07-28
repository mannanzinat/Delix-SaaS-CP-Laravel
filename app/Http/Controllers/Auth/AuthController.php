<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\PasswordRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ImageTrait;
use App\Traits\SendMailTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use ImageTrait, SendMailTrait;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function forgotPassword()
    {
        return view('auth.forget_password');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            try {
                $check_user_status = userAvailability($user);

                if (! $check_user_status['status']) {
                    Toastr::error($check_user_status['message']);

                    return back();
                }
                DB::table('password_resets')->where('email', $user->email)->delete();
                $token             = Str::random(64);
                $link              = url('/').'/password/reset/'.$token.'?email='.urlencode($user->email);

                DB::table('password_resets')->insert([
                    'email'      => $request->email,
                    'token'      => $token,
                    'created_at' => Carbon::now(),
                ]);

                // Create a PasswordRequest record (assuming this is a custom model)
                // Not necessary if you're not using it elsewhere
                // PasswordRequest::where('user_id', $user->id)->delete();
                // PasswordRequest::create([
                //     'user_id' => $user->id,
                //     'otp'     => $token,
                // ]);

                // Prepare data for email template
                $data              = [
                    'token'          => $token,
                    'user'           => $user,
                    'reset_link'     => $link,
                    'template_title' => 'password_reset',
                ];
                if(isMailSetupValid()){
                    $this->sendmail($request->email, 'emails.template_mail', $data);
                }

                Toastr::success(__('receive__mail_password_hints'));

                return redirect()->back();
            } catch (Exception $e) {
                Toastr::warning(__('An error occurred while processing your request.'));

                return redirect()->back();
            }
        } else {
            Toastr::warning(__('user_not_found'));

            return redirect()->back();
        }
    }

    public function showResetPasswordForm($token)
    {
        $resetRecord = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (! $resetRecord) {
            Toastr::error('Invalid token!');

            return redirect()->route('login');
        }

        return view('auth.forgetPasswordLink', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'email'                 => 'required|email|exists:users',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);

            $resetRecord    = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (! $resetRecord) {
                return back()->withInput()->with('error', 'Invalid token!');
            }

            $user           = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_resets')->where('email', $request->email)->delete();

            $data           = [
                'user'           => $user,
                'login_link'     => url('/login'),
                'template_title' => 'recovery_mail',
            ];
            if(isMailSetupValid()){
            $this->sendmail($request->email, 'emails.template_mail', $data);
            }

            Toastr::success(__('successfully_password_changed'));

            return redirect('/login')->with('message', 'Your password has been changed!');
        } catch (\Exception $e) {

            // dd($e->getMessage());
            // Log the exception or handle it accordingly
            Toastr::error('An error occurred while processing your request.');

            return redirect()->back();
        }
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:32|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            $otp  = PasswordRequest::where('otp', $request->otp)->where('user_id', $user->id)->latest()->first();
            if ($otp) {
                $data           = [
                    'user'           => $user,
                    'login_link'     => url('/login'),
                    'template_title' => 'recovery_mail',
                ];
                if(isMailSetupValid()){
                    $this->sendmail($request->email, 'emails.template_mail', $data);
                }
                $user->password = bcrypt($request->password);
                $user->save();
                $otp->delete();
                Toastr::success(__('successfully_password_changed'));

                return $this->logout($request);
            } else {
                Toastr::warning(__('please_request_another_code'));

                return redirect()->back();
            }
        } catch (Exception $e) {
            Toastr::warning(__($e->getMessage()));

            return redirect()->back();
        }
    }

    public function verified($token): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {

            $user           = User::where('token', $token)->first();
            $today          = Carbon::now()->setTimezone('UTC');
            $tokenExpiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $user->token_expired_at, 'UTC');

            if ($today->gt($tokenExpiredAt)) {
                Toastr::error(__('your_session_is_expired'));
                return back();
            }


            $response       = $this->userRepository->userVerified($token);

            return redirect()->route('whatsapp.verify', $user->token);

        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();  
        }
    }

    public function activation($email, $code)
    {
        $user       = User::whereEmail($email)->first();
        $activation = Activation::where([['code', $code], ['user_id', $user->id]])->first();
        if ($activation) {
            if ($activation->completed == 1) {
                Toastr::success(__('your_account_has_been_already_activated'));

                return redirect()->route('login');
            } else {
                try {
                    DB::beginTransaction();
                    $activation->completed   = 1;
                    $activation->save();
                    $user->email_verified_at = now();
                    $user->status            = 1;
                    $user->save();
                    $data                    = [
                        'user'           => $user,
                        'login_link'     => url('/login'),
                        'template_title' => 'welcome_email',
                    ];
                    if(isMailSetupValid()){
                        $this->sendmail($email, 'emails.template_mail', $data);
                    }
                    DB::commit();
                    Toastr::success(__('your_account_is_active_now'));

                    return redirect()->route('login');
                } catch (Exception $e) {
                    DB::rollBack();
                    Toastr::success(__($e->getMessage()));

                    return redirect()->route('login');
                }
            }
        } else {
            Toastr::error(__('please_check_your_credential'));

            return redirect()->route('login');
        }
    }

    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function changePasswordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => 'required|min:6|max:32|confirmed',
        ]);
        $user = $this->userRepository->findByEmail(auth()->user()->email);
        if (Hash::check($request->current_password, $user->password)) {
            try {
                $user->password = bcrypt($request->password);
                $user->save();
                Toastr::success(__('successfully_password_changed'));

                return $this->logout($request);
            } catch (Exception $e) {
                Toastr::warning(__($e->getMessage()));

                return redirect()->back();
            }
        } else {
            Toastr::warning(__('sorry_old_password_not_match'));

            return redirect()->back();
        }
    }

    public function whatsappVerify($token)
    {
        return view('backend.admin.auth.verify', compact('token'));
    }

    public function whatsappOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'unique:users,phone'],
        ]);

        try {
            return $this->userRepository->sendWhatsappOtp($request);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => __('something_went_wrong_please_try_again')], 500);
        }
    }
    public function whatsappOtpConfirm(Request $request)
    {
        $request->validate([
            'otp'   => 'required',
        ]);

        try {
            $result = $this->userRepository->confirmWhatsappOtp($request);

            if ($result['success']):
                return response()->json([
                    'message' => $result['message'],
                    'url'     => url($result['url']),
                    'success' => true
                ], 200);
            else:
                return response()->json(['message' => $result['message']], 400);
            endif;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('something_went_wrong_please_try_again'),
                'error'   => $e->getMessage()
            ], 500);
        }
    }




}
