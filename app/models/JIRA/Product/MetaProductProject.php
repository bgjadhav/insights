<?php
class MetaProductProject
{
	public static function component()
	{
		static $all = ['all' => 'All Product Categories'];

		return $all +

			ProdRoadmap::roadmap()
				->select(DB::raw('lower(first_component)'), 'first_component')
				->validated()
				->where('first_component', '<>', 'No components')
				->groupBy('first_component')
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
			ProdRoadmap::roadmap()
				->select('labels')
				->validated()
				->where('labels', '<>', 'No labels')
				->groupBy('labels')
				->remember(5)
				->lists('labels')
		);
	}

	public static function status()
	{
		static $data = [
			'all' => 'All Phases',
			'discovery' => 'Discovery',
			'design' => 'Design',
			'in development' => 'In Development',
			'partial release (alpha)' => 'Partial Release (Alpha)',
			'partial release (closed beta)' => 'Partial Release (Closed Beta)',
			'released (open beta)' => 'Released (Open Beta)',
			'released (ga)' => 'Released (GA)',
			'on hold' => 'On Hold',
			'cancelled' => 'Cancelled'
		];
		return $data;
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

	public static function year()
	{
		static $all = ['all' => 'All Years'];

		return $all +

			ProdRoadmap::roadmap()
				->select('year')
				->validated()
				->where('year', '<>', '')
				->groupBy('year')
				->orderBy('year', 'DESC')
				->remember(5)
				->lists('year', 'year');
	}

	public static function target()
	{
		static $data = [
			'all' => 'All Quarters',
			'q1' => 'Q1',
			'q2' => 'Q2',
			'q3' => 'Q3',
			'q4' => 'Q4'
		];
		return $data;
	}
}
