<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 * name="Authentication",
 * description="API Endpoints para autenticación de usuarios con sanctum."
 * )
 */
class AuthController extends Controller
{
    
    public function login(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales inválidas.'],
            ]);
        }

        $user = $request->user();
        $token = $user->createToken($request->input('device_name', 'auth_token'))->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Login exitoso'
        ]);
    }

    /**
     * @OA\Post(
     * path="/auth/logout",
     * tags={"Authentication"},
     * summary="Cerrar sesión",
     * description="Revoca el token de API del usuario actual.",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Sesión cerrada exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Logged out successfully")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado (token inválido o ausente).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * @OA\Get(
     * path="/auth/me",
     * tags={"Authentication"},
     * summary="Obtener información del usuario actual",
     * description="Retorna la información del usuario autenticado actualmente.",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Información del usuario obtenida exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", format="email", example="john@example.com")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado (token inválido o expirado).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     * )
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}