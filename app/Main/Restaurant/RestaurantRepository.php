<?php
namespace App\Main\Restaurant;

use App\Models\Restaurant;
use App\Traits\Permission;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\RestaurantResource;

class RestaurantRepository implements RestaurantRepositoryInterface
{
    use Permission;

    public function create($request)
    {
        if ($request->isMethod('post') && $this->can_manage($request)){

            $values = $request->all();
            $restaurant = new Restaurant($values);
            $restaurant->loss_margin = $request->loss_margin / 100;
            $restaurant->fix_margin = $request->fix_margin / 100;
            $restaurant->variavle_margin = $request->cariable_margin / 100;
            $restaurant->res_logo = "waiting logo update";
            $restaurant->save();
            return ;
        }
        throw new Exception(__('messages.permission'));
    }

    public function find()
    {
        return new RestaurantResource(
            Restaurant::find(Restaurant::RESTAURANT_KEY)
        );
    }
}
