<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

class ReportConfigFileCommand extends Command {
	protected $name			= 'generate:config_file_reports';
	protected $description	= 'CLI generator of config file by categories for reports.';
	protected $arguments	= false;
	protected $fileSystem;

	public function fire()
	{
		$this->info('Welcome to the reports config file generator.');

		try {
			$this->inializeFileSystem();

			$this->prepare();

			$this->writeMenuReportIntoTmpFile();

			$this->deleteOld();

			$this->renameTmpFile();

			$this->comment('Finished new config reports file.');

		} catch(Exception $e) {
			$this->error($e->getMessage());
		}
	}

	protected function writeMenuReportIntoTmpFile()
	{
		$output = $this->streamTmp();
		fputs($output,
			'<?php'."\n"
			.'/*NOT EDIT THIS FILE, READ README*/'.
			"\n"
			.' return '. $this->content().';'." \n"
			.' ?>'
		);
	}

	protected function content()
	{
		return var_export(
			(new MenuReport)->menu(),
			true
		);
	}

	protected function inializeFileSystem()
	{
		$this->fileSystem = new \Illuminate\Filesystem\Filesystem;
	}

	protected function getFileTmp($tmp = false)
	{
		return app_path() . '/config/reports/'.($tmp ? 'tmp__':'').'report.php';
	}

	protected function streamTmp()
	{
		return fopen($this->getFileTmp(true), 'w');
	}

	protected function prepare()
	{
		if ($this->fileSystem->exists($this->getFileTmp(true))) {
			$this->fileSystem->delete($this->getFileTmp(true));
		}
	}

	protected function deleteOld()
	{
		if ($this->fileSystem->exists($this->getFileTmp(false))) {
			$this->fileSystem->delete($this->getFileTmp(false));
		}
	}

	public function renameTmpFile()
	{
		return rename(
			$this->getFileTmp(true),
			$this->getFileTmp(false)
		);
	}
}
