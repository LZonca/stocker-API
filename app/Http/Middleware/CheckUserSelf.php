<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserSelf
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        // Check if the user is null
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Get the user ID from the route parameter
/*        $routeUser = $request->route('user');
        if (!$routeUser) {
            return response()->json(['message' => 'User ID not provided in the route.'], 400);
        }*/

        /*$userIdFromRoute = $routeUser->id;*/

        if ($user->id == $request->user()->id) {
            return $next($request);
        }

        return response()->json(['message' => 'You can only perform this action on your own account.'], 403);
    }
}
