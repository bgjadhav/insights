<?php
class GlobalDealIDPerformance extends Tile
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
		'DealID'		=> [
			'view'			=> 'Deal Id',
			'fieldName'		=> 'DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'		=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'a.DEAL_NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.DEAL_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealPrice'=> [
			'view' 			=> 'Deal Price',
			'fieldName' 		=> 'b.PRICE',
			'fieldAlias' 		=> 'DEAL_PRICE',
			'group' 			=> false,
			'join' 				=> [
				'type'		=> 'INNER',
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
			'group'			=> false,
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
			'group'			=> false,
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
			'group'			=> false,
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
		'CreativeSize'	=> [
			'view'			=> 'Creative Size',
			'fieldName'		=> 'a.FILE_SIZE',
			'fieldAlias'	=> 'FILE_SIZE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Clicks'		=> [
			'view'			=> 'Clicks',
			'fieldName'		=> 'sum(a.CLICKS)',
			'fieldAlias'	=> 'CLICKS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Conversions'	=> [
			'view'			=> 'Conversions',
			'fieldName'		=> 'sum(a.CONVERSIONS)',
			'fieldAlias'	=> 'CONVERSIONS',
			'join'			=> false,
			'group'			=> false,
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
			'group' 	 	=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPA'			=> [
			'view'			=> 'CPA',
			'fieldName'		=> 'CASE WHEN sum(a.CONVERSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.CONVERSIONS)) ELSE 0.00 END',
			'fieldAlias'	=> 'CPA',
			'group' 	 	=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPC'			=> [
			'view'			=> 'CPC',
			'fieldName'		=> 'CASE WHEN sum(a.CLICKS) > 0 THEN(sum(a.MEDIA_COST)/sum(a.CLICKS)) ELSE 0.00 END',
			'fieldAlias'	=> 'CPC',
			'group' 	 	=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CTR'			=> [
			'view'			=> 'CTR',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.CLICKS)/sum(a.IMPRESSIONS))*100 ELSE 0 END',
			'fieldAlias'	=> 'CTR',
			'group' 	 	=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'GLOBAL_DEAL a FORCE INDEX (GDID_PERFORMANCE)';

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
			'Exchanges' => Filter::getExchange(),
			'Columns'   => [$this->getColumnView(), ['DealPrice', 'DealPriceType', 'DealPriceMethod', 'DealExternal','CPM','CPA','CPC','CTR']]
		];
	}

	public function setQuery($options)
	{
		
		$date_str = "";
		if ($options['date_start'] == $options['date_end']) {
			$date_str = 'a.MM_DATE = \''.$options['date_start'].'\'';
		} else {
			$date_str = 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'';
		}

		$this->where = [
			'Date'			=> $date_str,
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}

}
