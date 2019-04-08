<?php

namespace App\Http\Middleware;

use Closure;

class InactiveRedirect
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
        if (auth()->check()) {
            if (date('Y-m-d H:i:s', strtotime(auth()->user()->last_activity)) < date('Y-m-d H:i:s', strtotime('-' . config('site.inactivity_logout') . ' min'))) {
                auth()->logout();
                session()->flash('message', 'Your session expired please login again.');
                session()->flash('class', 'danger');
                return redirect()->route('login.index');
            } else {
                auth()->user()->update([
                    'last_activity' => date_time_database('now')
                ]);
            }
        }
        return $next($request);
    }
}
