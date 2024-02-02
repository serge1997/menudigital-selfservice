<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Position\PositionRepositoryInterface;

class PositionController extends Controller
{
    protected PositionRepositoryInterface $positionRepositoryInterface;

    public function __construct(
        PositionRepositoryInterface $positionRepositoryInterface
    ){
        $this->positionRepositoryInterface = $positionRepositoryInterface;
    }
}
