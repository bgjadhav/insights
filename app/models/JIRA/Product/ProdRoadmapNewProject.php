<?php
class ProdRoadmapNewProject implements RoadmapChangesInterface
{
	public static function rawQuery($date, $end_date=false)
	{
		$extras = self::whereEndDate($end_date);

		return 'SELECT issue_id, status, CASE WHEN roadmap = \'Product Roadmap\' THEN \'roadmap\' ELSE \'candidate\' END AS roadmap, CASE WHEN roadmap = \'Product Roadmap\' THEN \'Roadmap\' ELSE \'Candidate\' END AS new, epic_name, first_component'.

			' FROM roadmap_product_issues'.

			' WHERE created >= \''.$date.' 00:00:00\''.
				$extras['created'] .

				' AND validate = 1 '.
				' AND issue_id NOT IN ('.
					' SELECT issue_id '.
					' FROM roadmap_product_issues_changed_roadmap'.
					' GROUP BY issue_id'.
					' HAVING COUNT(*) > 1 '.
				')'.

				' AND ('.
					' (roadmap = \'Product Roadmap\''
						.' AND status IN ('.Format::str(MetaProductProject::status()).')'
					.')'.

					' OR '.
					' (roadmap = \'Product-Roadmap Candidate\''
						.' AND status IN ('.Format::str(MetaCandidateProject::status()).')'
					.')'.
				' )'.
			' ORDER BY roadmap, created;';
	}

	public static function whereEndDate($end_date=false)
	{
		$exras = [
			'created' => ''
		];

		if ($end_date !== false) {
			$extras['created'] = 'AND created <= \''.$end_date.'\'';
		}

		return $extras;
	}
}
