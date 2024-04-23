<?php
namespace App\Http\Services\MealMarge;

use App\Models\Restaurant;

final class MealMarge implements MealMargeInterface
{
    public function __construct(
        protected float $value
    )
    {}

    public function margeFix(): float
    {
        return $this->value * Restaurant::retrive()->fix_margin;
    }
    public function margeVariable(): float
    {
        return $this->value * Restaurant::retrive()->variable_margin;
    }

    public function margeLose(): float
    {
        return $this->value * Restaurant::retrive()->loss_margin;
    }
}
