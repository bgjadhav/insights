<?php
class ExchangeMatchRates extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.exchange_id',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Imps Match',
			'fieldName'		=> 'AVG(a.match_rate)',
			'fieldAlias' 	=> 'IMPRESSION_MATCH_RATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		],
		'User'			=> [
			'view' 			=> 'User Match',
			'fieldName' 	=> 'AVG(c.match_rate)',
			'fieldAlias' 	=> 'USER_MATCH_RATE',
			'group' 		=> false,
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'match_rates.exchange_by_day_users c',
				'LongOn'		=> '(a.mm_date = c.mm_date and a.exchange_id = c.exchange_id)'
			],
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $conn = 'match_rates';
	protected $from = 'exchange_by_day a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges' => Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		array_walk($this->col, [&$this, 'dataColumn']);
		$this->where = [
			'Dashboard'		=> 'b.FOR_DASHBOARD = 1',
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
		];
		$this->join['match_rates.meta_exchange b'] = $this->appendJoin([
			'type'			=> 'INNER',
			'tableName' 	=> 'match_rates.meta_exchange b',
			'tableAlias'	=> 'b',
			'fieldA'    	=> 'EXCHANGE_ID',
			'joinAlias'		=> 'a',
			'fieldB'		=> 'exchange_id'
		]);
	}
}
