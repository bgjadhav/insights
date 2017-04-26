<?php
class ReporterProductPerformance  implements FilterInterface
{
	public static function filter($option)
	{
		return DB::reconnect('jira_prod')
			->table('roadmap_product_issues')
			->select('creatordisplay')
			->where('validate', '=', 1)
			->where('issue_id', '>', 0)
			->groupBy('creatordisplay')
			->orderByRaw('creatordisplay, LENGTH(creatordisplay)')
			->remember(2)
			->lists('creatordisplay', 'creatordisplay');
	}
}
?>
