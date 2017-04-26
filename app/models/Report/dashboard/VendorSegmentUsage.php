<?php
class VendorSegmentUsage extends Tile
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
		'VendorName'=> [
			'view'			=> 'Vendor Name',
			'fieldName'		=> 'a.VENDOR_NAME',
			'fieldAlias'	=> 'VENDOR_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SegmentID'=> [
			'view'			=> 'Segment ID',
			'fieldName'		=> 'a.SEGMENT_ID',
			'fieldAlias'	=> 'SEGMENT_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyUsage'	=> [
			'view'			=> 'Strategy Usage',
			'fieldName'		=> 'sum(a.STRATEGY_USAGE)',
			'fieldAlias'	=> 'STRATEGY_USAGE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'VENDOR_SEGMENT_USAGE a';

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
			//'Organization' => Filter::getOrganization(),
			'Columns'   => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'a.MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\''//,
			//'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('
			//	.Format::id($options['filters']['Organization']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
