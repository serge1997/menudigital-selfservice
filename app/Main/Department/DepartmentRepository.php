<?php
namespace App\Main\Department;

use App\Models\Department;
use Illuminate\Support\Collection;

class DepartmentRepository implements DepartmentRepositoryInterface
{

    public function getAll(): Collection
    {
        return new Collection(
            Department::all()
        );
    }
}
