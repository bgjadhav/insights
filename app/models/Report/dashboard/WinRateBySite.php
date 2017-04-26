<?php
class WinRateBySite extends Tile
{
	protected $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'MM_DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'DESC',
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange ID',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.exch_name',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		//~ 'SiteId'	=> [
			//~ 'view'			=> 'Site ID',
			//~ 'fieldName'		=> 'a.SITE_ID',
			//~ 'fieldAlias'	=> 'SITE_ID',
			//~ 'group'			=> true,
			//~ 'join'			=> false,
			//~ 'format'		=> false,
			//~ 'total'			=> false
		//~ ],
		//~ 'PublisherId'	=> [
			//~ 'view'			=> 'Publisher ID',
			//~ 'fieldName'		=> 'a.PUBLISHER_ID',
			//~ 'fieldAlias'	=> 'PUBLISHER_ID',
			//~ 'group'			=> true,
			//~ 'join'			=> false,
			//~ 'format'		=> false,
			//~ 'total'			=> false
		//~ ],
		'SiteURL'	=> [
			'view'			=> 'Site URL',
			'fieldName'		=> 'a.SITE_URL',
			'fieldAlias'	=> 'SITE_URL',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SupplyType'	=> [ // ------------------------------------------------------------FINISH THIS-------------------------------------------------------------
			'view'			=> 'Supply Type',
			'fieldName'		=> 'a.SUPPLY_TYPE',
			'fieldAlias'	=> 'SUPPLY_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AvgBid'	=> [
			'view'			=> 'Avg. Bid',
			'fieldName'		=> 'sum(a.AVG_BID_RATIO/a.BIDS)',
			'fieldAlias'	=> 'AVG_BID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'Wins'	=> [
			'view'			=> 'Wins',
			'fieldName'  	=> 'sum(a.WINS)',
			'fieldAlias' 	=> 'WINS',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Losses'	=> [
			'view'			=> 'Losses',
			'fieldName'  	=> 'sum(a.LOSSES)',
			'fieldAlias' 	=> 'LOSSES',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Bids'	=> [
			'view'			=> 'Bids',
			'fieldName'  	=> 'sum(a.BIDS)',
			'fieldAlias' 	=> 'BIDS',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> 'DESC',
			'total'			=> true
		],
		'WinRate'	=> [
			'view'			=> 'Win Rate',
			'fieldName'  	=> '(sum(a.WINS)/sum(a.BIDS))*100',
			'fieldAlias' 	=> 'WIN_RATE',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'WIN_RATE_BY_SITE a';
	protected $sumTotal = true;

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			//'type'			=> ['table','chart-line'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges'	=> Filter::getExchange(),
			'Supply_Type' => Filter::getInvSupplyType()
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'SupplyType'	=> 'a.SUPPLY_TYPE IN ('.Format::str($options['filters']['Supply_Type']).')'
		];

		array_walk($this->col, [&$this, 'dataColumn']);
	}

	/*public function getDataLine($default =[])
	{
		$key = array_search('MM_DATE', $this->group);
		unset($this->group[$key]);

		$categories = ['START_COUNT', 'FIRST_QUARTILE_COUNT', 'MIDPOINT_COUNT', 'THIRD_QUARTILE_COUNT', 'COMPLETE_COUNT'];

		$data = [
			'data'		=> [[]],
			'max'		=> 100,
			'text'		=> ' view: ',
			'scale'		=> 'percentage2',
			'format'	=> 'percentage2',
			'categories'=> ['Start', '25% Complete', '50% Complete', '75% Complete', '100% Complete']
		];

		$results = $this->data();
		foreach($results as $key => $result) {
			$id = preg_replace('/[^a-zA-Z0-9]+/', '', $result->{'TITLE'});

			if (!isset($data['data'][0][$key])) {
				$data['data'][0][$key]['name'] = $result->{'TITLE'};
			}
			foreach ($categories as $index => $cat) {
					$data['data'][0][$key]['data'][$index] = isset($result->$cat) ? (float)$result->$cat : 0;
			}
		}

		return $data;
	}*/
}
