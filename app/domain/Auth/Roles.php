<?php
class Roles
{
	public static function checkRoles(RoleInterface $AvailableRoles)
	{
		SessionService::tmpUpdateRoles();
		$roles = $AvailableRoles->getData();
		$permission = [];
		foreach ($roles as $i) {
			if (User::hasRole([$i])) {
				$permission[] = $i;
			}
		}
		return $permission;
	}

	public static function byCurrentUser()
	{
		$id = Session::get('user_id');
		return array_unique(
			array_merge(
				self::byId($id),
				self::byTeams($id),
				['MediaMath']
			)
		);
	}

	public static function byUser($id)
	{
		return array_unique(
			array_merge(
				self::byId($id),
				self::byTeams($id),
				['MediaMath']
			)
		);
	}

	private static function byId($id)
	{
		return UserRole::select('role')
			->where('user_id', '=',  $id)
			->groupBy('role')
			->lists('role');
	}

	private static function byTeams($id)
	{
		return TeamRole::select('role')
			->whereRaw('team IN (SELECT team FROM user_team WHERE user_id ='.$id.')')
			->groupBy('role')
			->lists('role');
	}

	public static function perEachTeam($id)
	{
		return TeamRole::select('team', DB::Raw('GROUP_CONCAT(DISTINCT role separator \', \') AS roles'))
			->whereRaw('team IN (SELECT team FROM user_team WHERE user_id ='.$id.')')
			->groupBy('team')
			->lists('roles', 'team');
	}

	public static function perSpecificTeam($ids, $teams)
	{
		return TeamRole::select('team', DB::Raw('GROUP_CONCAT(DISTINCT role separator \', \') AS roles'))
			->whereRaw('team IN ('
				.'SELECT team FROM user_team WHERE user_id IN ('.Format::str($ids).')'
				.' AND team ('.Format::str($ids).')'
				.')'
			)
			->groupBy('team')
			->lists('roles', 'team');
	}

	public static function teamHasRoles($roles)
	{
		return TeamRole::select('team')
			->whereRaw('role IN ('.Format::str($roles).')')
			->groupBy('team')
			->lists('team');
	}

	public static function all()
	{
		return DB::reconnect('dashboard')
			->table('roles')
			->select('name')
			->orderBy('name', 'ASC')
			->lists('name', 'name');
	}
}
?>
