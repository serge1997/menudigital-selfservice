<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Position\PositionRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    protected PositionRepositoryInterface $positionRepositoryInterface;

    public function __construct(
        PositionRepositoryInterface $positionRepositoryInterface
    ){
        $this->positionRepositoryInterface = $positionRepositoryInterface;
    }

    public function listAllAction(): JsonResponse
    {
        try{
            return response()->json($this->positionRepositoryInterface->getAll());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateByUserAction($user_id, $position_id, Request $request): JsonResponse
    {
        try{

            $message = __('messages.update');
            $this->positionRepositoryInterface->updateByUser($user_id, $position_id, $request);
            return response()->json($message);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
