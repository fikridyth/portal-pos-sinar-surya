<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class ServerMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            DB::connection('mysql_second')->getPdo();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Database server tidak bisa diakses',
                'message' => $e->getMessage()
            ], 500);
        }

        return $next($request);
    }
}
