<?php
namespace App\Main\Role;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function getAll(): Collection;
}
