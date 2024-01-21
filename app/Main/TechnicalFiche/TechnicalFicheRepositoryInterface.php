<?php
namespace App\Main\TechnicalFiche;


use Illuminate\Database\Eloquent\Collection;


interface TechnicalFicheRepositoryInterface
{
    public function findByItemId($id): Collection;
}
