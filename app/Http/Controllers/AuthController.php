<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __contruct(){
        $this->middleware('jwt', ['except' => ['login']]);
    }

    public function login(){
        $credentials = request(['email', 'password']);
        if(!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'unauthorized'], 401);
        }
        return $this->wToken($token);
    }
    protected function wToken($token){
        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ]);
    }
}
