<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Throwable;

class AuthController extends Controller
{
    protected $authService;

    /**
     * Inject AuthService dependency to handle authentication logic.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            // Register a user with validated request data
            $user = $this->authService->register($request->validated());
            return $this->sendResponse($user, 'User registered successfully.', 201);
        } catch (Throwable $e) {
            // Return error response if registration fails
            return $this->sendError($e, 'User registration failed');
        }
    }

    /**
     * Log in an existing user.
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            // Attempt to authenticate user and generate token
            $authData = $this->authService->login($request->validated());
            return $this->sendResponse($authData, 'User logged in successfully.');
        } catch (Throwable $e) {
            // Return error response if login fails
            return $this->sendError($e, 'Login failed');
        }
    }

    /**
     * Log out the currently authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke the user's current access token to log them out
            $this->authService->logout($request->user());
            return $this->sendResponse([], 'Successfully logged out.');
        } catch (Throwable $e) {
            // Return error response if logout fails
            return $this->sendError($e, 'Logout failed');
        }
    }
}
