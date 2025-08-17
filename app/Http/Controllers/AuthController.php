<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function registro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo|max:255',
            'clave' => 'required|string|min:6|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'clave' => $request->clave // Se hashea automáticamente en el modelo
            ]);

            $token = $this->generarJWT($user);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'user' => [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'correo' => $user->correo
                ],
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Inicio de sesión
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email|max:255',
            'clave' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('correo', $request->correo)->first();

            if (!$user || !$user->verificarClave($request->clave)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            $token = $this->generarJWT($user);

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'user' => [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'correo' => $user->correo
                ],
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar JWT token
     */
    private function generarJWT($user)
    {
        $payload = [
            'iss' => 'Sistema_Proyectos_Laravel',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // 24 horas
            'user_id' => $user->id,
            'correo' => $user->correo
        ];

        $key = env('JWT_SECRET', 'clave_secreta_por_defecto');
        
        return JWT::encode($payload, $key, 'HS256');
    }

    /**
     * Verificar JWT token
     */
    public static function verificarJWT($token)
    {
        try {
            $key = env('JWT_SECRET', 'clave_secreta_por_defecto');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener usuario autenticado
     */
    public static function obtenerUsuario($token)
    {
        $payload = self::verificarJWT($token);
        if (!$payload) {
            return null;
        }

        return User::find($payload['user_id']);
    }
}
