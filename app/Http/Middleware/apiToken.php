<?php

namespace App\Http\Middleware;

use Closure;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class apiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $apiKey = $_SERVER['HTTP_S3CR3T_API'];

            if($apiKey != env('S3CR3T_API') || !isset( $apiKey)){
                return response()->json(['message' => 'No way I will let you in']);
            }
            return $next($request);
        } catch (Exception $e) {
            return response()->json(['message' => 'No way I will let you in']);
        }
       
    }
}
