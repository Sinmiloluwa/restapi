<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return response()->json([
            'message' => 'You are not logged in'
        ], 401);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()
            ->json($validator->errors());       
        }
        
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json([
                    'message' => 'User not found'
                ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_login_token')->plainTextToken;

        if ($user->role == 2) {
            return response()
            ->json(['message' => 'Welcome Agent '.$user->name,
                    'access_token' => $token, 
                    'token_type' => 'Bearer'
                ]);
        } else {
            return response()
            ->json(['message' => 'Welcome '.$user->name,
                    'access_token' => $token, 
                    'token_type' => 'Bearer'
                ]);
        }

       
    }
}
