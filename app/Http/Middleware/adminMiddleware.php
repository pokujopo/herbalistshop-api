<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if ($user->usertype !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access only.'
            ], 403);
        }

        return $next($request);
    }
}
/*
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class adminMiddleware
{
  
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   
    public function handle(Request $request, Closure $next): Response
    {   
        if(Auth::check()){
            if(Auth::user()->usertype == 'admin'){

                return $next($request);

            } else{
                return redirect('/index')->with('error', 'Access denied.');

            }
        } else {
            return redirect('/login');
        }
        
    }
}

*/
