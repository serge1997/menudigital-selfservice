<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod("post")){

            $values = $request->all();
            return response()->json($values);
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod("put")){
            $values = $request->all();
            return response()->json($values);
        }
    }
}
