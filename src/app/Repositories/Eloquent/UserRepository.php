<?php

namespace App\Repositories\Eloquent;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new \App\Models\User();
    }

    public function findByEmail(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }
}
