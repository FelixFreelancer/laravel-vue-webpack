<?php

namespace App\Http\Middleware;

use Closure;

class NotificationRead
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
        if (request('notification_id')) {
            auth()->user()->notifications()->where('id', '=', request('notification_id'))->update([
                'read_at' => date_time_database('now')
            ]);
        }
        return $next($request);
    }
}
