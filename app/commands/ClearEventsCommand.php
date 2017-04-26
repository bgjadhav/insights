<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class ClearEventsCommand extends Command {

	protected $name = 'cache:events:clear';

	protected $description = 'Flusher all queued events .';

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

		$events = $this->eventsName();

		foreach ($events as $event) {
			Event::flush($event);
		}
	}

	private function eventsName()
	{
		return [
			'search.counter',
			
			'roadmap.counter',
		];
	}

}
