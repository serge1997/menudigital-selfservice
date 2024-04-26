<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IpController extends Controller
{
    public static function checkCurrentIp(Request $request)
    {
        #$request->session()->forget('ip');
        $geo = [$request->latitude, $request->longitude];
        $request->session()->put('ip', $geo);
    }

    public static function getHaversineDistance($latitude1, $longitude1, $latitude2, $longitude2) {
        $earth_radius = 6371;

        $dLat = (float)deg2rad($latitude2 - $latitude1);
        $dLon = (float)deg2rad($longitude2 - $longitude1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        return $d;
    }
}
