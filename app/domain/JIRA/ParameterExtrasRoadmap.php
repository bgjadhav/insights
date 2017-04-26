<?php
class ParameterExtrasRoadmap
{
	public static function all()
	{
		$total = Input::all();

		$extra = [
			'tid' => self::tid(),
			'search' => self::search(),
			'firstLoad' => self::firstLoad($total),
			'filtered' => self::filtered($total),
			'orderI' => self::idOrder(),
			'hideReleased' => self::hideReleased(),
			'roadmap' => self::subProject()
		];

		$extra['order'] = self::orderOption($extra['orderI']);

		return $extra;
	}

	public static function tid()
	{
		$tid = trim(Input::get('tid'));
		return $tid != '' && is_numeric($tid) ? $tid : 0;
	}

	public static function idOrder()
	{
		$orderI = trim(Input::get('idOrder'));
		static $valid = [
			'major',
			'epic_name',
			'components',
			'status',
			'o_target_closed_beta',
			'o_target_open_beta',
			'o_target_release',
			'geo',
			'year'
		];
		return in_array($orderI, $valid) ? $orderI : 'major';
	}


	private static function orderOption($orderI)
	{
		$order = trim(Input::get('order'));
		static $valid = [
			'ASC',
			'DESC'
		];
		return in_array($order, $valid) ? $order : ($orderI == 'major' ? 'DESC' : 'ASC');
	}

	public static function search()
	{
		return trim(Input::get('search'));
	}

	private static function firstLoad($total)
	{
		$firstLoad = Input::get('firstLoad');
		return $firstLoad === 'true' || $firstLoad === 'false' ? $firstLoad
			: (count($total) > 0 ? 'false' : 'true');
	}

	private static function filtered($total)
	{
		$filtered = Input::get('filtered');
		return $filtered === 'true' || $filtered === 'false' ? $filtered
			: (count($total) > 0 ? 'true' : 'false');
	}

	public static function hideReleased()
	{
		$hideReleased = Input::get('hideReleased');
		return $hideReleased === 'true' || $hideReleased === 'false' ? $hideReleased  : 'false';
	}

	public static function subProject()
	{
		$roadmap = Input::get('roadmap');
		static $valid = [
			'roadmap',
			'candidate'
		];
		return in_array($roadmap, $valid) ? $roadmap : 'roadmap';
	}

	public static function loadFiltered()
	{
		return Input::get('filtered') === 'true'
			? false
		: (in_array(Input::get('firstLoad'), ['true', 'false']) ? Input::get('firstLoad') : true);
	}


	public static function order($order)
	{
		return in_array($order, ['ASC', 'DESC']);
	}
}
