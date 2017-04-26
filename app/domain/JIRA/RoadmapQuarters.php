<?php
class RoadmapQuarters implements FilterInterface
{
	public static function filter($option)
	{
		try {

			$quarters = QueryService::run(

				$option['quarters'],

				$option['conn']
			);

			$clean = [];

			$none = false;

			foreach ($quarters as $key => $value) {

				$has_quarter = false;

				$clean = self::appendYear($clean, $value->target_open_beta_year_o);

				$clean = self::appendYear($clean, $value->target_release_year_o);

				$clean = self::appendYear($clean, $value->target_closed_beta_year_o);


				$clean = self::appendBelong($clean, $value->target_open_beta_year_o, $value->target_open_beta_o, $has_quarter);

				$clean = self::appendBelong($clean, $value->target_release_year_o, $value->target_release_o, $has_quarter);

				$clean = self::appendBelong($clean, $value->target_closed_beta_year_o, $value->target_closed_beta_o, $has_quarter);

				if ($has_quarter == false) {
					$none = true;
				}

			}

			if ($option['full'] != 'false') {
				if ($none) {
					$clean['none']['year'] = '';
					$clean['none']['belong'][''] = 'None';
				}

			}

			else {

				$quarters_options = [
					'Q1' => [],
					'Q2' => ['Q1'=>'Q1'],
					'Q3' => ['Q1'=>'Q1', 'Q2'=>'Q2'],
					'Q4' => ['Q1'=>'Q1', 'Q2'=>'Q2', 'Q3'=>'Q3']
				];

				$month = date('n');

				if ($month <= 3 ) {
					$final = self::cleanFull($clean, $quarters_options['Q1']);

				} elseif ($month <= 6 ) {
					$final = self::cleanFull($clean, $quarters_options['Q2']);

				} elseif ($month <= 9 ) {
					$final = self::cleanFull($clean, $quarters_options['Q3']);

				} elseif ($month <= 12 ) {
					$final = self::cleanFull($clean, $quarters_options['Q4']);
				}

				$clean = $final;

			}

			return $clean;

		} catch(Exception $e) {
			throw new Exception("Error Processing Full Config", 1);

		}
	}

	private static function cleanFull($clean, $quarters)
	{
		$final = [];

		$current_year = date('Y');
		$next_year = date('Y', strtotime('+362 days'));
		$final[$current_year] = $clean[$current_year];

		if (isset($clean[$next_year]['belong'])) {

			foreach ($clean[$next_year]['belong'] as $QX) {

				if (isset($quarters[$QX])) {
					$final[$next_year]['year'] = $next_year;
					$final[$next_year]['belong'][$QX] = $QX;

				}

			}
		}

		return $final;

	}


	private static function appendYear($clean, $year)
	{

		if ($year != 'ZZ') {
			$clean[$year]['year'] = $year;
			ksort($clean);
		}

		return $clean;

	}


	private static function appendBelong($clean, $year, $quarter, &$has_quarter)
	{

		if (!in_array('ZZ', [$quarter, $year])) {

			$clean[$year]['belong'][$quarter] = $quarter;

			ksort($clean[$year]['belong']);

			$has_quarter = true;
		}

		return $clean;

	}

}
