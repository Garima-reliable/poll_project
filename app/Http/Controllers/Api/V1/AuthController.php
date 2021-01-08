<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Requests\Api\Login;
use App\Http\Requests\Api\Register;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiController;

class AuthController extends ApiController {

    /* 
        Function is used for user Register
    */
    public function register(Register $request) {
        $input = request()->all();
        // create password
        $input['password'] = Hash::make($input['password']);
        $userData = User::create($input);
        return $this->jsonResponse(true, 200, 'User Created Successfully', $userData);
    }

    /* 
        Function is used for user Login
    */
    public function login(Login $request) {
        // user credentials
        $credential = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        if (auth()->attempt($credential)) {
            // pass authentication
            $user = auth()->user();
            $user->api_token = Str::random(80);
            $user->save();
            return $this->jsonResponse(true, 200, "Login Successfully Done", $user);
        }
        return $this->jsonResponse(false, 401, "Unauthenticated.", []);
    }

}
