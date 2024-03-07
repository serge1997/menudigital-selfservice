<?php
namespace App\Main\Reservation;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\ReservationResource;
use App\Traits\Permission;
use App\Http\Services\Util\Util;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Date;

class ReservationRepository implements ReservationRepositoryInterface
{
    use Permission;
    protected Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function create($request)
    {

        if ($this->can_manage($request) || $this->can_cashier($request)):

            $value = $request->all();
            $reservation = new Reservation($value);
            $reservation->date_come_in = substr($request->date_come_in, 0, 10);
            $reservation->user_id = $this->autth($request);
            $reservation->save();
        else:
            throw new Exception("Você não tem permissao");
        endif;



    }

    public function getAll(): Collection
    {
        return new Collection(
            ReservationResource::collection(Reservation::all())
        );
    }

    public function find($id): Collection
    {
        return new Collection(
            new ReservationResource(Reservation::find($id))
            // Reservation::select(
            //     'id',
            //     'person_quantity',
            //     'hour',
            //     'customer_firstName',
            //     'customer_lastName',
            //     'customer_email',
            //     'customer_tel',
            //     'reser_canal',
            //     'date_come_in',
            //     'observation'
            //     )
            //         ->where('id', $id)
            //             ->first()
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
                    'reser_canal' => $request->reser_canal ?? $reservation['reser_canal'],
                    'date_come_in'=> $request->date_come_in ?? $reservation['date_come_in'],
                    'hour' => substr($request->hour, 12, 4) ?? $reservation['hour'],
                    'observation' => $request->observation,
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

    public function ressourceTeste()
    {
        return new Collection(ReservationResource::collection(Reservation::all()));
    }
    public function listByCanalAVG()
    {
        $count = Reservation::count();
        $query = Reservation::select(
            'reser_canal as label',
            DB::raw("COUNT(*) / {$count} * 100 as value"),
            DB::raw("CASE
                WHEN reser_canal = 'Whatsapp' THEN '#4ade80'
                WHEN reser_canal = 'Instagram' THEN '#f43f5e'
                WHEN reser_canal = 'Telefone' THEN '#fbbf24'
                WHEN reser_canal = 'Facebook' THEN '#38bdf8'
                WHEN reser_canal = 'E-mail' THEN '#FE3958'
                ELSE '' END AS color
            ")
            )
                ->groupBy('reser_canal')
                    ->get();

        return $query;
    }
    public function autoCancelReservationByDate()
    {
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        //usar date_come_in
        $this->reservation::where([["created_at", '<', $date], ['status', 'W']])
            ->update([
                'status' => 'N'
            ]);
    }
    public function updateReservationStatus($id, $status, $request)
    {
        if ($this->can_manage($request) || $this->can_cashier($request)){
            $this->reservation::where('id', $id)
                ->update([
                    'status' => $status
                ]);
            return true;
        }
        throw new Exception(Util::PermisionExceptionMessage());
    }
}
