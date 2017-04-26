<?php
class AssigneeProductPerformance  implements FilterInterface
{
	public static function filter($option)
	{
		return DB::reconnect('jira_prod')
			->table('roadmap_product_issues')
			->select('assignee')
			->where('validate', '=', 1)
			->where('issue_id', '>', 0)
			->groupBy('assignee')
			->orderByRaw('assignee, LENGTH(assignee)')
			->remember(2)
			->lists('assignee', 'assignee');
	}
}
?>
