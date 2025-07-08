<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    public function registerUser(array $data): object
    {
        // Check if the user already exists
        $existingUser = $this->userRepository->findByEmail($data['email'] ?? '');
        if ($existingUser) {
            throw new Exception('User with this email already exists.');
        }
        // Ensure the password is hashed before saving
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // Validate and process the data as needed
        return $this->userRepository->create($data);
    }
}
