<?php
class GrapeshotPerformanceByStrategy extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationID'=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Organization'	=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'ORGANIZATION_NAME',
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
			'fieldName'		=> 'CAMPAIGN_ID',
			'fieldAlias'	=> 'CAMPAIGN_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyID'	=> [
			'view'			=> 'Strategy ID',
			'fieldName'		=> 'STRATEGY_ID',
			'fieldAlias'	=> 'STRATEGY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyName'	=> [
			'view'			=> 'Strategy Name',
			'fieldName'		=> 'STRATEGY_NAME',
			'fieldAlias'	=> 'STRATEGY_NAME',
			'group'			=> false,
								'gDependence'	=> 'STRATEGY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'VendorCost'	=> [
			'view'			=> 'Vendor Cost',
			'fieldName'		=> 'sum(VENDOR_COST)',
			'fieldAlias'	=> 'VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'GRAPESHOT_PERFORMANCE';

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
			'Date'				=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'OrganizationID'	=> 'ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
