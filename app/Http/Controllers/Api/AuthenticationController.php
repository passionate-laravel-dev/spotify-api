<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthenticationController extends Controller
{
    /**
     * Signin
     */
    public function signin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);

        try {
            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

            return response()->json([
                'message' => 'Signin successful!',
                'token' => $token,
            ]);
        } catch (Throwable $th) {
            Log::error('Signin happen issue: ' . $th->getMessage());
            return response()->json([
                'message' => 'Somethings went wrong!'
            ], 500);
        }
    }

    /**
     * Signup
     */
    public function signup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            return response()->json([
                'message' => 'Signup successful!'
            ]);
        } catch (Throwable $th) {
            Log::error('Signup happen issue: ' . $th->getMessage());
            return response()->json([
                'message' => 'Somethings went wrong!'
            ], 500);
        }
    }
}
