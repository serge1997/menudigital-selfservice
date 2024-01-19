<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealType extends Model
{
    use HasFactory;

    protected $table = 'menu_mealtype';
    protected $primakey = "id_type";
    protected $fillable = [
        'desc_type',
        'foto_type',
    ];

    public function option(): HasMany
    {
        return $this->hasMany(Option::class, "type_id");
    }
}
