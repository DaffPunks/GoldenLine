<?php


namespace App\Http\Controllers\Reset;

use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Support\Facades\Hash;

class ClearTokensController extends Controller {


    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index()
    {
        return 0;
    }

    public function clearTokens(){
//        'UPDATE "users" a SET a."remember_token" = null;';
    }
}
