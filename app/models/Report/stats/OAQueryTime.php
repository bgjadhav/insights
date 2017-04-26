<?php
class OAQueryTime extends Tile
{
	public $col = [
		'Date'		=> [
			'view'			=> 'Timestamp',
			'fieldName'		=> 'DATE_FORMAT(timestamp, \'%Y-%m-%d\')',
			'fieldAlias'	=> 'DATE',
			'group'			=> false,
			'gDependence'	=> 'DATE',
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Report'		=> [
			'view'			=> 'Report Name',
			'fieldName'		=> 'page',
			'fieldAlias'	=> 'page',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Query'		=> [
			'view'			=> 'Query',
			'fieldName'		=> 'query',
			'fieldAlias'	=> 'query',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'time_taken'	=> [
			'view'			=> 'Time Taken',
			'fieldName'		=> 'time_taken',
			'fieldAlias'	=> 'time_taken',
			'order'			=> false,
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Total'	=> [
			'view'			=> 'Total',
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
	protected $from = '`query-time`';
	protected $conn = 'dashboard';

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
		return [
			'Reports'	=> ConfigReport::reports(),
			'Columns'	=> [$this->getColumnView(), ['Query', 'time_taken']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> ' timestamp  >= \''.$options['date_start'].' 00:00:00\''
						.' AND timestamp <= \''.$options['date_end'].' 23:59:59\'',
			'Reports'	=> 'page IN ('
						.Format::str($options['filters']['Reports']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
