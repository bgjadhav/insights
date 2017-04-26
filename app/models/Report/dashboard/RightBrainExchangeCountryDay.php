<?php
class RightBrainExchangeCountryDay extends Tile
{
	public $col = [
		'Date'			 => [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view' 		=> 'Exchange Name',
			'fieldName' 	=> 'b.exch_name',
			'fieldAlias' 	=> 'EXCHANGE_NAME',
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
		'CountryBidder'	=> [
			'view'			=> 'Country',
			'fieldName'		=> 'c.COUNTRY_NAME',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> false,
			'gDependence'	=> 'a.COUNTRY_ID',
			'join'			=>  [
				'type'			=> 'INNER',
				'tableName'		=> 'META_COUNTRY_IMPRESSION_LOG c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'COUNTRY_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'COUNTRY_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Channel'		=> [
			'view'			=> 'Channel Type',
			'fieldName'		=> 'd.CHANNEL_NAME',
			'fieldAlias'	=> 'CHANNEL_TYPE',
			'group'			=> false,
			'gDependence'	=> 'a.CHANNEL_TYPE',
			'join'			=>  [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CHANNEL_TYPE_IMPRESSION_LOG d',
				'tableAlias'	=> 'd',
				'fieldA'		=> 'CHANNEL_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CHANNEL_TYPE'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'bids'			=> [
			'view'			=> 'Bids',
			'fieldName'		=> 'sum(a.WINS)+sum(a.LOSSES)',
			'fieldAlias'	=> 'BIDS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'wins'			=> [
			'view'			=> 'Wins',
			'fieldName'		=> 'sum(a.WINS)',
			'fieldAlias'	=> 'WINS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Losses'		=> [
			'view'			=> 'Losses',
			'fieldName'		=> 'sum(a.LOSSES)',
			'fieldAlias'	=> 'LOSSES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'winRate'		=> [
			'view'			=> 'Win Rate',
			'fieldName'		=> 'AVG((a.WINS/(a.WINS+a.LOSSES))*100)',
			'fieldAlias'	=> 'WIN_RATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> true
		],
		'avgb'			=> [
			'view'			=> 'Avg Bid CPM',
			'fieldName'		=> 'AVG(a.AVGBID_CPM)',
			'fieldAlias'	=> 'AVERAGE_BID_CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'avgw'			=> [
			'view'			=> 'Avg Win',
			'fieldName'		=> 'AVG(a.AVGWIN)',
			'fieldAlias'	=> 'AVERAGE_WIN_CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'BIDS_BY_COUNTRY_BY_EXCHANGE a';

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
			'Exchanges'	=> Filter::getExchange(),
			'Country'	=> Filter::getCountryBidder(),
			'Channel'	=> Filter::getChannel()
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Country'		=> 'a.COUNTRY_ID IN ('.Format::id($options['filters']['Country']).')',
			'Channel'		=> 'a.CHANNEL_TYPE IN ('.Format::id($options['filters']['Channel']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
