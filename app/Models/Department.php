<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    CONST ADMINISTRATION = 1;
    CONST COZINHA = 2;
    CONST BAR = 3;
    CONST SALA = 4;


    protected $table = 'departments';
}
