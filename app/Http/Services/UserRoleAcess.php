<?php
    namespace App\Http\Services;

    use App\Models\Role;
    use App\Models\UserRole;

    class UserRoleAcess
    {

        public static function can_manage($id)
        {
            $userRoles = UserRole::where('user_id', $id)
                ->get();
            foreach ($userRoles as $manager) {
                if ($manager->role_id == Role::MANAGER) {
                    return true;
                }
                return false;
            };
        }

        public static function can_delete_user($id)
        {
            foreach (UserInstance::get_user_roles($id) as $delete):
                if ($delete->role_id !== Role::CAN_DELETE_USER){
                    return false;
                }
                return true;
            endforeach;
        }

        public static function can_transfert_order($id): bool
        {
            foreach (UserInstance::get_user_roles($id) as $transfert):
                if ($transfert->role_id !== Role::CAN_TRANSFERT_ORDER){
                    return false;
                }
            endforeach;
            return true;
        }

        public static function can_take_order($id): bool
        {
            foreach (UserInstance::get_user_roles($id) as $take):
                if ($take->role_id !== Role::CAN_TAKE_ORDER){
                    return false;
                }
            endforeach;
            return true;
        }

        public static function can_cancel_order($id): bool
        {
            foreach (UserInstance::get_user_roles($id) as $cancel):
                if ($cancel->role_id !== Role::CAN_CANCEL_ORDER){
                    return false;
                }
            endforeach;
            return true;
        }

        public static function can_user_cashier($id): bool
        {
            foreach (UserInstance::get_user_roles($id) as $cashier):
                if ($cashier->role_id !== Role::MANAGER){
                    return false;
                }
            endforeach;
            return true;
        }
    }
