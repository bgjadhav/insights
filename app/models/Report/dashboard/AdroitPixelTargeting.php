<?php
class AdroitPixelTargeting extends Tile
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
			'view'			=> 'Organization',
			'fieldName'		=> 'ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'ORGANIZATION_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AgencyID'		=> [
			'view'			=> 'Agency ID',
			'fieldName'		=> 'AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Agency'		=> [
			'view'			=> 'Agency',
			'fieldName'		=> 'AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'group'			=> false,
			'gDependence'	=> 'AGENCY_ID',
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
		'Campaign'		=> [
			'view'			=> 'Campaign',
			'fieldName'		=> 'CAMPAIGN_NAME',
			'fieldAlias'	=> 'CAMPAIGN_NAME',
			'gDependence'	=> 'CAMPAIGN_ID',
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
			'view'			=> 'Strategy',
			'fieldName'		=> 'STRATEGY_NAME',
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
			'fieldName'		=> 'VENDOR_ID',
			'fieldAlias'	=> 'VENDOR_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Vendor'		=> [
			'view'			=> 'Vendor Name',
			'fieldName'		=> 'VENDOR_NAME',
			'fieldAlias'	=> 'VENDOR_NAME',
			'group'			=> false,
			'gDependence'	=> 'VENDOR_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PixelId'		=> [
			'view'			=> 'Pixel Id',
			'fieldName'		=> 'PIXEL_ID',
			'fieldAlias'	=> 'PIXEL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Pixel'			=> [
			'view'			=> 'Pixel',
			'fieldName'		=> 'PIXEL_NAME',
			'fieldAlias'	=> 'PIXEL_NAME',
			'group'			=> false,
			'gDependence'	=> 'PIXEL_ID',
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
		'BilledVendorImp'=> [
			'view'			=> 'Billed Vendor Imp',
			'fieldName'		=> 'sum(BILLED_VENDOR_IMPS)',
			'fieldAlias'	=> 'BILLED_VENDOR_IMPS',
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
			'order'			=> 'DESC',
			'total'			=> true
		],
		'BilledVendorCost'=> [
			'view'			=> 'Billed Vendor Cost',
			'fieldName'		=> 'sum(BILLED_VENDOR_COST)',
			'fieldAlias'	=> 'BILLED_VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'ADROIT_PIXEL_TARGETING';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters,
		];
	}

	public function filters()
	{
		return [
			'Organization'	=> Filter::getOrganization(),
			//~ 'Vendor'		=> [
				//~ 'xx694' => 'Adroit',
				//~ 'xx898' => 'Adroit - Pixel Targeting'
			//~ ],
			'Columns'		=> [$this->getColumnView(), ['VendorId', 'Vendor']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'	=> 'MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Org'	=> 'ORGANIZATION_ID IN ('
				.Format::id($options['filters']['Organization']).')',
			//~ 'Vendor'=> 'VENDOR_ID IN ('
				//~ .Format::id($options['filters']['Vendor']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
