<?php
class DataTechVendorImpressions extends Tile
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
		'DataImp'		=> [
			'view'			=> 'Data Vendor Impressions',
			'fieldName'		=> 'SUM(IF(VENDOR_TYPE = \'DATA\', `billed_imp_count`, 0))',
			'fieldAlias'	=> 'DATA_VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'TechImp'		=> [
			'view'			=> 'Tech Vendor Impressions',
			'fieldName'		=> 'SUM(IF(VENDOR_TYPE <> \'DATA\', `billed_imp_count`, 0))',
			'fieldAlias'	=> 'TECH_VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'TotalImp'		=> [
			'view'			=> 'Total Vendor Impressions',
			'fieldName'		=> 'SUM(billed_imp_count)',
			'fieldAlias'	=> 'TOTAL_VENDOR_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
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
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Columns' => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
		$this->where = [
			'Date' => 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\''
		];
	}
}
