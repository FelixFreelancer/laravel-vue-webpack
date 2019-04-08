<?php

namespace App\Http\Middleware;

use Closure;
use App\Library\Api;

class AdminApi
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
        $loggedInUser = Api::getAuthenticatedUser();
        if(!$loggedInUser['status']){
          $data['data']['error'] = $loggedInUser['error'];
          $statusCode =  422;
          return Api::ApiResponse($data, $statusCode);
        }
        if ($loggedInUser['user']->hasRole('Customer')) {
            $data['data']['error'] = 'Unauthorised Access';
            $statusCode =  422;
            return Api::ApiResponse($data, $statusCode);
        }
        return $next($request);
    }
}
