<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Http\Requests\RestaurantFormRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Main\Restaurant\RestaurantRepositoryInterface;

class RestaurantController extends Controller
{
    protected RestaurantRepositoryInterface $restaurantRepositoryInterface;

    public function __construct(RestaurantRepositoryInterface $restaurantRepositoryInterface)
    {
        $this->restaurantRepositoryInterface = $restaurantRepositoryInterface;
    }

    public function index(): JsonResponse
    {
        return response()->json(Restaurant::all());
    }
    public function create(RestaurantFormRequest $request)
    {
       try{
            $message = "Informação salvou com sucesso";
            $this->restaurantRepositoryInterface->create($request);
            return response()->json($message);
       }catch(Exception $e){
        return response()->json($e->getMessage(), 500);
       }
    }

    public function createLogo(Request $request)
    {
       if ($request->isMethod('post')){
           if($request->hasFile('res_logo') && $request->file('res_logo')->isValid()){
               $logo = $request->res_logo;
               $extension = $logo->extension();
               $fotoname = md5($logo->getClientOriginalName(). strtotime("now")). ".". $extension;
               $logo->move(public_path('img/logo'), $fotoname);
               list($width, $height) = getimagesize('img/logo/'.$fotoname);
               if (($width * $height) > Restaurant::RESTAURANT_LOGO_SIZE) {
                   unlink('img/logo/'.$fotoname);
                   return response()->json("logo muito grande", 422);
               }
               DB::table('restaurants')
                   ->where('id', Restaurant::RESTAURANT_KEY)
                   ->update([
                       'res_logo' => $fotoname
                   ]);
               return response()->json("Logo Salvou com sucesso");
           }
        return response()->json("Campo logo obrigatorio", 422);
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
