<?php
class MetaRequestsProject
{
	public static function component()
	{
		static $all = ['all' => 'All Product Categories'];

		return $all +

			ProdRequest::select(DB::raw('lower(first_component)'), 'first_component')
				->validated()
				->where('first_component', '<>', 'No components')
				->orderBy(DB::raw('lower(first_component)'), 'ASC')
				->groupBy('first_component')
				->remember(5)
				->lists('first_component', DB::raw('lower(first_component)')
			);
	}

	public static function label()
	{
		static $all = ['all' => 'All Labels'];

		return $all +

			CleanMetaConcated::filter(
			ProdRequest::select('labels')
				->validated()
				->where('labels', '<>', 'No labels')
				->groupBy('labels')
				->remember(5)
				->lists('labels')
		);
	}


	public static function status()
	{
		static $all = ['all' => 'All Phases'];

		return $all +

			ProdRequest::select(DB::raw('lower(status)'), 'status')
				->validated()
				->orderBy(DB::raw('lower(status)'), 'ASC')
				->groupBy('status')
				->remember(5)
				->lists('status', DB::raw('lower(status)')
			);
	}

	public static function resolution()
	{
		static $all = ['all' => 'All Resolutions'];

		return $all +

			ProdRequest::select(
					DB::raw(self::noneLowerCaseResolution(). ' AS l_index'),
					DB::raw(self::noneUpperCaseResolution().' AS r_index')
				)
				->validated()
				->orderBy(DB::raw('lower(candidate_consid)'), 'ASC')
				->groupBy('candidate_consid')
				->remember(5)
				->lists('r_index', 'l_index')
			;
	}

	protected static function noneUpperCaseResolution()
	{
		return 'CASE WHEN candidate_consid = \'\' THEN \'None\' ELSE candidate_consid END';
	}

	protected static function noneLowerCaseResolution()
	{
		return 'CASE WHEN candidate_consid = \'\' THEN \'none\' ELSE lower(candidate_consid) END';
	}

	public static function reporter()
	{
		static $all = ['all' => 'All Reporter'];

		return $all +

			ProdRequest::select(DB::raw('lower(reporter)'), 'reporter')
				->validated()
				->orderBy(DB::raw('lower(reporter)'), 'ASC')
				->groupBy('reporter')
				->remember(5)
				->lists('reporter', DB::raw('lower(reporter)')
			);
	}
}
