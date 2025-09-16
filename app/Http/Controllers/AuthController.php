<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Authenticate user and return JWT token.
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => auth()->user()
        ], 200);
    }

    /**
     * Register a new user and return JWT token.
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ], [
        'name.required' => 'Username is required',
        'email.required' => 'Email is required',
        'email.email' => 'Email must be valid',
        'email.unique' => 'Email already exists',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 6 characters',
        'password.confirmed' => 'Passwords do not match',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']), // bcrypt by default
    ]);

    $token = auth()->login($user);

    return response()->json([
        'success' => true,
        'message' => 'Registration successful',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]
    ], 201);
}

    /**
     * Return CSRF token (optional for hybrid apps).
     */
    public function getToken(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'csrf_token' => csrf_token()
        ], 200);
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Refresh JWT token.
     */
    public function refresh(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'token' => auth()->refresh()
        ], 200);
    }

    /**
     * Return authenticated user info.
     */
    public function me(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => auth()->user()
        ], 200);
    }
}