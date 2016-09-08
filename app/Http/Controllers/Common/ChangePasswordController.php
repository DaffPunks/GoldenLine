<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $data = Input::all();
        $oldPass = $data['oldPass'];
        $newPass = $data['newPass'];

        if(Request::ajax())
        {
            $user = Auth::user();

            $response = null;
            if(Hash::check($oldPass, $user->password)){

                if(strlen($newPass) >= 6){

                    $user->password = Hash::make($newPass);
                    $user->save();
                    $response = new Response('Success');
                }
                else{
                    $response = new Response('Новый пароль слишком короткий. Минимум 6 символов');
                }
            }
            else{
                $response = new Response('Неправильный старый пароль');
            }
            return $response;
        }

        return 0;
    }
}
