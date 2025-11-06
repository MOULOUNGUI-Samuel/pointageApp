<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Le nom standard pour l'en-tête de la clé d'API
        $apiKeyHeader = 'X-API-KEY';

        $apiKey = $request->header($apiKeyHeader);
        $validApiKey = config('app.api_key'); // Nous allons la définir dans config/app.php

        // Vérifier que la clé est présente et valide
        if (!$apiKey || $apiKey !== $validApiKey) {
            return response()->json(['message' => 'Unauthorized. Invalid API Key.'], 401);
        }

        return $next($request);
    }
}