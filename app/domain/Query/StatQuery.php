<?php
class StatQuery
{
	public static function run($starttime, $query, $page)
	{
		try {
			$diff = microtime(true) - $starttime;
			if (intval($diff) > 3) {
				Queue::push('StatsController@queryTime', [
						'diff'	=> $diff,
						'query'	=> Format::noComment($query),
						'page'	=> $page
					]
				);
			}
		}  catch (Exception $e) {
		}
	}
}
?>
