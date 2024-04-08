<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IpController extends Controller
{
    public function checkCurrentIp($ip, Request $request)
    {
        $request->session()->put('ip', $ip);
    }
}
