<?php
class AudienceManagement extends Tile
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
		'AgencyID'=> [
			'view'			=> 'Agency ID',
			'fieldName'		=> 'a.AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AgencyName'=> [
			'view'		=> 'Agency Name',
			'fieldName'		=> 'a.AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'group'			=> false,
								'gDependence'	=> 'a.AGENCY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserID'=> [
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
			'view'		=> 'Advertiser Name',
			'fieldName'		=> 'a.ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
								'gDependence'	=> 'a.ADVERTISER_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PixelType'=> [
			'view'			=> 'Pixel Type',
			'fieldName'		=> 'a.PIXEL_TYPE',
			'fieldAlias'	=> 'PIXEL_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		/*'PixelUniques'=> [
			'view'			=> 'Unique Segments',
			'fieldName'		=> 'COALESCE(UNIQUES,0)',
			'fieldAlias'	=> 'UNIQUES',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_UNIQUE_SEGMENT_TARGETING d',
				'tableAlias'	=> 'a',
				'fieldA'		=> '`CONCAT`',
				'joinAlias'		=> 'd',
				'fieldB'		=> '`CONCAT`'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],*/
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
		'BilledSpend'		=> [
			'view'			=> 'Billed Spend',
			'fieldName'		=> 'sum(a.BILLED_SPEND)',
			'fieldAlias'	=> 'BILLED_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $sumTotal = true;
	protected $from = 'AUDIENCE_MANAGEMENT a';

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
			'PixelType'		=> Filter::getPixelTypes(),
			'Columns'			=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'				=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'OrganizationID'	=> 'ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'PixelType'	=> 'PIXEL_TYPE IN ('.Format::str($options['filters']['PixelType']).')',
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
