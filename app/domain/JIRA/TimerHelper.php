<?php

class TimerHelper {

	private $time = null;

	public function __construct()
	{
		$this->time = microtime(TRUE);
	}

	public function __destruct()
	{
		echo 'finished in '.round((microtime(TRUE)-$this->time), 2, PHP_ROUND_HALF_UP).' seconds.'."\n";
	}
}
