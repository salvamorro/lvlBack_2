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
            $apiKey = $_SERVER['HTTP_TOKEN'];
            
            if($apiKey != env('S3CR3T_API')){
                return response()->json(['message' => 'Access not Authorized']);
                
               // throw new Error('Access Denied: wrong api Token');
            }
            if(!isset( $apiKey)){
                return response()->json(['message' => 'Access not Authorized']);
               // throw new Error('Access Denied: not api Token');

            }
            return $next($request);
        } catch (Exception $e) {
             return response()->json(['message' => 'Exception in Middleware API TOKEN' . $e->getMessage()]);
           // throw new Error('Exception in Middleware API TOKEN' . $e->getMessage());

        }
       
    }
}
