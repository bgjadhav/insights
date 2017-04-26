<?php
class PMPEAverageBidByStrategy extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeID'	=> [
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
			'fieldName'		=> 'e.EXCH_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE e',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'EXCHANGE_ID',
				'joinAlias'		=> 'e',
				'fieldB'		=> 'EXCH_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyID'	=> [
			'view'			=> 'Strategy ID',
			'fieldName'		=> 'a.STRATEGY_ID',
			'fieldAlias'	=> 'STRATEGY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealID'		=> [
			'view'			=> 'Deal ID',
			'fieldName'		=> 'a.Deal_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'		=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'b.NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.Deal_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'ID'
								],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealPrice'		=> [
			'view'			=> 'Deal Price',
			'fieldName'		=> 'b.PRICE',
			'fieldAlias'	=> 'DEAL_PRICE',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'ID'
								],
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'DealPriceType'	=> [
			'view'			=> 'Deal Price Type',
			'fieldName'		=> 'b.PRICE_TYPE',
			'fieldAlias'	=> 'DEAL_PRICE_TYPE',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'ID'
								],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealPriceMethod'=> [
			'view'			=> 'Deal Price Method',
			'fieldName'		=> 'b.PRICE_METHOD',
			'fieldAlias'	=> 'DEAL_PRICE_METHOD',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'ID'
								],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealExternal'	=> [
			'view'			=> 'Deal External ID',
			'fieldName'		=> 'b.EXTERNAL_IDENTIFIER',
			'fieldAlias'	=> 'DEAL_EXTERNAL_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'ID'
								],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AvgBidPrice'	=> [
			'view'			=> 'Avg Bid Price',
			'fieldName'		=> 'avg(a.BID_PRICE_CPM)',
			'fieldAlias'	=> 'AVERAGE_BID_PRICE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'PMPE_BID_PRICE a';

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
		return[
			'Exchanges'	=> Filter::getExchange(),
			'Columns'	=> [$this->getColumnView(),['DealPrice', 'DealPriceType', 'DealPriceMethod', 'DealExternal']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId'=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
