<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}