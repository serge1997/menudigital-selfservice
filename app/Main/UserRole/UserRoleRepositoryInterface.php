<?php
namespace App\Main\UserRole;

use Illuminate\Support\Collection;

interface UserRoleRepositoryInterface
{
    public function createUserRole($id, $request): void;
    public function deleteUserRole($id, $request): void;
    public function findRolesByUser($id): Collection;
}
