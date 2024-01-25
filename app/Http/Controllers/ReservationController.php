<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Reservation\ReservationRepositoryInterface;
use App\Http\Requests\StoreReservationRequest;
use Exception;

class ReservationController extends Controller
{
    protected ReservationRepositoryInterface $reservationRepositoryInterface;

    public function __construct(ReservationRepositoryInterface $reservationRepositoryInterface)
    {
        $this->reservationRepositoryInterface = $reservationRepositoryInterface;
    }

    public function createReservation(StoreReservationRequest $request)
    {
        try {

            $message = "Reservação salva com sucesso";
            $request->validate();
            $this->reservationRepositoryInterface->create($request);
            return response()->json($message);

        }catch (Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
