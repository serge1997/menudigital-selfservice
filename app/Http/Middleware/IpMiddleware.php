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
        Log::info("Current Ip ". str_replace('.', '', session()->get('ip')));
        $appIp = Restaurant::find(Restaurant::RESTAURANT_KEY);
        if ( str_replace('.', '', session()->get('ip')) !== $appIp->res_ip ) {
            return response()->json("unauthorized", 401);
        }
        return $next($request);
    }
}
