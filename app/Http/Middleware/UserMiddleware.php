<?php

namespace App\Http\Middleware;

use Closure;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {

    	$sessionId = $request->header('token');
		session_id($sessionId);
		session_start();
		if (empty($role)) {
			if (isset($_SESSION['id'])) {
				return $next($request);
			} else {
				return response()->json(['code' => -1, 'msg' => 'no auth']);
			}
		}
		if (isset( $_SESSION['id'] ) and $_SESSION['type'] === $role) {
			return $next($request);
		} else {
			return response()->json(['code' => -1, 'msg' => 'no auth']);
		}
    }
}
