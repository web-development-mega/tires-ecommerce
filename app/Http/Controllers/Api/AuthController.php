<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * POST /api/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->userData();

        /** @var User $user */
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => UserResource::make($user),
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    /**
     * POST /api/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->credentials();

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => UserResource::make($user),
            'token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * POST /api/auth/logout
     */
    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Revocar solo el token actual
        $user?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Sesión cerrada.',
        ], Response::HTTP_OK);
    }

    /**
     * GET /api/auth/me
     *
     * Devuelve el usuario actual + sus pedidos.
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->load([
            'orders' => function ($q) {
                $q->with(['items', 'serviceLocation', 'payments'])
                  ->latest();
            },
        ]);

        return response()->json([
            'user'   => UserResource::make($user),
            'orders' => OrderResource::collection($user->orders),
        ], Response::HTTP_OK);
    }
}
