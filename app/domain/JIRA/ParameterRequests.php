<?php
class ParameterRequests
{
	public static function all()
	{
		return [
			'labels' => self::label(),
			'reporter' => self::reporter(),
			'considerations' => self::resolution(),
			'statusT' => self::status(),
			'components' => self::component()

		];
	}

	private static function component()
	{
		$data = MetaRequestsProject::component();
		$input = strtolower(Input::get('idComponent'));

		$decode = self::replaceSpecials($input);

		return [
			'data' => $data,
			'select' => isset($data[$decode]) ? $input : 'all'
		];
	}

	private static function status()
	{
		$data = MetaRequestsProject::status();
		$input = strtolower(Input::get('status'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}


	private static function resolution()
	{
		$data = MetaRequestsProject::resolution();
		$input = strtolower(Input::get('idConsideration'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function reporter()
	{
		$data = MetaRequestsProject::reporter();
		$input = strtolower(Input::get('idReporter'));

		$decode = self::replaceSpecials($input);

		return [
			'data' => $data,
			'select' => isset($data[$decode]) ? $input : 'all'
		];
	}

	private static function label()
	{
		$data = MetaRequestsProject::label();
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
