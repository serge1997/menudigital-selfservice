<?php
namespace App\Main\Reservation;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Permission;
use App\Http\Services\Util\Util;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;

class ReservationRepository implements ReservationRepositoryInterface
{
    use Permission;

    public function create($request)
    {

        if ($this->can_manage($request) || $this->can_cashier($request)):

            $value = $request->all();
            $reservation = new Reservation($value);
            $reservation->user_id = $this->autth($request);
            $reservation->save();
        else:
            throw new Exception("Você não tem permissao");
        endif;



    }

    public function getAll(): Collection
    {
        return new Collection(
            Reservation::all()
        );
    }

    public function find($id): Collection
    {
        return new Collection(

            Reservation::select(
                'id',
                'person_quantity',
                'hour',
                'customer_firstName',
                'customer_lastName',
                'customer_email',
                'customer_tel',
                'reser_canal',
                'date_come_in',
                'observation'
                )
                    ->where('id', $id)
                        ->first()
        );
    }

    public function update($request)
    {
        if ($this->can_cashier($request) || $this->can_manage($request)){
            $reservation = $this->find($request->id);
            Reservation::where('id', $request->id)
                ->update([
                    'person_quantity' => $request->person_quantity,
                    'hour' => $request->hour,
                    'customer_firstName' => $request->customer_firstName,
                    'customer_lastName' => $request->customer_lastName,
                    'customer_email' => $request->customer_email,
                    'customer_tel' => $request->customer_tel,
                    'reser_canal' => $request->reser_canal,
                    'date_come_in'=> $request->date_come_in ?? $reservation['date_come_in'],
                    'observation' => $request->observation
                ]);

            return;
        }
        throw new Exception("Você não tem permissão");
    }

    public function delete($request, $id)
    {
        if ($this->can_manage($request) || $this->can_cashier($request)){
            Reservation::where('id', $id)->delete();
            return;
        }
        throw new Exception("Você não tem permissao");
    }
}
