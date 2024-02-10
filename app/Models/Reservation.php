<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'person_quantity',
        'date_come_in',
        'hour',
        'customer_firstName',
        'customer_lastName',
        'customer_email',
        'customer_tel',
        'reser_canal',
        'observation'
    ];

    protected $dateFormat = 'd/m/y';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*public function setDateComeInAttribute( $value ) {
        $this->attributes['date_come_in'] = (new Carbon($value))->format('d/m/y');
    }*/
}
