<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // 認証成功時の処理
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'type' => $user->type,
                'token' => $token,
            ]);
        } else {
            // 認証失敗時の処理
            return response()->json([
                'success' => false,
            ], 401);
        }
    }
}