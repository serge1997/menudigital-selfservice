<?php
namespace App\Main\Reservation;


use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function create($request);
    public function getAll(): Collection;
    public function find($id): Collection;
    public function update($request);
    public function delete($request, $id);
    public function ressourceTeste();
    public function listByCanalAVG();
}
