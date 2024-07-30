<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user):
            return redirect()->route('login');
        endif;
        if ($user->client_id !== null && $user->whatsapp_verify_at !== null):

            return $next($request);
        elseif ($user->client_id !== null):

            return redirect()->route('whatsapp.verify', $user->token);
        else:

            return redirect()->route('social.register');
        endif;
    }
}
