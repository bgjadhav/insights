<?php
class EventsByAdvertiserByDay extends Tile
{
	protected $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationId'	=> [
			'view'			=> 'Organization Id',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationName'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.ORGANIZATION_ID',
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AgencyId'	=> [
			'view'			=> 'Agency Id',
			'fieldName'		=> 'a.AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Agency'		=> [
			'view'			=> 'Agency',
			'fieldName'		=> 'a.AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.AGENCY_ID',
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserId'		=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'a.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserName'	=> [
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
		'TotalEvents'	=> [
			'view'			=> 'Total Events',
			'fieldName'		=> 'sum(a.TOTAL_EVENTS)',
			'fieldAlias'	=> 'TOTAL_EVENTS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'EVENTS_BY_ADVERTISER_BY_DAY a';
	protected $sumTotal = true;

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
			'Organization'		=> Filter::getOrganization()
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'				=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')'
		];

		array_walk($this->col, [&$this, 'dataColumn']);

		//~ dd(print_r($this->buildQuery()));
		//~ die;
	}
}
