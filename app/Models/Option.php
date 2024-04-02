<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';


    public function mealType(): BelongsTo
    {
        return $this->belongsTo(MealType::class, "type_id");
    }
}
