<?php

namespace App\Http\Controllers;
use App\Constants\AppConstant;

use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get default role
        $defaultRole = Role::where('name', AppConstant::DEFAULT_USER_ROLE)->first();

        if (!$defaultRole) {
            return response()->json([
                'success' => false,
                'message' => 'Default role not found'
            ], 500);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $defaultRole->id,
            'status' => AppConstant::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        // Create token
        $token = $user->createToken($user->email, ['basic'])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'accessToken' => $token,
                'tokenType' => 'Bearer',
                'user' => new UserResource($user),
            ]
        ], 201);
    }

    /**
     * Login user
     */


public function login(Request $request)
{
    // 1. Validate request
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string|min:6',
    ]);

    // 2. Attempt login using Auth
    if (!Auth::attempt($request->only('email', 'password'), true)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password'
        ], 401);
    }

    $user = Auth::user(); // Auth::user() is now guaranteed to exist

    // 3. Optional: check email verification
    if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
        return response()->json([
            'success' => false,
            'message' => 'Email not verified'
        ], 403);
    }

    // 4. Delete old tokens
    $user->tokens()->delete();

    // 5. Prepare abilities
    $abilities = $user->role->abilities->pluck('action')->toArray();

    // Restrict abilities for certain roles
    if (in_array($user->role->name, [
        AppConstant::DEFAULT_USER_ROLE['FRONTEND_DEV'],
        AppConstant::DEFAULT_USER_ROLE['CUSTOMER'],
    ])) {
        $abilities = ['basic'];
    }

    // 6. Create new token
    $token = $user->createToken($user->email, $abilities)->plainTextToken;

    // 7. Prepare response
    $responseData = [
        'accessToken' => $token,
        'user' => new UserResource($user),
    ];

    // Add abilities for admin or other roles
    if (!in_array($user->role->name, [
        AppConstant::DEFAULT_USER_ROLE['CUSTOMER'],
        AppConstant::DEFAULT_USER_ROLE['FRONTEND_DEV'],
    ])) {
        $responseData['abilities'] = $abilities;
    }

    // 8. Return response
    return response()->json([
        'success' => true,
        'data' => $responseData,
    ]);
}

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('role.abilities');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user),
            ]
        ], 200);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->load('role.abilities');

        // Delete current token
        $request->user()->currentAccessToken()->delete();

        // Get abilities
        $abilities = AppConstant::isBasicRole($user->role->name)
            ? ['basic']
            : ($user->role->abilities->pluck('action')->toArray() ?: ['basic']);

        // Create new token
        $token = $user->createToken($user->email, $abilities)->plainTextToken;

        $responseData = [
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'accessToken' => $token,
                'tokenType' => 'Bearer',
            ]
        ];

        if (!AppConstant::isBasicRole($user->role->name)) {
            $responseData['data']['abilities'] = $abilities;
        }

        return response()->json($responseData, 200);
    }
}