<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->registerUser($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully.',
                'user' => new UserResource($user),
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $result = $this->userService->loginUser($data);
            return response()->json([
                'message' => 'Login successful.',
                'user' => new UserResource($result->user),
                'token' => $result->token,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Login failed.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Revoke the current user's access token.
     */
    public function logout(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $token = $request->user()?->currentAccessToken();
            if ($token) {
                $token->delete();
            }

            return response()->json([
                'message' => 'Logout successful.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Logout failed.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
