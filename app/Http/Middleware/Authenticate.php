<?php

namespace App\Http\Middleware;
use Closure;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $guards = [];

    public function handle($request, Closure $next, ...$guards)
    {
        $this->guards = $guards;
        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ( ! $request->expectsJson()) {

            if (in_array('admin', $this->guards, true)) {
                return route('admin.login');
            }

            return route('web.login');
        }

        return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
    }
}
