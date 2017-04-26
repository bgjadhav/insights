<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class CheckNewReportToUploadInMenuCommand extends Command {

	protected $name = 'update:config_file_reports';

	protected $description = 'If there is a new report schedule upload the config file.';

	public function __construct()
	{
		parent::__construct();
	}

	public function getArguments()
	{
		return [];
	}

	public function fire()
	{
		try {

			if ($this->countNewReports() > 0) {
				$this->uploadConfigFile();

			} else {
				$this->info(date('Y-m-d H:i:s').' - No new reports.');
			}

		} catch (Exception $e) {
			return $this->error('Error in process.');
		}
	}

	private function uploadConfigFile()
	{
		$this->call('generate:config_file_reports');
		$this->info(date('Y-m-d H:i:s').' - Uploaded Config File');
	}

	private function countNewReports()
	{
		$data = $this->data();

		return $data[0]->total;
	}

	private function data()
	{
		return QueryService::run(
			$this->prepare_query(),
			'dashboard',
			false
		);
	}

	private function prepare_query()
	{
		return 'SELECT count(*) as total'
			. ' FROM reports'
			. ' WHERE inlive >= \''. date('Y-m-d H:i:s', strtotime('-1 day')).'\''
			. ' AND inlive <= \''. date('Y-m-d H:i:s').'\''
			;
	}

}
