<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGroupAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $group = $request->route('groupe');
        $user = auth('sanctum')->user();
        if (!$group->users->contains($request->user())){
            return response()->json(['message' => 'You are not a member of this group.'], 403);
        }
        if($group->proprietaire_id != $request->user()->id) {
            return response()->json(['message' => 'You are not the owner of this group.'], 403);
        }
        return $next($request);
    }
}
