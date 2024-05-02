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
        $groupe = $request->route('groupe');

        // Check if the 'groupe' parameter is an ID
        if (is_numeric($groupe)) {
            // If it's an ID, retrieve the Groupe model instance from the database
            $groupe = Groupe::find($groupe);
        }

        $user = auth('sanctum')->user();

        // Check if the group is null
        if (!$groupe) {
            return response()->json(['message' => __('Group not found.')], 404);
        }

        if (!$groupe->members->contains($request->user())){
            return response()->json(['message' => __('You are not a member of this group.')], 403);
        }
        if($groupe->proprietaire_id != $request->user()->id) {
            return response()->json(['message' => __('You are not the owner of this group.')], 403);
        }
        return $next($request);
    }
}
