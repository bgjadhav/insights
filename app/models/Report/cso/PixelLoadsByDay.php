<?php
class PixelLoadsByDay extends Tile
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
		'OrgID'		=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrgName'	=> [
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
		'AgencyName'	=> [
			'view'			=> 'Agency Name',
			'fieldName'		=> 'AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'group'			=> false,
			'gDependence'	=> 'AGENCY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserID'		=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserName'	=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
			'gDependence'	=> 'ADVERTISER_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PixelID'		=> [
			'view'			=> 'Pixel ID',
			'fieldName'		=> 'PIXEL_ID',
			'fieldAlias'	=> 'PIXEL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PixelName'		=> [
			'view'			=> 'Pixel Name',
			'fieldName'		=> 'PIXEL_NAME',
			'fieldAlias'	=> 'PIXEL_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Loads'	=> [
			'view'			=> 'Loads',
			'fieldName'		=> 'SUM(LOADS)',
			'fieldAlias'	=> 'LOADS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'PIXEL_LOADS_BY_DAY';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Organization'	=> Filter::getOrganization(),
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Organization'	=> 'ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
