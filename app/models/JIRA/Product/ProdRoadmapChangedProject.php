<?php
class ProdRoadmapChangedProject implements RoadmapChangesInterface
{
	public static function rawQuery($date, $end_date=false)
	{
		$extras = self::whereEndDate($end_date);

		return 'SELECT issues.issue_id, issues.status, CASE WHEN issues.roadmap = \'Product Roadmap\' THEN \'roadmap\' ELSE \'candidate\' END AS roadmap, '.
			' CASE WHEN issues.roadmap = \'Product Roadmap\' THEN \'Roadmap\' ELSE \'Candidate\' END AS project, issues.epic_name, issues.first_component,'.
			' CASE WHEN changed.roadmap = \'Product Roadmap\' THEN \'Roadmap\' WHEN changed.roadmap = \'Product-Roadmap Candidate\' THEN \'Candidate\' ELSE \'None\' END AS changes,'.
			' changed.first_validation, changed.last_validation'.

			' FROM roadmap_product_issues issues'.

			' INNER JOIN roadmap_product_issues_changed_roadmap AS changed ON issues.issue_id=changed.issue_id'.

			' WHERE issues.issue_id IN ('
					.'SELECT ta.issue_id  '.
					' FROM roadmap_product_issues_changed_roadmap AS ta '.

					' WHERE ta.issue_id IN ('.
						' SELECT issue_id '.
						' FROM roadmap_product_issues_changed_roadmap'.
						' WHERE first_validation >= \''.$date.' 00:00:00\''.
						$extras['first_validation'].
						' GROUP BY issue_id'.
					')'.

					$extras['ta.first_validation'].

					' GROUP BY ta.issue_id'.
					' HAVING COUNT(*) > 1 '.
				') '.

				' AND issues.validate = 1  '.

				' AND ( '.
					'(issues.roadmap = \'Product Roadmap\' '.
						' AND issues.status IN ('.Format::str(MetaProductProject::status()).')'.
					')'.

					' OR  (issues.roadmap = \'Product-Roadmap Candidate\' '.
						' AND issues.status IN ('.Format::str(MetaCandidateProject::status()).')'.
					')'.
				')'.

			' ORDER BY issues.issue_id, changed.last_validation DESC;';
	}

	public static function whereEndDate($end_date=false)
	{
		$exras = [
			'first_validation' => '',
			'ta.first_validation' => ''
		];

		if ($end_date !== false) {

			$extras['first_validation'] = 'AND first_validation <= \''.$end_date.'\'';

			$extras['ta.first_validation'] = 'AND ta.first_validation <= \''.$end_date.'\'';

		}

		return $extras;

	}
}
