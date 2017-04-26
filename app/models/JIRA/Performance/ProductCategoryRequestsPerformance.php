<?php
class ProductCategoryRequestsPerformance  implements FilterInterface
{
	public static function filter($option)
	{
		return  DB::reconnect('jira_prod')
			->table('roadmap_prod_req_issues')
			->select('first_component')
			->where('validate', '=', 1)
			->where('issue_id', '>', 0)
			->groupBy('first_component')
			->orderByRaw('first_component, LENGTH(first_component)')
			->remember(2)
			->lists('first_component', 'first_component');
	}
}
?>
