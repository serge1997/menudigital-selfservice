<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Planning\PlanningRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class PlanningController extends Controller
{
    protected PlanningRepositoryInterface $planningRepositoryInterface;

    public function __construct(
        PlanningRepositoryInterface $planningRepositoryInterface
    ){
        $this->planningRepositoryInterface = $planningRepositoryInterface;
    }

    public function createAction(Request $request): JsonResponse
    {
        try{
            // $request->validate([
            //     'hour_in' => ['required'],
            //     'hour_out' => ['required'],
            //     'user_name'
            // ]);
            $message = __('messages.create', ['model' => 'Planning']);
            $this->planningRepositoryInterface->create($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function listAllAction(): JsonResponse
    {
        try{
            return response()->json($this->planningRepositoryInterface->getAll());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function clearAction(Request $request): JsonResponse
    {
        try{
            $message = __('messages.delete');
            $this->planningRepositoryInterface->clearTable($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteAction($id, Request $request): JsonResponse
    {
        try{
            $message = __('messages.delete');
            $this->planningRepositoryInterface->delete($id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function findByHtmlIdAction($id): JsonResponse
    {
        try{
            return response()
                ->json($this->planningRepositoryInterface->findByHtmlId($id));
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function updateAction($id, Request $request): JsonResponse
    {
        try{
            $message = __('messages.update');
            $this->planningRepositoryInterface->update($id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
