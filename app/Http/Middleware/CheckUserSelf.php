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
        $user = $request->route('user');

        if ($user->id == $request->user()->id) {
            return $next($request);
        }

        return response()->json(['message' => 'You can only perform this action on your own account.'], 403);
    }
}
