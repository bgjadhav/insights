<?php
class PMPDPerformanceByStrategyByCountry extends Tile
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
		'OrganizationID'=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Organization'	=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.ORGANIZATION_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserID'	=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'a.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserName'=> [
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
		'StrategyName'	=> [
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
			'group'			=> true,
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
	protected $from = 'PMPD_BY_COUNTRY_BY_STRATEGY a';

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
			'Organization'		=> Filter::getOrganization(),
			'Country'			=> Filter::getCountry(),
			'World_Region_Code'	=> Filter::getWorldRegionCode(),
			'Columns'			=> [$this->getColumnView(), ['DealPrice', 'DealPriceType', 'DealPriceMethod', 'DealExternal','CPM','CPA','CPC','CTR']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\'',
			'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Country'			=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')'
		];
		if (in_array('WorldRegion', $options['filters']['Columns'])) {
			$this->where['WorldRegionCode']	= 'c.WORLD_REGION_CODE IN ('.Format::str($options['filters']['World_Region_Code']).')';
		}
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
