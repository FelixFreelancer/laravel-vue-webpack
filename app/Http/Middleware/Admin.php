<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (auth()->check() && auth()->user()->hasRole('Admin')) {
            return $next($request);
        } else {
            session()->flash('message', 'Unauthorized Access.');
            session()->flash('class', 'danger');
            return redirect()->route('home');
        }
    }
}
