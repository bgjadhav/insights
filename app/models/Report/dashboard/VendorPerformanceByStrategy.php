<?php
class VendorPerformanceByStrategy extends Tile
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
		'Organization'	=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'ORGANIZATION_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CampaignID'	=> [
			'view'			=> 'Campaign ID',
			'fieldName'		=> 'a.CAMPAIGN_ID',
			'fieldAlias'	=> 'CAMPAIGN_ID',
			'group'			=> true,
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
			'gDependence'	=> 'STRATEGY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorId'		=> [
			'view'			=> 'Vendor Id',
			'fieldName'		=> 'a.VENDOR_ID',
			'fieldAlias'	=> 'VENDOR_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Vendor'		=> [
			'view'			=> 'Vendor Name',
			'fieldName'		=> 'a.VENDOR_NAME',
			'fieldAlias'	=> 'VENDOR_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorType'		=> [
			'view'			=> 'Vendor Type',
			'fieldName'		=> 'b.VENDOR_TYPE',
			'fieldAlias'	=> 'VENDOR_TYPE',
			'group'			=> false,
			'gDependence'	=> 'a.VENDOR_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_VENDOR_FULL b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'VENDOR_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'VENDOR_ID'
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
		'VendorCost'	=> [
			'view'			=> 'Vendor Cost',
			'fieldName'		=> 'sum(a.VENDOR_COST)',
			'fieldAlias'	=> 'VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'DESC',
			'total'			=> true
		]
	];
	protected $from = 'VENDOR_PERFORMANCE a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(2),
				'end'	=> Format::datePicker(2)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Organization' => Filter::getOrganization(),
			'Columns'   => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'a.MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('
				.Format::id($options['filters']['Organization']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
