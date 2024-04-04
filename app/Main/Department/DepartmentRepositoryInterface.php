<?php
namespace App\Main\Department;

use Illuminate\Support\Collection;

interface DepartmentRepositoryInterface
{
    public function getAll(): Collection;
}
