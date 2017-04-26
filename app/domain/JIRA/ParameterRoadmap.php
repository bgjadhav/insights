<?php
class ParameterRoadmap
{
	public static function all()
	{
		return [
			'labels' => self::label(),
			'geoT' => self::geo(),
			'yearT' => self::year(),
			'targetMM' => self::quarter(),
			'statusT' => self::status(),
			'components' => self::component(),
		];
	}

	private static function component()
	{
		$data = MetaProductProject::component();
		$input = strtolower(Input::get('idComponent'));

		$decode = self::replaceSpecials($input);

		return [
			'data' => $data,
			'select' => isset($data[$decode]) ? $input : 'all'
		];
	}

	private static function status()
	{
		$data = MetaProductProject::status();
		$input = strtolower(Input::get('status'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function year()
	{
		$data = MetaProductProject::year();
		$input = strtolower(Input::get('year'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : date('Y')
		];
	}

	private static function geo()
	{
		$data = MetaProductProject::geo();
		$input = strtolower(Input::get('geo'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function quarter()
	{
		$data = MetaProductProject::target();
		$input = strtolower(Input::get('idTarget'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function label()
	{
		$data = MetaProductProject::label();
		$input = strtolower(Input::get('idLabel'));

		$decode = self::replaceSpecials($input);

		return [
			'data' => $data,
			'select' => isset($data[$decode]) ? $input : 'all'
		];
	}

	public static function validate($input)
	{
		$data = self::$input();
		return $data['select'];
	}

	private static function replaceSpecials($input)
	{
		return str_replace('’', "&#8217;", $input);
	}
}
