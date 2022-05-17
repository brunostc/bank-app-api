<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller {

    public function register(Request $request) {
        $this->validate($request, [
            'username' => ['required', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6'],
        ]);

        $user = new User();

        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->user_type = 'customer';

        $user->save();

        $balance = new Balance();

        $balance->bal_user_id = $user->id;
        $balance->bal_current_balance = 0;

        $balance->save();

        return response()->json($user);
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6']
        ]);

        $user = User::where(['email' => $request->email])->firstOrFail();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['errors' => ['Invalid credentials.']], 403);
        }

		$token = $user->createToken('user', ['api:web', $user->user_type])->plainTextToken;

        $user->token_type = $user->user_type;

		return response()->json(['user' => $user, 'token' => $token]);
    }
}
