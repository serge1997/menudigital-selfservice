<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod("post")){
            $values = $request->all();
            Restaurant::create($values);
            return response()->json($values);
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod("put")){
            $values = $request->all();
            Restaurant::where('id', Restaurant::RESTAURANT_KEY)
                ->update($values);
            return response()->json($values);
        }
    }
}
