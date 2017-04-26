<?php
class RoadmapFullDownload
{
	public static function config()
	{
		try {
			$config = ['full' => true];

			$config = self::appendConn($config);

			$config = self::appendQuarter($config);

			$config = self::appendQueryRoadmap($config);

			$config = self::appendQueryCandidate($config);

			$config = self::appendExtension($config);

			$config = self::appendReportName($config);

			$config = self::appendUser($config);

			$config = self::appendFilters($config);

			$config = self::appendFileNameAndPattern($config);

			return $config;

		} catch(Exception $e) {
			return [
				'error' => true,
				'success' => false,
				'data' => $e->getTraceAsString()
			];

		}
	}

	private static function appendConn($config)
	{
		$config['conn'] = ProdRoadmap::getConnName();

		return $config;
	}

	private static function appendFilters($config)
	{
		$config['applied'] = [

			'roadmap' => 'Applied Filters: '
				.'All Product Categories, '
				.'All Phases, '
				.'All Quarters, '
				.'All Years, '
				.'All Regions, '
				.'All Labels, '
				.'No Hide Released, '
				.'No Search Word',

			'candidates' => 'Applied Filters: '
				.'All Product Categories, '
				.'All Phases, '
				.'All Regions, '
				.'All Labels, '
				.'No Search Word'
		];

		return $config;
	}

	private static function appendQuarter($config)
	{

		$config['quarters'] = ProdRoadmap::roadmap()

			->validated()

			->select(DB::Raw(self::caseTargetAndField().'year'))

			->firstComponent('all')

			->label('all')

			->status('all')

			->geo('all')

			->year('all')

			->targetMM('all')

			->toSql();

		return $config;
	}

	private static function appendQueryRoadmap($config)
	{
		$config['query'] = ProdRoadmap::roadmap()

			->validated()

			->select(DB::Raw(self::selectToRoadmap()))

			->firstComponent('all')

			->label('all')

			->status('all')

			->geo('all')

			->year('all')

			->targetMM('all')

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

	private static function appendQueryCandidate($config)
	{
		$config['queryCandidate'] = ProdRoadmap::candidate()

			->validated()

			->select(
				DB::Raw(self::selectToCandidate())
			)

			->status('all')

			->firstComponent('all')

			->label('all')

			->geo('all')

			->searchRegex('')

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
