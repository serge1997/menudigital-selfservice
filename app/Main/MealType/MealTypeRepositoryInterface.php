<?php
namespace App\Main\MealType;

interface MealTypeRepositoryInterface
{

    public function beforeSave(string $type_name): void;
    public function create($request);
    public function getAll();
}
