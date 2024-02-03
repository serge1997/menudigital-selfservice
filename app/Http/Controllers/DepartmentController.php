<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Department\DepartmentRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    protected DepartmentRepositoryInterface $departmentRepositoryInterface;

    public function __construct(
        DepartmentRepositoryInterface $departmentRepositoryInterface
    ){
        $this->departmentRepositoryInterface = $departmentRepositoryInterface;
    }

    public function listAllAction(): JsonResponse
    {
        try{
            return response()->json($this->departmentRepositoryInterface->getAll());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
