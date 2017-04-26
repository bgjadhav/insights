<?php
class ParameterCandidate
{
	public static function all()
	{
		return [
			'labels' => self::label(),
			'geoT' => self::geo(),
			'yearT' => self::year(),
			'targetMM' => self::quarter(),
			'statusT' => self::status(),
			'components' => self::component()
		];
	}

	private static function component()
	{
		$data = MetaCandidateProject::component();
		$input = strtolower(Input::get('idComponent'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function status()
	{
		$data = MetaCandidateProject::status();
		$input = strtolower(Input::get('status'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function year()
	{
		$data = MetaCandidateProject::year();
		$input = strtolower(Input::get('year'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	private static function geo()
	{
		$data = MetaCandidateProject::geo();
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
		$data = MetaCandidateProject::label();
		$input = strtolower(Input::get('idLabel'));

		return [
			'data' => $data,
			'select' => isset($data[$input]) ? $input : 'all'
		];
	}

	public static function validate($input)
	{
		$data = self::$input();
		return $data['select'];
	}
}
