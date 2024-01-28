<?php
namespace App\Traits;

use App\Models\UserRole;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

trait Permission
{
    use AuthSession;

    public function can_manage($request): bool
    {
        $id = $this->autth($request);

        return UserRole::where([['user_id', $id], ['role_id', Role::MANAGER]])->exists();
    }

    public function can_cashier($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_USE_CASHIER]])->exists();
    }

    public function can_transfert($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_TRANSFERT_ORDER]])->exists();
    }

    public function can_cancel($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_CANCEL_ORDER]])->exists();
    }
}
