<?php

namespace App\Http\Controllers;


use App\Main\Role\RoleRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected RoleRepositoryInterface $roleRepositoryInterface;

    public function __construct(
        RoleRepositoryInterface $roleRepositoryInterface
    ){
        $this->roleRepositoryInterface = $roleRepositoryInterface;
    }

    public function listAll(): JsonResponse
    {
        try{
            $roleResponse = $this->roleRepositoryInterface->getAll();
            return response()->json($roleResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
