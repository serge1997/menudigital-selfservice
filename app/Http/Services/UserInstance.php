<?php
    namespace App\Http\Services;

    use App\Models\User;
    use App\Models\UserRole;
    use App\Models\Product;
    use App\Models\Menuitems;
    use App\Models\Supplier;
    use App\Models\ItensPedido;
    use Illuminate\Http\Request;

    class UserInstance
    {
        public function __construct()
        {

        }

        public static function get_user_instance($id)
        {
            return User::find($id);
        }

        public static function get_user_roles($id)
        {
            return UserRole::where('user_id', $id)
                ->get();
        }

        public static function AuthUser($request)
        {
            return $request->session()->get('auth-vue');
        }
    }
