<?php

namespace App\Http\Middleware;

use App\Models\Groupe;
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
        $groupe=$request->route('groupe');

        if (is_numeric($groupe)) {
            // If it's an ID, retrieve the Groupe model instance from the database
            $groupe = Groupe::find($groupe);
        }
        if ($groupe->proprietaire_id == $request->user()->id) {
            return $next($request);
        }

        return response()->json(['message' => __('You are not the owner of this group.')], 403);
    }
}
