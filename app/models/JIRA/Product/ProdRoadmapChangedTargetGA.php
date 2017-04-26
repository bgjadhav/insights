<?php
class ProdRoadmapChangedTargetGA implements RoadmapChangesInterface
{
	public static function rawQuery($date, $end_date=false)
	{
		$extras = self::whereEndDate($end_date);

		return 'SELECT issues.issue_id, issues.target_release AS target, \'roadmap\' AS roadmap,'.
			' issues.epic_name, issues.first_component,'.
			' changed.target_release AS changes, changed.first_validation, changed.last_validation'.

			' FROM roadmap_product_issues issues'.

			' INNER JOIN roadmap_product_issues_changed_target AS changed ON issues.issue_id=changed.issue_id'.

			' WHERE issues.issue_id IN ('
					.'SELECT ta.issue_id  '.
					' FROM roadmap_product_issues_changed_target AS ta '.

					' WHERE ta.issue_id IN ('.
						' SELECT issue_id '.
						' FROM roadmap_product_issues_changed_target'.
						' WHERE first_validation >= \''.$date.' 00:00:00\''.
						$extras['first_validation'].
						' GROUP BY issue_id'.
					')'.

					$extras['ta.first_validation'].

					' GROUP BY ta.issue_id'.
					' HAVING COUNT(*) > 1 '.

				') '.

				' AND issues.validate = 1  '.
				' AND issues.roadmap = \'Product Roadmap\' '.
				' AND issues.status IN ('.Format::str(MetaProductProject::status()).')'.

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
