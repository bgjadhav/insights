<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\Console;
use Illuminate\Cache\FileStore;

class ClearReportCacheCommand extends Command {

	protected $name			= 'cache:report:clear';
	protected $description	= 'CLI to clean reports in cache.';
	protected $tilesActive	= [];
	protected $date			= [];
	protected $mgHelp		= ' Use --help to more information.';

	public function __construct()
	{
		parent::__construct();
		$this->tilesActive = ConfigReport::classesDisplay();
	}

	public function fire()
	{
		if ($this->argument('report') != 'all') {

			$this->info('pendient to implemntation');
			//array_walk($this->tilesActive, [&$this, 'clearCacheTile']);
		} else {
			$this->clearReport();
		}
	}

	protected function clearReport()
	{
		$this->info('Clearing report: all');
		Cache::flush();
	}

	protected function ignoreTile()
	{
		$this->tilesActive = array_diff(
			$this->tilesActive,
			explode(',', $this->argument('ignore'))
		);
	}

	protected function filterTile()
	{
		$this->tilesActive = array_intersect(
			$this->tilesActive,
			explode(',', $this->argument('report'))
		);
	}

	/*@todo fix it, doesn't clear the report'*/
	protected function clearCacheTile($tile)
	{
		try {

			if (class_exists($report)) {

				$this->info('Clearing report: '.$tile);
				$controller = new ReportsController;
				$filter = array_replace(
					$controller->options,
					$this->getFiltersTile($tile)
				);
				$report = new $tile;

				$options = ConfigReport::cleanFilters($options);

				$report->setQuery($filter);

				$sql = [
					'data'		=> $report->buildQuery(),
					'total'		=> $report->buildQuery(
						false,
						$report->columnsView['totals']
					),

					/*
					 * @Todo
					 * calll with false to Dasboard_Controller
					 * */
					//~ 'status'	=> Status::buildQuery(
						//~ Format::clearAlias($report->getFrom()),
						//~ $report->getConnection(),
						//~ date('Y-m-d',strtotime('yesterday'))
					//~ )
				];

				/*for today?*/
				if ($filter['type'] == 'table' && $filter['pagination']) {
					$sql['data'] .= ' LIMIT '.'0,100';
				}

				foreach ($sql as $query) {
					$cacheKey = md5($query);
					if (Cache::has($cacheKey)) {
						Cache::forget($cacheKey);
					}
				}
			}
		} catch (Exception $e) {
			$this->error('Error with '.$tile.': '.$e->getTraceAsString());
		}
	}

	protected function getArguments()
	{
		return [
			[
				'report',
				InputArgument::OPTIONAL,
				'Report Name: all or ClassName(,s)/Model.'
					."\n"
					.'1- If is one report you must write the class Name.'
					."\n"
					.'    e.x. ClassNameOne'."\n"
					.'2- For two or more you must write Class names separate'
						.' them with commas(,).'
					."\n"
					.'   e.x. ClassNameOne,ClassNameTwo'
					."\n",
				'all'
			],
			[
				'ignore',
				InputArgument::OPTIONAL,
				'With load or clear report and option \'all\' you could '
					.'ignore one or some reports.',
				''
			]
		];
	}

	protected function getOptions()
	{
		return [
			[
				'date',
				'-d',
				InputOption::VALUE_OPTIONAL,
				'In load or clean report you could indicate the start and'
					.' end date separate them with comma(,).'
				."\n"
				.'\'startDate,endDate\' (YYYY-MM-DD))',
				''
			],
			[
				'check',
				'-c',
				InputOption::VALUE_OPTIONAL,
				'In the load_report check if the process import has been'
					.' finished before to create the new cache and forcing '
					.'clear the old cache.'
				."\n"
				.'enable or disable',
				'disable'
			]
		];
	}
}
