<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Get default role, e.g. "user"
    $defaultRole = Role::where('name', 'user')->first();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $defaultRole ? $defaultRole->id : 1, // fallback to ID 1
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('user', 'token'), 201);
}
public function login(Request $request)
    {
        // 1. Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Attempt to authenticate the user using the 'api' guard
        $credentials = $request->only('email', 'password');
        
        try {
            // **Crucial Step:** Explicitly use the 'api' guard for JWT authentication
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Login failed', 'message' => 'Invalid Credentials'], 401);
            }
        } catch (JWTException $e) {
            // Handle cases where the token creation itself fails (very rare)
            return response()->json(['error' => 'Login failed', 'message' => 'Could not create token'], 500);
        }

        // 3. If successful, return the response with the token
        return $this->respondWithToken($token);
    }
    
    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // **Crucial Step:** Use auth('api') to access the JWT factory() method
            'expires_in' => auth('api')->factory()->getTTL() * 60, 
            'user' => auth('api')->user(), // Optionally return the user data
        ]);
    }


    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to fetch user profile'], 500);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $user = Auth::user();
            $user->update($request->only(['name', 'email']));
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }
}