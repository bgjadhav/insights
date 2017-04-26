<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\Console;
use Illuminate\Cache\FileStore;

class CacheCommand extends Command {

	protected $name			= 'command:cacheCommand';
	protected $description	= 'CLI to help to clean and load Cache.';
	protected $tilesActive	= [];
	protected $date			= [];
	protected $mgHelp		= ' Use --help to more information.';

	public function __construct()
	{
		parent::__construct();
		//$this->tilesActive = ConfigReport::getConfig('open-analytics');
		$this->files = new \Illuminate\Filesystem\Filesystem;
	}

	public function fire()
	{
		$this->validateArguments();

		$this->{camel_case($this->argument('action'))}();

		$this->info(
			'Completed '.$this->argument('action').' run in '.$this->name
		);
	}

	protected function getArguments()
	{
		return [
			[
				'action',
				InputArgument::REQUIRED,
				'Process type: '."\n\t"
				. implode("\n\t", $this->configArguments())
			]
		];
	}

	protected function configArguments()
	{
		return [
			'load_report',
			'clear_queues',
			'clear_report',
			'clear_views',
			'clear_files',
			'clear_events',
			//'clear_sessions',
			'clear_all',
			//'reset_reports'
		];
	}

	protected function validateArguments()
	{
		if (!in_array($this->argument('action'), $this->configArguments())) {

			$this->error('Invalid \'action\'. '.$this->mgHelp);
			die;

		}
	}

	protected function loadReport()
	{
		$this->call('cache:report:load');
	}

	protected function clearAll()
	{
		$this->clearQueues();
		$this->clearReport();
		$this->clearViews();
		$this->clearFiles();
		//$this->resetDiagnostic();
		//$this->clearSessions();
	}

	protected function clearQueues()
	{
		$this->call('queue:beanstalkd:clear');
	}

	protected function clearViews()
	{
		$this->call('cache:view:clear');
	}

	protected function clearFiles()
	{
		$this->call('cache:file:clear');
	}

	protected function clearEvents()
	{
		$this->call('cache:events:clear');
	}

	protected function resetDiagnostic()
	{
		$this->call('command:diagnosticCommand');
	}

	protected function clearReport()
	{
		$this->call('cache:report:clear');
	}

	protected function resetReports()
	{
		$this->clearFiles();
		$this->clearReport();
		$this->loadReport();
		$this->resetDiagnostic();
	}

	protected function clearSessions()
	{
		$this->call('cache:session:clear');
	}

}
