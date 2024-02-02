<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Department\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    protected DepartmentRepositoryInterface $departmentRepositoryInterface;

    public function __contruct(
        DepartmentRepositoryInterface $departmentRepositoryInterface
    ){
        $this->departmentRepositoryInterface = $departmentRepositoryInterface;
    }
}
