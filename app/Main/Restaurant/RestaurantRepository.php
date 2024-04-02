<?php
namespace App\Main\Restaurant;

use App\Models\Restaurant;
use App\Traits\Permission;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class RestaurantRepository implements RestaurantRepositoryInterface
{
    use Permission;

    public function create($request)
    {
        if ($request->isMethod('post') && $this->can_manage($request)){

            $values = $request->all();
            $restaurant = new Restaurant($values);
            $restaurant->res_logo = "waiting logo update";
            $restaurant->save();
            return ;
        }
        throw new Exception(__('messages.permission'));
    }

    public function find(): Collection
    {
        return new Collection(
            Restaurant::where('id', Restaurant::RESTAURANT_KEY)
                ->first()
        );
    }
}
