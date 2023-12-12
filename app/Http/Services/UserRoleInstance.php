<?php
    namespace App\Http\Services;

    use App\Models\Role;

    class UserRoleInstance
    {

        public function __construct(
            protected Role $user_roles
        ){}

        public function findAll()
        {
            return $this->user_roles::all();
        }

        public function find($id)
        {
            return $this->user_roles::find($id);
        }

    }
