<?php
class ProdRequest extends Eloquent
{
	protected $connection = 'jira_prod';
	protected $table = 'roadmap_prod_req_issues';


	public static function scopeValidated($query)
	{
		return $query->where('validate', '=' , 1);
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
		if ($component != 'all') {
			$component = RegexMySQL::filter($component);

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
			return $query->where('status', '=', $status);
		}
		return $query;
	}

	public static function scopeCandidateConsideration($query, $consideration)
	{
		if ($consideration != 'all') {
			$consideration = RegexMySQL::filter($consideration);

			return $query->where('candidate_consid', '=', $consideration);
		}
		return $query;
	}

	public static function scopeReporter($query, $reporter)
	{
		if ($reporter != 'all') {

			$reporter = RegexMySQL::filter($reporter);

			return $query->whereRaw('creatordisplay = \''.$reporter.'\'');
		}
		return $query;
	}

	public static function scopeOrderTickets($query, $firstLoad, $i, $order)
	{

		if ($firstLoad === 'true') {
			$query->orderBy('created', 'DESC');

		} else {

			if (in_array($i, ['components', 'labels'])) {
				$query->orderByRaw('('.$i.' = "No '.ucwords($i).'"), '.$i.' '.$order);

			} else {
				$query->orderByRaw('('.$i.' = ""), '.$i.' '.$order);
			}
		}

		return $query;
	}

	public static function scopeValidID($query, $id)
	{
		$query->where('issue_id', '>', '0');
		$query->where('issue_id', '=', $id);
	}
}
