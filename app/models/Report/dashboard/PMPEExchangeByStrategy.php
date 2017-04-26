<?php
class PMPEExchangeByStrategy extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		/*'MonthYear'			=> [
			'view'			=> 'Month',
			'fieldName'		=> 'CONCAT(MONTHNAME(STR_TO_DATE(MONTH(a.MM_DATE), "%m")), " ", YEAR(a.MM_DATE))',
			'fieldAlias'	=> 'MONTH',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],*/
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
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserId'	=> [
			'view'			=> 'Advertiser Id',
			'fieldName'		=> 'a.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Advertiser'	=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'a.ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.ADVERTISER_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyId'	=> [
			'view'			=> 'Strategy Id',
			'fieldName'		=> 'a.STRATEGY_ID',
			'fieldAlias'	=> 'STRATEGY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Strategy'		=> [
			'view'			=> 'Strategy Name',
			'fieldName'		=> 'a.STRATEGY_NAME',
			'fieldAlias'	=> 'STRATEGY_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.STRATEGY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealId'		=> [
			'view'			=> 'Deal Id',
			'fieldName'		=> 'a.DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Deal'			=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'b.NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.DEAL_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'DEAL_ID'
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
			'total'			=> false
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
			'view'		=> 'Deal Price Method',
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
		'ExternalId'	=> [
			'view'			=> 'External Id',
			'fieldName'		=> 'b.EXTERNAL_IDENTIFIER',
			'fieldAlias'	=> 'EXTERNAL_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DEALS b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'DEAL_ID'
				],
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
			],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'PMPE_BY_EXCHANGE_BY_STRATEGY a';

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
			'Columns'	=> [$this->getColumnView(),['DealPrice', 'DealPriceType', 'DealPriceMethod', 'ExternalId', 'CPM']]
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'			=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
