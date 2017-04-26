<?php
class ReporterRequestPerformance  implements FilterInterface
{
	public static function filter($option)
	{
		return array_merge(
			['all' => 'All'],
			['aggregated' => 'All Together'],
			DB::reconnect('jira_prod')
				->table('roadmap_prod_req_issues')
				->select('creator', 'creatordisplay')
				->where('validate', '=', 1)
				->where('issue_id', '>', 0)
				->groupBy('creator')
				->groupBy('creatordisplay')
				->orderByRaw('creatordisplay, LENGTH(creatordisplay)')
				->remember(2)
				->lists('creatordisplay', 'creator')
		);
	}
}
?>
