<?php
class ProdRoadmap extends Eloquent
{
	protected $connection = 'jira_prod';
	protected $table = 'roadmap_product_issues';

	public static function getConnName()
	{
		return 'jira_prod';
	}

	public static function roadmapAndCandidate()
	{
		return self::whereRaw('roadmap IN (\'Product Roadmap\', \'Product-Roadmap Candidate\')');
	}

	public static function roadmap()
	{
		$year = date('Y');
		return self::whereRaw('roadmap = \'Product Roadmap\'')
			->whereRaw(
				'(year >= \''.RegexMySQL::filter($year).'\''
					.' OR (year = \'\' AND (created >= \''.RegexMySQL::filter($year).'-01-01 00:00:00'.'\'))'
				.')')
			->whereRaw('status IN ('.Format::str(MetaProductProject::status()).')');
	}

	public static function candidate()
	{
		return self::whereRaw('roadmap = \'Product-Roadmap Candidate\'')
			->whereRaw('status IN ('.Format::str(MetaCandidateProject::status()).')');
	}

	public static function scopeValidated($query)
	{
		return $query->whereRaw('validate = 1');
	}

	public static function scopeComponent($query, $component)
	{
		if ($component != 'all') {
			$component = RegexMySQL::filter($component);

			return $query->whereRaw('('.
				'components LIKE \''.$component.',%\''.
				' OR components LIKE \'% '.$component.', %\''.
				' OR components LIKE \'% '.$component.'\''.
				' OR components = \''.$component.'\''.
				')'
			);
		}
		return $query;
	}

	public static function scopeFirstComponent($query, $component)
	{
		$component = RegexMySQL::filter($component);

		if ($component != 'all') {
			return $query->whereRaw('first_component LIKE  \'%'.$component.'%\'');
		}
		return $query;
	}

	public static function scopeLabel($query, $label)
	{
		if ($label != 'all') {

			$label = RegexMySQL::filter($label);

			return $query->whereRaw('('.
				'labels LIKE \''.$label.',%\''.
				' OR labels LIKE \'% '.$label.', %\''.
				' OR labels LIKE \'% '.$label.'\''.
				' OR labels = \''.$label.'\''.
				')'
			);
		}
		return $query;
	}

	public static function scopeStatus($query, $status)
	{
		if ($status != 'all') {

			return $query->whereRaw('status =\''. $status. '\'');
		}
		return $query;
	}

	public static function scopeGeo($query, $geo)
	{
		if ($geo != 'all') {

			$geo = RegexMySQL::filter($geo);

			return $query->whereRaw('geo_all LIKE \'%'. $geo. '%\'');
		}
		return $query;
	}

	public static function scopeValidID($query, $id)
	{
		$id = RegexMySQL::filter($id);

		$query->whereRaw('issue_id > 0 AND issue_id ='. $id);

		return $query;
	}

	public static function scopeYear($query, $year)
	{
		if ($year != 'all') {

			$year = RegexMySQL::filter($year);

			return $query->whereRaw('year = '. $year);
		}
		return $query;
	}

	public static function scopeTargetGA($query, $target)
	{
		if ($target != 'all') {
			if ($target != '') {

				$target = RegexMySQL::filter($target);
				return $query->whereRaw('target_release LIKE \''.$target.'%\'');

			} else {
				return $query->whereRaw('target_release= \'\'');
			}
		}
		return $query;
	}

	public static function scopeTargetClosedBeta($query, $target)
	{
		if ($target != 'all') {
			if ($target != '') {

				$target = RegexMySQL::filter($target);
				return $query->whereRaw('target_closed_beta LIKE\''.$target.'%\'');

			} else {
				return $query->whereRaw('target_closed_beta = \'\'');
			}
		}
		return $query;
	}

	public static function scopeTargetOpen($query, $target)
	{
		if ($target != 'all') {
			if ($target != '') {

				$target = RegexMySQL::filter($target);
				return $query->whereRaw('target_open_beta LIKE \''.$target.'%\'');
			} else {
				return $query->whereRaw('target_open_beta = \'\'');
			}
		}
		return $query;
	}

	public static function scopeReleased($query, $released)
	{
		if ($released == 'true') {
			return $query->whereRaw('status NOT IN (\'Released (Open Beta)\', \'Released (GA)\')');
		}
		return $query;
	}

	public static function scopeTargetMM($query, $target)
	{
		if ($target != 'all') {
			$where = '';

			if ($target != '') {
				$target = RegexMySQL::filter($target);

				$where .= ' (target_open_beta LIKE \''.$target.'%\'';
				$where .= ' OR target_closed_beta LIKE \''.$target.'%\'';
				$where .= ' OR target_release LIKE \''.$target.'%\')';

			} else {

				$where .= ' (target_open_beta = \'\'';
				$where .= ' OR target_closed_beta LIKE \'\'';
				$where .= ' OR target_release LIKE \'%\')';
			}

			return $query->whereRaw($where);
		} else {
			return $query;
		}
	}

	public static function scopeOrderTickets($query, $firstLoad, $i, $order)
	{
		if ($firstLoad === 'true') {
			$query->orderBy('major', 'DESC')
				->orderBy('year', 'DESC')
				->orderBy('first_component', 'ASC')
				->orderBy('status', 'ASC')
				->orderByRaw('(labels = ""), labels ASC');
		} else {

			if (in_array($i, ['components', 'labels'])) {
				$query->orderByRaw('('.$i.' = "No '.ucwords($i).'"), '.$i.' '.$order);

			} else {

				$target_years = ['o_target_closed_beta', 'o_actual_ga', 'o_target_open_beta', 'o_target_release'];
				if (in_array($i, $target_years)) {
					$query->orderBy(substr($i,2).'_year', $order);
				}

				if ($i == 'major') {
					$query->orderBy($i, $order);
				} else {
					$query->orderByRaw('('.$i.' = ""), '.$i.' '.$order);
				}
			}

			$query->orderBy('year', 'DESC');
		}

		$query->orderBy('epic_name', 'ASC');

		return $query;
	}


	public static function scopeAllTargetsByYearAndQuarter($query, $data)
	{
		$where = [];

		array_walk($data, function($quarter) use (&$where) {

			$statement = '(target_release_year = '.$quarter['year'];
			$statement .= ' OR target_closed_beta_year = '.$quarter['year'];
			$statement .= ' OR target_open_beta_year = '.$quarter['year'];

			if ($quarter['year'] == date('Y')) {
				$statement .= ' OR target_release_year = \'\'';
				$statement .= ' OR target_closed_beta_year = \'\'';
				$statement .= ' OR target_open_beta_year = \'\'';
			}

			$statement .= ')';

			if (!empty($quarter['belong'])) {

				$belong = Format::str($quarter['belong']);

				$statement .= ' AND (SUBSTRING(target_release, 1, 2) IN ('.$belong.')';

				$statement .= ' OR SUBSTRING(target_closed_beta, 1, 2) IN ('.$belong.')';

				$statement .= ' OR SUBSTRING(target_open_beta, 1, 2) IN ('.$belong.'))';
			}

			$where[] = $statement;
		});

		if (!empty($where)) {
			$where = implode(' OR ', $where);
			return $query->whereRaw('('.$where.')');

		} else {
			return $query;
		}
	}

	public static function scopeAllInTwoYears($query, $first, $second)
	{
		return $query->whereRaw('year IN ('.$first.','.$second.')');
	}

	public static function scopeSearchRegex($query, $search)
	{
		if ($search !='' ) {
			$query->whereRaw($search);
		}

		return $query;
	}

}
