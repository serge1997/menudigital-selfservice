<?php
namespace App\Main\User;

use App\Models\User;
use App\Traits\Permission;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    use Permission { autth as protected; }

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

    public function findGerente(): Collection
    {
        return new Collection (
            User::where('position_id', User::GERENTE)->first()
        );
    }
}
