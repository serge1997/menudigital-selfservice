<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Reservation\ReservationRepositoryInterface;
use App\Http\Requests\StoreReservationRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    protected ReservationRepositoryInterface $reservationRepositoryInterface;

    public function __construct(ReservationRepositoryInterface $reservationRepositoryInterface)
    {
        $this->reservationRepositoryInterface = $reservationRepositoryInterface;
    }

    public function index(): JsonResponse
    {
        try{
            $reservationResponse = $this->reservationRepositoryInterface->getAll();
            return response()->json($reservationResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }


    public function createReservation(StoreReservationRequest $request)
    {
        try {

            $request->validated();
            $message = "Reservação salva com sucesso";
            $this->reservationRepositoryInterface->create($request);
            return response()->json($message);

        }catch (Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function findById($id): JsonResponse
    {
        try {

            return response()->json($this->reservationRepositoryInterface->find($id));
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function updateAction(Request $request): JsonResponse
    {
        try {
            $message = "Reservação editada com sucesso";
            $this->reservationRepositoryInterface->update($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(). " ".$e->getLine(), 500);
        }
    }

    public function deleteAction(Request $request, $id)
    {
        try {
            $message = "Reservação deletado com successo";
            $this->reservationRepositoryInterface->delete($request, $id);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function teste()
    {
        try{
            return response()->json($this->reservationRepositoryInterface->ressourceTeste());
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function reservationBiData(): JsonResponse
    {
        try{
            return response()->json($this->reservationRepositoryInterface->listByCanalAVG());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
