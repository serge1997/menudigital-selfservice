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

    public function can_take_order($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_TAKE_ORDER]])->exists();
    }

    public function can_delete_user($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_DELETE_USER]])->exists();
    }

    public function can_cange_paiement_method($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_CHANGE_PAIEMENT_METHOD]])->exists();
    }

    public function can_create_product($request): bool
    {
        return UserRole::where([['user_id', $this->autth($request)], ['role_id', Role::CAN_CREATE_PRODUCT]])->exists();
    }
}
