<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Position extends Model
{
    use HasFactory;
    private CONST MANGER = 1;
    private CONST CHEF_KITCHEN = 2;
    private CONST CHEF_BAR = 3;
    private CONST COMI_KITCHEN = 4;
    private CONST BARMAN = 5;
    private CONST GARCOM = 6;
    private CONST SALE_MANAGER = 7;
    private CONST CASH_REGISTER = 8;


    protected $table = 'positions';

    public function scopeManager(Builder $query): void
    {
        $query->whereIn('id', [
            self::MANGER,
            self::CHEF_BAR,
            self::CHEF_BAR,
            self::SALE_MANAGER
        ]);
    }

    public function scopeStock(Builder $query): void
    {
        $query->whereIn('id', [
            self::MANGER,
            self::CHEF_KITCHEN,
            self::CHEF_BAR,
            self::COMI_KITCHEN,
            self::SALE_MANAGER,
            self::CASH_REGISTER,
            self::BARMAN
        ]);
    }
}
