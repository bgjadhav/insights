<?php
class ValidateRoadmap
{
	public static function component()
	{
		$data = MetaProductProject::component();
		$input = strtolower(Input::get('idComponent'));

		return isset($data[$input]) ? $input : 'all';
	}

	public static function status()
	{
		$data = MetaProductProject::status();
		$input = strtolower(Input::get('status'));

		return isset($data[$input]) ? $input : 'all';
	}

	public static function year()
	{
		$data = MetaProductProject::year();
		$input = strtolower(Input::get('year'));

		return isset($data[$input]) ? $input : date('Y');
	}

	public static function quarter()
	{
		$data = MetaProductProject::target();
		$input = strtolower(Input::get('idTarget'));

		return isset($data[$input]) ? $input : 'all';
	}

	public static function label()
	{
		$data = MetaProductProject::label();
		$input = strtolower(Input::get('idLabel'));

		return isset($data[$input]) ? $input : 'all';
	}

	public function validateOrder($order)
	{
		return in_array($order, ['ASC', 'DESC']);
	}

	private static function idOrder()
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
			'release_phase',
			'year'
		];
		return in_array($orderI, $valid) ? $orderI : 'major';
	}
}
