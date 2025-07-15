<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        try {
            DB::beginTransaction();
            // Check if the user already exists
            $existingUser = $this->userRepository->findByEmail($data['email'] ?? '');
            if ($existingUser) {
                throw new Exception('User with this email already exists.');
            }
            // Ensure the password is hashed before saving
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            $user = $this->userRepository->create($data);
            if (!$user) {
                throw new Exception('User registration failed.');
            }

            DB::commit();

            return $user;
        } catch (Throwable $th) {

            DB::rollBack();

            throw new Exception('Registration failed: ' . $th->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function loginUser(array $data): object
    {
        try {
            // Attempt to authenticate the user
            if (!auth()->attempt($data)) {
                throw new Exception('Tài khoản hoặc mật khẩu không chính xác.');
            }

            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return (object) [
                'user' => $user,
                'token' => $token,
            ];
        } catch (Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
}
