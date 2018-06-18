<?php

namespace App\Http\Middleware;
use Closure;

class CheckRole
{
	public function handle($request, Closure $next)
    {
		// Get the required roles from the route
        $role = $this->getRequiredRoleForRoute($request->route());
        // Check if a role is required for the route, and
        // if so, ensure that the user has that role.
        if ($request->user()->hasRole($role) || !$role) {
            return $next($request);
        }
        return redirect()->back()->with('error', 'No permission');
		/*return response([
			//return redirect('/dashboard');
			'error' => ['code' => 'INSUFFICIENT_ROLE','description' => 'You are not authorized to access this resource. '.
				$request->user()->role. $request->user()->name]
		], 401);*/
		
		//return redirect()->route('noPermission');
		//echo "Roles: ".$roles;
		//return $next($request);
		
    }
	private function getRequiredRoleForRoute($route)
    {
        $actions = $route->getAction();
		return isset($actions['role']) ? $actions['role'] : null;
    }
}
