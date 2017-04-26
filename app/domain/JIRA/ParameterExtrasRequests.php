<?php
class ParameterExtrasRequests
{
	public static function all()
	{
		$total = Input::all();
		return [
			'tid' => self::tid(),
			'search' => self::search(),
			'firstLoad' => self::firstLoad($total),
			'filtered' => self::filtered($total),
			'orderI' => self::idOrder(),
			'order' => self::orderOption(),
			'page' => self::page()
		];
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
			'summary',
			'components',
			'status',
			'candidate_consid',
			'reporter',
			'created',
			'labels'
		];
		return in_array($orderI, $valid) ? $orderI : 'created';
	}

	private static function orderOption()
	{
		$order = trim(Input::get('order'));
		static $valid = [
			'ASC',
			'DESC'
		];
		return in_array($order, $valid) ? $order : 'ASC';
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

	public static function page()
	{
		$page = Input::get('page');
		return is_numeric($page) ? $page : '0';
	}

	private static function filtered($total)
	{
		$filtered = Input::get('filtered');
		return $filtered === 'true' || $filtered === 'false' ? $filtered
			: (count($total) > 0 ? 'true' : 'false');
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
