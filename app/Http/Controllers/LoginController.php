<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Login\LoginRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    private LoginRepositoryInterface $loginRepositoryInterface;

    public function __construct(
       LoginRepositoryInterface $loginRepositoryInterface
    )
    {
        $this->loginRepositoryInterface = $loginRepositoryInterface;
    }

    public function loginAction(Request $request): JsonResponse
    {
        try {

            $request->validate([
                'username' => ['required'],
                'password' => ['required']
            ]);
            $tokenResponse = $this->loginRepositoryInterface->login($request);
            return response()->json($tokenResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function logoutAction(Request $request): JsonResponse
    {
        try{
            $this->loginRepositoryInterface->logout($request);
            return response()->json("Logout efetuado com sucesso");
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
