<?php
class UserUsageName  implements FilterInterface
{
	public static function filter($option)
	{
		return DB::reconnect('jira_prod')
			->table('DASHBOARD_USAGE')
			->select(DB::raw('concat("xx", USER_ID) as U_ID'),  DB::raw('CONCAT(FIRST_NAME, \' \', LAST_NAME) as FULL_NAME'))
			->where('USER_ID', '>', 0)
			->groupBy('FULL_NAME')
			->orderBy(DB::raw('CONCAT(FIRST_NAME, \' \', LAST_NAME)'), 'ASC')
			->remember(2)
			->lists('FULL_NAME', 'U_ID');
	}
}
