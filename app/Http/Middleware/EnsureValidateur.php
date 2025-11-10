<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidateur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $role = $user->role;

        // Super Admin ou rôle ValideAudit
        if (!$role || (!$role->SuperAdmin && $role->nom !== 'ValideAudit')) {
            abort(403, 'Accès réservé aux validateurs.');
        }

        return $next($request);
    }
}
