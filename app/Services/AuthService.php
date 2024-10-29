<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(array $data)
    {
        $user = $this->userRepo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return [
            'user_id' => $user->id,
        ];
    }

    public function login(array $data)
    {
        $user = $this->userRepo->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Login failed, The provided credentials are incorrect.');
        }

        // Return the token data, but leave response handling to the controller
        return [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
    }
}
