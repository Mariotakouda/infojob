<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Vérifie que l'utilisateur connecté possède l'un des rôles autorisés.
     *
     * Usage dans les routes :
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,recruteur')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
