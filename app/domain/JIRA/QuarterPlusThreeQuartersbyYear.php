<?php
class QuarterPlusThreeQuartersbyYear implements FilterInterface
{
	public static function filter($option)
	{
		if ($option['start'] <= 3 || $option['start'] == 'Q1') {
			return self::QOne($option['year']);

		} elseif ($option['start'] <= 6 || $option['start'] == 'Q2') {
			return self::QTwo($option['year']);

		} elseif ($option['start'] <= 9 || $option['start'] == 'Q3') {
			return self::QThree($option['year']);

		} elseif ($option['start'] <= 12 || $option['start'] == 'Q4') {
			return self::QFour($option['year']);
		}
	}

	private static function QOne($year)
	{
		return self::setQuarter(
			[
				'first' => ['Q1', 'Q2', 'Q3', 'Q4'],
				'next' => [],
				'year' => $year
			]
		);
	}

	private static function QTwo($year)
	{
		return self::setQuarter(
			[
				'first' => ['Q2', 'Q3', 'Q4'],
				'next' => ['Q1'],
				'year' => $year
			]
		);
	}

	private static function QThree($year)
	{
		return self::setQuarter(
			[
				'first' => ['Q3', 'Q4'],
				'next' => ['Q1', 'Q2'],
				'year' => $year
			]
		);
	}

	private static function QFour($year)
	{
		return self::setQuarter(
			[
				'first' => ['Q4'],
				'next' => ['Q1', 'Q2', 'Q3'],
				'year' => $year
			]
		);
	}

	private static function setQuarter($quarters)
	{
		return  [
			[
				'belong' => $quarters['first'],
				'year' => $quarters['year']
			],
			[
				'belong' => $quarters['next'],
				'year' => self::nextYear($quarters['year'])
			]
		];
	}

	private static function nextYear($year)
	{
		return date(
			'Y',
			strtotime(date('Y-01-03', strtotime($year.'-01-03')).'+ 1 year')
		);
	}
}
?>
