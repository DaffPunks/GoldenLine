<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\RegisteredClient;

use App\Services\Decoder;

class MainClientController extends Controller {

    public static function renderViewWithClientInfo($viewName, $additionalData = array()){

        $currentUser = Auth::User();

        $saloonClientId = $currentUser->clientid;

        $registeredClient = RegisteredClient::findBySaloonId($saloonClientId);

        $name = Decoder::decodeName("$registeredClient->SURNAME $registeredClient->NAME");

        $userRole = 'client';
        if(Auth::user()->doctorid != null)
            $userRole = 'doctorAsClient';

        $clientInfo = array('userRole' => $userRole, 'username' => $name);

        return view($viewName, array_merge($clientInfo, $additionalData));
    }


}