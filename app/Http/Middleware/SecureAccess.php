<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class SecureAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Validar que el header Authorization exista
        $header = $request->header('Authorization');
        if (!$header) {
            return response()->json(['error' => 'Token no encontrado'], 401);
        }

        // Extraer el token del header (Bearer ...)
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        } else {
            return response()->json(['error' => 'Formato de token inválido'], 401);
        }

        // Buscar el token en la base de datos de Sanctum
        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        // Autenticar el usuario manualmente
        $user = $accessToken->tokenable;
        Auth::login($user);

        return $next($request);
    }
}