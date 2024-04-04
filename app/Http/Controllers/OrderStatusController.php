<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\OrderStatus\OrderStatusRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Exception;

class OrderStatusController extends Controller
{
    protected OrderStatusRepositoryInterface $orderStatusRepositoryInterface;

    public function __construct(OrderStatusRepositoryInterface $orderStatusRepositoryInterface)
    {
        $this->orderStatusRepositoryInterface = $orderStatusRepositoryInterface;
    }

    public function listAll(): JsonResponse
    {
        try{
            $orderStatusReponse = $this->orderStatusRepositoryInterface->getAll();
            return response()->json($orderStatusReponse);

        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
