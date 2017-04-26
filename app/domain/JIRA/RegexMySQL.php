<?php
class RegexMySQL implements FilterInterface
{
	public static function filter($val)
	{
		$val = self::lowerCaseAndClean($val);

		$val = self::dobleSlashes($val);

		$val = self::fourSlashes($val);

		$val = self::treeSlashes($val);

		return $val;
	}


	public static function prepare($search, $start=false)
	{
		return ($start ? '^' : '')
			.str_replace(' ', '|'. ($start ? '^' : ''),
			strtolower(trim($search))
		);
	}

	protected static function lowerCaseAndClean($val)
	{
		return strtolower(trim($val));
	}

	protected static function dobleSlashes($val)
	{
		$val = self::stripslashesDeep($val);
		$val = self::stripslashesDeep($val);

		return $val;
	}

	protected static function fourSlashes($val)
	{
		$regex = [
			'.', '$', '+', '|', '[', ']', '(', ')', '?', '/', '^'
		];

		foreach($regex as $exp) {
			$val = str_replace($exp, '\\\\'.$exp, $val);
		}

		return $val;
	}

	protected static function treeSlashes($val)
	{
		$val = str_replace("'", '\\\'', $val);

		$val = str_replace("*", '\\\*', $val);

		return $val;
	}

	protected static function stripslashesDeep($val)
	{
		$val = is_array($val) ?
			array_map('stripslashesDeep', $val) :
			addslashes($val);

		return $val;
	}
}
