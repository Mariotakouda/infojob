<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * 2FA requis uniquement pour admin et recruteur.
     * Les citoyens passent directement sans vérification OTP.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            in_array($user->role, ['admin', 'recruteur']) &&
            ! $request->session()->get('two_factor_verified') &&
            ! $request->routeIs('two-factor.*') &&
            ! $request->routeIs('logout')
        ) {
            return redirect()->route('two-factor.show');
        }

        return $next($request);
    }
}