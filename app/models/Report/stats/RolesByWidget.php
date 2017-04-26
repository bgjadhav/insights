<?php
class RolesByWidget extends Tile
{
	public $col = [
		'Id'			=> [
			'view'			=> 'Id',
			'fieldName'		=> 'id',
			'fieldAlias'	=> 'id',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Widget'			=> [
			'view'			=> 'Script',
			'fieldName'		=> 'script',
			'fieldAlias'	=> 'script',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Role'		=> [
			'view'			=> 'Roles',
			'fieldName'		=> 'role',
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
	protected $from = 'widgets';

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
