<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class LogoutCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Sentinel::check()) {
            $user = Sentinel::getUser();
            \Log::info('User authenticated. User type: ' . $user->user_type);

            switch ($user->user_type) {
                case 'merchant':
                    return redirect()->route('merchant.dashboard');
                case 'merchant_staff':
                    return redirect()->route('merchant.staff.dashboard');
                case 'sas_admin':
                case 'sas_admin_staff':
                    return redirect()->route('sas.admin.dashboard');
                case 'courier_admin':
                case 'courier_admin_staff':
                    return redirect()->route('courier.admin.dashboard');
                default:
                    \Log::warning('Unknown user type: ' . $user->user_type);
                break;
            }
        }

        return $next($request);
    }


}
