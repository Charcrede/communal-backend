<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Exemple de contrôle : vérifier si l'utilisateur est super admin
        if (!auth()->check() || auth()->user()->role !== 'super_admin') {
            abort(403, 'Accès refusé');
        }
        return $next($request);
    }
}
