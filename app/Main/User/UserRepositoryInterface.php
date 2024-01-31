<?php
namespace App\Main\User;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create($request);
    public function findGerente(): Collection;
}
