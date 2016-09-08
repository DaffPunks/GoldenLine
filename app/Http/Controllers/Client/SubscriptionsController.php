<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Decoder;
use App\Services\Translator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Subscription;
use App\Models\DictSubscription;
use App\Models\SubscriptionVisit;

use Request;

class SubscriptionsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function formatSubscriptions($subscriptions, $dictSubscriptions){

		//SubscriptionVisit

		$newSubscriptions = [];

		foreach($subscriptions as $subscription){
			foreach ($dictSubscriptions as $dictSubscription) {
				if($subscription->DICTSUBSCRIPTIONID == $dictSubscription->ID){

					$newSubscription = [];

					$newSubscription["name"] = Decoder::decodeName($dictSubscription->NAME);

					if($subscription->SUBSCRIPTIONSTATUSID == 2){
						$leftVisits = 0 + (int)$dictSubscription->VISITCOUNT - SubscriptionVisit::visitsCountForSubscriptionId($subscription->ID);
					}
					else{
						$leftVisits = 0;
					}

					$newSubscription["number"] = $subscription->NUMBER;
					$newSubscription["leftVisits"] = $leftVisits;

					$startDate = Carbon::parse($subscription->STARTDATE);
					$finishDate = Carbon::parse($subscription->FINISHDATE);

					$newSubscription["startDay"] = $startDate->day;
					$newSubscription["startMonth"] = trans('date.' . $startDate->format('F'));
					$newSubscription["endDay"] = $finishDate->day;
					$newSubscription["endMonth"] = trans('date.'. $finishDate->format('F'));
					$newSubscription["year"] = $finishDate->year;

					$newSubscriptions[] = $newSubscription;

					break;
				}
			}
		}
		return $newSubscriptions;
	}

	public function getDictSubscriptionsForSubscriptions($subscriptions){

		$length = count($subscriptions);
		if($length > 0) {

			$query = ' where ';
			$i = 0;
			foreach ($subscriptions as $subscription) {
				$id = $subscription->DICTSUBSCRIPTIONID;

				if ($i < $length - 1) {
					$query .= " id=$id or ";
				} else if ($i == $length - 1) {
					$query .= " id=$id ";
				}

				$i++;
			}

			$dictSubscriptions = DictSubscription::select('ID, NAME, VISITCOUNT ', $query);

			return $dictSubscriptions;
		}
		else{
			return [];
		}
	}

	public function index()
	{
		$clientId = Auth::user()->clientid;

		$subscriptions = Subscription::select("ID, DICTSUBSCRIPTIONID, NUMBER, SUBSCRIPTIONSTATUSID, STARTDATE, FINISHDATE", "where CLIENTID = $clientId order by SALEDATE desc ");
		$dictSubscriptions = $this->getDictSubscriptionsForSubscriptions($subscriptions);
		$formattedSubscriptions = $this->formatSubscriptions($subscriptions, $dictSubscriptions);

		return MainClientController::renderViewWithClientInfo('client.subscriptions', [
			'tab'=>'sport',
			'title' => 'Мои Абонементы',
			'subscriptions' => $formattedSubscriptions
		]);
	}

}
