<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Main\TableNumber\TableNumberRepositoryInterface;
use Exception;

class TableController extends Controller
{
    protected TableNumberRepositoryInterface $tableNumberRepositoryInterface;

    public function __construct(TableNumberRepositoryInterface $tableNumberRepositoryInterface)
    {
        $this->tableNumberRepositoryInterface = $tableNumberRepositoryInterface;
    }

    public function listFreeTable(): JsonResponse
    {
        try{
            return response()->json($this->tableNumberRepositoryInterface->getAllFreeTable());

        }catch(Exception $e){
            return response()->json($e->getMessage(),500);
        }

    }

    public function listTableByOrderStatus(): JsonResponse
    {
        try {

            $freeTableResponse = $this->tableNumberRepositoryInterface->getAllFreeTable();
            $busyTableResponse = $this->tableNumberRepositoryInterface->getAllBusyTable();
            return response()->json([
                'tables' => $freeTableResponse,
                'busyTables' => $busyTableResponse
            ]);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
