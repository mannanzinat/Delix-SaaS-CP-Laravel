<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class LoginCheckMerchant
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

        if (Sentinel::check()) :
            if(Sentinel::getUser()->user_type == 'merchant'){
                return redirect()->route('merchant.dashboard');
            }elseif(Sentinel::getUser()->user_type == 'merchant_staff'){
                return redirect()->route('merchant.staff.dashboard');
            }elseif(Sentinel::getUser()->user_type == 'sas_admin' || Sentinel::getUser()->user_type == 'sas_admin_staff'){
                // return $next($request);
                return redirect()->route('sas.admin.dashboard');
            }elseif(Sentinel::getUser()->user_type == 'courier_admin' || Sentinel::getUser()->user_type == 'courier_admin_staff'){
                return redirect()->route('courier.admin.dashboard');
            }
        endif;
        return redirect()->route('login');

    }
}
