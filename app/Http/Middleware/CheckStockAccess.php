<?php

namespace App\Http\Middleware;

use App\Models\Stock;
use Closure;
use Illuminate\Http\Request;

class CheckStockAccess
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
        $user = $request->user();
        $stock = Stock::find($request->stock);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => 'Stock not found.'], 404);
        }

        // Check if the user owns the stock
        if ($user->id === $stock->proprietaire_id) {
            return $next($request);
        }

        // Check if the user belongs to a group that can see the stock
        foreach ($user->groupes as $group) {
            if ($group->stocks->contains($stock)) {
                return $next($request);
            }
        }

        // If the user does not own the stock and does not belong to a group that can see the stock, return a 403 response
        return response()->json(['message' => 'You do not have access to this stock.'], 403);
    }
}
