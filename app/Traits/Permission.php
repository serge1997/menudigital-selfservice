<?php
namespace App\Traits;

trait Permission
{
    use AuthSession { autth as protected; }
}
