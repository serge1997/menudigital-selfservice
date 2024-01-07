<?php
namespace App\Http\Services\Restaurant;

use App\Models\Restaurant;
class RestaurantRepository
{
    public static function getOpenTime(): string
    {
        return Restaurant::select('res_open')->limit(1);
    }

    public static function getCloseTime(): string
    {
        return Restaurant::select('res_close')->limit(1);
    }
}
