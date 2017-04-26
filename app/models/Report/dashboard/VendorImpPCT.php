<?php
class VendorImpPCT extends Tile
{
	public  $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
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
		'VendorType'	=> [
			'view'			=> 'Vendor Type',
			'fieldName'		=> 'VENDOR_TYPE',
			'fieldAlias'	=> 'VENDOR_TYPE',
			'group'			=> false,
			'gDependence'	=> 'VENDOR_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'SUM(IMP_COUNT)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'BilledCount'	=> [
			'view'			=> 'Billed Vendor Imps',
			'fieldName'		=> 'SUM(BILLED_IMP_COUNT)',
			'fieldAlias'	=> 'BILLED_VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'BilledPct'		=> [
			'view' 		=> 'Billed Vendor PCT',
			'fieldName'  	=> 'CASE WHEN SUM(IMP_COUNT) > 0 THEN (sum(BILLED_IMP_COUNT)/sum(IMP_COUNT))*100 ELSE 0 END',
			'fieldAlias' 	=> 'IMPRESSION_PERCENT',
			'group' 	 	=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'VENDOR_IMP_PCT';

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
			'Vendor_Name' 	=> Filter::getVendor(),
			'Vendor_Type'	=> Filter::getVendorTypes(),
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Vendor_Name'	=> 'VENDOR_NAME IN ('.Format::str($options['filters']['Vendor_Name']).')',
			'Vendor_Type'	=> 'VENDOR_TYPE IN ('.Format::str($options['filters']['Vendor_Type']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
?>