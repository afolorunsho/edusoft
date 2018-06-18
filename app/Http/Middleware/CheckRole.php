<?php

namespace App\Http\Middleware;
use Closure;
use Symfony\Component\HttpFoundation\Session\Session;

use Auth;

class CheckRole
{
	public function handle($request, Closure $next) {
	
		$roles = array_slice(func_get_args(), 2); // [default, admin, manager]
		//$session_set = Session::get('_token');
		//if(empty($session_set) || $session_set == NULL) return redirect('/signon')->with('status', 'You have been timed out...');
		
		if( Auth::check() ) { 
			foreach ($roles as $role) {
				
				try {
					if (Auth::user()->hasRole($role)) {
						return $next($request);
					}
				} catch (Exception $e) {
					return redirect('/signon')->with('status', 'Sorry, you have to login again to continue...');
				}
			}
			return redirect('/dashboard')->with('status', 'Sorry, access to this task is restricted... Contact the System Administrator');
		}else{
			return redirect('/signon')->with('status', 'You have been timed out...');
		}
	}
}
