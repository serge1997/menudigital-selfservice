<?php
namespace App\Main\Login;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Position;

class LoginRepository implements LoginRepositoryInterface
{

    public function login($request)
    {
        $user = User::where([['username', $request->username], ['isactive', true]])->first();
        $data = Array();

        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msgerr' => ['senha ou usuario incoreto']
            ]);
        }

        $request->session()->put('auth-vue', $user->id);
        $data['token'] = $user->createToken('browser')->plainTextToken;
        $data['stockAccess'] = Position::stock()->pluck('id');
        $data['managerAccess'] = Position::manager()->pluck('id');
        $data['administrativeAccess'] = Position::administrative()->pluck('id');


        return $data;
    }

    public function logout($request): void
    {
        $request->session()->forget('auth-vue');
        $request->user()->currentAccessToken()->delete();
    }
}
