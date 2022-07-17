<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\TokenTransformer;
use App\Http\Requests\{
    RegisterRequest,
    LoginRequest
};

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'  => $request->get('name'),
            'email'  => $request->get('email'),
            'password'  => bcrypt($request->get('password')),
        ]);

        $token = auth()->attempt($request->only('email', 'password'));

        return fractal($token, new TokenTransformer)->respond(201);
    }

    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->only('email', 'password'));

        if (!$token) {
            return abort(422);
        }

        return fractal($token, new TokenTransformer)->respond();
    }

    public function logout()
    {
        
        
    }
}
