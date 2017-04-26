<?php
class UserTeam extends Eloquent {
	protected $connection = 'dashboard';
	protected $table = 'user_team';

	public static function byIdsAndTeam($ids, $teams)
	{
		return self::select('user_id')
			->whereRaw('user_id IN ('.Format::id($ids).')')
			->whereRaw('team IN ('.Format::str($teams).')')
			->groupBy('user_id')
			->lists('user_id', 'user_id');
	}
}
