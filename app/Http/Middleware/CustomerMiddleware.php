<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerMiddleware {

    public function handle(Request $request, Closure $next) {
		if (auth()->user()->tokenCan('api:web')) {
            if (auth()->user()->tokenCan('customer')) {
                return $next($request);
            }
        }

        return response()->json(null, 403);
    }
}
