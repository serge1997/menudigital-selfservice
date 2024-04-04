<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';
    protected $fillable = [
        'user_id',
        'role_id'
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_id');
    }

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'role_id');
    }
}
