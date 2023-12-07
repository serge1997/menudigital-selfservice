<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    CONST MANAGER = 1;
    CONST CAN_USE_CASHIER = 2;
    CONST CAN_DELETE_USER = 3;
    CONST CAN_CANCEL_ORDER = 4;
    CONST CAN_TRANSFERT_ORDER = 5;
    CONST CAN_TAKE_ORDER = 6;

    public function user_role(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'role_id');
    }


}
