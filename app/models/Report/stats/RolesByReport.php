<?php
class RolesByReport extends Tile
{
	public $col = [
		'Report'			=> [
			'view'			=> 'Report',
			'fieldName'		=> 'report',
			'fieldAlias'	=> 'report',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Role'		=> [
			'view'			=> 'Roles',
			'fieldName'		=> 'GROUP_CONCAT(DISTINCT role separator \', \')',
			'fieldAlias'	=> 'roles',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $conn = 'dashboard';
	protected $from = 'report_role';

	public function options($filters)
	{
		return [
			'date_picker' => false,
			'filters' => $filters
		];
	}

	public function filters()
	{
		return [
			'Roles' => Roles::all()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Role' => ' role IN ('.Format::str($options['filters']['Roles']).')',
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
