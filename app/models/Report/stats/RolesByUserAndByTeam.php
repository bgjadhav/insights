<?php
class RolesByUserAndByTeam extends Tile
{
	public $col = [
		'User'			=> [
			'view'			=> 'User',
			'fieldName'		=> 'user_id',
			'fieldAlias'	=> 'user_id',
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
		],
		'Team'		=> [
			'view'			=> 'Roles by Team',
			'fieldName'		=> '\'\'',
			'fieldAlias'	=> 'teams',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $conn = 'dashboard';
	protected $from = 'user_role';
	protected $roles = [];
	protected $users = [];

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
			'Users' => Filter::usersV3(),
			'Roles' => Roles::all()
		];
	}

	public function setQuery($options)
	{
		$this->roles = $options['filters']['Roles'];
		$this->users = $options['filters']['Users'];

		$this->where = [
			'User' => ' user_id IN ('.Format::id($this->users).')',
			'Role' => ' role IN ('.Format::str($this->roles).')',
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}

	public function data()
	{
		$data = parent::data();
		$users = Filter::usersV3();
		$this->users = array_flip($this->users);

		foreach ($data as $item) {

			$item->teams = $this->getRolesByTeam($item->user_id);

			if (isset($this->users[$item->user_id])) {
				unset($this->users[$item->user_id]);
			}

			$item->user_id = $users['xx'.$item->user_id];
		}

		$this->users = array_flip($this->users);

		$withTeams = $this->getDataTeams();

		foreach ($withTeams as $id) {
			$item = new ItemRolesTeam;
			$item->teams = $this->getRolesByTeam($id);
			$item->roles = '';
			$item->user_id = $users['xx'.$id];
			$data[] = $item;
		}

		return $data;
	}

	private function getRolesByTeam($id)
	{
		$teams = [];
		$byTeams = Roles::perEachTeam($id);

		foreach ($byTeams as $team => $roles) {
			$teams[] = $team . ': '. $roles;
		}
		return implode(' | ', $teams) ;
	}

	private function getDataTeams()
	{
		return UserTeam::byIdsAndTeam($this->users, Roles::teamHasRoles($this->roles));
	}
}
