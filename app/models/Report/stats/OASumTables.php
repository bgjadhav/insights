<?php
class OASumTables extends Tile
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
		'Table'		=> [
			'view'			=> 'Table Name',
			'fieldName'		=> 'DB_TABLE',
			'fieldAlias'	=> 'DB_TABLE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Total'	=> [
			'view'			=> 'Total days',
			'fieldName'		=> 'COUNT(*)',
			'fieldAlias'	=> 'TOTAL',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $conn = 'update_process';
	protected $from = 'open_update_sum_tables';

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
			'Table'		=> Filter::tablesSum(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> ' MM_DATE  >= \''.$options['date_start'].'\' '
							.' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Table'	=> 'DB_TABLE IN ('
							.Format::str($options['filters']['Table']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
