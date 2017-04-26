<?php
class WidgetsController extends Controller
{

	public function website_uniques_page($start_date = null, $end_date = null) {
		if($start_date===NULL){
			$start_date=date('Y-m-d', strtotime("-7 day"));
		}
		if($end_date===NULL){
			$end_date=date('Y-m-d', strtotime("-1 day"));
		}

		try {
			$data = DB::reconnect('dashboard')->
				table('open-page-views')->
				select('name', 'unique_views', 'unique_users')->
				where('interval', '30_DAY')->
				orderBy('UNIQUE_VIEWS', 'desc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach ($data as &$value) {
			$value->name=str_replace("_", " ", $value->name);
			$value->name=ucwords($value->name);
		}

		$return = array(
			"success" => true,
			"title" => "Open Website <strong>Page Views</strong> <i>(last 7 days)</i>",
			"info" => 'Showing unique views and unique users that have visited the various parts of the open website within the last 7 days',
			"type" => 'table',
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Page Name", "Unique Views", "Unique Users"
				),
				"columns" => array(
					0 => array(
						"bold" => true,
						"align" => "left"
					)
				)
			),
			"data" => $data
		);
		return Response::json($return);
	}


	public function christmas_party_countdown()
	{

		$data = '2015/12/5 17:00:00';

		$return = array(
			"success" => true,
			"title" => 'Christmas <strong>Party</strong>',
			"type" => 'countdown',
			"class" => 'christmas',
			"data" => $data
		);

		return Response::json($return);
	}

	public function certified($type) {
		try {
			if($type == 'buyers') {
				$certified = DB::reconnect('dashboard')->
					table('certified-buyers')->
					select('org_id', 'org_name', 'score' )->
					where('org_id', '!=', '0')->
					orderBy('score', 'desc')->
					remember(Format::timeOut())->
					get();
				DB::disconnect('dashboard');

			} else if($type == 'partners') {
				$certified = DB::reconnect('dashboard')->
					table('certified-partners')->
					select('partner_id', 'partner_name', 'score' )->
					where('partner_id', '!=', '100315')->
					orderBy('score', 'desc')->
					remember(Format::timeOut())->
					get();
				DB::disconnect('dashboard');
			}
		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$total = array(
			"Platinum" => 0,
			"Gold" => 0,
			"Silver" => 0
		);

		$rows = array(
			"Platinum" => array(),
			"Gold" => array(),
			"Silver" => array()
		);

		foreach($certified as $row) {
			if($type == 'buyers') {
				if($row->score > 49) {
					$total['Platinum']++;
					array_push($rows['Platinum'], $row);
				} else if($row->score > 34) {
					$total['Gold']++;
					array_push($rows['Gold'], $row);
				} else if($row->score > 24) {
					$total['Silver']++;
					array_push($rows['Silver'], $row);
				}
			} else {
				if($row->score > 54) {
					$total['Platinum']++;
					array_push($rows['Platinum'], $row);
				} else if($row->score > 39) {
					$total['Gold']++;
					array_push($rows['Gold'], $row);
				} else if($row->score > 24) {
					$total['Silver']++;
					array_push($rows['Silver'], $row);
				}
			}
		}

		$return = array(
			"success" => true,
			"title" => 'Certified <strong>' . $type . '</strong>',
			"type" => 'certified',
			"class" => 'certified-' . $type,
			"certified" => array(
				"type" => $type
			),
			"data" => $rows,
			"total" => $total
		);
		if($type == 'buyers') {
			$return['info'] = 'Showing the number of certified  buyers, by which we mean a MediaMath client within T1, and the level they have achieved so far. Click a cert level to see the buyers';
		} else {
			$return['info'] = 'Showing the number of certified  partners, by which we mean a company MediaMath is integrated with, and the level they have achieved so far. Click a cert level to see the partners';
		}

		return Response::json($return);
	}

	public function billed_spend() {

		$data = array(
			array(
				"name" => "Billed",
				"y" => 65
			),
			array(
				"name" => "Unbilled",
				"y" => 35
			)
		);

		$return = array(
			"success" => true,
			"title" => 'Billed <strong>Spend</strong>',
			"type" => 'chart',
			"class" => 'billed-spend',
			"chart" => array(
				"type" => "pie"
			),
			'tooltip'	=> array(
					'pointFormat'	=> 'Impressions'
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function open_marketplace() {

		try {
			$data = DB::reconnect('dashboard')->
				table('open-marketplace-share-of-voice')->
				select('exchange_name', 'sov_impressions')->
				where('interval','7_DAYS')->
				remember(Format::timeOut())->
				get();

			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$supply_array = array();

		foreach($data as $key => $sov){

			$supply_array[$key]['name'] = $sov->exchange_name;
			$supply_array[$key]['y'] = (int)$sov->sov_impressions;

		}

		$return = array(
			"success" => true,
			"title" => 'Open Marketplace <strong>Share of Voice</strong><br><i>(last 7 days)</i>',
			"info" => "Showing the overall volume of impressions and % that each of the OPEN marketplace supply sources made up in the last 7 days. Hover over for values and %",
			"type" => 'chart',
			"class" => 'open-marketplace',
			"chart" => array(
				"type" => "pie"
			),
			'tooltip'	=> array(
					'pointFormat'	=> 'Impressions'
			),
			"data" => $supply_array,
		);

		return Response::json($return);
	}

	public function open_exchanges() {

		try {
			$data = DB::reconnect('dashboard')->
				table('open-exchange-share-of-voice')->
				select('exchange_name', 'sov_media_cost')->
				where('interval','7_DAYS')->
				orderBy('sov_media_cost', 'DESC')->
				remember(Format::timeOut())->
				get();

			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$exchangeNames = array();
		$impressionsData = array();

		foreach($data as $key=>$value){
			$exchangeNames[]=$value->exchange_name;
			$impressionsData[]=(int)$value->sov_media_cost;
		}

		$exchanges = array(
			array(
				"name" => "exchanges",
				"data" => $impressionsData
			)
		);

		$return = array(
			"success" => true,
			"title" => 'Open Exchanges & SSPs <strong>Share of Voice</strong> <i>(last 7 days)</i>',
			"info" => 'Showing the overall volume of media cost of all of the open exchanges & SSPs not in the OPEN marketplace in the last 7 days. Hover over for values',
			"type" => 'chart',
			"class" => 'open-exchanges',
			"chart" => array(
				"type" => "column",
				"categories" => $exchangeNames
			),
			"data" => $exchanges,
		);

		return Response::json($return);
	}

	public function global_deals() {

		try {
			$data = DB::reconnect('dashboard')->
				table('global-deals')->
				select('exchange_name', 'impressions')->
				where('interval','7_DAYS')->
				remember(Format::timeOut())->
				get();

			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$supply_array = array();

		foreach($data as $key => $sov){
			$supply_array[$key]['name'] = str_replace('Partner Sites', 'Partners', $sov->exchange_name);
			$supply_array[$key]['y'] = (int)$sov->impressions;
		}

		$return = array(
			"success" => true,
			"title" => 'Global Deals <strong>Share of Voice</strong> <br /><i>(last 7 days)</i>',
			"info" => 'Showing the volume of impressions by supply source for those that we have integrated global deals with. Hover for values and %',
			"type" => 'chart',
			"class" => 'global-deals',
			"chart" => array(
				"type" => "pie"
			),
			'tooltip'	=> array(
					'pointFormat'	=> 'Impressions'
			),
			"data" => $supply_array,
		);

		return Response::json($return);
	}

	public function privileged_supply() {

		try {
			$all_data = DB::reconnect('dashboard')->
				table('privileged-supply')->
				select('mm_date', 'percentage', 'type')->
				orderBy(DB::raw('type = "Open Exchange & SSPs"'), 'DESC')->
				orderBy(DB::raw('type = "iAds"'), 'DESC')->
				orderBy('type','DESC')->
				orderBy('mm_date', 'ASC')->
				remember(Format::timeOut())->
				get();

			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		try {
			$totals = DB::reconnect('dashboard')->
				table('privileged-supply')->
				select('*', DB::raw('sum(percentage) as percent'))->
				where('mm_date', DB::raw('(SELECT max(mm_date) FROM `privileged-supply`)'))->
				where('category', '!=', '')->
				groupBy('category')->
				remember(Format::timeOut())->
				get();

			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$all = array();
		$data = array();
		$totaldata = array();

		// get all dates
		$dates = array();
		foreach($all_data as $key=>$value) {
			array_push($dates, $value->mm_date);
		}
		$dates = array_unique($dates);

		// set all values as 0
		foreach($all_data as $key=>$value) {
			foreach($dates as $date) {
				$all[$value->type][$date] = 0;
			}
		}

		// add actual values
		foreach($all_data as $key=>$value) {
			$all[$value->type][$value->mm_date] = (float)$value->percentage;
		}

		// put it all in the correct format
		foreach($all as $key=>$value) {
			$data[] = array(
				"name" => $key,
				"data" => array_values($value)
			);
		}

		foreach($totals as $key=>$value) {
			$totaldata[$value->category] = number_format($value->percent,2);
		}

		$months_names = array();
		for ($i = count($data[0]['data']); $i >= 0; $i--) {
			$months_names[] = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
		}

		$return = array(
			"success" => true,
			"title" => 'Privileged/Private <strong>Supply</strong> <i>(last 6 months)</i>',
			"info" => 'Showing the breakout of privileged supply in the last 6 months. Hover for %. You can show and hide options by clicking on the legend.',
			"type" => 'chart',
			"class" => 'revenue',
			"chart" => array(
				"type" => "area",
				"categories" => $months_names
			),
			"extras" => array(
				"type" => "percent",
				"data" => $totaldata
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function partner_offices() {

		try {
			$data = DB::reconnect('dashboard')->
				table('office-locations')->
				select('lat', 'long')->
				remember(Format::timeOut())->
				get();

			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => 'Partner Office <strong>Locations</strong>',
			"info" => 'Showing the office locations of OPEN partners all over the world',
			"type" => 'map',
			"class" => 'office-location',
			"data" => $data,
		);

		return Response::json($return);
	}

	public function revenue() {

		$data = array(
			array(
				"name" => "Billed",
				"y" => 30
			),
			array(
				"name" => "Unbilled",
				"y" => 30
			),
			array(
				"name" => "Other",
				"y" => 40
			)
		);

		$return = array(
			"success" => true,
			"title" => '<strong>Revenue</strong>',
			"type" => 'chart',
			"class" => 'revenue',
			"chart" => array(
				"type" => "pie"
			),
			'tooltip'	=> array(
					'pointFormat'	=> 'Impressions'
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function media_partner() {

		try {
			$people = DB::reconnect('mysql')->
				table('people')->
				select('id', 'full_name', 'machine', 'skype_id', 'country', 'updated_at')->
				orderBy('full_name', 'asc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => 'Media Partner <strong>Breakdown</strong>',
			"type" => 'table',
			"class" => 'revenue',
			"table" => array(
				"export" => true,
				"thead" => true,
				"headings" => array(
					"id", "Full Name", "Machine", "Skype", "Country", "Last Updated"
				),
				"columns" => array(
					array(
						"index" => 0,
						"bold" => true,
						"centered" => true,
						"color" => 'pink'
					)
				)
			),
			"data" => $people,
		);

		return Response::json($return);
	}

	public function open_revenue($months) {

		$data = array(
			array(
				"name" => "<span>PMP</span> Marketplace",
				"data" => array(10,14,22,12,9,14,9)
			),
			array(
				"name" => "<span>PMP</span> Marketplace",
				"data" => array(2,4,7,9,14,12,19,9)
			),
			array(
				"name" => "<span>PMP</span> Marketplace",
				"data" => array(6,7,2,5,8,15,14,9)
			),
			array(
				"name" => "<span>PMP</span> Marketplace",
				"data" => array(6,7,2,5,8,15,14,9)
			)
		);

		$months_names = array();
//		for ($i = $months-1; $i >= 0; $i--) {
//			$months_names[] = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
//		}
//		$months_names[] = date("M", strtotime( date( 'Y-m-01' )." +1 months"));

		$return = array(
			"success" => true,
			"title" => 'Inventory <strong>Split</strong>',
			"type" => 'chart',
			"class" => 'revenue',
			"chart" => array(
				"type" => "area",
//				"categories" => $months_names
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function partner_types() {

		try {
			$data = DB::reconnect('dashboard')->
				table('partner-types')->
				select('name', 'count')->
				orderBy('name', 'asc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => 'Partner <strong>Types</strong>',
			"type" => 'table',
			"class" => 'revenue',
			"info" => "Showing the number of partners that MediaMath have integrated with and who's data can be leveraged in T1.",
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Partner Type", "Partner Count"
				),
				"columns" => array(
					0 => array(
						"bold" => true,
						"align" => 'left'
					)
				)
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function kpi($type) {

		if($type == 'gating') {
			$table = 'kpi-open-gating';
			$name = 'Open Gating <strong>Oportunities KPI<strong>';
		} else if($type == 'sra') {
			$table = 'kpi-sra';
			$name = 'SRA <strong>Oportunities KPI<strong>';
		}

		try {
			$data = DB::reconnect('dashboard')->
				table($table)->
				select('name', 'opened', 'closed')->
				whereIn('id', function($query) use ($table) {
					$query->select(DB::raw('max(id)'))->
					from($table)->
					groupBY('name');
				})->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		// add totals
		$total = array(
			"name" => "Total",
			"opened" => 0,
			"closed" => 0
		);
		foreach($data as $row) {
			$total['opened'] += $row->opened;
			$total['closed'] += $row->closed;
		}
		array_push($data, $total);

		$return = array(
			"success" => true,
			"title" => $name,
			"type" => 'table',
			"class" => 'open-kpi',
			"data" => $data,
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Name", "Open Tickets", "Closed Tickets"
				),
				"columns" => array(
					0 => array(
						"bold" => true,
						"align" => 'left'
					)
				)
			),
		);

		return Response::json($return);
	}

	public function message_count() {

		try {
			$data = DB::reconnect('dashboard')->
				table('message-count-last-30-days')->
				select('type', 'count')->
				where('interval', '30_DAY')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$array = array();

		foreach($data as $row) {
			$array[$row->type] = $row->{'count'};
		}

		$return = array(
			"success" => true,
			"title" => "Message <strong>Count</strong> <i>(last 30 days)</i>",
			"info" => "Showing the number of messages that have been sent by buyers and partners via the open website messaging system in the last 30 days",
			"type" => 'count',
			"count" => array(
				"commas" => true
			),
			"class" => 'message-count',
			"data" => $array
		);

		return Response::json($return);
	}

	public function private_marketplace() {

		try {
			$data = DB::reconnect('dashboard')->
				table('private-marketplace')->
				select(DB::raw('deals as "Deals Initiated"'), DB::raw('spend as "Declared Spend"'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Private Marketplace <strong>Info</strong> <i>(last 30 days)</i>",
			"info" => "Showing the number of deals that have been initiated on automated guaranteed in the last 30 days and the total spend that was declared across them",
			"type" => 'count',
			"count" => array(
				"currency" => array("","$"),
				"commas" => array(false,true)
			),
			"class" => 'private-marketplace',
			"data" => $data
		);

		return Response::json($return);
	}

	public function open_visits() {

		try {
			$data = DB::reconnect('dashboard')->
				table('open-visits')->
				select(DB::raw('visits as "Unique<br>Visitors"'), DB::raw('logged_in as "Logged In Visitors"'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "OPEN <strong>Visitors</strong> <i>(last 30 days)</i>",
			"info" => 'Showing overall unique visitors to open.mediamath.com and users who have actively logged in',
			"type" => 'count',
			"count" => array(
				"commas" => true
			),
			"class" => 'open-visits',
			"data" => $data
		);

		return Response::json($return);
	}

	public function deal_discovery_advertisers() {

		try {
			$data = DB::reconnect('dashboard')->
				table('deal-discovery')->
				select(DB::raw('advertisers as "Unique<br>Advertisers"'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Deal Discovery <strong>Usage</strong>",
			"info" => "Showing the number of unique advertisers that have initiated a deal with a publisher via Deal Discovery",
			"type" => 'count',
			"count" => array(
				"commas" => true
			),
			"class" => 'deal-discovery-advertisers',
			"data" => $data
		);

		return Response::json($return);
	}

	public function global_deals_overview() {

		try {
			$data = DB::reconnect('dashboard')->
				table('global-deals-overview')->
				select(DB::raw('number as "Global Deals<br>Spending"'))->
				where('interval','7_DAYS')->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Global <strong>Deals</strong> <i>(last 7 days)</i>",
			"info" => "Showing the number of global deals that have spent in the last 7 days",
			"type" => 'count',
			"count" => array(
				"commas" => true
			),
			"class" => 'single-count',
			"data" => $data
		);

		return Response::json($return);
	}

	public function raptor_attack() {

		try {
			$data = DB::reconnect('dashboard')->
				table('raptor-attack')->
				select(DB::raw('DATEDIFF(NOW(),date) as "Days <strong>Since</strong> Last"'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "",
			"info" => "Showing the number of days since the OPEN development team experienced a serious outage such as a server going down for a period of time",
			"type" => 'count',
			"count" => array(
				"commas" => true
			),
			"class" => 'raptor-attack',
			"data" => $data
		);

		return Response::json($return);
	}

	public function employee_visits() {

		try {
			$data = DB::reconnect('dashboard')->
				table('open-visits')->
				select(DB::raw('employees as "MediaMath<br>Employee Visitors"'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "OPEN Employee <strong>Visitors</strong> <i>(last 30 days)</i>",
			"info" => "Showing the number of of visits to open.mediamath.com within the last 30 days by MediaMath employees",
			"type" => 'count',
			"class" => 'employee-visits',
			"data" => $data
		);

		return Response::json($return);
	}

	public function ag_spend() {

		try {
			$data = DB::reconnect('dashboard')->
				table('ag-spend')->
				select(DB::raw('spend as "Automated Guaranteed<br>Spend"'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Automated Guaranteed <strong>Spend</strong>",
			"info" => "Showing the overall media cost on automated guaranteed since it came online in Autumn 2014",
			"type" => 'count',
			"count" => array(
				"currency" => "$",
				"commas" => true
			),
			"class" => 'ag-spend',
			"data" => $data
		);

		return Response::json($return);
	}

	public function pending_partners() {
		try {
			$data = DB::reconnect('dashboard')->
				table('open-messages-sent')->
				select(DB::raw('sum(buyer_messages) as "Buyer Messages"'), DB::raw('sum(partner_messages ) as "Partner Messages"'))->
				where('date', '>', DB::raw('DATE_SUB(NOW(),INTERVAL 31 DAY)'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Message <strong>Count</strong> <i>(last 30 days)</i>",
			"type" => 'count',
			"class" => 'message-count',
			"data" => $data
		);

		return Response::json($return);
	}

	public function open_revenue_target_data() {
		try {
			$results = DB::reconnect('dashboard')->
				table('open-revenue')->
				select('id', 'month', 'target', 'actual', 'low_forecast', 'high_forecast')->
				whereIn('id', function($query) {
					$query->select(DB::raw('max(id)'))->
					from('open-revenue')->
					groupBY('month');
				})->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		return $results;
	}

	public function open_revenue_target() {
		$results = $this->open_revenue_target_data();

		$data = array(
			array(
				"name" => "Actual",
				"type" => "column",
				"data" => array()
			),
			array(
				"name" => "Target",
				"type" => "spline",
				"data" => array()
			),
			array(
				"name" => "Low Forecast",
				"type" => "spline",
				"data" => array()
			),
			array(
				"name" => "High Forecast",
				"type" => "spline",
				"data" => array()
			)
		);
		function stringToInt($value) {
			if($value == '0' || $value == '') {
				$value = null;
			} else {
				$value = (int)$value;
			}
			return $value;
		}
		foreach($results as $row) {
			array_push($data[0]['data'], stringToInt($row->actual));
			array_push($data[1]['data'], stringToInt($row->target));
			array_push($data[2]['data'], stringToInt($row->low_forecast));
			array_push($data[3]['data'], stringToInt($row->high_forecast));
		}

		array_push($data[0]['data'], null);

		$months_names = array();
		for ($i = count($results) -1; $i >= 0; $i--) {
			$months_names[] = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
		}
		$months_names[] = date("M", strtotime( date( 'Y-m-01' )." +1 months"));

		$return = array(
			"success" => true,
			"title" => "Open <strong>Revenue</strong> <i>(last 12 months)</li>",
			"type" => 'chart',
			"info" => "Showing the revenue targets, forecasts and actuals of the OPEN team by month for the last year. Hover over for values",
			"chart" => array(
				"type" => "line",
				"cut" => false,
				"categories" => $months_names
			),
			"edit" => User::hasRole(['Financial']),
			"data" => $data
		);

		return Response::json($return);
	}

	public function top_organisations() {
		try {
			$results = DB::reconnect('dashboard')->
				table('top-10-orgs')->
				select('org_name', 'total_spend', 'billed_spend')->
				where('interval', '30_DAY')->
				orderBy('billed_spend', 'desc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => $result) {
			$results[$key]->total_spend = number_format($result->total_spend, 0);
			$results[$key]->billed_spend = number_format($result->billed_spend, 0);
		}

		$return = array(
			"success" => true,
			"title" => "Top 10 <strong>Organisations</strong>",
			"info" => 'Showing the top spending organizations in T1 in the last 7 days',
			"type" => 'table',
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Name",
					"Total Spend",
					"Billed Spend"
				)
			),
			"data" => $results
		);

		return Response::json($return);
	}

	public function web_vs_inapp() {

		try {
			$data = DB::reconnect('dashboard')->
				table('web-vs-inapp')->
				select('type', 'media_cost')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$rows = array();
		foreach($data as $key => $row){
			$rows[$row->type] = (int)$row->media_cost;
		}

		$return = array(
			"success" => true,
			"title" => 'Web vs <strong>in-app</strong> <i>(30 Days)</i>',
			"info" => 'Showing the volume of media cost on web and in-app on mobile inventory in the last 30 days',
			"type" => 'count',
			"count" => array(
				"currency" => '$',
				"commas" => true
			),
			"data" => $rows,
			"class" => 'web-inapp'
		);

		return Response::json($return);
	}

	public function message_topics() {

		try {
			$data = DB::reconnect('dashboard')->
				table('message-topics')->
				select('name', 'messages')->
				where('interval', '30_DAY')->
				orderBy('name', 'asc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => 'Message <strong>Topics</strong> <em>(30 Days)</em>',
			"type" => 'table',
			"class" => 'revenue',
			"info" => "Showing the volume of messages sent in the last 30 days by category",
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Message Type", "Message Count"
				),
				"columns" => array(
					0 => array(
						"bold" => true,
						"align" => 'left'
					)
				)
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function region_exchange_sov() {
		try {
			$results = DB::reconnect('dashboard')->
				table('region-sov')->
				select('exchange_name', 'region', 'media_cost', 'percentage')->
				orderBy('percentage', 'desc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		try {
			$regions = DB::reconnect('dashboard')->
				table('region-sov-figures')->
				select('sov', 'region', 'percentage')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$data = array();

		foreach($regions as $key => $row) {
			$data[$row->region][$row->sov] = number_format($row->percentage, 2) . '%';
			$data[$row->region]['data'] = array();
		}
		$data['Global']['data'] = array();
		$data['Global']['main'] = true;

		foreach($results as $key => $row) {
			array_push($data[$row->region]['data'], array(
				'exchange_name'	=> $row->exchange_name,
				'percentage' 	=> number_format($row->percentage, 2) . '%'
			));
		}

		foreach($data as $key => $row) {
			$data[$key]['data'] = array_slice($row['data'], 0, 10);
		}

		$return = array(
			"success" => true,
			"title" => 'Region SOV <em>(Global)</em>',
			"type" => 'table-compare',
			"info" => "Showing the global split of media cost across open exchanges and SSPs and in each individual business region. Figures showing the % the region as a whole makes up of global supply and how that has changed from last month is also shown when you click on the region tab.",
			"table" => array(
				"thead" => false,
				"columns" => array(
					0 => array(
						"bold" => true,
						"align" => 'left'
					)
				)
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function vendor_sov() {
		try {
			$results = DB::reconnect('dashboard')->
				table('vendor-sov')->
				select('vendor_id', 'vendor_name', 'billed_vendor_cost', 'percentage')->
				orderBy('percentage', 'desc')->
				limit('10')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$data = array();
		foreach($results as $key => $value) {
			$data[$key] = array(
				"Top performers" => preg_replace("/\([^)]+\)/","", $value->vendor_name),
				"SOV" => number_format($value->percentage, 2) . '%'
			);
		}

		$return = array(
			"success" => true,
			"title" => 'Vendor <strong>SOV</strong> <em>(Top 10)</em>',
			"type" => 'table',
			"info" => "Showing the % makeup of billed vendor cost across the biggest 10 vendors.
",
			"table" => array(
				"thead" => true,
				"columns" => array(
					0 => array(
						"bold" => true,
						"align" => 'left'
					)
				)
			),
			"data" => $data,
		);

		return Response::json($return);
	}

	public function certified_buyer_spend() {
		try {
			$results = DB::reconnect('dashboard')->
				table('certified-buyer-spend')->
				select('MONTH', 'LEVEL', 'PERCENT')->
				orderBy('ID', 'asc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		try {
			$totals = DB::reconnect('dashboard')->
				table('certified-buyer-spend')->
				select('MONTH', DB::raw('sum(PERCENT) as PERCENT'))->
				groupBy('MONTH')->
				orderBy('ID', 'asc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$data = array(
			"Silver" => array(),
			"Gold" => array(),
			"Platinum" => array(),
			"Total" => array(
				"type" => "spline",
				"name" => "Total",
				"dashStyle" => 'dash',
				"data" => array()
			)
		);
		// set defaults
		foreach($results as $row) {
			$data[$row->LEVEL] = array(
				"name" => $row->LEVEL,
				"data" => array()
			);
		}
		// add values to correct level
		foreach($results as $row) {
			if( !next( $results ) ) {
				$date = explode('-', $row->MONTH)[0];
			}
			array_push($data[$row->LEVEL]['data'], $row->PERCENT*100);
		}
		// add totals
		foreach($totals as $row) {
			array_push($data['Total']['data'], $row->PERCENT*100);
		}

//		echo "<pre>";

		// get previous months names
		$months_names = array();
		for ($i = 0; $i < count($data['Silver']['data']); $i++) {
			$months_names[] = date('M', strtotime ( '-'.$i.' months' , strtotime ( $date ) ));
		}

		$return = array(
			"success" => true,
			"title" => 'Certified Buyer <strong>Spend</strong>',
			"type" => 'chart',
			"info" => "Showing % of overall billed spend by certified buyers by month",
			"chart" => array(
				"type" => "line",
				"cut" => false,
				"categories" => array_reverse($months_names)
			),
			"data" => array_values($data),
		);

		return Response::json($return);
	}

	public function creative_bulk_uploader_spend() {

		try {
			$data = DB::reconnect('dashboard')->
				table('creative-bulk-uploader-spend')->
				select(DB::raw('media_cost as `Media Cost`'))->
				where('t1_bulk_upload', 0)->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Creative Bulk Uploader (30 Days)",
			"info" => "Showing media spend on creatives uploaded using the OPEN bulk creative uploader in the last 30 days.",
			"type" => 'count',
			"count" => array(
				"currency" => "$",
				"commas" => true
			),
			"class" => 'ag-spend creative-bulk-uploader',
			"data" => $data
		);

		return Response::json($return);
	}

	public function organisations() {
		try {
			$results = DB::reconnect('warroom')->
				table('META_ORGS')->
				select(DB::raw('CONCAT("id", ORG_ID) as ORG_ID'), 'ORG_NAME')->
				where('ORG_ID', '!=', '0')->
				orderBy('ORG_NAME', 'ASC')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		return Response::json($results);
	}

	public function exchanges() {
		try {
			$results = DB::reconnect('analytics')->
				table('META_EXCHANGE')->
				select(DB::raw('CONCAT("id", EXCH_ID) as EXCH_ID'), 'EXCH_NAME')->
				orderBy('EXCH_NAME', 'ASC')->
				remember(Format::timeOut())->
				get();
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		return Response::json($results);
	}

	public function countries() {
		try {
			$results = DB::reconnect('analytics')->
				table('META_COUNTRY')->
				select('COUNTRY')->
				orderBy('COUNTRY', 'ASC')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		return Response::json($results);
	}

	public function publisher_query_tool() {

		try {
			$results = DB::reconnect('dashboard')->
				table('publisher-query-tool')->
				select('id', 'name', 'time_started', 'time_finished', 'user_id', 'qubole_query_id', 'status', 'first_name', 'last_name', 'progress')->
				orderBy('time_started', 'DESC')->
				limit('10')->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => $result) {
			if($result->time_finished) {
				$results[$key]->status = 'done';
			} else {
				$results[$key]->status = 'active';
			}
			$results[$key]->initials = $result->first_name[0] . $result->last_name[0];
		}

		$data = array(
			"items" => $results
		);

		$return = array(
			"success" => true,
			"info" => "",
			"type" => 'publisher-query-tool',
			"data" => $data,
			"permission" => User::hasRole(['PublisherTool']),
			"user_id" => Session::get('user_id'),
			"info" => "This publisher query tool allows the OPEN media team to run ad-hoc queries to show publisher performance comparisons on open auction vs private markerplace"
		);

		return Response::json($return);
	}

	public function publisher_query_tool_save() {

		$count = DB::reconnect('dashboard')->
				table('publisher-query-tool')->
				where('user_id', Input::get('user_id'))->
				where('status', 'pending')->
				count();
		DB::disconnect('dashboard');

		if($count > 0) {
			return Response::json(array('success' => false, 'error' => 'count'));
		} else {
			try {
				$data = array(
					"name" => Input::get('name'),
					"deal_id" => Input::get('deal_id'),
					"pmp" => Input::get('pmp'),
					"oa" => Input::get('oa'),
					"user_id" => Input::get('user_id'),
					"organizations" => @implode(',', Input::get('organisations')),
					"exchanges" => @implode(',', Input::get('exchanges')),
					"countries" => @implode(',', Input::get('countries')),
					"urls" => @implode(',', Input::get('urls')),
					"status" => 'pending',
					"first_name" => Session::get('first_name'),
					"last_name" => Session::get('last_name'),
					"user_email" => Session::get('user_email')
				);

				DB::reconnect('dashboard')->table('publisher-query-tool')->insert($data);
				DB::disconnect('dashboard');

				return Response::json(array('success' => true));
			} catch(Exception $e) {
				return Response::json(array('success' => false, 'error' => 'database'));
			}
		}
	}

	public function incremental_reach_tool() {
		try {
			$results = DB::reconnect('dashboard')->
				table('incremental-reach-tool')->
				select('id', 'name', 'time_started', 'time_finished', 'user_id', 'qubole_query_id', 'status', 'first_name', 'last_name', 'progress')->
				orderBy('time_started', 'DESC')->
				limit('10')->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => $result) {
			if($result->time_finished) {
				$results[$key]->status = 'done';
			} else {
				$results[$key]->status = 'active';
			}
			$results[$key]->initials = $result->first_name[0] . $result->last_name[0];
		}

		$data = array(
			"items" => $results
		);

		$return = array(
			"success" => true,
			"info" => "",
			"type" => 'incremental-reach-tool',
			"data" => $data,
			"permission" => User::hasRole(['PublisherTool']),
			"user_id" => Session::get('user_id'),
			"info" => ""
		);

		return Response::json($return);
	}

	public function incremental_reach_tool_save() {

		$count = DB::reconnect('dashboard')->
				table('incremental-reach-tool')->
				where('user_id', Input::get('user_id'))->
				where('status', 'pending')->
				count();
		DB::disconnect('dashboard');

		if($count > 0) {
			return Response::json(array('success' => false, 'error' => 'count'));
		} else {
			try {
				$data = array(
					"name" => Input::get('name'),
					"deal_id" => Input::get('deal_id'),
					"pmpd" => Input::get('pmp'),
					"pmpe" => Input::get('pmpe'),
					"globaldeals" => Input::get('globaldeals'),
					"oa" => Input::get('oa'),
					"user_id" => Input::get('user_id'),
					"organizations" => @implode(',', Input::get('organisations')),
					"exchanges" => @implode(',', Input::get('exchanges')),
					"granularity" => @implode(',', Input::get('granularity')),
					"urls" => @implode(',', Input::get('urls')),
					"status" => 'pending',
					"first_name" => Session::get('first_name'),
					"last_name" => Session::get('last_name'),
					"user_email" => Session::get('user_email')
				);

				DB::reconnect('dashboard')->table('incremental-reach-tool')->insert($data);
				DB::disconnect('dashboard');

				return Response::json(array('success' => true));
			} catch(Exception $e) {
				return Response::json(array('success' => false, 'error' => 'database', 'info' => $e));
			}
		}
	}

	public function qubole_data_status() {
		$date = Input::get('date', date("Y-m-d"));
		try {
			$results = DB::reconnect('dashboard')->
				table('qubole-data-status')->
				select('id', 'mm_date', 'bucket_id', 'bucket_name', 'status', 'comment')->
				orderBy(DB::Raw("FIELD(status,'Ready','Partial','Waiting')"), 'ASC')->
				where('mm_date', $date)->
				remember(15)->
				get();
				DB::disconnect('dashboard');
		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => &$value) {
			$value->comment = json_decode($value->comment);
			$value->bucket_name = str_replace("mm-prod-", "", $value->bucket_name);
		}

		$data = array(
			"items" => $results
		);

		$return = array(
			"success" => true,
			"info" => "",
			"type" => 'qubole-data-status',
			"data" => $data,
			"permission" => User::hasRole(['PublisherTool']),
			"user_id" => Session::get('user_id'),
			"info" => ""
		);

		return Response::json($return);
	}

	public function revenue_gross_profit()
	{
		try {
			$widget = new RevenueGrossProfit();
			return Response::json($widget->getDataWidget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
		return Response::json($return);
	}

	public function revenue_3rd_party()
	{
		try {
			$widget = new Revenue3rdParty();
			return Response::json($widget->getDataWidget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	}

	public function direct_revenue_profit_margin()
	{
		try {
			$widget = new DirectRevenueProfitMargin();
			return Response::json($widget->getDataWidget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	}

	public function preferred_indirect_revenue()
	{
		try {
			$widget = new PreferredIndirectRevenue();
			return Response::json($widget->getDataWidget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	}

	public function indirect_fees()
	{
		try {
			$widget = new IndirectFees();
			return Response::json($widget->getDataWidget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	}

	public function vendor_data_tech()
	{
		try {
			$widget = new VendorDataTech();
			return Response::json($widget->getDataWidget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	}

	public function UniqueDealsBySupplyType()
	{
		try {
			return Response::json((new UniqueDealsBySupply($this->getFilters()))->widget());
		} catch(Exception $e) {
			echo $e;
			return Response::json($this->getError());
		}
	}

	public function segment_usage()
	{
		try {
			$results = DB::reconnect('dashboard')->
				table('audience-man-30-counts')->
				select('type', 'count')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}


		foreach($results as $row) {
			if($row->type == 'orgs') {
				$orgs = $row->count;
			}
			if($row->type == 'advs') {
				$advs = $row->count;
			}
		}

		$return = array(
			"success" => true,
			"title" => 'Adaptive Segments <strong>Usage</strong>',
			"info" => "Showing the number of advertisers and organizations who have created at least 1 segment.",
			"type" => 'count',
			"count" => array(
				"commas" => array(false,true)
			),
			"data" => array(
				'Organisations' => $orgs,
				'Advertisers' => $advs
			)
		);

		return Response::json($return);
	}

	public function segment_spend()
	{
		try {
			$segments = DB::reconnect('dashboard')->
				table('audience-man-30-counts')->
				select('count')->
				where('type', 'segments')->
				remember(Format::timeOut())->
				first();

			DB::disconnect('dashboard');

			$spend = $data = DB::reconnect('dashboard')->
				select(DB::raw('
					SELECT ROUND(SUM(billed_spend)) as Spend
					FROM (
						SELECT *
						FROM `audience-man-kpi-counts-and-bs`
						ORDER BY mm_date DESC
						LIMIT 30
					) a
				'));
			DB::disconnect('dashboard');

			$spend = (array)$spend[0];
		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => 'Adaptive Segments <strong>Spend</strong>',
			"info" => "Showing the number of segments actively targeted within strategies and the total billed spend across those segments in the last 30 days",
			"type" => 'count',
			"count" => array(
				"currency" => array("","$"),
				"commas" => array(false,true)
			),
			"data" => array(
				'Unique Segments' => $segments->count,
				'Billed Spend' => $spend['Spend']
			)
		);

		return Response::json($return);
	}

	public function new_segments()
	{

		try {
			// not sure how to do this as a laravel query
			$data = DB::reconnect('dashboard')->
				table('am-segs-30-days')->
				select('segs')->
				first();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => 'New Adaptive<br /> Segments Created',
			"info" => "Showing the number of new segments that have been created within the last 30 days across all organizations",
			"type" => 'count',
			"class" => 'employee-visits',
			"data" => array(
				'Within the Last 30 Days' => $data->segs
			)
		);

		return Response::json($return);
	}

	public function top_organisations_segments()
	{
		try {
			$results = DB::reconnect('dashboard')->
				table('audience-man-top-orgs')->
				select('name', 'count', 'billed_spend')->
				orderBy('billed_spend', 'desc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => $result) {
			$results[$key]->billed_spend = '$' . number_format($result->billed_spend, 0);
		}

		$return = array(
			"success" => true,
			"title" => "Top Adaptive Segments <strong>Orgs</strong>",
			"info" => 'Showing the number of segments that are being targeted by strategies and the total billed spend of strategies targeting segments by organization within the last 30 days',
			"type" => 'table',
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Name",
					"Segments",
					"Billed Spend"
				)
			),
			"data" => $results
		);

		return Response::json($return);
	}

	public function top_advertisers_segments()
	{
		try {
			$results = DB::reconnect('dashboard')->
				table('audience-man-top-advs')->
				select('name', 'count', 'billed_spend')->
				orderBy('billed_spend', 'desc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => $result) {
			$results[$key]->billed_spend = '$' . number_format($result->billed_spend, 0);
		}

		$return = array(
			"success" => true,
			"title" => "Top Adaptive Segments <strong>Advs</strong>",
			"info" => 'Showing the number of segments that are being targeted by strategies and the total billed spend of strategies targeting segments by advertiser within the last 30 days',
			"type" => 'table',
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Name",
					"Segments",
					"Billed Spend"
				)
			),
			"data" => $results
		);

		return Response::json($return);
	}

	public function top_segments()
	{
		try {
			$results = DB::reconnect('dashboard')->
				table('audience-man-top-segs')->
				select('name', 'billed_spend')->
				orderBy('billed_spend', 'desc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		foreach($results as $key => $result) {
			$results[$key]->billed_spend = '$' . number_format($result->billed_spend, 0);
		}

		$return = array(
			"success" => true,
			"title" => "Top 10 <strong>Segments</strong> <i>(last 30 days)</i>",
			"info" => 'Showing the number of strategies targeting and the total billed spend by segment within the last 30 days',
			"type" => 'table',
			"table" => array(
				"thead" => true,
				"headings" => array(
					"Name",
					"Billed Spend"
				)
			),
			"data" => $results
		);

		return Response::json($return);
	}

	public function decisioning_and_opto_usage()
	{
		try {
			return Response::json((new DecisioningAndOptoUsage)->widget());
		} catch(Exception $e) {
			echo $e;
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	}

	public function user_list($start_date = null, $end_date = null)
	{
		try {
			$people = DB::reconnect('mysql')->
				table('people')->
				select('id', 'full_name', 'machine')->
				orderBy('full_name', 'asc')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			"success" => true,
			"title" => "Users List",
			"type" => 'table',
			"table" => array(
				"columns" => array(
					0 => array(
						"bold" => true
					)
				)
			),
			"data" => $people
		);
		return Response::json($return);
	}

	public function getFilters()
	{
		$params = [];
		parse_str(Input::get('filters'), $params);
		return $params;
	}

	public function channel_main_widgets($channel)
	{
		$type = Input::get('type', 'MoM');
		$category = Input::get('category', 'orgs');
		try {
			$results = DB::reconnect('dashboard')->
				table($channel . '-top-' . $category)->
				select('rank1', 'rank2', 'name', DB::raw('FORMAT(TRUNCATE(billed_spend1, 0),0) as billed_spend1'), DB::raw('FORMAT(TRUNCATE(billed_spend2, 0),0) as billed_spend2'), 'change')->
				where('period', $type)->
				orderBy('rank1', 'asc')->
				limit(14)->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$return = array(
			'success' => true,
			'title' => $channel,
			'type' => 'channel',
			'class' => 'channel-main',
			'data' => $results
		);

		return Response::json($return);
	}

	public function channel_main_widgets_export($channel)
	{
		$category = Input::get('category', 'orgs');
		try {
			$results = DB::reconnect('dashboard')->
				table($channel . '-top-' . $category)->
				select('period', 'rank1', 'rank2', 'name', DB::raw('TRUNCATE(billed_spend1, 0) as billed_spend1'), DB::raw('TRUNCATE(billed_spend2, 0) as billed_spend2'), 'change')->
				orderBy('rank1', 'asc')->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$data = [];
		foreach($results as $row) {
			if(!array_key_exists($row->period, $data)) {
				$data[$row->period] = [];
			}
			array_push($data[$row->period], (array)$row);
		}

		Excel::create($channel . '-top-' . $category . '-export', function($excel) use($data) {
			foreach($data as $key => $row) {
				$excel->sheet($key, function($sheet) use($row) {
					$sheet->fromArray($row);
				});
			}
		})->download('xls');
	}

	public function channel_small_widgets($channel)
	{
		$type = Input::get('type', 'MoM');
		try {
			$results = DB::reconnect('dashboard')->
				table('channel-org-adv-counts')->
				where('period', $type)->
				where('channel', $channel)->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$data = [];
		foreach($results as $key => $row) {
			$row->sov = number_format($row->sov*100, 2) . '%';
			// probably a better way to do this
			if($row->billed_spend > 1000000) {
				$row->billed_spend = number_format($row->billed_spend/1000000,2) . 'M';
			} if($row->billed_spend > 1000) {
				$row->billed_spend = number_format($row->billed_spend/1000,2) . 'Th';
			}

			$row->billed_spend = '$' . $row->billed_spend;

			$data[$row->range] = $row;
		}

		$return = array(
			'success' => true,
			'title' => $channel,
			'type' => 'channel',
			'size' => 'small',
			'class' => 'channel-main channel-small',
			'data' => array_values($data)
		);

		return Response::json($return);
	}

	public function channel_small_widgets_export($channel)
	{
		$type = Input::get('type', 'MoM');
		try {
			$results = DB::reconnect('dashboard')->
				table('channel-org-adv-counts')->
				where('channel', $channel)->
				get();
			DB::disconnect('dashboard');

		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}

		$data = [];
		foreach($results as $key => $row) {
			array_push($data, (array)$row);
		}

		Excel::create($channel . '-export', function($excel) use($data) {
			$excel->sheet('test', function($sheet) use($data) {
				$sheet->fromArray($data);
			});
		})->download('xls');
	}

	public function channel_chart_widgets($channel)
	{
		$category = Input::get('category', 'billed_spend');
		$results = DB::reconnect('dashboard')->
			table('channel-spend-sov-month')->
			where('channel', $channel)->
			orderBy('date', 'asc')->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$dates = [];
		$data = [];
		foreach($results as $key => $row) {
			if($row->type == 'Actual') {
				$index = 0;
			} else if($row->type == 'Forecast') {
				$index = 2;
			} else {
				$index = 1;
				array_push($dates, date('F', strtotime('01-'.$row->date))[0]);
			}
			if(!array_key_exists($index, $data)) {
				$data[$index] = array(
					'name' => $row->type,
					'data' => []
				);
			}
			if($category == 'billed_spend') {
				array_push($data[$index]['data'], round($row->billed_spend));
			} else {
				array_push($data[$index]['data'], $row->sov*100);
			}
		}

		// hack to get the styling looking right
		$data[3] = $data[2];
		$data[4] = $data[1];
		$data[5] = $data[0];
		for($i = 0; $i <= 2; $i++) {
			$data[$i]['lineWidth'] = 7;
			$data[$i]['color'] = '#fff';
			$data[$i]['marker']['enabled'] = false;
			$data[$i]['enableMouseTracking'] = false;
			$data[$i]['showInLegend'] = false;
		}

		$return = array(
			'success' => true,
			'title' => $channel,
			'type' => 'channel',
			'size' => 'chart',
			'chart' => array(
				'style' => 'channel-black',
				'categories' => $dates
			),
			'class' => 'channel-main channel-chart',
			'data' => array_values($data)
		);

		return Response::json($return);
	}

	public function channel_chart_widgets_brain()
	{
		$category = Input::get('category', 'billed_spend');
		$results = DB::reconnect('dashboard')->
			table('decisioning_and_opto_usage')->
			orderBy('date', 'asc')->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$dates = [];
		$data = [];
		foreach($results as $key => $row) {
			if($row->type == 'Right Brain') {
				$index = 0;
			} else {
				$index = 1;
				array_push($dates, date('F', strtotime($row->date))[0]);
			}
			if(!array_key_exists($index, $data)) {
				$data[$index] = array(
					'name' => $row->type . ':',
					'data' => []
				);
			}
			if($category == 'billed_spend') {
				array_push($data[$index]['data'], round($row->billed_spend));
			} else {
				array_push($data[$index]['data'], (int)$row->count);
			}
		}

		if($category == 'billed_spend') {
			$data[0]['color'] = '#5bbbea';
		} else {
			$data[0]['color'] = '#f69f19';
		}

		$data[1]['color'] = '#fff';
		$data[1]['dashStyle'] = 'Dash';
		$data[1]['marker']['enabled'] = false;
//		$data[0]['data'] = $data[1]['data'];
//		$data[0]['type'] = 'column';
//		$data[0]['borderWidth'] = 0;
//		$data[0]['pointWidth'] = 1;
//		$data[0]['enableMouseTracking'] = false;
//		$data[0]['color'] = array(
//			'linearGradient' => array(
//				'x1' => 0,
//				'x2' => 0,
//				'y1' => 0,
//				'y2' => 1,
//			),
//			'stops' => array(
//				array(0, 'rgba(255,255,255,0.2)'),
//				array(1, 'rgba(255,255,255,0)')
//			)
//		);
		$return = array(
			'success' => true,
			'title' => 'brain',
			'type' => 'channel',
			'size' => 'chart',
			'chart' => array(
				'description' => 'Number of <span class="count">strategies</span> and their <span class="billed_spend">spend</span> using optimization vs no optimization',
				'brain' => true,
				'style' => 'styled',
				'type' => 'styled',
				'categories' => $dates
			),
			'class' => 'channel-main channel-chart channel-styled',
			'data' => array_values($data)
		);

		return Response::json($return);
	}

	public function channel_chart_widgets_adaptive()
	{
		$category = Input::get('category', 'billed_spend');
		$results = DB::reconnect('dashboard')->
			table('audience-man-kpi-counts-and-bs-month')->
			orderBy('mm_date', 'asc')->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$dates = [];
		$data = [];
		foreach($results as $key => $row) {
			$index = 0;
			array_push($dates, date('F', strtotime($row->mm_date))[0]);
			if(!array_key_exists($index, $data)) {
				$data[$index] = array(
					'name' => str_replace('_', ' ', $category) . ':',
					'data' => []
				);
			}
			if($category == 'billed_spend') {
				array_push($data[$index]['data'], round($row->billed_spend));
			} else {
				array_push($data[$index]['data'], (int)$row->n_strats);
			}
		}

		if($category == 'billed_spend') {
			$data[0]['color'] = '#19c4a2';
		} else {
			$data[0]['color'] = '#5bbbea';
		}

//		$data[1]['color'] = '#fff';
//		$data[1]['dashStyle'] = 'Dash';
//		$data[1]['marker']['enabled'] = false;
//		$data[0]['data'] = $data[1]['data'];
//		$data[0]['type'] = 'column';
//		$data[0]['borderWidth'] = 0;
//		$data[0]['pointWidth'] = 1;
//		$data[0]['enableMouseTracking'] = false;
//		$data[0]['color'] = array(
//			'linearGradient' => array(
//				'x1' => 0,
//				'x2' => 0,
//				'y1' => 0,
//				'y2' => 1,
//			),
//			'stops' => array(
//				array(0, 'rgba(255,255,255,0.2)'),
//				array(1, 'rgba(255,255,255,0)')
//			)
//		);
		$return = array(
			'success' => true,
			'title' => 'brain',
			'type' => 'channel',
			'size' => 'chart',
			'chart' => array(
				'description' => 'Number of <span class="count">adaptive segments</span> <br /> and their <span class="billed_spend">spend</span>',
				'style' => 'styled',
				'sexy' => false,
				'type' => 'styled',
				'categories' => $dates
			),
			'class' => 'channel-main channel-chart channel-styled',
			'data' => array_values($data)
		);

		return Response::json($return);
	}

	public function stats_countries()
	{
		$results = DB::reconnect('dashboard')->
			table('top-5-orgs-country')->
			select('COUNTRY')->
			groupBy('COUNTRY')->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$data = [];
		foreach($results as $key => $value) {
			array_push($data, $value->COUNTRY);
		}

		return $data;
	}

	public function top_organisations_media_cost()
	{
		$country = Input::get('country', 'United Kingdom');
		$results = DB::reconnect('dashboard')->
			table('top-5-orgs-country')->
			select('ORGANIZATION_NAME', DB::raw('CONCAT("$", format(MEDIA_COST, 2)) as "Media Cost"'))->
			where('COUNTRY', $country)->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');
        
        foreach($results as $key => $value) {
            if(strlen($value->ORGANIZATION_NAME) > 30) {
                $results[$key]->ORGANIZATION_NAME = substr($value->ORGANIZATION_NAME, 0, 30) . '...';
            }
		}

		$return = array(
			'success' => true,
			'title' => 'Top Organisations',
			'type' => 'stats',
			'size' => 'table',
			'table' => array(
				'thead' => true,
				'columns' => array(
					0 => array(
						'bold' => true,
						'align' => 'left'
					)
				)
			),
			'class' => 'stats-main stats-table',
			'data' => $results,
			'info' => 'Showing last 30 days top 5 orgs by media cost'
		);

		if(!Input::get('data')) {
			$return['countries'] = array_values($this->stats_countries());
		}

		return Response::json($return);
	}

	public function top_verticals_spend()
	{
		$country = Input::get('country', 'United Kingdom');
		$results = DB::reconnect('dashboard')->
			table('top-5-verticals-country')->
			select('VERTICAL_NAME', DB::raw('CONCAT("$", format(MEDIA_COST, 2)) as "Media Cost"'))->
			where('COUNTRY', $country)->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$return = array(
			'success' => true,
			'title' => 'Top Verticals',
			'type' => 'stats',
			'size' => 'table',
			'table' => array(
				'thead' => true,
				'columns' => array(
					0 => array(
						'bold' => true,
						'align' => 'left'
					)
				)
			),
			'class' => 'stats-main stats-table',
			'data' => $results,
			'info' => 'Showing last 30 days top 5 verticals by media cost'
		);

		if(!Input::get('data')) {
			$return['countries'] = array_values($this->stats_countries());
		}

		return Response::json($return);
	}

	public function top_verticals_spend_pie()
	{
		$country = Input::get('country', 'United Kingdom');
		$results = DB::reconnect('dashboard')->
			table('top-5-verticals-country')->
			select('VERTICAL_NAME', 'MEDIA_COST')->
			where('COUNTRY', $country)->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$pie = array();
		$total = 0;
		$totalother = 0;
		foreach($results as $key => $value) {
			if($value->VERTICAL_NAME != 'TOTAL') {
				$pie[$key]['name'] = substr($value->VERTICAL_NAME, 0, 20);
				$pie[$key]['y'] = (int)$value->MEDIA_COST;
				$total = $total + $value->MEDIA_COST;
			} else {
				$totalother = $value->MEDIA_COST;
			}
		}

		array_push($pie, array(
			'name' => 'other',
			'y' => $totalother - $total
		));

		$return = array(
			'success' => true,
			'title' => 'Top Verticals',
			'type' => 'stats',
			'size' => 'pie',
			'chart' => array(
				'type' => 'pie'
			),
			'tooltip'	=> array(
				'pointFormat'	=> 'Media Cost'
			),
			'class' => 'stats-main stats-pie',
			'data' => $pie,
			'info' => 'Showing last 30 days top 5 verticals by media cost and their Share of Voice'
		);

		if(!Input::get('data')) {
			$return['countries'] = array_values($this->stats_countries());
		}

		return Response::json($return);
	}

	public function top_urls_media_cost()
	{
		$country = Input::get('country', 'United Kingdom');
		$results = DB::reconnect('dashboard')->
			table('top-5-sites-country')->
			select('SITE_URL', 'MEDIA_COST', 'CPM')->
			where('COUNTRY', $country)->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$total = 0;
		$othertotal = 0;
		$data = [];
		foreach($results as $key => $value) {
			array_push($data, array(
				'site url' => $value->SITE_URL,
				'CPM' => '$' . number_format($value->CPM, 2)
			));
			if($value->SITE_URL != 'AVERAGE') {
				$total = $total + $value->MEDIA_COST;
			} else {
				$othertotal = $value->MEDIA_COST;
			}
		}
		$percent = ($othertotal / ($total + $othertotal)) * 100;

		$return = array(
			'success' => true,
			'title' => 'Top URLs',
			'type' => 'stats',
			'size' => 'table',
			'table' => array(
				'thead' => true,
				'columns' => array(
					0 => array(
						'bold' => true,
						'align' => 'left'
					)
				)
			),
			'class' => 'stats-main stats-table has-sidebar',
			'data' => $data,
			'sidebar' => array(
				array(
					'name' => 'Top 5',
					'value' => '$' . number_format($total, 0)
				),
				array(
					'name' => 'Other',
					'value' => '$' . number_format($othertotal, 0)
				)
			),
			'percent' => $percent,
			'info' => 'Showing last months top 5 URLs by media cost and their Share of Voice'
		);

		if(!Input::get('data')) {
			$return['countries'] = array_values($this->stats_countries());
		}

		return Response::json($return);
	}

	public function size_comparison() {
		$country = Input::get('country', 'United Kingdom');
		$results = DB::reconnect('dashboard')->
			table('top-5-sizes-country')->
			select('COUNTRY', 'SIZE', 'OPPORTUNITIES')->
			where('COUNTRY', $country)->
			remember(Format::timeOut())->
			get();
		DB::disconnect('dashboard');

		$total = 0;
		$othertotal = 0;
		$data = [
			[
				'data' => [],
				'size' => '60%',
				'colors' => ['#7cb5ec', '#90ed7d'],
				'dataLabels' => [
					'distance' => -40,
					'color' => '#fff'
				],
			],
			[
				'data' => [],
				'size' => '80%',
				'innerSize' => '60%',
				'dataLabels' => [
					'distance' => 20
				],
				'colors' => ['#9ed7ff', '#95ceff', '#8dc6fd', '#84bdf4', '#77b3ed', '#9efb8b']
			]
		];

		$standard_total = 0;
		$total = 0;
		$new_total = 0;
		foreach($results as $key => $value) {
			if($value->SIZE == 'TOTAL') {
				$total = $value->OPPORTUNITIES;
			} else {
				$standard_total += $value->OPPORTUNITIES;
				array_push($data[1]['data'], array(
					'y' => (int)$value->OPPORTUNITIES,
					'name' => $value->SIZE
				));
			}
		}

		$other_total = $total - $standard_total;
		$data[0]['data'] = [
			[
				'y' => (int)$standard_total,
				'name' => 'Top 5'
			], [
				'y' => $other_total,
				'name' => 'Other'
			]
		];
		array_push($data[1]['data'], [
			'y' => intval($other_total),
			'name' => 'Other'
		]);

		$return = array(
			'success' => true,
			'title' => 'Top Sizes',
			'type' => 'stats',
			'size' => 'donut',
			'chart' => array(
				'type' => 'donut'
			),
			'tooltip'	=> array(
				'pointFormat'	=> 'Opportunities'
			),
			'class' => 'stats-main stats-pie',
			'data' => $data,
			'info' => 'Showing last 30 days top 5 ad size opportunities'
		);

		if(!Input::get('data')) {
			$return['countries'] = array_values($this->stats_countries());
		}

		return Response::json($return);
	}
}
