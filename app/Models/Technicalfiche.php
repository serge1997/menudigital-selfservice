<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technicalfiche extends Model
{
    use HasFactory;

    protected $table = 'technicalfiches';
    protected $fillable =
        [
            'productID',
            'itemID',
            'quantity'
        ];

    public function itemmenu(): BelongsTo
    {
        return $this->belongsTo(Menuitems::class, 'itemID');
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'productID');
    }

}
