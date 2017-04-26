<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\Console;
use Illuminate\Cache\FileStore;

class KillQueriesSleepingFromMachineCommand extends Command {

	protected $name			= 'kill:my:sleeping:queries';
	protected $description	= 'CLI to kill the queries are sleeping more that 3600 seconds.';
	protected $tilesActive	= [];
	protected $date			= [];
	protected $mgHelp		= ' Use --help to more information.';

	public function fire()
	{

		$config = $this->configMySQLConnections();

		foreach ($config as $conn => $data) {

			$sleeping = $this->sleepingQueriesByDBAndUser($conn, $data);

			$this->killConnections($conn, $sleeping);

		}

	}

	private function sleepingQueriesByDBAndUser($conn, $data)
	{
		$query = $this->buildQuery(
			$data['username'],
			$data['database']
		);

		$results = DB::reconnect($conn)
			->select(DB::raw($query));

		DB::disconnect($conn);

		return $results;
	}


	private function buildQuery($user, $db)
	{
		return 'SELECT *'
			.' FROM information_schema.processlist p'
			.' WHERE COMMAND = \'Sleep\' AND p.TIME > 3600'
			.' AND USER=\''.$user.'\' AND DB=\''.$db.'\'';
	}

	private function configMySQLConnections()
	{
		$config = Config::get('database.connections');

		$clean = [];

		foreach ($config as $conn => $data) {

			if ($data['driver'] == 'mysql') {
				$clean[$conn] = $data;
			}

		}

		return $clean;
	}


	private function killConnections($conn, $sleeping)
	{
		if (!empty($sleeping)) {

			foreach ($sleeping as $data) {
				$this->runKill($conn, $data->ID);
			}
		}
	}

	private function runKill($conn, $pid)
	{
		try {
			$query = 'KILL '. $pid.';';

			DB::reconnect($conn)->statement($query);
			DB::disconnect($conn);

		} catch(Exception $e) {
		}
	}

	protected function getArguments()
	{
		return [];
	}

	protected function configArguments()
	{
		return [];
	}

}
