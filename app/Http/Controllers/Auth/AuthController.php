<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Login Api for all users
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->is_active != 1 && $user->user_type != 'super_admin') {
            return apiError('Your account is inactive. Please contact support.', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $permissions = $user->getAllPermissions()->pluck('name');

        return apiSuccess([
            'user'        => $user,
            'permissions' => $permissions,
        ], 'Login successful', 200, $token, 'Bearer');
    }

    /**
     * Logout Api
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * User Info with details
     */
    public function user(Request $request)
    {
        $user = User::where('id', $request->user()->id)
            ->with('settings')
            ->first();

        $permissions = $user->getAllPermissions()->pluck('name');
        $roles = $user->roles->pluck('name');

        return apiSuccess([
            'user'        => $user,
            'roles'       => $roles,
            'permissions' => $permissions,
        ], 'User retrieved successfully');
    }


    public function register(Request $request)
    {
        return apiError('Cannot register user here');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return apiSuccess($user, 'User created successfully', 200, $token, 'Bearer');
    }
}
