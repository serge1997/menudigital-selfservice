<?php
namespace App\Main\Reservation;


use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function create($request);
    public function getAll(): Collection;
    public function update($request);
    public function delete($id);
}
