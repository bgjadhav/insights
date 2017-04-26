<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\Console;
use Illuminate\Cache\FileStore;

class ClearFileCacheCommand extends Command {

	protected $name			= 'cache:file:clear';
	protected $description	= 'CLI to clean files in cache.';

	public function __construct()
	{
		parent::__construct();
		$this->files = new \Illuminate\Filesystem\Filesystem;
	}

	public function fire()
	{
		$this->info('Clearing file: all');
		foreach ($this->files->files(storage_path().'/files') as $file) {
			$this->files->delete($file);
		}
	}
}
