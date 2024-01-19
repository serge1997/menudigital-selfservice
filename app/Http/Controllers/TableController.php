<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Main\TableNumber\TableNumberRepositoryInterface;

class TableController extends Controller
{
    protected TableNumberRepositoryInterface $tableNumberRepository;

    public function __construct(TableNumberRepositoryInterface $tableNumberRepository)
    {
        $this->tableNumberRepository = $tableNumberRepository;
    }

    public function getAllTable(): JsonResponse
    {
        return response()->json($this->tableNumberRepository->getAll());
    }
}
