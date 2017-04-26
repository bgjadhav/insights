<?php
class SiteByCountryByExchangeOA extends Tile
{
	public $col = [

		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName'        => 'a.MM_DATE',
			'fieldAlias'		=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'			=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'		=> [
			'view' 		=> 'Exchange ID',
			'fieldName' 		=> 'a.EXCHANGE_ID',
			'fieldAlias' 		=> 'EXCHANGE_ID',
			'group' 			=> false,
			'join' 				=> [
				'type'      	=> 'INNER',
				'tableName' 	=> 'META_EXCHANGE b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'EXCHANGE_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'EXCH_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'		=> [
			'view' 	=> 'Exchange Name',
			'fieldName' 	=> 'a.EXCHANGE_NAME',
			'fieldAlias' 	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 				=> [
				'type'			=> 'INNER',
				'tableName' 	=> 'META_EXCHANGE b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'EXCHANGE_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'EXCH_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'WorldRegion'	=> [
			'view'			=> 'World Region',
			'fieldName'		=> 'c.WORLD_REGION_CODE',
			'fieldAlias'	=> 'WORLD_REGION_CODE',
			'group'			=> false,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_COUNTRY_WORLD_REGION c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'COUNTRY',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'COUNTRY'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SiteURL'		=> [
			'view'			=> 'Site URL',
			'fieldName'		=> 'CASE WHEN a.SITE_URL <> \'\' THEN a.SITE_URL ELSE \'Unknown\' END',
			'fieldAlias'	=> 'SITE_URL',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PubID'			=> [
			'view'			=> 'Publisher ID',
			'fieldName'		=> 'a.PUBLISHER_ID',
			'fieldAlias'	=> 'PUBLISHER_ID',
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
		],
		'CPA'			=> [
			'view'			=> 'CPA',
			'fieldName'		=> 'CASE WHEN sum(a.CONVERSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.CONVERSIONS)) ELSE 0.00 END',
			'fieldAlias'	=> 'CPA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPC'			=> [
			'view'			=> 'CPC',
			'fieldName'		=> 'CASE WHEN sum(a.CLICKS) > 0 THEN(sum(a.MEDIA_COST)/sum(a.CLICKS)) ELSE 0.00 END',
			'fieldAlias'	=> 'CPC',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CTR'			=> [
			'view'			=> 'CTR',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.CLICKS)/sum(a.IMPRESSIONS))*100 ELSE 0 END',
			'fieldAlias'	=> 'CTR',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'SITE_BY_COUNTRY_BY_EXCHANGE_OA a';

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
			'Exchanges'			=> Filter::getExchange(),
			'Country'			=> Filter::getCountry(),
			'World_Region_Code'	=> Filter::getWorldRegionCode(),
			'Columns'			=> [$this->getColumnView(), ['CPM','CPA','CPC','CTR']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'		=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Country'			=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')'
		];
		if (in_array('WorldRegion', $options['filters']['Columns'])) {
			$this->where['WorldRegionCode']	= 'c.WORLD_REGION_CODE IN ('.Format::str($options['filters']['World_Region_Code']).')';
		}
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
