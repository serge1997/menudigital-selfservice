<?php

namespace App\Http\Controllers;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Main\User\UserRepositoryInterface;



class UserController extends Controller
{
    protected $user;
    protected $auth_user;
    // CONST USER_ID = 1;
    // CONST IS_CANCEL = true;
    protected UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function create(StoreUserRequest $request)
    {
        try {
            $message = __('messages.create', ['model' => 'User']);
            $this->userRepositoryInterface->create($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function listAllAction(): JsonResponse
    {
        try{
            return response()->json($this->userRepositoryInterface->getAll());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteAction($id, Request $request): JsonResponse
    {
        try{
            $message = __('messages.delete');
            $this->userRepositoryInterface->delete($id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateAction(Request $request): JsonResponse
    {
        try{
            $message = __('messages.update');
            $this->userRepositoryInterface->update($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function currentUser(Request $request)
    {
        $user_id = $request->session()->get('auth-vue');
       var_dump($user_id);
    }

}
