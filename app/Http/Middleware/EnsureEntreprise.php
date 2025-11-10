<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEntreprise
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $entrepriseId = session('entreprise_id');

        if (!$entrepriseId) {
            return redirect()->route('dashboard')
                ->with('error', 'Aucune entreprise n\'est assignée à votre compte.');
        }

        return $next($request);
    }
}
