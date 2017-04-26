<?php
class MetaCandidateProject
{
	public static function component()
	{
		static $all = ['all' => 'All Product Categories'];

		return $all +

			ProdRoadmap::candidate()
				->select(DB::raw('lower(first_component)'), 'first_component')
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
			ProdRoadmap::candidate()
				->select('labels')
				->validated()
				->where('labels', '<>', 'No labels')
				->groupBy('labels')
				->remember(5)
				->lists('labels')
		);
	}

	public static function geo()
	{
		static $data = [
			'all' => 'All Regions',
			'global' => 'GLOBAL',
			'apac' => 'APAC',
			'emea' => 'EMEA',
			'latam' => 'LATAM',
			'us/can' => 'US/CAN'
		];

		return $data;
	}

	public static function status()
	{
		static $data = [
			'all' => 'All Phases',
			'not scoped' => 'Not Scoped',
			'not prioritized' => 'Not Prioritized',
			'discovery' => 'Discovery',
			'design' => 'Design',
			'on hold' => 'On Hold',
			'cancelled' => 'Cancelled'
		];
		return $data;
	}

	public static function year()
	{
		static $all = ['all' => 'All Years'];
		return $all;
	}
}
