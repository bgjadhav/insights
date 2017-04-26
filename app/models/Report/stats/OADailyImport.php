<?php
class OADailyImport extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.mm_date',
			'fieldAlias'	=> 'DATE',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'location'=> [
			'view'			=> 'Location',
			'fieldName'		=> 'a.report_location',
			'fieldAlias'	=> 'report_location',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'table'=> [
			'view'			=> 'Table',
			'fieldName'		=> 'a.table_name',
			'fieldAlias'	=> 'table_name',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'status'		=> [
			'view'			=> 'Status',
			'fieldName'		=> 'a.status',
			'fieldAlias'	=> 'status',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Last'		=> [
			'view'			=> 'Last Updated',
			'fieldName'		=> 'a.last_update',
			'fieldAlias'	=> 'last_update',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'process_id'		=> [
			'view'			=> 'Process Id',
			'fieldName'		=> 'a.process_id',
			'fieldAlias'	=> 'process_id',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $conn = 'update_process';
	protected $from = 'open_daily_import a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(0),
				'end'	=> Format::datePicker(0)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		$tables = Filter::tablesDaily();
		$tables['null'] = 'Null';
		return [
			'Locations'	=> Filter::locations(),
			'Table'		=> $tables,
			'Status'	=> Filter::update(),
			'Columns'	=> [$this->getColumnView(), ['process_id', 'table']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> ' a.mm_date  >= \''.$options['date_start'].'\' '
						.' AND mm_date <= \''.$options['date_end'].'\'',
			'Status'	=> 'a.status IN ('
						.Format::str($options['filters']['Status']).')'
		];

		if (!empty($options['filters']['Locations'])) {
			$this->where['Locations']	= 'a.report_location IN ('
				.Format::str($options['filters']['Locations']).')';
		}

		if (in_array('table', $options['filters']['Columns'])) {
			$null = in_array('null', $options['filters']['Table'])
				? 'or a.table_name is null' : '' ;

			$this->where['Table'] = '(a.table_name IN ('
					.Format::str($options['filters']['Table'])
				.') '.$null.')';
		}

		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
