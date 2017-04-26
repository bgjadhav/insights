<?php
class OrganizationPerformanceByCountry extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
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
		'OrganizationName'=> [
			'view'		=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
								'gDependence'	=> 'a.ORGANIZATION_ID',
			'join'			=> false,
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
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
								'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
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
	protected $sumTotal = true;
	protected $from = 'ORGANIZATION_COUNTRY_DAY a';

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
			'Country'			=> Filter::getGeo(),
			'World_Region_Code'	=> Filter::getWorldRegionCode(),
			'Columns'			=> [$this->getColumnView(), ['CPM']]
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'				=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Country'			=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')'
		];
		if (in_array('WorldRegion', $options['filters']['Columns'])) {
			$this->where['WorldRegionCode']	= 'c.WORLD_REGION_CODE IN ('.Format::str($options['filters']['World_Region_Code']).')';
		}
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
