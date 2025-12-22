<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Events\Pusher;
use Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;
            return response()->json([
                'token' => $token,
                'user' => [
                    'uid' => $user->uid
                ]
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'registered successfully',
            'user' => [
                'uid' => $user->uid,
                'email' => $user->email,
            ]
        ], 201);
    }

    public function sendMessage(Request $request)
    {
        try {
            $message = 'Test';
            event(new Pusher($message));
            return response()->json(['status' => 'Message sent!']);
        } catch (Exception $e) {
            Log::channel('error')->error('An error occurred: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
