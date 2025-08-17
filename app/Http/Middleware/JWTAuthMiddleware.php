<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Intentar obtener el token desde Authorization: Bearer o desde cookie 'jwt_token'
        $token = $request->bearerToken();
        if (!$token) {
            // Primero intenta desde la bolsa de cookies de Laravel
            $token = $request->cookie('jwt_token');
        }
        if (!$token && isset($_COOKIE['jwt_token'])) {
            // Fallback: si EncryptCookies eliminó la cookie no cifrada, léela directo del superglobal
            $token = $_COOKIE['jwt_token'];
        }

        if (!$token) {
            if ($request->expectsJson() || str_starts_with($request->path(), 'api')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de autenticación requerido'
                ], 401);
            }
            return redirect()->route('auth.login')->with('error', 'Debes iniciar sesión.');
        }

        $payload = AuthController::verificarJWT($token);
        
        if (!$payload) {
            if ($request->expectsJson() || str_starts_with($request->path(), 'api')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o expirado'
                ], 401);
            }
            return redirect()->route('auth.login')->with('error', 'Tu sesión ha expirado.');
        }

        // Agregar información del usuario a la request
        $request->merge(['user_id' => $payload['user_id']]);
        $request->merge(['user_email' => $payload['correo']]);

        return $next($request);
    }
}
