<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Restaurant extends Model
{
    use HasFactory;
    /**
     * @const RESTAURANT_LOGO_SIZE
     */
    CONST RESTAURANT_LOGO_SIZE = 360000;
    CONST RESTAURANT_KEY = 1;
    CONST RESTAURANT_AREA = 0.60;

    protected $table = 'restaurants';
    protected $fillable = [
        'rest_name',
        'rest_email',
        'rest_cnpj',
        'res_city',
        'res_neighborhood',
        'rest_cep',
        'rest_streetName',
        'rest_StreetNumber',
        'res_logo',
        'res_open',
        'res_close',
        'loss_margin',
        'fix_margin',
        'variable_margin'
    ];

    public function scopeRetrive(Builder $query)
    {
        return $query->where('id', self::RESTAURANT_KEY)
            ->first();

    }

}
