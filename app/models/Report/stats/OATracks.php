<?php
class OATracks extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'DATE_FORMAT(MM_DATE, \'%Y-%m-%d\')',
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
			'fieldName'		=> 'REPORT_ID',
			'fieldAlias'	=> 'REPORT_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'User'		=> [
			'view'			=> 'User Id',
			'fieldName'		=> 'USER_ID',
			'fieldAlias'	=> 'USER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Name'	=> [
			'view'			=> 'User Name',
			'fieldName'		=> 'CONCAT(FIRST_NAME, \' \',LAST_NAME)',
			'fieldAlias'	=> 'USER_NAME',
			'order'			=> false,
			'group'			=> false,
			'gDependence'	=> 'USER_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Action'		=> [
			'view'			=> 'Action',
			'fieldName'		=> 'ACTION',
			'fieldAlias'	=> 'ACTION',
			'group'			=> true,
			'order'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Action_Data'		=> [
			'view'			=> 'Action Data',
			'fieldName'		=> 'ACTION_DATA',
			'fieldAlias'	=> 'ACTION_DATA',
			'group'			=> true,
			'order'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		//~ 'Success'		=> [
			//~ 'view'			=> 'Success',
			//~ 'fieldName'		=> 'SUCCESS',
			//~ 'fieldAlias'	=> 'SUCCESS',
			//~ 'group'			=> true,
			//~ 'order'			=> false,
			//~ 'join'			=> false,
			//~ 'format'		=> false,
			//~ 'order'			=> 'ASC',
			//~ 'total'			=> false
		//~ ],
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
	protected $from = 'DASHBOARD_USAGE';

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
			//'Users'		=> Filter::usersV3(),
			'Reports'	=> ConfigReport::reports(),
			'Type_Report' => ConfigReport::typeReport(),
			'Columns'	=> [$this->getColumnView(), ['Action_Data', 'Action']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> ' MM_DATE  >= \''.$options['date_start'].' 00:00:00\''
						.' AND MM_DATE <= \''.$options['date_end'].' 23:59:59\'',
			//'Users'		=> 'USER_ID IN ('
			//	.Format::id($options['filters']['Users']).')',
			'Reports'	=> 'REPORT_ID IN ('
						.Format::str($options['filters']['Reports']).')',
			'TypesR'	=> 'ACTION IN ('
						.Format::str($options['filters']['Type_Report']).')',
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
