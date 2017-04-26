<?php
class VendorOrgAgencyGeo extends Tile
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
		'OrgID'		=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrgName'	=> [
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
		'AgencyID'		=> [
			'view'			=> 'Agency ID',
			'fieldName'		=> 'a.AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AgencyName'	=> [
			'view'			=> 'Agency Name',
			'fieldName'		=> 'a.AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.AGENCY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserID'		=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'a.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> false,
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
		'VendorID'		=> [
			'view'			=> 'Vendor ID',
			'fieldName'		=> 'a.VENDOR_ID',
			'fieldAlias'	=> 'VENDOR_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorName'	=> [
			'view'			=> 'Vendor Name',
			'fieldName'		=> 'a.VENDOR_NAME',
			'fieldAlias'	=> 'VENDOR_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.VENDOR_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorType'	=> [
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
		'Region'		=> [
			'view'			=> 'Region',
			'fieldName'		=> 'a.REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'BilledVendorImpressions'=> [
			'view'			=> 'Billed Vendor Impressions',
			'fieldName'		=> 'SUM(a.BILLED_VENDOR_IMP_COUNT)',
			'fieldAlias'	=> 'BILLED_VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'BilledVendorCost'	=> [
			'view'		=> 'Billed Vendor Cost',
			'fieldName'		=> 'SUM(a.BILLED_VENDOR_COST)',
			'fieldAlias'	=> 'BILLED_VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'VendorImpressions'	=> [
			'view'			=> 'Vendor Impressions',
			'fieldName'		=> 'SUM(a.IMP_COUNT)',
			'fieldAlias'	=> 'VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'VendorCost'	=> [
			'view'			=> 'Vendor Cost',
			'fieldName'		=> 'SUM(a.VENDOR_COST)',
			'fieldAlias'	=> 'VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'VENDOR_ORG_AGENCY_GEO a';

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
			'Organization'	=> Filter::getOrganization(),
			'Vendor'		=> Filter::getVendorMeta(),
			'Country'		=> Filter::getOrgCountry(),
			'Columns'		=> [$this->getColumnView(), ['VendorImpressions','VendorCost']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Organization'	=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Vendor'	=> 'a.VENDOR_ID IN ('.Format::id($options['filters']['Vendor']).')',
			'Country'	=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
