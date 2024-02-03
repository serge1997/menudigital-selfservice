<?php
namespace App\Main\User;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create($request);
    public function getAll(): Collection;
    public function find($id): Collection;
    public function findGerente(): Collection;
    public function update($request): void;
    public function delete($id, $request): void;
}
