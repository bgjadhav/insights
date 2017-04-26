<?php
class OADiagnostic extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'location'=> [
			'view'			=> 'Report',
			'fieldName'		=> 'REPORT_NAME',
			'fieldAlias'	=> 'REPORT_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'table'=> [
			'view'			=> 'Table',
			'fieldName'		=> 'TABLE_NAME',
			'fieldAlias'	=> 'table_name',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Location'		=> [
			'view'			=> 'Location',
			'fieldName'		=> 'LOCATION',
			'fieldAlias'	=> 'LOCATION',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Comment'		=> [
			'view'			=> 'Comment',
			'fieldName'		=> 'GROUP_CONCAT(COMMENT SEPARATOR \', \')',
			'fieldAlias'	=> 'COMMENT',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $conn = 'update_process';
	protected $from = 'open_update_table_fail a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(6),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Table'		=> ConfigReport::tables(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->filters = $options;
		$this->where = [
			'Date'		=> ' MM_DATE  >= \''.$options['date_start'].'\' '
						.' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Table'	=> ' TABLE_NAME IN ('
						.Format::str($options['filters']['Table']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}

}
