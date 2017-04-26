<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\Console;
use Illuminate\Cache\FileStore;

class LoadReportCacheCommand extends Command {

	protected $name			= 'cache:report:load';
	protected $description	= 'CLI generator and cleaner of Cache.';
	protected $tilesActive	= [];
	protected $date			= [];
	protected $mgHelp		= ' Use --help to more information.';

	public function __construct()
	{
		parent::__construct();
		$this->tilesActive = ConfigReport::classesDisplay();
		$this->files = new \Illuminate\Filesystem\Filesystem;
	}

	public function fire()
	{
		$this->validateArgumentReport();
		$this->setSession();
		array_walk($this->tilesActive, [&$this, 'loadCacheTile']);
		$this->clearSession();
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

	protected function validateOptions()
	{
		if ($this->option('date') != '') {
			$date = explode(',', $this->option('date'));
			if (count($date) != 2
				|| ($this->validateDate($date[0])
				|| $this->validateDate($date[1])) === false) {
				$this->error('Invalid option for dates.'.$this->mgHelp);
				die;
			}
			if (count($date) == 2) {
				$this->date = $date;
			}
		}

		if (!in_array($this->option('check'), ['disable', 'enable'], true)) {
			$this->error('Invalid option for check.'.$this->mgHelp);
			die;
		}
	}

	protected function validateDate($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') == $date;
	}

	protected function validateArgumentReport()
	{
		if ($this->argument('report') == 'all'
			&& $this->argument('ignore') != '') {
			$this->ignoreTile();
		} elseif ($this->argument('report') != 'all') {
			$this->filterTile();
		}

		if (empty($this->tilesActive)) {
			$this->error('The Reports are invalid or inactive.');
			die;
		}
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

	public static function buildQuery($table, $conn, $date)
	{
		$sql  = 'SELECT MM_DATE, STATUS FROM AD_REPORT_ERROR_LOG';
		$sql .= ' WHERE STATUS = 0 AND TABLE_NAME=\''.$table.'\' AND MM_DATE';
		$sql .= is_array($date)
				? ' BETWEEN \''.$date['date_start'].'\' AND \''.$date['date_end'].'\''
				:' = \''.$date.'\'';
		$sql .= ' AND CONNECTION=\''.$conn.'\'';
		return $sql;
	}

	protected function loadCacheTile($tile)
	{
		try {
			if (class_exists($tile)) {

				App::runningInConsole();
				$controller = new ReportsController;

				$report = new $tile;
				if ($report->getTimeOut() !== false) {
					$controller->datajson(
						'data',
						$tile,
						$this->getFiltersTile($tile),
						true
					);
					$this->info(print_r($tile, true));
				}
			}
		} catch (Exception $e) {
			$this->error('Error with '.$tile.': '.$e->getTraceAsString());
		}
	}

	protected function getFiltersTile($tile)
	{
		$report		= new $tile;
		$filters	= ConfigReport::validateOptionsTile(
			$report->options($report->filters()),
			$this->date,
			'fromCLI '.$this->name
		);

		return $filters;
	}

	protected function setSession()
	{
		Session::set('user_type', 1);
		Session::set('user_role', 1);
		Session::set('first_name', $this->name);
		Session::set('last_name', 'CLI');
		Session::set('user_id', 1616);
		Session::set('user_email', 'koruequiroz@mediamath.com');
	}

	protected function clearSession()
	{
		Session::flush();
	}
}
