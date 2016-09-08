<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Client extends Role{

    protected $col_name_client = 'clientid';
    protected $col_name_doctor = 'doctorid';

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
        else if( !is_null(auth()->user()[$this->col_name_client]) || !is_null(auth()->user()[$this->col_name_doctor]) ){
            return $next($request);
        }

        return view('errors/404');
    }
}