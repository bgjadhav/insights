<?php
class QueryService
{
	public static function run($query, $conn = 'analytics', $timeout = null)
	{
		try {
			$starttime = microtime(true);

			if ($timeout === false) {
				$results = DB::reconnect($conn)
					->select(DB::raw($query));

				DB::disconnect($conn);

			} else {
				$results = self::cache($query, $conn, $timeout);
			}

			try {
				self::sendStat($starttime, $query);
			} catch (Exception $e) {
			}

			return $results;
		} catch (Exception $e) {
			self::error($e->getMessage());
		}
	}

	protected static function cache($query, $conn, $timeout)
	{
		$results = CacheQuery::run($query, $conn, $timeout);
		if (count($results) == 0) {
			Cache::forget(Format::nameCache($query));
		}
		return $results;
	}

	protected static function sendStat($starttime, $query)
	{
		if (App::environment('production') && !User::hasRole(['Insights'])) {
			StatQuery::run($starttime, $query, Format::objectName());
		}
	}

	protected static function error($message)
	{
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo Response::json(Format::dashError(false, 'A database error occured.', $message));
		die();
	}
}
?>
