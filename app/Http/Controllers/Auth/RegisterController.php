<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()
            ->json($validator->errors());       
        }

        switch ($request->role) {
            case 2:
                $role = 2;
                break;

            default:
                $role = 1;
                break;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role
         ]);

        $token = $user->createToken('auth_user_token')->plainTextToken;

        return response()
            ->json(['data' => $user,
                    'access_token' => $token, 
                    'token_type' => 'Bearer'
                ]);
    }
}
