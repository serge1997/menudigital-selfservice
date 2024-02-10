<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menuitems extends Model
{
    use HasFactory;
    protected $table ='menuitems';

    protected $fillable = [
        'item_name',
        'item_price',
        'item_image',
        'item_status',
        'item_desc',
        'type_id'
    ];

    public function fiche(): HasMany
    {
        return $this->hasMany(Technicalfiche::class, 'itemID');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MealType::class, 'type_id', 'id_type');
    }
}
