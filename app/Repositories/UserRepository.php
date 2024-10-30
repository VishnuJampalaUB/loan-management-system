<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    // Initialize repository with User model instance
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    // Create a new user with the provided data
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Find a user by email
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
