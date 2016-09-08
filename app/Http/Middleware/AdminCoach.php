<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminCoach extends Role {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->guest())
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->guest('/login');
            }
        }
        else if(!is_null(auth()->user()['cabinetadminid']) && auth()->user()['cabinetadminid'] == 3){
            return $next($request);
        }

        return view('errors/404');
    }
}