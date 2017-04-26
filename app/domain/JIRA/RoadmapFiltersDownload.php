<?php
class RoadmapFiltersDownload
{
	public static function config()
	{
		try {

			$config = ['full' => false];

			$filters = self::filters();

			$config = self::appendConn($config);

			$config = self::appeddSearch($config, $filters);

			$config = self::appendQuarter($config, $filters);

			$config = self::appendQueryRoadmap($config, $filters);

			$config = self::appendQueryCandidate($config, $filters);

			$config = self::appendExtension($config);

			$config = self::appendReportName($config);

			$config = self::appendUser($config);

			$config = self::appendFilters($config, $filters);

			$config = self::appendFileNameAndPattern($config);

			return $config;

		} catch (Exception $e) {

			return [
				'error' => true,
				'success' => false,
				'data' => 'Error config'
			];

		}
	}

	private static function filters()
	{
		return [
			'component' => Input::get('idComponent'),

			'label' => Input::get('idLabel'),

			'status' => Input::get('status'),

			'geo' => ParameterRoadmap::validate('geo'),

			'year' => ParameterRoadmap::validate('year'),

			'quarter' => ParameterRoadmap::validate('quarter'),

			'hide_released' => ParameterExtrasRoadmap::hideReleased(),

			'search' => ParameterExtrasRoadmap::search()
		];

	}

	private static function appendConn($config)
	{
		$config['conn'] = ProdRoadmap::getConnName();

		return $config;
	}

	private static function appeddSearch($config, $filters)
	{
		$config['search'] = SearchJIRA::generateRegex(
			[
				'epic_name',
				'labels'
			],
			'RoadmapFiltersDownload',
			$filters['search']
		);

		return $config;
	}

	private static function appendQuarter($config, $filters)
	{
		$config['quarters'] = ProdRoadmap::roadmap()

			->validated()

			->select(DB::Raw(self::caseTargetAndField().'year'))

			->firstComponent($filters['component'])

			->label($filters['label'])

			->status($filters['status'])

			->geo($filters['geo'])

			->year($filters['year'])

			->targetMM($filters['quarter'])

			->released($filters['hide_released'])

			->searchRegex($config['search'])

			->toSql();

		return $config;
	}


	private static function appendFilters($config, $filters)
	{
		$component = $filters['component'] == 'all' ? 'All Product Categories' : 'Product Category '.ucwords($filters['component']);
		$phases = $filters['status'] == 'all' ? 'All Phases' : 'Phase '.ucwords($filters['status']);
		$quarters = $filters['quarter'] == 'all' ? 'All Quarters' : 'Quarter '.ucwords($filters['quarter']);
		$year = $filters['year'] == 'all' ? 'All Years' : 'Year '.$filters['year'];
		$region = $filters['geo'] == 'all' ? 'All Regions' : 'Region '.strtoupper($filters['geo']);
		$labels = $filters['label'] == 'all' ? 'All Labels' : 'Label '.ucwords($filters['label']);
		$released = $filters['hide_released'] == 'false' ? 'No Hide Released' : 'Hide Released';
		$search = $filters['search'] == '' ? 'No Search Word' : 'Search Word '.$filters['search'];

		$config['applied'] = [

			'roadmap' => 'Applied Filters: '
				.$component.', '
				.$phases.', '
				.$quarters.', '
				.$year.', '
				.$region.', '
				.$labels.', '
				.$released.', '
				.$search,

			'candidates' => 'Applied Filters: '
				.$component.', '
				.$phases.', '
				.$region.', '
				.$labels.', '
				.$search
		];

		return $config;
	}

	private static function appendQueryRoadmap($config, $filters)
	{
		$config['query'] = ProdRoadmap::roadmap()

			->validated()

			->select(DB::Raw(self::selectToRoadmap()))

			->firstComponent($filters['component'])

			->label($filters['label'])

			->status($filters['status'])

			->geo($filters['geo'])

			->year($filters['year'])

			->targetMM($filters['quarter'])

			->released($filters['hide_released'])

			->searchRegex($config['search'])

			->orderByRaw(
				'`first_component` ASC, `major` DESC,'

				.' target_open_beta_year_o ASC,'

				.' target_open_beta_o ASC,'

				.' target_release_year_o ASC,'

				.' target_release_o ASC,'

				.' target_closed_beta_o ASC,'

				.' target_closed_beta_o ASC,'

				.' released ASC,'

				.' epic_name ASC'
			)

			->toSql();

		return $config;
	}

	private static function appendQueryCandidate($config, $filters)
	{
		$config['queryCandidate'] = ProdRoadmap::candidate()

			->validated()

			->select(
				DB::Raw(self::selectToCandidate())
			)

			->firstComponent($filters['component'])

			->label($filters['label'])

			->status($filters['status'])

			->geo($filters['geo'])

			->searchRegex($config['search'])

			->orderByRaw(
				'`first_component` ASC, '

				.'`major` DESC, '

				.'released ASC, '

				.'`summary` ASC'
			)

			->toSql();

		return $config;

	}

	private static function appendExtension($config)
	{
		$config['extension'] = 'xls';

		return $config;
	}

	private static function appendReportName($config)
	{
		$config['report'] = 'RoadmapAndCandidate';

		return $config;
	}

	private static function appendUser($config)
	{
		$config['user'] = User::basicInfo();

		return $config;
	}

	private static function appendFileNameAndPattern($config)
	{
		$id_download = Format::nameCache(
			$config['query'].$config['queryCandidate']
		);

		$config['pattern'] = $id_download;
		$config['file'] = $id_download;

		return $config;
	}

	private static function selectToRoadmap()
	{
		return 'first_component AS `category`,'

			. 'major,'

			. self::caseTargetAndField()

			. self::statusReleased()

			. 'epic_name as `summary`,'

			. 'issue_id,'

			. '`key` as browse';
	}

	private static function caseTargetAndField()
	{
		return self::fieldAndYear('target_open_beta', 'target_open_beta_year')

			. self::fieldAndYear('target_release', 'target_release_year')

			. self::fieldAndYear('target_closed_beta', 'target_closed_beta_year');
	}

	private static function fieldAndYear($field, $field_year)
	{
		$current_year = '\''.date('Y').'\'';

		$empty = '\'\'';

		$null = '\'ZZ\'';


		return ' CASE WHEN '.$field.' = '.$empty.' then '.$null

			.' WHEN '.$field_year.' = '.$empty.' then '.$current_year

			.' WHEN '.$field_year.' != '.$empty

				.' AND '.$field_year.' < '.$current_year.' then '.$null

			.' ELSE '.$field_year.' END '.$field_year.'_o,'


			.' CASE WHEN '.$field.' = '.$empty.' then '.$null

				.' ELSE SUBSTRING('.$field.', 1, 2) END '.$field.'_o,';
	}

	private static function statusReleased()
	{
		return 'CASE WHEN status IN (\'Released (Open Beta)\', \'Released (GA)\') THEN 1 ELSE 0 END released,';
	}

	private static function selectToCandidate()
	{
		return 'first_component AS `category`,'
			.' CASE WHEN status IN (\'Released (Open Beta)\', \'Released (GA)\') THEN 1 ELSE 0 END released,'
			.' epic_name as `summary`, issue_id, `key` as browse';
	}

}
