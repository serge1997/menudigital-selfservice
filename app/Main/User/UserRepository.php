<?php
namespace App\Main\User;

use App\Http\Services\Util\Util;
use App\Models\User;
use App\Traits\Permission;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    use Permission { autth as protected; }

    public function getAll(): Collection
    {
        return new Collection(
            User::select(
                "users.id",
                "users.name",
                "users.email",
                "users.tel",
                "positions.name AS position",
                "positions.id as position_id"
            )
                ->join("positions", "users.position_id", "=", "positions.id")
                    ->where('isactive', true)
                        ->get()

        );
    }


    public function create($request)
    {

        if ($this->can_manage($request) || $this->can_cashier($request))
        {
            $values = $request->all();
            $user = new User($values);
            $user->password = Hash::make($request->password);
            $user->save();
            return;
        }
        throw new Exception("Você não tem permissão");
    }

    public function find($id): Collection
    {
        return new Collection(
            User::where('id', $id)->get()
        );
    }

    public function findGerente(): Collection
    {
        return new Collection (
            User::where('position_id', User::GERENTE)->first()
        );
    }

    public function delete($id, $request): void
    {
        if ($this->can_manage($request)):
            User::where('id', $id)
                ->update([
                    'isactive' => false
                ]);
        else:
            throw new Exception(Util::PermisionExceptionMessage());
        endif;

    }
}
