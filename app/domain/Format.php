<?php
class Format
{

	public static function str($a)
	{
		$a = str_replace('\'', "\'", $a);
		return '\''.implode('\',\'', $a).'\'';
	}

	public static function id($a)
	{
		return implode(',',array_unique($a));
	}

	public static function timeOut()
	{
		return (int)(strtotime('tomorrow') - time()) / 60;
	}

	public static function objectName($parent = 'Tile')
	{
		$debug	= debug_backtrace();
		$index	= array_search($parent, array_column($debug, 'class'));
		try {
			$clasName = get_class($debug[$index]['object']);
			return $clasName !== false ?$clasName :'NoIsObject';
		} catch (Exception $e) {
			return 'ErrorObject';
		}
	}

	public static function timeFinal($diff)
	{
		return gmdate('H:i:s', $diff);
	}

	public static function dateEasyView($v)
	{
		return date('jS M', strtotime($v));
	}

	public static function dashError($succes, $error, $msg=null)
	{
		return [
			'success'	=> $succes,
			'error'		=> $error,
			'errormsg'	=> $msg !==null ? $msg : $error
		];
	}

	public static function titleUrl($name)
	{
		return strpos($name, '_') ? explode('_', $name)[1] : $name;
	}

	public static function toUrl($name)
	{
		$name = str_replace(' ', '-', strtolower($name));
		$name = str_replace('/', '-', strtolower($name));
		return $name;
	}

	public static function datePicker($days=1)
	{
		return date('D M d Y H:i:s O', time() - 60 * 60 * (24*$days));
	}

	public static function nameCache($sql)
	{
		return md5(self::noComment($sql));
	}

	public static function noComment($sql)
	{
		$noComment = explode('*/SELECT ', $sql);
		$noComment = count($noComment)>1 ?$noComment[1] :$noComment[0];
		return 'SELECT '.$noComment;
	}

	public static function lastWeek()
	{
		$days = [];
		for ($end=7;$end>=1;$end-- ) {
			$days[] = date('Y-m-d', strtotime('-'.$end.' days'));
		}
		return $days;
	}

	public static function clearAlias($table)
	{
		$table = explode(' ', $table);
		return $table[0];
	}

	public static function clearDataBaseFrom($table)
	{
		$table = explode('.', $table);
		return count($table) > 1 ? $table[1] : $table[0];
	}

	public static function getDays($dates)
	{
		$count = count($dates);
		$init = $dates[0];
		$label = '';
		$end = end($dates);
		if ($count==1) {
			$init = self::dateEasyView($init);
			$label = $init;
		} elseif (date('Y-m-d', strtotime($init.' +'.($count-1).' days')) == $end) {
			$label = self::dateEasyView($init).' - '.self::dateEasyView($end);
		} elseif ($count == 2) {
			$label = self::dateEasyView($init).', '.self::dateEasyView($end);
		} else {
			$continue = array('init'=>false);
			$group = 0;
			for ($i=1; $i<($count-1);$i++) {
				$current       = date('Y-m-d', strtotime($init.' +1 days'));
				$after_current = date('Y-m-d', strtotime($dates[$i+1].' +1 days'));
				if ($current == $dates[$i] && $after_current == $dates[$i+1]
					&& !$continue['init']) {
						$continue['init'] = true;
						$continue['values'][$group][] = $init;
				} elseif ($current == $dates[$i] && $after_current == $dates[$i+1]
					&& !isset($continue['values'][$group])) {
					$continue['values'][$group][] = $init;
				} elseif ($current == $dates[$i] && $after_current != $dates[$i+1]) {
					if ( !isset($continue['values'][$group])) {
						$continue['values'][$group][] = $init;
					}
				} elseif ($current != $dates[$i]){
					if (!isset($continue['values'][$group])) {
						$continue['values'][$group][] = $init;
					} else{
						$continue['values'][$group][] = $init;
					}
					$group++;
					if ($continue['init'] == false) {
						$continue['init'] = true;
					}
				}

				if( $dates[$i+1] == $end ){
					if($current == $dates[$i] && $after_current == $dates[$i+1]) {
					} elseif ($current != $dates[$i] && $after_current == $dates[$i+1]) {
						$continue['values'][$group][] = $dates[$i];
					} elseif ($current == $dates[$i] && $after_current != $dates[$i+1]) {
						$continue['values'][$group][] = $dates[$i];
						$group++;
					} elseif ($current == $dates[$i] && $after_current != $dates[$i+1]) {
						$continue['values'][$group][] = $dates[$i];
						$group++;
					} elseif ($current != $dates[$i] && $after_current != $dates[$i+1]) {
						$group++;
						$continue['values'][$group][] = $dates[$i];
						$group++;
					}
					$continue['values'][$group][] = $end;
				}
				$init = $dates[$i];
			}
			$label_val = [];
			foreach ($continue['values'] as $level) {
				foreach ($level as &$level2) {
					$level2 = self::dateEasyView($level2);
				}
				$label_val[] = implode(' - ', $level);
			}
			$label = implode(', ', $label_val); ;
		}
		return $label;
	}

	public static function getRangeDays($filter)
	{
		$init = $filter['date_start'];
		$result = [];

		do {
			$result[] = $init;
			$init = date('Y-m-d', strtotime('+1 days', strtotime($init)));
		} while ($init <= $filter['date_end']);
		return $result;
	}

	public static function getYearMonth($aggregate)
	{
		array_walk($aggregate, function(&$y_m) {
			$y_m = self::textToDateYearMonth($y_m);
		});
		return $aggregate;
	}

	public static function textToDateYearMonth($y_m, $short = false, $break=false, $strong=false)
	{
		$y_m = explode('-', $y_m);
		$break = $break ? '<br>' : '';
		$year = $strong ? '<strong>'.$y_m[0].'</strong>' : $y_m[0];
		return $break.date((!$short ?'F' :'M'), strtotime('2015-'.$y_m[1].'-01'))
			.$break.' '.$year;
	}

	public static function getArrayYearMonth($aggregate)
	{
		$data = [];
		$data[0] = array();
		$data[1] = array();

		array_walk($aggregate, function($y_m) use(&$data) {
			$date_separate_array = self::dateToArrayYearMonth($y_m);

			array_push($data[0], $date_separate_array['year']);
			array_push($data[1], $date_separate_array['month']);
			/*$data = array_merge_recursive(
				$data,
				self::dateToArrayYearMonth($y_m)
			);*/
		});
		return $data;
	}

	public static function dateToArrayYearMonth($y_m)
	{
		$y_m = explode('-', $y_m);

		return [
			'year' => isset($y_m[0]) ? $y_m[0] : date('Y'),
			'month' => isset($y_m[1]) ? $y_m[1] : date('j')
		];
	}

	public static function yearMonth()
	{
		return date('Y-n', strtotime('-1 day', strtotime('last month')));
	}

	public static function naturalSort($origin)
	{
		$output = $origin;
		natcasesort($output);
		return $output;
	}

	public static function removeEmptyItems($origin)
	{
		return array_filter($origin);
	}

}
?>
