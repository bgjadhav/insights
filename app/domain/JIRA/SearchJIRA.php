<?php
class SearchJIRA
{
	public static function generateRegex($fields=[], $page, $search)
	{
		$where = false;

		$search = RegexMySQL::filter($search);

		$search = RegexMySQL::prepare($search);


		if ($search != null && $search != '') {

			$where = [];

			array_walk($fields, function($field) use (&$where, $search) {
				$where[] = $field.' REGEXP \''.$search.'\'';
			});

			$where = '(' .implode(' OR ', $where).')';

			SearchController::storagedSearchInPage($page, $search);

		}

		return $where;
	}
}
