<?php
class JiraF
{
	public static function assignTotal($val, $tmp_Totals, $index)
	{
		foreach ($index as $i => $v) {
			$tmp_Totals[$i] += $val->{$v};
		}
		return $tmp_Totals;
	}

	public static function assignFinalcTotal($items, $tmp_Totals, $index)
	{
		foreach ($index as $i => $v) {
			$tmp_Totals[$i] = $items[$v] > 0 ? $tmp_Totals[$i]/$items[$v] : 0;
		}
		return $tmp_Totals;
	}

	public static function getEndDate($start, $seconds)
	{
		return date('Y-m-d H:i:s', (strtotime($start)+$seconds));
	}

	public static function getDayWeek($date)
	{
		return date('N', strtotime($date));
	}

	public static function getDiffDays($start, $end)
	{
		$end = date('Y-m-d 00:00:00', strtotime($end));
		//$init = date('Y-m-d  00:00:00', strtotime('+1 days', strtotime($start)));
		$init = date('Y-m-d  00:00:00', strtotime($start));
		$count = 0;

		do {
			$count++;
			$init = date('Y-m-d  00:00:00', strtotime('+1 days', strtotime($init)));
		} while ($init < $end);

		return $count;
	}

	public static function getInfoDates($row, $field)
	{
		$row->{$field} = self::getEndDate(
			$row->{'CREATED'},
			$row->{$field.'_SECONDS'}
		);

		$row->{'OPEN_DAY'} = self::getDayWeek($row->{'CREATED'});
		$row->{$field.'_DAY'} = self::getDayWeek($row->{$field});
		$row->{'DIFFERENCE'} = self::getDiffDays(
			$row->{'CREATED'},
			$row->{$field}
		);
		return $row;
	}

	public static function getMonday($row, $field)
	{
		if ($row->{'DIFFERENCE'} > 5) {
			$row = self::getThisFriday($row, $field);
		} elseif ($row->{'DIFFERENCE'} == 5) {
			$row = self::getLastDays($row, $field);
		} else {
			// If close during the weekdays
			$row->{'VALID_'.$field} = $row->{$field.'_SECONDS'};
			$row->{'VALID_'.$field.'_DAY'} = date(
				'Y-m-d H:i:s',
				strtotime($row->{'CREATED'})+$row->{$field.'_SECONDS'}
			);
		}
		return $row;
	}

	public static function getWeekDay($row, $field, $less, $great)
	{
		//~ $row->{'$less'} = $less;
		//~ $row->{'$great'} = $great;
		if ($row->{'DIFFERENCE'} <= $less) {
			$row->{'VALID_'.$field} = $row->{$field.'_SECONDS'};
			$row->{'VALID_'.$field.'_DAY'} = date(
				'Y-m-d H:i:s',
				strtotime($row->{'CREATED'})+$row->{$field.'_SECONDS'}
			);
		} elseif ($row->{'DIFFERENCE'} >= $great) {
			// Closed durring the weekend
			if (($row->{'OPEN_DAY'} == 5 && $row->{'DIFFERENCE'} == 3)
				|| ($row->{'OPEN_DAY'} == 4 && $row->{'DIFFERENCE'} == 4)
				|| ($row->{'OPEN_DAY'} == 3 && $row->{'DIFFERENCE'} == 5)
				|| ($row->{'OPEN_DAY'} == 2 && $row->{'DIFFERENCE'} == 6)
			) {
				$row = self::getThisFriday($row, $field);
			} else {
				$row->{'VALID_'.$field} = self::restWeekend(
					2,
					$row->{$field.'_SECONDS'}
				);
				$row->{'VALID_'.$field.'_DAY'} = $row->{$field};
				$row->{'VALID_'.$field.'_days'} = $row->{'VALID_'.$field}/(60*60*24);
				$row->{'note'} = 'restweekedn';
			}
		} elseif ($row->{'OPEN_DAY'} == 5 && $row->{$field.'_DAY'} == 5) {
			$row = self::getLastDays($row, $field);
		} else {
			$row = self::getThisFriday($row, $field);
		}
		return $row;
	}

	public static function getLastDays($row, $field)
	{
		$row->{'VALID_'.$field} = $row->{$field.'_SECONDS'};
		$row->{'VALID_'.$field.'_days'} = $row->{'VALID_'.$field}/(60*60*24);
		$row->{'VALID_'.$field.'_DAY'} = $row->{$field};
		return $row;
	}

	public static function getThisFriday($row, $field)
	{
		$days = 5;
		$close = date(
			'Y-m-d 23:59:59',
			strtotime(
				'+'.($days - $row->{'OPEN_DAY'}).' days',
				strtotime(date('Y-m-d 00:00:00', strtotime($row->{'CREATED'})))
			)
		);
		$row->{'VALID_'.$field} = strtotime($close)-strtotime($row->{'CREATED'});
		$row->{'VALID_'.$field.'_days'} = $row->{'VALID_'.$field}/(60*60*24);
		//$row->{'VALID_'.$field.'_DAY'} = $close;
		$row->{'VALID_'.$field.'_DAY'} = date(
			'Y-m-d H:i:s',
			(strtotime($row->{'CREATED'})+ $row->{'VALID_'.$field})
		);
		return $row;
	}

	public static function restWeekend($days, $row)
	{
		return $row - ((24*60*60)*$days);
	}

	public static function getNumberWeekends($start, $end)
	{
		$end = date('Y-m-d 00:00:00', strtotime($end));
		$init = date('Y-m-d  00:00:00', strtotime($start));
		//$init = date('Y-m-d  00:00:00', strtotime('+1 days', strtotime($start)));
		$count = 0;

		do {
			if (date('N', strtotime($init)) >= 6) {
				$count++;
			}
			$init = date('Y-m-d  00:00:00', strtotime('+1 days', strtotime($init)));
		} while ($init < $end);

		return $count;
	}

	public static function getWeekDaysTime($results, $field = 'CLOSED', $sub=false)
	{
		$categories = [];
		foreach ($results as $row) {
			//test
			//~ $row->{'CREATED'} = '2015-11-21 00:00:00';
			//~ $row->{$field.'_SECONDS'} = (86400 *14) -8200;

			$row->{'VALID_'.$field} = $row->{$field.'_SECONDS'};


			$row = self::getInfoDates($row, $field);

 			if ($row->{'DIFFERENCE'} <= 7) {
				if ($row->{'OPEN_DAY'} == 1) {
					$row = self::getMonday($row, $field);
				} elseif ($row->{'OPEN_DAY'} <= 5) {
					$less = 5 - $row->{'OPEN_DAY'};
					$noWeekend = $less + 3 ;
					$row = self::getWeekDay($row, $field, $less, $noWeekend);
				} elseif ($row->{'DIFFERENCE'} == 1
					|| ($row->{'OPEN_DAY'} == 6 && $row->{'DIFFERENCE'} == 2 )) {
						// Only weekend
					//$row->{'note'} = 'onlyweekend';
					$row = self::getLastDays($row, $field);
				} else {
					$d = 2;
					if ($row->{'OPEN_DAY'} == 7) {
						$d = 3;
					}
					$row->{'ORIGIN_CREATED'} = $row->{'CREATED'};
					$row->{'CREATED'} = date('Y-m-d 00:00:00',
						strtotime('+'.$d.' days',
						strtotime(date('Y-m-d 00:00:00', strtotime($row->{'CREATED'}))
						))
					);
					$row->{'note'} = 'openWeekendCloseWeekday';
					$row = self::getInfoDates($row, $field);
					// change start monday 00:00:00
				}
			} else {
					//@todo
					// what happens when start in weekend
					// what happens when close in weekend

					$row->{'WEEKENDS_DAYS'} = self::getNumberWeekends(
						$row->{'CREATED'},
						$row->{$field}
					);

					$row->{'VALID_'.$field} = self::restWeekend(
						$row->{'WEEKENDS_DAYS'},
						$row->{$field.'_SECONDS'}
					);

					//#todo thisFriday
					$row->{'VALID_'.$field.'_days'} = $row->{'VALID_'.$field}/(60*60*24);

					//~ $row->{'VALID_'.$field.'_DAY'} = date(
						//~ 'Y-m-d H:i:s',
						//~ (strtotime(
							//~ date('Y-m-d 00:00:00', strtotime($row->{'CREATED'}))
						//~ )+ $row->{'VALID_'.$field})
					//~ );

					$row->{'IN'} = 'MORE 7';
			}

			if ($sub) {
				if (!isset($categories[$row->{'ISSUETYPE'}][$row->{'SUBTYPE'}])) {
					$categories[$row->{'ISSUETYPE'}][$row->{'SUBTYPE'}] = 0;
				}
				$categories[$row->{'ISSUETYPE'}][$row->{'SUBTYPE'}] += $row->{'VALID_'.$field};
			} else {
				if (!isset($categories[$row->{'ISSUETYPE'}])) {
					$categories[$row->{'ISSUETYPE'}] = 0;
				}
				$categories[$row->{'ISSUETYPE'}] += $row->{'VALID_'.$field};
			}
		}
		return $categories;
	}

	public static function translateToHourMinutesSeconds($diff)
	{
		$h_ = (($diff / 3600) >= 1) ? '%02d:':'';
		$m_ = (($diff / 60 % 60) >= 1) ? '%02d:':'';
		$format = sprintf($h_.$m_.'%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);
		return $format;
	}

}
