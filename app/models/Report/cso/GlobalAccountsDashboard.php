<?php
class GlobalAccountsDashboard extends Tile
{
	private $timeFrame = '';
	protected $from = 'DATA_TECH_DASHBOARD';
	private $vendor = [];
	private $months = [];
	private $target = 0;
	private $mtarget = 0;
	private $title;

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'search'		=> false,
			'pagination'	=> false,
			'scrollY'		=> '1245px',
			'type'			=> ['mix'],
			'download'		=> 'get',
			'total'			=> false,
			'group'			=> 1,
			'uniqueFilter'	=> ['EAM Client', 'Week select'],
			'filters'		=> $filters,
			'definitions' => [
				'PMP' => 'Total Spend across all PMP incl. PMP-D, PMP-E and Global Deals',
				'Total Spend' => 'Total Ad Cost plus MediaMath Fees and Agency Margin',
				'Direct Revenue' => 'The revenue MediaMath earns from Clients spending through the platform, and accounting for financial billing adjustments (Monthly Minimums, SOWs, Additional Fees, Credits, Rebates, Retiering etc.)'
			],
			'disclaimer' => 'All regions are based on client mappings and timezone is in UTC'
		];
	}

	public function filters()
	{
		$date = new DateTime();

		$week = $date->format("W");
		if($date->format("D") == 'Mon') {
			$week = $date->format("W") - 1;
		}

		return [
			'EAM Client' => [
				$this->eam_group(),
				[2,3,4,5,6]
			],
			'Week select' => [
				$this->months(),
				[$date->format("Y") . $week],
				'invert'
			]
		];
	}

	public function setQuery($options)
	{
		$this->eam_client = $options['filters']['EAM_Client'];
		$this->week_select = $options['filters']['Week_select'];
		return false;
	}

	public function format_data($data, $option)
	{
		// !ddd($data);
		foreach($data as $d) {
			if(isset($d->THIS_WEEK_TOTAL_SPEND))
				$d->THIS_WEEK_TOTAL_SPEND = floatval($d->THIS_WEEK_TOTAL_SPEND);
			if(isset($d->THIS_WEEK_PMP))
				$d->THIS_WEEK_PMP = floatval($d->THIS_WEEK_PMP);
			if(isset($d->THIS_WEEK_DIRECT_REVENUE))
				$d->THIS_WEEK_DIRECT_REVENUE = floatval($d->THIS_WEEK_DIRECT_REVENUE);
			if(isset($d->LAST_WEEK_TOTAL_SPEND))
				$d->LAST_WEEK_TOTAL_SPEND = floatval($d->LAST_WEEK_TOTAL_SPEND);
			if(isset($d->LAST_WEEK_PMP))
				$d->LAST_WEEK_PMP = floatval($d->LAST_WEEK_PMP);
			if(isset($d->LAST_WEEK_DIRECT_REVENUE))
				$d->LAST_WEEK_DIRECT_REVENUE = floatval($d->LAST_WEEK_DIRECT_REVENUE);
			if(isset($d->WoWMC))
				$d->WoWMC = floatval($d->WoWMC);
			if(isset($d->WoWPMP))
				$d->WoWPMP = floatval($d->WoWPMP);
			if(isset($d->WoWDR))
				$d->WoWDR = floatval($d->WoWDR);
		}
		$array = json_decode(json_encode(($data)), true);

		switch ($option) {
			case 'org':
				array_unshift($array, array($this->title), array(
					"ORGANIZATION",
					"TW TOTAL SPEND",
					"TW PMP",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW PMP",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWPMP",
					"WOWDR"
				));
				break;
			
			case 'agency':
				array_unshift($array, array($this->title), array(
					"COUNTRY",
					"AGENCY",
					"TW TOTAL SPEND",
					"TW PMP",
					"TW DIRECT RECENUE",
					"LW TOTAL SPEND",
					"LW PMP",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWPMP",
					"WOWDR"
				));
				break;
			case 'adv':
				array_unshift($array, array($this->title), array(
					"COUNTRY",
					"ADVERTISER",
					"TW TOTAL SPEND",
					"TW PMP",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW PMP",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWPMP",
					"WOWDR"
				));
				break;
			case 'reg':
				array_unshift($array, array($this->title), array(
					"REGION",
					"TW TOTAL SPEND",
					"TW PMP",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW PMP",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWPMP",
					"WOWDR"
				));
				break;
			case 'country':
				array_unshift($array, array($this->title), array(
					"COUNTRY",
					"TW TOTAL SPEND",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWDR"
				));
				break;
			case 'disp':
				array_unshift($array, array($this->title), array(
					"REGION",
					"TW TOTAL SPEND",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWDR"
				));
				break;
			case 'video':
				array_unshift($array, array($this->title), array(
					"REGION",
					"TW TOTAL SPEND",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWDR"
				));
				break;
			case 'mob':
				array_unshift($array, array($this->title), array(
					"REGION",
					"TW TOTAL SPEND",
					"TW DIRECT REVENUE",
					"LW TOTAL SPEND",
					"LW DIRECT REVENUE",
					"WOWTS",
					"WOWDR"
				));
				break;
		}
		
		return $array;
	}

	public static function export()
	{

		$dashboard = new GlobalAccountsDashboard();
		$options = [
			'filters' => [
				'EAM_Client' => Input::get('EAM_Client'),
				'Week_select' => Input::get('Week_select')
			]
		];
		$dashboard->setQuery($options);

		$dashboard->title = Input::get('title');

		Excel::create($dashboard->title, function($excel) use ($dashboard) {
			$excel->sheet('Topline By Org', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->topline_by_org()), "org"));
			});
			$excel->sheet('Top Agencies', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->top_agencies()), "agency"));
			});
			$excel->sheet('Top Advertisers', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->top_advertisers()), "adv"));
			});
			$excel->sheet('Topline By Region', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->topline_by_region()), "reg"));
			});
			$excel->sheet('Topline By Country', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->topline_by_country()), "country"));
			});
//			$excel->sheet('North Americas Top Orgs', function($sheet) use ($dashboard) {
//				$sheet->fromArray($dashboard->format_data(($dashboard->top_orgs('North Americas'))));
//			});
//			$excel->sheet('EMEA Top Orgs', function($sheet) use ($dashboard) {
//				$sheet->fromArray($dashboard->format_data(($dashboard->top_orgs('EMEA'))));
//			});
//			$excel->sheet('APAC Top Orgs', function($sheet) use ($dashboard) {
//				$sheet->fromArray($dashboard->format_data(($dashboard->top_orgs('APAC'))));
//			});
//			$excel->sheet('LATAM Top Orgs', function($sheet) use ($dashboard) {
//				$sheet->fromArray($dashboard->format_data(($dashboard->top_orgs('LATAM'))));
//			});
			$excel->sheet('Channel By Region Display', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->channel_by_region('DISPLAY')), "disp"));
			});
			$excel->sheet('Channel By Region Video', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->channel_by_region('VIDEO')), "video"));
			});
			$excel->sheet('Channel By Region Mobile', function($sheet) use ($dashboard) {
				$sheet->fromArray($dashboard->format_data(($dashboard->channel_by_region('MOBILE')), "mob"));
			});
		})->download('xlsx');
	}

	public function getDataMix()
	{
		return ['data' => [
			'topline-by-org' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Topline By Org'],
					['columns' => ['Organization', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
					['rows' => $this->topline_by_org()],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
			'top-agencies' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Top Agencies'],
					['columns' => ['Country', 'Agency', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
					['rows' => $this->top_agencies()],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
			'top-advertisers' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Top Advertisers'],
					['columns' => ['Country', 'Advertiser', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
					['rows' => $this->top_advertisers()],
					['totals' => false]
//					['totals' => [
//						0 => null,
//						1 => null,
//						2 => 0,
//						3 => 0
//					]]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
			'topline-region' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Topline By Region'],
					['columns' => ['Region', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
					['rows' => $this->topline_by_region()],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
            'topline-by-country' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Topline by Country'],
					['columns' => ['Country', 'TW total spend', 'TW direct revenue', 'LW total spend', 'LW direct revenue', 'wowts', 'wowdr']],
					['rows' => $this->topline_by_country()],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
//			'north-americas' => [
//				'type' => 'basictable',
//				'full' => true,
//				'table' => array_merge(
//					['title' => 'North Americas Top Organizations'],
//					['columns' => ['Organization', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
//					['rows' => $this->top_orgs('North Americas')],
//					['totals' => false]
//				),
//				'formats' => [
//					'LAST_WEEK_TOTAL_SPEND' => 'money',
//					'LAST_WEEK_PMP' => 'money',
//					'LAST_WEEK_DIRECT_REVENUE' => 'money',
//					'THIS_WEEK_TOTAL_SPEND' => 'money',
//					'THIS_WEEK_PMP' => 'money',
//					'THIS_WEEK_DIRECT_REVENUE' => 'money',
//					'WoWMC' => 'wow',
//					'WoWPMP' => 'wow',
//					'WoWDR' => 'wow'
//				]
//			],
//			'emea-top-orgs' => [
//				'type' => 'basictable',
//				'full' => true,
//				'table' => array_merge(
//					['title' => 'EMEA Top Organizations'],
//					['columns' => ['Organization', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
//					['rows' => $this->top_orgs('EMEA')],
//					['totals' => false]
//				),
//				'formats' => [
//					'LAST_WEEK_TOTAL_SPEND' => 'money',
//					'LAST_WEEK_PMP' => 'money',
//					'LAST_WEEK_DIRECT_REVENUE' => 'money',
//					'THIS_WEEK_TOTAL_SPEND' => 'money',
//					'THIS_WEEK_PMP' => 'money',
//					'THIS_WEEK_DIRECT_REVENUE' => 'money',
//					'WoWMC' => 'wow',
//					'WoWPMP' => 'wow',
//					'WoWDR' => 'wow'
//				]
//			],
//			'apac-top-orgs' => [
//				'type' => 'basictable',
//				'full' => true,
//				'table' => array_merge(
//					['title' => 'APAC Top Organizations'],
//					['columns' => ['Organization', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
//					['rows' => $this->top_orgs('APAC')],
//					['totals' => false]
//				),
//				'formats' => [
//					'LAST_WEEK_TOTAL_SPEND' => 'money',
//					'LAST_WEEK_PMP' => 'money',
//					'LAST_WEEK_DIRECT_REVENUE' => 'money',
//					'THIS_WEEK_TOTAL_SPEND' => 'money',
//					'THIS_WEEK_PMP' => 'money',
//					'THIS_WEEK_DIRECT_REVENUE' => 'money',
//					'WoWMC' => 'wow',
//					'WoWPMP' => 'wow',
//					'WoWDR' => 'wow'
//				]
//			],
//			'latam-top-orgs' => [
//				'type' => 'basictable',
//				'full' => true,
//				'table' => array_merge(
//					['title' => 'LATAM Top Organizations'],
//					['columns' => ['Organization', 'TW total spend', 'TW pmp', 'TW direct revenue', 'LW total spend', 'LW PMP', 'LW direct revenue', 'wowts', 'wowpmp', 'wowdr']],
//					['rows' => $this->top_orgs('LATAM')],
//					['totals' => false]
//				),
//				'formats' => [
//					'LAST_WEEK_TOTAL_SPEND' => 'money',
//					'LAST_WEEK_PMP' => 'money',
//					'LAST_WEEK_DIRECT_REVENUE' => 'money',
//					'THIS_WEEK_TOTAL_SPEND' => 'money',
//					'THIS_WEEK_PMP' => 'money',
//					'THIS_WEEK_DIRECT_REVENUE' => 'money',
//					'WoWMC' => 'wow',
//					'WoWPMP' => 'wow',
//					'WoWDR' => 'wow'
//				]
//			],
			'display-by-region' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Display by Region'],
					['columns' => ['Region', 'TW total spend', 'TW direct revenue', 'LW total spend', 'LW direct revenue', 'wowts', 'wowdr']],
					['rows' => $this->channel_by_region('DISPLAY')],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
			'mobile-by-region' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Mobile by Region'],
					['columns' => ['Region', 'TW total spend', 'TW direct revenue', 'LW total spend', 'LW direct revenue', 'wowts', 'wowdr']],
					['rows' => $this->channel_by_region('MOBILE')],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			],
			'video-by-region' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
					['title' => 'Video by Region'],
					['columns' => ['Region', 'TW total spend', 'TW direct revenue', 'LW total spend', 'LW direct revenue', 'wowts', 'wowdr']],
					['rows' => $this->channel_by_region('VIDEO')],
					['totals' => false]
				),
				'formats' => [
					'LAST_WEEK_TOTAL_SPEND' => 'money',
					'LAST_WEEK_PMP' => 'money',
					'LAST_WEEK_DIRECT_REVENUE' => 'money',
					'THIS_WEEK_TOTAL_SPEND' => 'money',
					'THIS_WEEK_PMP' => 'money',
					'THIS_WEEK_DIRECT_REVENUE' => 'money',
					'WoWMC' => 'wow',
					'WoWPMP' => 'wow',
					'WoWDR' => 'wow'
				]
			]
		]];
	}

	public function eam_group()
	{
		return DB::reconnect('analytics')
			->table('META_EAM_GROUP')
			->select('GROUP_ID', 'GROUP_NAME')
			->where('GROUP_ID', '!=', -1)
            ->where('ACTIVE', 1)
			->orderBy('GROUP_NAME', 'ASC')
			->remember(Format::timeOut())
			->lists('GROUP_NAME', 'GROUP_ID');
	}

	public function months()
	{
		return DB::reconnect('analytics')
			->table('META_YEARWEEK')
			->select(DB::raw("CONCAT('xx',YEARWEEK) as YEARWEEK"), DB::raw("CONCAT(MONDAY, '-', SUNDAY) as week"))
			->orderBy('YEARWEEK', 'DESC')
			->remember(Format::timeOut())
			->lists('week', 'YEARWEEK');
	}

	public function top_advertisers()
	{
		$data = DB::reconnect('analytics')
			->table('CSO_DASHBOARD_TOP_ADVERTISERS_COUNTRY')
			->select('COUNTRY_NAME', 'ADVERTISER_NAME', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_PMP', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_PMP', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWPMP', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();

		$total_tw_ts = 0;
		$total_tw_pmp = 0;
		$total_tw_dr = 0;
		$total_lw_ts = 0;
		$total_lw_pmp = 0;
		$total_lw_dr = 0;
		foreach ($data as $d) {
			$total_tw_ts += $d->THIS_WEEK_TOTAL_SPEND;
			$total_tw_pmp += $d->THIS_WEEK_PMP;
			$total_tw_dr += $d->THIS_WEEK_DIRECT_REVENUE;
			$total_lw_ts += $d->LAST_WEEK_TOTAL_SPEND;
			$total_lw_pmp += $d->LAST_WEEK_PMP;
			$total_lw_dr += $d->LAST_WEEK_DIRECT_REVENUE;
		}
		$total_wow_mc = ($total_tw_ts - $total_lw_ts) / $total_lw_ts;
		$total_wow_pmp = ($total_tw_pmp - $total_lw_pmp) / $total_lw_pmp;
		$total_wow_dr = ($total_tw_dr - $total_lw_dr) / $total_lw_dr;

		$obj = (object) array(
			'COUNTRY' => 'TOTAL',
			'ADVERTISER' => ' ',
			'THIS_WEEK_TOTAL_SPEND' => $total_tw_ts,
			'THIS_WEEK_PMP' => $total_tw_pmp,
			'THIS_WEEK_DIRECT_REVENUE' => $total_tw_dr,
			'LAST_WEEK_TOTAL_SPEND' => $total_lw_ts,
			'LAST_WEEK_PMP' => $total_lw_pmp,
			'LAST_WEEK_DIRECT_REVENUE' => $total_lw_dr,
			'WoWMC' => $total_wow_mc,
			'WoWPMP' => $total_wow_pmp,
			'WoWDR' => $total_wow_dr
		);

		array_unshift($data, $obj);
		return $data;
	}

	public function top_agencies()
	{
		$data = DB::reconnect('analytics')
			->table('CSO_DASHBOARD_TOP_AGENCIES_COUNTRY')
			->select('COUNTRY_NAME', 'AGENCY_NAME', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_PMP', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_PMP', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWPMP', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();

		$total_tw_ts = 0;
		$total_tw_pmp = 0;
		$total_tw_dr = 0;
		$total_lw_ts = 0;
		$total_lw_pmp = 0;
		$total_lw_dr = 0;
		foreach ($data as $d) {
			$total_tw_ts += $d->THIS_WEEK_TOTAL_SPEND;
			$total_tw_pmp += $d->THIS_WEEK_PMP;
			$total_tw_dr += $d->THIS_WEEK_DIRECT_REVENUE;
			$total_lw_ts += $d->LAST_WEEK_TOTAL_SPEND;
			$total_lw_pmp += $d->LAST_WEEK_PMP;
			$total_lw_dr += $d->LAST_WEEK_DIRECT_REVENUE;
		}
		$total_wow_mc = ($total_tw_ts - $total_lw_ts) / $total_lw_ts;
		$total_wow_pmp = ($total_tw_pmp - $total_lw_pmp) / $total_lw_pmp;
		$total_wow_dr = ($total_tw_dr - $total_lw_dr) / $total_lw_dr;

		$obj = (object) array(
			'COUNTRY' => 'TOTAL',
			'AGENCY' => ' ',
			'THIS_WEEK_TOTAL_SPEND' => $total_tw_ts,
			'THIS_WEEK_PMP' => $total_tw_pmp,
			'THIS_WEEK_DIRECT_REVENUE' => $total_tw_dr,
			'LAST_WEEK_TOTAL_SPEND' => $total_lw_ts,
			'LAST_WEEK_PMP' => $total_lw_pmp,
			'LAST_WEEK_DIRECT_REVENUE' => $total_lw_dr,
			'WoWMC' => $total_wow_mc,
			'WoWPMP' => $total_wow_pmp,
			'WoWDR' => $total_wow_dr
		);

		array_unshift($data, $obj);
		return $data;
	}

	public function topline_by_org()
	{
		$data = DB::reconnect('analytics')
			->table('CSO_DASHBOARD_TOPLINE_BY_ORG')
			->select('ORGANIZATION_NAME', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_PMP', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_PMP', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWPMP', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();

		$total_tw_ts = 0;
		$total_tw_pmp = 0;
		$total_tw_dr = 0;
		$total_lw_ts = 0;
		$total_lw_pmp = 0;
		$total_lw_dr = 0;
		foreach ($data as $d) {
			$total_tw_ts += $d->THIS_WEEK_TOTAL_SPEND;
			$total_tw_pmp += $d->THIS_WEEK_PMP;
			$total_tw_dr += $d->THIS_WEEK_DIRECT_REVENUE;
			$total_lw_ts += $d->LAST_WEEK_TOTAL_SPEND;
			$total_lw_pmp += $d->LAST_WEEK_PMP;
			$total_lw_dr += $d->LAST_WEEK_DIRECT_REVENUE;
		}
		$total_wow_mc = ($total_tw_ts - $total_lw_ts) / $total_lw_ts;
		$total_wow_pmp = ($total_tw_pmp - $total_lw_pmp) / $total_lw_pmp;
		$total_wow_dr = ($total_tw_dr - $total_lw_dr) / $total_lw_dr;

		$obj = (object) array(
			'ORGANIZATION_NAME' => 'TOTAL',
			'THIS_WEEK_TOTAL_SPEND' => $total_tw_ts,
			'THIS_WEEK_PMP' => $total_tw_pmp,
			'THIS_WEEK_DIRECT_REVENUE' => $total_tw_dr,
			'LAST_WEEK_TOTAL_SPEND' => $total_lw_ts,
			'LAST_WEEK_PMP' => $total_lw_pmp,
			'LAST_WEEK_DIRECT_REVENUE' => $total_lw_dr,
			'WoWMC' => $total_wow_mc,
			'WoWPMP' => $total_wow_pmp,
			'WoWDR' => $total_wow_dr
		);

		array_unshift($data, $obj);
		return $data;
	}

	public function topline_by_region()
	{
		$data = DB::reconnect('analytics')
			->table('CSO_DASHBOARD_TOPLINE_BY_REGION')
			->select('REGION', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_PMP', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_PMP', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWPMP', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();

		$total_tw_ts = 0;
		$total_tw_pmp = 0;
		$total_tw_dr = 0;
		$total_lw_ts = 0;
		$total_lw_pmp = 0;
		$total_lw_dr = 0;
		foreach ($data as $d) {
			$total_tw_ts += $d->THIS_WEEK_TOTAL_SPEND;
			$total_tw_pmp += $d->THIS_WEEK_PMP;
			$total_tw_dr += $d->THIS_WEEK_DIRECT_REVENUE;
			$total_lw_ts += $d->LAST_WEEK_TOTAL_SPEND;
			$total_lw_pmp += $d->LAST_WEEK_PMP;
			$total_lw_dr += $d->LAST_WEEK_DIRECT_REVENUE;
		}
		$total_wow_mc = ($total_tw_ts - $total_lw_ts) / $total_lw_ts;
		$total_wow_pmp = ($total_tw_pmp - $total_lw_pmp) / $total_lw_pmp;
		$total_wow_dr = ($total_tw_dr - $total_lw_dr) / $total_lw_dr;

		$obj = (object) array(
			'REGION' => 'TOTAL',
			'THIS_WEEK_TOTAL_SPEND' => $total_tw_ts,
			'THIS_WEEK_PMP' => $total_tw_pmp,
			'THIS_WEEK_DIRECT_REVENUE' => $total_tw_dr,
			'LAST_WEEK_TOTAL_SPEND' => $total_lw_ts,
			'LAST_WEEK_PMP' => $total_lw_pmp,
			'LAST_WEEK_DIRECT_REVENUE' => $total_lw_dr,
			'WoWMC' => $total_wow_mc,
			'WoWPMP' => $total_wow_pmp,
			'WoWDR' => $total_wow_dr
		);

		array_unshift($data, $obj);
		return $data;
	}

	public function top_orgs($region)
	{
		return DB::reconnect('analytics')
			->table('CSO_DASHBOARD_REGION_TOP_ORGS')
			->select('ORGANIZATION_NAME', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_PMP', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_PMP', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWPMP', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->where('REGION', $region)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();


	}

	public function channel_by_region($channel)
	{
		$data = DB::reconnect('analytics')
			->table('CSO_DASHBOARD_CHANNEL_BY_REGION')
			->select('REGION', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->where('CHANNEL_TYPE', $channel)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();

		$total_tw_ts = 0;
		$total_tw_dr = 0;
		$total_lw_ts = 0;
		$total_lw_dr = 0;
		$total_wow_mc = 0;
		$total_wow_dr = 0;
		foreach ($data as $d) {
			$total_tw_ts += $d->THIS_WEEK_TOTAL_SPEND;
			$total_tw_dr += $d->THIS_WEEK_DIRECT_REVENUE;
			$total_lw_ts += $d->LAST_WEEK_TOTAL_SPEND;
			$total_lw_dr += $d->LAST_WEEK_DIRECT_REVENUE;
		}
		if($total_lw_ts > 0 || $total_lw_dr > 0) {
			$total_wow_mc = ($total_tw_ts - $total_lw_ts) / $total_lw_ts;
			$total_wow_dr = ($total_tw_dr - $total_lw_dr) / $total_lw_dr;
		}
		
		$obj = (object) array(
			'REGION' => 'TOTAL',
			'THIS_WEEK_TOTAL_SPEND' => $total_tw_ts,
			'THIS_WEEK_DIRECT_REVENUE' => $total_tw_dr,
			'LAST_WEEK_TOTAL_SPEND' => $total_lw_ts,
			'LAST_WEEK_DIRECT_REVENUE' => $total_lw_dr,
			'WoWMC' => $total_wow_mc,
			'WoWDR' => $total_wow_dr
		);

		array_unshift($data, $obj);
		return $data;
	}
    
    public function topline_by_country()
	{
		$data = DB::reconnect('analytics')
			->table('CSO_DASHBOARD_TOPLINE_BY_COUNTRY')
			->select('COUNTRY', 'THIS_WEEK_TOTAL_SPEND', 'THIS_WEEK_DIRECT_REVENUE', 'LAST_WEEK_TOTAL_SPEND', 'LAST_WEEK_DIRECT_REVENUE', 'WoWMC', 'WoWDR')
			->whereIn('GROUP_ID', $this->eam_client)
			->whereIn('YEARWEEK', $this->week_select)
			->orderBy('THIS_WEEK_TOTAL_SPEND', 'DESC')
			->remember(Format::timeOut())
			->get();

		$total_tw_ts = 0;
		$total_tw_dr = 0;
		$total_lw_ts = 0;
		$total_lw_dr = 0;
		foreach ($data as $d) {
			$total_tw_ts += $d->THIS_WEEK_TOTAL_SPEND;
			$total_tw_dr += $d->THIS_WEEK_DIRECT_REVENUE;
			$total_lw_ts += $d->LAST_WEEK_TOTAL_SPEND;
			$total_lw_dr += $d->LAST_WEEK_DIRECT_REVENUE;
		}
		$total_wow_mc = ($total_tw_ts - $total_lw_ts) / $total_lw_ts;
		$total_wow_dr = ($total_tw_dr - $total_lw_dr) / $total_lw_dr;

		$obj = (object) array(
			'COUNTRY' => 'TOTAL',
			'THIS_WEEK_TOTAL_SPEND' => $total_tw_ts,
			'THIS_WEEK_DIRECT_REVENUE' => $total_tw_dr,
			'LAST_WEEK_TOTAL_SPEND' => $total_lw_ts,
			'LAST_WEEK_DIRECT_REVENUE' => $total_lw_dr,
			'WoWMC' => $total_wow_mc,
			'WoWDR' => $total_wow_dr
		);

		array_unshift($data, $obj);
		return $data;
	}
}
