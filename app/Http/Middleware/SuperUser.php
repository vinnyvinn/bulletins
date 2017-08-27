<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class SuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect('/404');
        }

        $permissions = \json_decode(Auth::user()->permissions);

        if (! \in_array('*', $permissions)) {
            return redirect('/404');
        }

        return $next($request);
    }
}
