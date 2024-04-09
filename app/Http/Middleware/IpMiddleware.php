<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Log;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUserInfo = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='. $_SERVER['REMOTE_ADDR']));
        $appInfo = Restaurant::find(Restaurant::RESTAURANT_KEY);
        if ( $currentUserInfo['geoplugin_latitude'] !== $appInfo->latitude || $currentUserInfo['geoplugin_longitude'] !== $appInfo->longitude ) {
            return response()->json("unauthorized", 401);
        }
        return $next($request);
    }
}
