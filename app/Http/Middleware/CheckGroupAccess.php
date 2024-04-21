<?php

namespace App\Http\Middleware;

use App\Models\Groupe;
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

        // Check if the group is null
        if (!$group) {
            return response()->json(['message' => 'Group not found.'], 404);
        }

        if (!$group->users->contains($request->user())){
            return response()->json(['message' => 'You are not a member of this group.'], 403);
        }
        if($group->proprietaire_id != $request->user()->id) {
            return response()->json(['message' => 'You are not the owner of this group.'], 403);
        }
        return $next($request);
    }
}
