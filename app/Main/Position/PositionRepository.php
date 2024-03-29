<?php
namespace App\Main\Position;

use Illuminate\Support\Collection;
use App\Models\Position;
use App\Models\User;
use App\Traits\Permission;
use App\Http\Services\Util\Util;
use Exception;

class PositionRepository implements PositionRepositoryInterface
{
    use Permission;

    public function getAll(): Collection
    {
        return new Collection(
            Position::all()
        );
    }


    //update user position
    public function updateByUser($user_id, $position_id, $request): void
    {
        if ($this->can_manage($request)):
            User::where("id", $user_id)
                ->update([
                    "position_id" => $position_id
                ]);
        else:
            throw new Exception(__('messages.permission'));
        endif;
    }
}
