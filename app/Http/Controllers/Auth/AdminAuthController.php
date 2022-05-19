<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins',
                'password' => 'required|string|min:8'
            ]);
    
            if($validator->fails()){
                return response()
                ->json($validator->errors());       
            }

            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
             ]);
    
            $token = $user->createToken('auth_admin_token')->plainTextToken;
    
            return response()
                ->json(['data' => $user,
                        'access_token' => $token, 
                        'token_type' => 'Bearer'
                    ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()
            ->json($validator->errors());       
        }

        $credentials = $request->only('email', 'password');
        $user = Admin::where('email', $request->email)->firstOrFail();

        if (Auth::attempt($credentials))
        {
            $token = $user->createToken('admin_login_token')->plainTextToken;

            return response()
                ->json(['message' => 'Welcome Admin '.$user->name,
                        'access_token' => $token, 
                        'token_type' => 'Bearer'
                    ]);
        }

    }
}
