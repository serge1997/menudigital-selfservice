<?php
namespace App\Main\Login;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginRepository implements LoginRepositoryInterface
{
    public function login($request)
    {
        $user = User::where([['username', $request->username], ['isactive', true]])->first();

        if (!$user->username || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msgerr' => ['senha ou usuario incoreto']
            ]);
        }
        $request->session()->put('auth-vue', $user->id);
        return $user->createToken('browser')->plainTextToken;
    }

    public function logout($request): void
    {
        $request->session()->forget('auth-vue');
        $request->user()->currentAccessToken()->delete();
    }
}
