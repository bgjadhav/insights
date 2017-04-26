<?php
class CleanRoadmapAndCandidateChanges
{
	public static function toArray($data)
	{
		$clean = [];

		foreach ($data['data'] as $item) {

			$item = self::forceArray($item);

			$clean = self::clean($item, $clean);

			$clean = self::append($item, $clean, $data);

			$clean = self::appendShortName($item, $clean);

		}

		return $clean;
	}


	private static function forceArray($item)
	{
		return (array) $item;
	}

	private static function clean($item, $clean)
	{
		$clean[$item['issue_id']] = self::cleanItem($item, $clean);

		return $clean;
	}

	private static function cleanItem($item, $clean)
	{
		if (!isset($clean[$item['issue_id']])) {

			return $item;

		} else {
			return $clean[$item['issue_id']];
		}

	}

	private static function append($item, $clean, $data)
	{
		$clean[$item['issue_id']] = self::appendHistorial(
			[
			'clean' => $clean[$item['issue_id']],

			'item' => $item,

			'label' => $data['label'],

			'specificitation' => $data['specificitation']

			]
		);

		return $clean;
	}


	private static function appendHistorial($data)
	{

		if (method_exists('CleanRoadmapAndCandidateChanges', $data['specificitation'])) {

			$data['clean'] = self::$data['specificitation'] (

				[
				'clean' => $data['clean'],

				'item' => $data['item'],

				'label' => $data['label']
				]

			);

		}

		return $data['clean'];
	}

	private static function appendShortName($item, $clean) {

		if (!isset($clean[$item['issue_id']]['short_name'])) {
			$clean[$item['issue_id']]['short_name'] = self::shortName($item['epic_name']);
		}

		return $clean;
	}


	private static function shortName($epic_name)
	{

		$epic_name = trim($epic_name);

		if (strlen($epic_name) > 60) {

			$epic_name = self::subEpicName($epic_name).'...';
		}

		return $epic_name;
	}

	private static function subEpicName($epic_name, $limit=57) {
		return trim(substr($epic_name, 0, $limit));
	}


	private static function withPrevious($data)
	{
		$indexes = ['changes', 'first_validation', 'last_validation'];

		foreach ($indexes as $index) {

			$data = self::cleanDataWithIndex($data, $index);

		}

		return $data['clean'];
	}


	private static function cleanDataWithIndex($data, $index)
	{
		$data['clean']['historial'][$data['label']][$index][] = self::cleanSpaceAndForceValueOrNone(
			$data['item'][$index]
		);

		return $data;
	}


	private static function withoutPrevious($data)
	{
		$data = self::cleanDataFirstIndex($data, 'changes');

		return $data['clean'];
	}


	private static function newTicket($data)
	{
		$data = self::cleanDataFirstIndex($data, 'changes');

		$data = self::addDataSecondIndexToNone($data, 'changes');

		$data = self::forceAddStatusTicket($data);

		return $data['clean'];
	}


	private static function movedTicket($data)
	{
		$data['clean'] = self::withPrevious($data);

		$data = self::forceAddStatusTicket($data);

		return $data['clean'];
	}

	private static function cleanDataFirstIndex($data, $index)
	{
		$data['clean']['historial'][$data['label']][$index][0] = self::cleanSpaceAndForceValueOrNone(
			$data['item'][$data['label']]
		);

		return $data;
	}

	private static function addDataSecondIndexToNone($data, $index)
	{
		$data['clean']['historial'][$data['label']][$index][1] = 'None';

		return $data;
	}

	private static function cleanSpaceAndForceValueOrNone($val)
	{
		$val = trim($val);

		if ($val == '') {
			$val = 'None';
		}

		return $val;
	}

	private static function forceAddStatusTicket($data)
	{
		$data['clean']['historial']['status']['changes'][0] = self::cleanSpaceAndForceValueOrNone(
			$data['item']['status']
		);

		return $data;
	}
}
?>
