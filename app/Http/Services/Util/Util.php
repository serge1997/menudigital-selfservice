<?php
namespace App\Http\Services\Util;

use DateTime;
use Exception;

class Util
{
    CONST ERROR_EXCEPTION_MESSAGE = "Ação não pode ser concluída";
    public static function randomString(): string
    {
        $charactere = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $length = 2;
        $random_string = "";

        for ($i = 0; $i < $length; $i++){
            $random_number = rand(0, 25);
            $random_string.= $charactere[$random_number];
        }

        return $random_string;
    }

    public static function randomNumber()
    {
        return rand(1, 999);
    }

    /**
     * @return string
     */
    public static function Today(): string
    {
        $date = new DateTime();
        $date = $date->format("Y-m-d");

        return $date;
    }

    /** format date Y-m-d */
    public static function formatDate($date): string
    {
        $set_date = new DateTime($date);
        $date = $set_date->format('dd/mm/y');
        return $date;
    }

    /** format hour H:i */
    public static function formatHour($hour): string
    {
        $set_hour = new DateTime($hour);
        $hour = $set_hour->format('H:i');
        return $hour;
    }

    public static function PermisionExceptionMessage(): string
    {
        return 'Vocês não tem permissão';
    }

}
