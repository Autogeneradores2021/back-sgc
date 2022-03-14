<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Client\Request;

class AfterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
 
        // $token = auth()->tokenById($request->user()->id);
 
        return $response;
    }
}