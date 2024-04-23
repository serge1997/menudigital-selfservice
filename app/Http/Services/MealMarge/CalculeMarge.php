<?php
namespace App\Http\Services\MealMarge;

class CalculeMarge
{
    public function __construct(
        protected MealMargeInterface $mealMargeInterface
    ){}
    public function calculeMargeFix()
    {
        return $this->mealMargeInterface->margeFix();
    }

    public function calculeMargeLose(): float
    {
        return $this->mealMargeInterface->margeLose();
    }

    public function calculeMargeVariable(): float
    {
        return $this->mealMargeInterface->margeVariable();
    }
}
