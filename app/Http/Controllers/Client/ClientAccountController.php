<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Client;
use App\Models\CardType;

use Illuminate\Support\Facades\Input;
use Request;

class ClientAccountController extends Controller {

    public function getAccountData(){

        if(Request::ajax()) {

            $currentUser = Auth::User();
            $client = Client::find($currentUser->clientid);

            $debt = $client->DOLG;
            $deposit = $client->DEPOSIT - $debt;
            $bonus = $client->BONUS;

            $accountData = [];
            $accountData['deposit'] = $deposit;
            $accountData['bonus'] = $bonus;
            $accountData['discount'] = $this->getDiscount($client);

            return $accountData;
        }
    }

    function getDiscount($client){

        if(isset($client->CARDTYPEID)){
            $card = CardType::find($client->CARDTYPEID);

            return $card->DISCOUNT;
        }
        else
            return 0;
    }
}