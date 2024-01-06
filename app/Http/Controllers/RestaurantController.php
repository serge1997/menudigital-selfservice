<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Http\Requests\RestaurantFormRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Restaurant::all());
    }
    public function create(RestaurantFormRequest $request)
    {
        if ($request->isMethod("post")){
            try {

                $request->validated();
                $values = $request->all();
                $restaurant = new Restaurant($values);
                if($request->hasFile('res_logo') && $request->file('res_logo')->isValid()){
                    $logo = $request->res_logo;
                    $extension = $logo->extension();
                    $fotoname = md5($logo->getClientOriginalName(). strtotime("now")). ".". $extension;
                    $logo->move(public_path('img/logo'), $fotoname);
                    $restaurant->res_logo = $fotoname;
                    list($width, $height) = getimagesize('img/logo/'.$fotoname);
                    if (($width * $height) > Restaurant::RESTAURANT_LOGO_SIZE) {
                        unlink('img/logo/'.$fotoname);
                        return response()->json("logo muito grande");
                    }
                }
                $restaurant->save();
                return response()->json("Informação salvou com successo");
            }catch (Exception $e){
                return response()->json($e->getMessage(), 422);
            }
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod("put")){
            DB::table('restaurants')->where('id', Restaurant::RESTAURANT_KEY)
                ->update([
                    'rest_name' => $request->rest_name,
                    'rest_email' => $request->rest_email,
                    'rest_cnpj' =>$request->rest_cnpj,
                    'res_city' => $request->res_city,
                    'res_neighborhood' => $request->res_neighborhood,
                    'rest_cep' => $request->rest_cep,
                    'rest_streetName' =>$request->rest_streetName,
                    'rest_StreetNumber' => $request->rest_StreetNumber,
                    'res_logo' => $request->res_logo,
                    'res_open' => $request->res_open,
                    'res_close' => $request->res_close
                ]);
            return response()->json("Informação atualizado com successo");
        }
    }
}
