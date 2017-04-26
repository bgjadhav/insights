<?php
class RoadmapCounterHandler
{
	public function handle($data)
	{

		$data = unserialize($data);

		if (in_array($data['report'], ['roadmap', 'candidate', 'requests'])) {

			$data['data'] = self::decodeData($data['data']);

			$action = self::firstOrNew($data);

			$data['clicked'] = self::initClicks($data);

			$data = self::clicked($data);

			if ($action->id) {

				$data['clicked'] = self::sumCounter($data['clicked'], $action);
			}

			$action = self::appendUserInfo($data['user'], $action);

			$action = self::appendClicks($data['clicked'], $action);

			$action->save();
		}

		return false;
	}

	protected static function decodeData($data)
	{
		parse_str($data[0], $output);

		return $output;

	}

	protected static function firstOrNew($data)
	{
		return UsageStats::firstOrNew([

			'mm_day' => $data['day'],

			'user_id' => $data['user']['uid'],

			'environment' => $data['environment']

		]);
	}

	protected static function initClicks()
	{
		return  array_fill_keys([
			'roadmap', 'candidate', 'requests',

			'export_filtered', 'export_current',

			'reset', 'link_to_ticket', 'link_to_phase',

			'alert', 'help', 'share_filtered', 'export_open', 'make_request',

			'open_detail', 'open_gear',

			'comment', 'follow', 'share_project', 'view',

			'add_comment', 'follow_be_watcher', 'view_go_jira',

			'total'
		], 0);
	}

	protected static function clicked($data)
	{
		$toCheck = self::optionToCheck($data['action']);

		foreach ($toCheck as $className) {
			$data = self::data(new $className($data));
		}

		$data = self::data(new ProductClickImp($data));

		return $data;
	}

	protected static function optionToCheck($action)
	{
		static $config = [
			'table' => [
				'ProductPageClick',

				'ProductResetClick'
			],

			'click' => [

				'ProductDetailAndGearClick',

				'ProductShareProject',

				'ProductShareFiltered',

				'ProductExportClick',

				'ProductButonClick',

				'ProductLinkClick'

			]
		];

		return $config[$action];
	}

	protected static function data(ProductClick $checkClick)
	{
		$checkClick->check();

		return $checkClick->data();
	}

	protected static function sumCounter($clicked, $action)
	{
		foreach ($clicked as $key => $val) {

			$clicked[$key] = $action->{$key} + $clicked[$key];
		}

		return $clicked;

	}

	protected static function appendUserInfo($data, $action)
	{
		$action->full_name = trim($data['uname'].' '.$data['ulastn']);

		$action->email = $data['email'];

		return $action;
	}

	protected static function appendClicks($clicked, $action)
	{
		foreach ($clicked as $key => $val) {

			if ($clicked[$key] > 0) {

				$action->{$key} = $clicked[$key];
			}
		}

		return $action;
	}

}
