<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\Console;
use Illuminate\Cache\FileStore;

class ClearViewCacheCommand extends Command {

	protected $name			= 'cache:view:clear';
	protected $description	= 'CLI to clean views in cache';

	public function __construct()
	{
		parent::__construct();
		$this->files = new \Illuminate\Filesystem\Filesystem;
	}

	public function fire()
	{
		$this->info('Clearing view: all');
		foreach ($this->files->files(storage_path().'/views') as $file) {
			$this->files->delete($file);
		}
	}
}
