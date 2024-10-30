<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    protected $userRepo;

    // Inject the UserRepository dependency
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    // Register a new user with hashed password
    public function register(array $data)
    {
        $user = $this->userRepo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Return the created user's ID
        return [
            'user_id' => $user->id,
        ];
    }

    // Handle user login and token creation
    public function login(array $data)
    {
        $user = $this->userRepo->findByEmail($data['email']);

        // Check credentials and throw exception if invalid
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Login failed, The provided credentials are incorrect.');
        }

        // Return access token for authenticated user
        return [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }

    // Log out by deleting the current access token
    public function logout($user)
    {
        $user->currentAccessToken()->delete();
    }
}
