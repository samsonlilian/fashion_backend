<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthApiController extends Controller
{
    //creating of  new user
    public function createUserAccount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);


        $create_new_user =  User::create([
            'role' => 1,
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'api_token' => bcrypt(request('email'))
        ]);
        //
        $token =  $create_new_user->createToken('api_token');
        $create_new_user->api_token = $token->plainTextToken;
        $create_new_user->save();


        if ($create_new_user) {
            return response([
                'status' => 'success',
                'message' => 'your account was created successfully'
            ]);
        } else {
            return response([
                'status' => 'error',
                'message' => 'Failed to create Account...Please try again'
            ]);
        }
    }

    //login user 
    //# MNTW - DWNT- 1DSJ D9T7 QSNU VL2
    // invr 624e d367 cc
    public function loginUser(Request $request)
    {
        //

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if (auth()->attempt([
            'email' => $request['email'],
            'password' => $request['password']
        ])) {
            $user = User::where('email', $request['email'])->first();

            return response([
                'status' => 'success',
                'data' => $user
            ]);
        }
    }
}
