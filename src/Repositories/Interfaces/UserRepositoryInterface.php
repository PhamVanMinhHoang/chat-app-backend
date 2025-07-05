<?php

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?object;
    public function create(array $data): object;

}
