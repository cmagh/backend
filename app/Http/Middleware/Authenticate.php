<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth; 

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {               
        if(! Auth::guard('api')->check() ){
            return response()->json([
                'error' => [
                    'status' => '401',
                    'title' => 'Unauthorized',
                    'detail' => 'Unauthorized to view this page',
                    'source' => [
                        'pointer' => $request->fullUrl()
                    ]
    
                ]
            ], 401);
        }
        return $next($request);
    }


    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
