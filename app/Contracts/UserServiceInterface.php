<?php

namespace App\Contracts;

use App\Models\User;

interface UserServiceInterface
{
    public function getAllUsers();
    public function findUserById($id);
    public function createUser(array $data);
    public function updateUser(User $user, array $data);
    public function deleteUser(User $user);
}
