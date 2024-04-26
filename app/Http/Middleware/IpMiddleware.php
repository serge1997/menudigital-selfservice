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
        $latitude = $currentUserLocal[0] ;
        $longitude = $currentUserLocal[1] ;
        $haversineDistance = IpController::getHaversineDistance(Restaurant::retrive()->latitude, Restaurant::retrive()->longitude, $currentUserLocal[0], $currentUserLocal[1]);
        if ( $haversineDistance > 0.06 ) {
            return response()->json("Your position area is out $haversineDistance", 401);
        }
        return $next($request);
    }
}
