<?php

namespace App\Http\Middleware\api;

use Closure;
use Illuminate\Http\Request;

class CheckUserSelf
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        // Check if the user is null
        if (!$user) {
            return response()->json(['message' => __('User not found.')], 404);
        }

        if ($user->id == $request->user()->id) {
            return $next($request);
        }

        return response()->json(['message' => __('You can only perform this action on your own account.')], 403);
    }
}
