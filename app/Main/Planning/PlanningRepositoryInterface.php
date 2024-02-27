<?php
namespace App\Main\Planning;

use Illuminate\Support\Collection;

interface PlanningRepositoryInterface
{
    public function create($request): void;
    public function getAll(): Collection;
    public function beforeSave(string $user_name, string $htm_id): bool;
    public function clearTable($request): void;
    public function findByHtmlId($id);
    public function update($id, $request);
    public function delete($id, $request);
}
