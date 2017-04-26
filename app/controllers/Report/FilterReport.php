<?php
class FilterReport
{
	public static function get($filters = null)
	{
		return $filters == null ? self::inputs() :$filters;
	}

	private static function inputs()
	{
		return [
			'range_selector'	=> Input::get('range_selector'),
			'date_start'		=> Input::get('date_start'),
			'date_end'			=> Input::get('date_end'),
			'page'				=> Input::get('page'),
			'pid'				=> Input::get('pid'),
			'optionType'		=> Input::get('optionType'),
			'sumTotal'			=> Input::get('sumTotal'),
			'filters'			=> self::asParameters('filters'),
			'search'			=> Input::get('search'),
			'type'				=> Input::get('type')
		];
	}

	private static function asParameters($id)
	{
		$params = [];
		parse_str(Input::get($id), $params);
		return $params;
	}

	public static function options($base, $options, $filters)
	{
		$opt = array_replace($base, $options);
		$opt['page'] = $filters['page'];
		return $opt;
	}
}
?>
