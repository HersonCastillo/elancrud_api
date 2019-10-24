<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __contruct(){
        $this->middleware('jwt', ['except' => ['signin', 'signup']]);
    }

    public function signin(){
        $credentials = request(['email', 'password']);
        if(!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'unauthorized'], 401);
        }
        return $this->wToken($token);
    }
    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email:rfc',
            'password' => 'required'
        ]);
        if(!$validator->fails()){
            try{
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);

                $user->save();

                return response()->json([
                    'message' => 'User saved'
                ]);
            } catch(\Exception $ex){
                return response()->json([
                    'message' => 'An error occurred when trying save an User object',
                    'errors' => [
                        'exception' => [$ex->getMessage()]
                    ]
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Some errors found',
                'errors' => $validator->errors()
            ], 500);
        }
    }
    public function user(Request $request){
        try {
            return $this->wToken($request['token']);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'An error occurred when trying save an User object',
                'errors' => [
                    'exception' => [$ex->getMessage()]
                ]
            ], 500);
        }
    }
    protected function wToken($token){
        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ]);
    }
}
