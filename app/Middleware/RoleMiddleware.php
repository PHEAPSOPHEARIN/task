<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param string $role The required role (e.g., 'MANAGER', 'ADMIN'). This is passed from the route definition.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Check if the user is authenticated at all.
        // If 'auth:sanctum' middleware fails before this, the request will never reach here.
        // If the request *does* reach here without an authenticated user (which shouldn't happen 
        // if auth:sanctum is first), this provides a direct 401 response.
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized: User not authenticated. Pass the correct auth credentials'], 401);
        }

        // 2. Check if the user's role matches the required role defined in the route.
        // NOTE: This assumes your User model has a 'role' column/attribute.
        $userRole = Auth::user()->role ?? 'GUEST'; // Default to GUEST if role is null

        if ($userRole !== strtoupper($role)) {
            // Role mismatch. Return a 403 Forbidden response.
            return response()->json(['message' => 'Forbidden: Insufficient role permissions.'], 403);
        }

        // 3. If the role matches, allow the request to proceed.
        return $next($request);
    }
}