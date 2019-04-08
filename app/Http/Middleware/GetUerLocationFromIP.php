<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Country;
class GetUerLocationFromIP
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
        if (!session()->has('location')) {
            $location = geoip(request()->ip());
            if($location->iso_code != NULL){
              $country = Country::where('iso',$location->iso_code)->first();
            }
            $location['short_code'] = isset($country) ? $country['short_code'] : NULL;
            session([
                'location' => $location
            ]);
        }
        return $next($request);
    }
}
