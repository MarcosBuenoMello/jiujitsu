<?php

namespace App\Http\Middleware;

use Closure;
use Response;

class ValidaLogin
{
	public function handle($request, Closure $next){
		$value = session('user_logged');
		if($value){
			return redirect('/dashboard');
		}
		return $next($request);
	}

}