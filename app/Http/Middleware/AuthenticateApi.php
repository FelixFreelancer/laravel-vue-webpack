<?php

namespace App\Http\Middleware;

use Closure;
use App\Library\Api;
use Auth;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!auth()->check()){
          $data['data']['error'] = "Unauthorised Access";
          $statusCode =  401;
          return Api::ApiResponse($data, $statusCode);
        }
        return $next($request);
    }
}
