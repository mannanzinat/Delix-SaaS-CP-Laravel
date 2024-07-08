<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\GetUserBrowser;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use GetUserBrowser;

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

        return view('backend.admin.auth.login');
    }

    public function store(LoginRequest $request)
    {
        if ($this->activityLog($request)) {
            $request->authenticate();
            $request->session()->regenerate();

            if (Auth::user()->role_id == 1) {
                $url = RouteServiceProvider::ADMIN;
            }
            if (Auth::user()->role_id == 2) {
                $url = RouteServiceProvider::ADMIN;
            }

            if (Auth::user()->role_id == 3) {
                $url = RouteServiceProvider::CLIENT;
            }

            return redirect()->intended($url);
        }

        return redirect()->back();
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(url('/'));
    }

    public function login_as(Request $request, $id): RedirectResponse
    {
        $admin_id = Auth::id();
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        Auth::loginUsingId($id, $remember = true);
        session()->put('admin_id', $admin_id);
        Toastr::success(__('login_as_client_successfully'));

        return redirect()->route('client.dashboard');
    }

    public function back_to_admin(Request $request): RedirectResponse
    {
        $admin_id = session()->pull('admin_id');
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        Auth::loginUsingId($admin_id, $remember = true);
        Toastr::success(__('back_to_admin_panel_successfully'));
        if (Auth::user()->role_id == 1) {
            $url = RouteServiceProvider::ADMIN;
        }

        if (Auth::user()->role_id == 3) {
            $url = RouteServiceProvider::CLIENT;
        }

        return redirect(url($url));
    }

    public function activityLog(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (blank($user)) {

            Toastr::error(__('user_not_found'));

            return redirect()->back();
        } else {
            if ($user->status == 0) {
                Toastr::error(__('your_account_is_inactive'));

                return false;
            } elseif ($user->is_deleted == 1) {
                Toastr::error(__('you_account_has_been_deleted'));

                return false;
            } else {
                try {
                    $log             = [];
                    $log['url']      = $request->fullUrl();
                    $log['method']   = $request->method();
                    $log['ip']       = $request->ip();
                    $log['browser']  = $this->getBrowser($request->header('user-agent'));
                    $log['platform'] = $this->getPlatForm($request->header('user-agent'));
                    $log['user_id']  = $user->id;
                    ActivityLog::create($log);

                    return true;
                } catch (Exception $e) {
                    Toastr::error('something_went_wrong_please_try_again');

                    return redirect()->back();
                }
            }
        }
    }
}
