<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = !is_null($request->session()->get('lang')) ? $request->session()->get('lang') : 'pt';
        app()->setlocale($lang);
        Log::info("Language seted succesfuly ". $lang);
        return $next($request);
    }
}
