<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGroupOwnership
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
        $group = $request->route('groupe');

        if ($group->proprietaire_id == $request->user()->id) {
            return $next($request);
        }

        return response()->json(['message' => 'You do not own this group.'], 403);
    }
}
