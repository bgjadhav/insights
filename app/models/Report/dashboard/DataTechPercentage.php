<?php
class DataTechPercentage extends Tile
{
	public $col = [
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
		'Month'			=> [
			'view'			=> 'Month',
			'fieldName'		=> 'DATE_FORMAT(MM_DATE, \'%M\')',
			'fieldAlias'	=> 'MONTH',
			'group'			=> false,
			'gDependence'	=> 'MM_DATE',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Year'			=> [
			'view'			=> 'Year',
			'fieldName'		=> 'DATE_FORMAT(MM_DATE, \'%Y\')',
			'fieldAlias'	=> 'Year',
			'group'			=> false,
			'gDependence'	=> 'MM_DATE',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorName'			=> [
			'view'			=> 'Vendor name',
			'fieldName'		=> 'KNOX_VENDOR_PACING.VENDOR_NAME',
			'fieldAlias'	=> 'VENDOR_NAME',
			'group'			=> false,
			'gDependence'	=> 'KNOX_VENDOR_PACING.VENDOR_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DataTech'		=> [
			'view'			=> 'Vendor Type',
			'fieldName'		=> 'META_VENDOR_FULL.VENDOR_TYPE',
			'fieldAlias'	=> 'VENDOR_TYPE',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_VENDOR_FULL',
				'tableAlias'	=> 'META_VENDOR_FULL',
				'fieldA'		=> 'VENDOR_ID',
				'joinAlias'		=> 'KNOX_VENDOR_PACING',
				'fieldB'		=> 'VENDOR_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Inv'		=> [
			'view'			=> 'Invoice Amount',
			'fieldName'		=> 'SUM(KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT)',
			'fieldAlias'	=> 'VENDOR_INVOICE_AMOUNT',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'VendorTypeInvoicePercentage'		=> [
			'view'			=> 'Vendor Type Invoice Percentage',
			'fieldName'		=> '(SUM(KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT)/(SELECT SUM(a.VENDOR_INVOICE_AMOUNT) FROM KNOX_VENDOR_PACING a INNER JOIN META_VENDOR_FULL b ON a.VENDOR_ID=b.VENDOR_ID  WHERE a.MM_DATE = KNOX_VENDOR_PACING.MM_DATE and b.VENDOR_TYPE = META_VENDOR_FULL.VENDOR_TYPE and a.VENDOR_ID not in (635)))*100',
			'fieldAlias'	=> 'VENDOR_TYPE_INVOICE_PERCENT',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'TotalVendorInvoicePercentage'		=> [
			'view'			=> 'Total Vendor Invoice Percentage',
			'fieldName'		=> '(SUM(KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT)/(SELECT SUM(a.VENDOR_INVOICE_AMOUNT) FROM KNOX_VENDOR_PACING a INNER JOIN META_VENDOR_FULL b ON a.VENDOR_ID=b.VENDOR_ID WHERE a.MM_DATE = KNOX_VENDOR_PACING.MM_DATE and a.VENDOR_ID not in (635)))*100',
			'fieldAlias'	=> 'TOTAL_INVOICE_PERCENT',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'GrossBillablePercentage'		=> [
			'view'			=> 'Gross Billable Percentage',
			'fieldName'		=> '(SUM(KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT)/(SELECT SUM(INVOICE_AMOUNT) FROM GROSS_BILLABLES_BY_DAY WHERE  GROSS_BILLABLES_BY_DAY.MM_DATE = KNOX_VENDOR_PACING.MM_DATE))*100',
			'fieldAlias'	=> 'GROSS_BILLABLE_PERCENT',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		]
		
		
		//,
		/*'TechInvPer'		=> [
			'view'			=> 'Tech Vendor Percentage',
			'fieldName'		=> '(SUM(CASE WHEN META_VENDOR_TECH_DATA.TYPE = "TECH" THEN KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT ELSE 0 END)/(SELECT SUM(INVOICE_AMOUNT) FROM GROSS_BILLABLES_BY_DAY WHERE GROSS_BILLABLES_BY_DAY.MM_DATE = KNOX_VENDOR_PACING.MM_DATE))*100',
			'fieldAlias'	=> 'TECH_VENDOR_PERCENTAGE',
			'group'			=> false,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_VENDOR_TECH_DATA',
				'tableAlias'	=> 'KNOX_VENDOR_PACING',
				'fieldA'		=> 'VENDOR_ID',
				'joinAlias'		=> 'META_VENDOR_TECH_DATA',
				'fieldB'		=> 'VENDOR_ID'
			],
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'DataInvPer'		=> [
			'view'			=> 'Data Vendor Percentage',
			'fieldName'		=> '(SUM(CASE WHEN META_VENDOR_TECH_DATA.TYPE = "DATA" THEN KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT ELSE 0 END)/(SELECT SUM(INVOICE_AMOUNT) FROM GROSS_BILLABLES_BY_DAY WHERE GROSS_BILLABLES_BY_DAY.MM_DATE = KNOX_VENDOR_PACING.MM_DATE))*100',
			'fieldAlias'	=> 'DATA_VENDOR_PERCENTAGE',
			'group'			=> false,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_VENDOR_TECH_DATA',
				'tableAlias'	=> 'KNOX_VENDOR_PACING',
				'fieldA'		=> 'VENDOR_ID',
				'joinAlias'		=> 'META_VENDOR_TECH_DATA',
				'fieldB'		=> 'VENDOR_ID'
			],
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		]*/
	];
	protected $from = 'KNOX_VENDOR_PACING';

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
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date' => 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'NoAdserver' => 'KNOX_VENDOR_PACING.VENDOR_ID not in (635)'
		];

		array_walk($this->col, [&$this, 'dataColumn']);

		//~ dd(print_r($this->buildQuery()));
		//~ die;
	}
}
