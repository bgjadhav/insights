<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AdxReportFromApi extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:AdxApiImportMarketplaceDeals';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Imports deals from MarketplaceDeals.list using Google AdExchangeBuyer API.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		echo "PROCESS STARTED\r\n";

		$service_account_name = 'laravel-adx@laravel-adx.iam.gserviceaccount.com';
		$key_file_location = storage_path().'/google_key/laravel-adx-41d5989f5875.p12';

		$client = new Google_Client();
		$client->setApplicationName('laravel-adx');

		$service = new Google_Service_AdExchangeBuyer($client);

		$key = file_get_contents($key_file_location);
		$cred = new Google_Auth_AssertionCredentials(
		    $service_account_name,
		    array('https://www.googleapis.com/auth/adexchange.buyer'),
		    $key
		);

		$client->setAssertionCredentials($cred);
		if($client->getAuth()->isAccessTokenExpired()) {
		  $client->getAuth()->refreshTokenWithAssertion($cred);
		}

		$_SESSION['service_token'] = $client->getAccessToken();

		if ($client->getAccessToken()) {
			echo "GOT ACCESS TOKEN\r\n";
			$service = new Google_Service_AdExchangeBuyer($client);

			$result = $service->accounts->listAccounts();

			//MP8466938, MP52211035
			echo "CALLING MARKETPLACE DEALS\r\n";
			$result = $service->marketplacedeals->listMarketplacedeals('-');

			$comma = '';
			$final_values = '';

			// Get proposal IDs separately so we dont exceed the daily calls limit
			$prop_comma = '';
			$proposal_ids = '';
			$count = 0;
			$final_props = array();
			$total = 0;
			$actual_ids = array();
			$inserted_ids = array();

			foreach($result as $r) {

				$actual_ids[] = $r->proposalId;
				$total++;
				if($count < 400) {
					$proposal_ids .= $prop_comma."'".$r->proposalId."'";
					$prop_comma = ',';
					$count++;
					// echo $r->flightStartTimeMs."\r\n";
				} else {
					// die;
					$prop_pqlQuery = "WHERE proposalId IN (".$proposal_ids.",'".$r->proposalId."')";
					$prop = $service->proposals->search(['pqlQuery' => $prop_pqlQuery]);

					foreach ($prop as $p) {
						$final_props[$p->proposalId]['setup_complete'] = $p->isSetupComplete;
						$final_props[$p->proposalId]['buyer_id'] = $p->buyer->accountId;
						$final_props[$p->proposalId]['billed_buyer_id'] = $p->billedBuyer->accountId;
						$inserted_ids[] = $p->proposalId;
					}
					echo "Batch reuslt: ".count($prop)."\r\n";
					sleep(5);
					$proposal_ids = '';
					$prop_comma = '';
					$count = 0;
				}
			}
			echo "\r\nTOTAL found: ".$total."\r\n";
			echo "\r\nTOTAL populated: ".count($final_props)."\r\n";
			echo "\r\nDiff count:".count(array_diff_key($actual_ids, $inserted_ids));

			$diff = array_diff_key($actual_ids, $inserted_ids);
			$diff_populated = 0;
			$diff_string = '';
			$diff_comma = '';
			foreach($diff as $d) {
				$diff_string .= $diff_comma."'".$d."'";
				$diff_comma = ',';
			}
			$diff_prop_pqlQuery = "WHERE proposalId IN (".$diff_string.")";
			$diff_prop = $service->proposals->search(['pqlQuery' => $diff_prop_pqlQuery]);

			foreach ($diff_prop as $p) {
				$final_props[$p->proposalId]['setup_complete'] = $p->isSetupComplete;
				$final_props[$p->proposalId]['buyer_id'] = $p->buyer->accountId;
				$final_props[$p->proposalId]['billed_buyer_id'] = $p->billedBuyer->accountId;
				$inserted_ids[] = $p->proposalId;
			}
			echo "\r\nTOTAL populated after missed ones: ".count($final_props)."\r\n";


			foreach($result as $r) {
				// Final variables
				$final_name 					= '';
				$final_proposal_id 				= '';
				$final_external_deal_id 		= '';
				$final_has_buyer_paused 		= '';
				$final_has_seller_paused 		= '';
				$final_type 					= '';
				$final_guaranteed_impressions 	= '';
				$final_guaranteed_looks 		= '';
				$final_pricing_type 			= '';
				$final_micros_amount 			= '';
				$final_currency 				= '';
				$final_flight_start 			= '';
				$final_flight_end 				= '';
				$final_is_setup_complete 		= '';
				$final_buyer_id 				= '';
				$final_billed_buyer_id 			= '';

				if($r->terms->nonGuaranteedAuctionTerms) {
					$final_type = 'Non Guaranteed Auction Terms';
					$final_pricing_type = $r->terms->nonGuaranteedAuctionTerms->reservePricePerBuyers[0]->price->pricingType;
					$final_micros_amount = $r->terms->nonGuaranteedAuctionTerms->reservePricePerBuyers[0]->price->amountMicros;
					$final_currency = $r->terms->nonGuaranteedAuctionTerms->reservePricePerBuyers[0]->price->currencyCode;
				} else if($r->terms->guaranteedFixedPriceTerms) {
					$final_type = 'Guaranteed Fixed Price Terms';
	   				$final_guaranteed_impressions = $r->terms->guaranteedFixedPriceTerms->guaranteedImpressions;
	   				$final_guaranteed_looks = $r->terms->guaranteedFixedPriceTerms->guaranteedLooks;
					$final_pricing_type = $r->terms->guaranteedFixedPriceTerms->fixedPrices[0]->price->pricingType;
					$final_micros_amount = $r->terms->guaranteedFixedPriceTerms->fixedPrices[0]->price->amountMicros;
					$final_currency = $r->terms->guaranteedFixedPriceTerms->fixedPrices[0]->price->currencyCode;
				} else {
					$final_type = 'Non Guaranteed Fixed Price Terms';
					$final_pricing_type = $r->terms->nonGuaranteedFixedPriceTerms->fixedPrices[0]->price->pricingType;
					$final_micros_amount = $r->terms->nonGuaranteedFixedPriceTerms->fixedPrices[0]->price->amountMicros;
					$final_currency = $r->terms->nonGuaranteedFixedPriceTerms->fixedPrices[0]->price->currencyCode;
				}

				// echo "Deal ID: ".$r->dealId."<br />";
				$final_name = $r->name;
				$final_proposal_id = $r->proposalId;
				$final_external_deal_id = $r->externalDealId;
				$final_has_buyer_paused = $r->dealServingMetadata['dealPauseStatus']['hasBuyerPaused'];
				$final_has_seller_paused = $r->dealServingMetadata['dealPauseStatus']['hasSellerPaused'];
				$final_flight_start = $r->flightStartTimeMs > 0 ? date("Y-m-d", $r->flightStartTimeMs/1000) : "0000-00-00";
				$final_flight_end = $r->flightEndTimeMs > 0 ? date("Y-m-d", $r->flightEndTimeMs/1000) : "0000-00-00";

				//$prop = $service->proposals->get($r->proposalId);
				if(!isset($final_props[$r->proposalId])) {
					// echo $r->proposalId;
					$prop_pqlQuery = "WHERE proposalId IN ('".$r->proposalId."')";
					$prop = $service->proposals->search(['pqlQuery' => $prop_pqlQuery]);
					foreach ($prop as $p) {
						$final_props[$p->proposalId]['setup_complete'] = $p->isSetupComplete;
						$final_props[$p->proposalId]['buyer_id'] = $p->buyer->accountId;
						$final_props[$p->proposalId]['billed_buyer_id'] = $p->billedBuyer->accountId;
					}
					echo "\r\nadding missing proposal: ".$r->proposalId;
					sleep(10);
					// var_dump($prop);
				}
				$final_is_setup_complete = $final_props[$r->proposalId]['setup_complete']; //$prop->isSetupComplete;
				$final_buyer_id = $final_props[$r->proposalId]['buyer_id']; //$prop->buyer->accountId;
				$final_billed_buyer_id = $final_props[$r->proposalId]['billed_buyer_id']; //$prop->billedBuyer->accountId;
				$final_rate = $final_micros_amount/1000000;

				$final_values .= $comma."(
					'".str_replace("'","\'",$final_name)."',
					'".$final_proposal_id."',
					'".$final_external_deal_id."',
					'".$final_has_buyer_paused."',
					'".$final_has_seller_paused."',
					'".str_replace("'","\'",$final_type)."',
					'".$final_guaranteed_impressions."',
					'".$final_guaranteed_looks."',
					'".str_replace("'","\'",$final_pricing_type)."',
					'".$final_rate."',
					'".$final_currency."',
					'".$final_flight_start."',
					'".$final_flight_end."',
					'".$final_is_setup_complete."',
					'".$final_buyer_id."',
					'".$final_billed_buyer_id."'
				)";
				$comma = ',';
			}


			echo "\r\nTruncating...";
			DB::reconnect('sergiu_partner_dashboard')
				->table('ADX_API')
				->truncate();
			echo "\r\nInserting new data...";
			DB::reconnect('sergiu_partner_dashboard')
				->insert("INSERT INTO ADX_API VALUES ".$final_values);
		}
	}
}
