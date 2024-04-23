<?php
namespace App\Main\Restaurant;

use App\Models\Restaurant;
use App\Traits\Permission;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\RestaurantResource;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RestaurantRepository extends Controller implements RestaurantRepositoryInterface
{
    use Permission;

    public function create($request)
    {
        if ($request->isMethod('post') && $this->can_manage($request)){
            $values = $request->all();
            $restaurant = new Restaurant($values);
            $restaurant->loss_margin = $request->loss_margin / 100;
            $restaurant->fix_margin = $request->fix_margin / 100;
            $restaurant->variable_margin = $request->variable_margin / 100;
            $restaurant->res_logo = "waiting logo update";
            $restaurant->save();
            return ;
        }
        throw new Exception(__('messages.permission'));
    }

    public function update($request)
    {
        if ($request->isMethod("put") && $this->can_manage($request)) {
            DB::table('restaurants')->where('id', Restaurant::RESTAURANT_KEY)
                ->update([
                    'rest_name' => $request->rest_name,
                    'rest_email' => $request->rest_email,
                    'rest_cnpj' => $request->rest_cnpj,
                    'res_city' => $request->res_city,
                    'res_neighborhood' => $request->res_neighborhood,
                    'rest_cep' => $request->rest_cep,
                    'rest_streetName' => $request->rest_streetName,
                    'rest_StreetNumber' => $request->rest_StreetNumber,
                    'res_logo' => $request->res_logo,
                    'res_open' => $request->res_open,
                    'res_close' => $request->res_close,
                    'loss_margin' => $request->loss_margin / 100,
                    'variable_margin' => $request->variable_margin / 100,
                    'fix_margin' => $request->fix_margin / 100
                ]);
        }
    }

    public function find()
    {
        return new RestaurantResource(
            Restaurant::find(Restaurant::RESTAURANT_KEY)
        );
    }
}
