<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Language\LanguageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Exception;

class LanguageController extends Controller
{
    public function __construct(
        protected LanguageRepositoryInterface $languageRepositoryInterface
    )
    { }
    public function setSysLanguageAction(Request $request): JsonResponse
    {
        try{
            $message = "Language reseted successfully";
            $this->languageRepositoryInterface->setSysLanguage($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
