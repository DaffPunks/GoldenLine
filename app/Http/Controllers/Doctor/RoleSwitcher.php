<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;


use Illuminate\Support\Facades\DB;
use App\User;

class RoleSwitcher extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return 'Here';
    }

    public static function switchRole(){

        if(Request::ajax())
        {
//            $data = Input::all();

            $user = Auth::user();
            $currentRole = $user->role == 2? 3 : 2;

            $user->role = $currentRole;

            if($user->save()){
                return 'Success';
            }
            else {
                return 'Fail';
            }
        }
    }
}
