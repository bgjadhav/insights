<?php
class VendorGrossNetRevPerformance extends Tile
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
		'VendorID'		=> [
			'view'			=> 'Vendor ID',
			'fieldName'		=> 'VENDOR_ID',
			'fieldAlias'	=> 'VENDOR_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorName'	=> [
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
		'VendorType'	=> [
			'view'			=> 'Vendor Type',
			'fieldName'		=> 'VENDOR_TYPE',
			'fieldAlias'	=> 'VENDOR_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'BilledVendorImpressions'=> [
			'view'			=> 'Billed Vendor Impressions',
			'fieldName'		=> 'SUM(BILLED_VENDOR_IMP_COUNT)',
			'fieldAlias'	=> 'BILLED_VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'BilledVendorCost'	=> [
			'view'		=> 'Billed Vendor Cost',
			'fieldName'		=> 'SUM(BILLED_VENDOR_COST)',
			'fieldAlias'	=> 'BILLED_VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'VendorImpressions'	=> [
			'view'			=> 'Vendor Impressions',
			'fieldName'		=> 'SUM(IMP_COUNT)',
			'fieldAlias'	=> 'VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'VendorCost'	=> [
			'view'			=> 'Vendor Cost',
			'fieldName'		=> 'SUM(VENDOR_COST)',
			'fieldAlias'	=> 'VENDOR_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'VendorMarkup'	=> [
			'view'			=> 'Vendor Markup',
			'fieldName'		=> 'SUM(VENDOR_MARKUP)',
			'fieldAlias'	=> 'VENDOR_MARKUP',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'VENDOR_GROSS_NET_COSTS';

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
			'Vendors' => Filter::getVendor(),
			'Vendor_Types' => Filter::getVendorType(),
			'Columns'	=> [$this->getColumnView(), ['BilledVendorImpressions','BilledVendorCost', 'VendorMarkup']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Vendors'		=> 'VENDOR_NAME IN ('.Format::str($options['filters']['Vendors']).')',
			'Vendor Types'	=> 'VENDOR_TYPE IN ('.Format::str($options['filters']['Vendor_Types']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
