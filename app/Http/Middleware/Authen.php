<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Authen
{
	public function handle($request, Closure $next, $guard = 'web')
    {
       if(!Auth::guard($guard)->check()){
			return redirect()->route('/');
		}
		return $next($request);
    }
}
