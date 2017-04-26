<?php
class RoadmapAndCandidateChanges implements FilterInterface
{
	public static function filter($data)
	{

		if (!isset($data['end_date'])) {
			$data['end_date'] = false;
		}

		return self::data(
			$data['config'],
			$data['date'],
			$data['end_date']
		);
	}

	private static function data($config, $date, $end_date=false)
	{
		$data = [];

		foreach ($config as $index => $info) {

			$data[$index] = self::dataByChange(
				[
					'info' => $info,

					'date' => $date,

					'label' => $index
				],

				$end_date

			);

		}

		return $data;
	}

	private static function dataByChange($data, $end_date=false)
	{
		$raw = self::rawData(

			$data['info']['class'],

			$data['date'],

			$end_date

		);

		return CleanRoadmapAndCandidateChanges::toArray(
			[
				'data' => $raw,

				'specificitation' => $data['info']['clean'],

				'label' => $data['label']
			]

		);
	}

	private static function rawData($class, $date, $end_date=false)
	{
		return self::changesSince(

			new $class,

			$date,

			$end_date

		);
	}

	private static function changesSince(RoadmapChangesInterface $obj, $date, $end_date=false)
	{

		return self::runQuery(

			$obj::rawQuery(
				$date,
				$end_date
			)

		);
	}

	private static function runQuery($query)
	{
		return QueryService::run(

			$query,

			'jira_prod',

			10
		);
	}
}
?>
