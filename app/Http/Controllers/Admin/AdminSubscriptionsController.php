<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Services\Decoder;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;
//use Illuminate\Contracts\Routing\ResponseFactory;

use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\RegisteredClient;
use App\User;

class AdminSubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


}