<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearSessionCacheCommand extends Command {

	protected $name			= 'cache:session:clear';
	protected $description	= 'CLI to clean session in cache.';

	public function fire()
	{
		/*@Todo doesn't delete file in storage'*/
		if ($this->confirm('Do you wish to continue? [y|n]')) {
			$this->info('Clearing session: all');
			Auth::logout();
			//$this->call('auth:clear-reminders');
			$this->setSession(false);
		}
	}
}
