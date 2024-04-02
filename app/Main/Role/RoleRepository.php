<?php
namespace App\Main\Role;

use Illuminate\Support\Collection;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{

    public function getAll(): Collection
    {
        return new Collection(
            Role::all()
        );
    }
}
