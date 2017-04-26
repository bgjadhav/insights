<?php
class CacheQuery
{
	public static function run($sql, $conn = 'analytics', $timeout = null)
	{
		$cacheKey_name = Format::nameCache($sql);
		if (Cache::has($cacheKey_name)) {
			return Cache::get($cacheKey_name);
		} else{
			if ($timeout === null) {
				$timeout = Format::timeOut();
			}
			return Cache::remember($cacheKey_name, $timeout, function() use (
					$sql,
					$conn
				) {
				return DB::reconnect($conn)
					->select(DB::raw($sql));

					DB::disconnect($conn);
			});
		}
	}
}
?>
