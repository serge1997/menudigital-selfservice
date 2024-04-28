<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\IpController;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        #$currentUserInfo = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='. $_SERVER['REMOTE_ADDR']));
        #IpController::checkCurrentIp($request);
        $currentUserLocal = $request->session()->get('ip');
        $latitude = $currentUserLocal[0] ?? Restaurant::retrive()->latitude;
        $longitude = $currentUserLocal[1] ?? Restaurant::retrive()->longitude;
        $haversineDistance = IpController::getHaversineDistance(Restaurant::retrive()->latitude, Restaurant::retrive()->longitude, $latitude, $longitude);
        if ( $haversineDistance > Restaurant::RESTAURANT_AREA ) {
            return response()->json("Your position area is out $haversineDistance", 401);
        }
        return $next($request);
    }
}
