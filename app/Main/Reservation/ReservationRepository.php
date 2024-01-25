<?php
namespace App\Main\Reservation;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function create($request)
    {

        $value = $request->all();
        $reservation = new Reservation($value);
        $reservation->save();

    }

    public function getAll(): Collection
    {
        return new Collection(
            Reservation::all()
        );
    }

    public function update($request)
    {

    }

    public function delete($id)
    {

    }
}
