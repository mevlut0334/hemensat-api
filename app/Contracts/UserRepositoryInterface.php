<?php

namespace App\Contracts; // Namespace'i güncelledik

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update(User $user, array $data);
    public function delete(User $user);
}
