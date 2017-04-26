<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DiagnosticCommand extends Command {
	protected $name = 'command:diagnosticCommand';
	protected $description = 'CLI generator of the process to fill log for Status of the tables.';
	protected $config = [
		'conn'	=> 'update_process',
		'table'	=> 'open_update_table_fail'
	];

	public function fire()
	{
		$this->setSession(true);

		try {

			DiagnosticTmp::truncate();

			$noSum = $this->analiseReportSum();

			foreach ($noSum as $table => $val) {
				$this->analiseReport($table, $val);
			}
		} catch (Exception $e) {
			$this->error('Error in insertLog '.$e->getTraceAsString());
		}

		$this->truncateTable();
		$this->copyLogTmpToTable();
		$this->setSession(false);
	}

	protected function setSession($init=true)
	{
		if ($init==false) {
			Session::flush();
		} else {
			Session::set('user_type', 1);
			Session::set('user_role', 1);
			Session::set('user_roles', ['Admin']);
			Session::set('first_name', 'DiagnosticCommand');
			Session::set('last_name', 'CLI');
			Session::set('user_id', 1616);
			Session::set('user_email', 'koruequiroz@mediamath.com');
		}
	}

	protected function getArguments()
	{
		return [
			[
			'type',
			InputArgument::REQUIRED,
			'An type of tile argument.'
			],
		];
	}

	protected function getOptions()
	{
		return [
			[
			'idTile',
			null,
			InputOption::VALUE_OPTIONAL,
			'An example option.',
			null
			],
		];
	}

	private function getLocations()
	{
		$config = ConfigReport::classesDisplay();
		$data = [];

		array_walk($config, function($report) use (&$data) {

			if (class_exists($report)) {
				$tile = new $report();
				$table = Format::clearAlias($tile->getFrom());

				if (!isset($data[$table])) {
					$result = $this->getLocation($table);
					if (!empty($result)) {
						$data[$table] = $result;
					} else {
						$data[$table]['No found'] = 'No found';
					}
				}
			}
		});
		return $data;
	}

	private function getLocation($table)
	{
		return json_decode(json_encode(
			DailyImport::select('report_location')
			->where('MM_DATE', '!=', '0000-00-00')
			->where('table_name', '=', $table)
			->groupBy('report_location')
			->orderBy('MM_DATE', 'DESC')
			->lists('report_location', 'report_location'))
		, true);
	}

	// from status, sum table
	private function analiseReportSum()
	{
		$config = ConfigReport::classesDisplay();
		$data = [];
		$locations = $this->getLocations();

		array_walk($config, function($report) use (&$data, $locations) {
			if (class_exists($report)) {
				$tile = new $report();
				$table = Format::clearAlias($tile->getFrom());

			//	$this->info(print_r($report, true));

				$init = StatusReport::getInitSum($table);

				if ($init !== null && $tile->getSumTotal()) {
					$date = [
						'date_start'=> $init->{'MM_DATE'},
						'date_end'	=> date('Y-m-d', strtotime('yesterday')),
					];

					if (($notice = StatusReport::getStatus(
						$table,
						$tile->getConnection(),
						$date,
						$report,
						true,
						false
						)) && $notice['status'] == 'error'
						&& trim($notice['message']) != 'noData') {

							$fails = explode(',', trim($notice['message']));
							foreach ($fails as $day) {
								foreach ($locations[$table] as $loc) {
									$this->saveTmp([
										'table' => $table,
										'report' => $report,
										'location' => $loc,
										'conn' 	=> $tile->getConnection(),
										'comment' 	=> 'From Sum table process',
										'status' 	=> 0,
										'date' => $day
									]);
								}
							}
					}
				} else {
					$data[$table][] = $report;
				}
			}
		});
		return $data;
	}

	private function saveTmp($data)
	{
		try {
			$exist = DiagnosticTmp::
				where('MM_DATE', '=', $data['date'])
				->where('TABLE_NAME', '=', $data['table'])
				->where('REPORT_NAME', '=', $data['report'])
				->where('LOCATION', '=', $data['location'])
				->where('CONN', '=', $data['conn'])
				->first();

			if (!$exist) {
				$log = new DiagnosticTmp;
				$log->MM_DATE		= $data['date'];
				$log->TABLE_NAME	= $data['table'];
				$log->REPORT_NAME	= $data['report'];
				$log->LOCATION		= $data['location'];
				$log->CONN			= $data['conn'];
				$log->COMMENT		= $data['comment'];
				$log->STATUS		= (int)$data['status'];
				$log->save();
			}

		} catch (Exception $e) {
			$this->error('Error in saveTmp '.$e->getTraceAsString());
		}
	}

	private function getSumConfig()
	{
		return [
			'sum' => [],
			'noSum' => [],
		];
	}


	// No sum table
	protected function analiseReport($table, $data)
	{
		$exception = ['ExchangeChannelRankeCPA'];
		$locations = $this->getLocations();
		foreach ($data as $report) {
			if (!in_array($report, $exception)) {
				try {
					$tile = new $report();

					if ($tile->getConnection() != 'warroom') {
						if ($haveDate = $this->getHaveDate(
							$table, $tile->getConnection()
							)) {
							$lastest 	= '';
							$init		= true;
							$last		= '';
							$conn		= $tile->getConnection();
							$init		= StatusReport::getInit(
								$table,
								$conn
							);
							$date = [
								'date_start'=> $init->{'MM_DATE'},
								'date_end'	=> date('Y-m-d',
									strtotime('yesterday')
								),
							];
							$options 	= ConfigReport::validateOptionsTile(
								$tile->options($tile->filters()),
								$date
							);
							$options['optionType'] = [];

							$options = ConfigReport::cleanFilters($options);

							$tile->setQuery($options);

							$haveExch	= $this->getHaveExchangeId(
								$table,
								$conn
							);
							$dates		= $this->getDate(
								$table,
								$conn,
								$haveDate,
								$haveExch
							);
							$dates		= $this->getDate(
								$table,
								$conn,
								$haveDate,
								$haveExch
							);
							$avg		= $this->getAvg(
								$table,
								$conn,
								$haveDate,
								$haveExch
							);
							$results	= $this->getResultTable(
								$table,
								$conn,
								$haveDate,
								$haveExch
							);
							$today		= date('Y-m-d');
							foreach ($results as $row) {
								if (!$init) {
									$missing = date('Y-m-d',
										strtotime('+1 day', strtotime($lastest))
									);
									if ($row->{'MM_DATE'} != $missing) {

										$this->insertLog(
											$table,
											$conn,
											$missing,
											0,
											$report,
											$locations[$table]
										);

										$this->validateMissingTmp(
											$table,
											$conn,
											$row->{'MM_DATE'},
											$dates,
											$lastest,
											$report,
											isset($locations[$table])
												? $locations[$table]
												: 'No found'
										);
									}
									$this->validateTolerance(
										$table,
										$conn,
										$haveDate,
										$row->{'MM_DATE'},
										$row->{'row_count'},
										$avg,
										$report,
										isset($locations[$table])
											? $locations[$table]
											: 'No found'
									);
									$lastest = $row->{'MM_DATE'};
									$last = $lastest;
								} else {
									$lastest = $row->{'MM_DATE'};
									$last = $lastest;
									$this->validateTolerance(
										$table,
										$conn,
										$haveDate,
										$lastest,
										$row->{'row_count'},
										$avg,
										$report,
										isset($locations[$table])
											? $locations[$table]
											: 'No found'
									);
									$init = false;
								}
							}
							unset($tile);
						} else {
							foreach ($locations[$table] as $loc) {
								$this->analiseIDaily([
									'date'		=> date('Y-m-d'),
									'table'		=> $table,
									'report'	=> $report,
									'location'	=> $loc,
									'conn'		=> $tile->getConnection()
									],
									' No date field.'
								);
							}
						}
					}
				} catch (Exception $e) {
					$this->error('Error with '.$report.': '
						.$e->getTraceAsString());
				}
			}
		}
	}

	public static function getHaveDate($table, $conn, $force = false)
	{
		$date = false;
		try {
			$structure = DB::reconnect($conn)->select('DESC '. $table);
			DB::disconnect($conn);
			foreach ($structure as $field) {
				if (in_array($field->{'Field'}, ['MM_DATE', 'mm_date'])) {
					if ($field->{'Type'} == 'date') {
						$date = true;
					}
					break;
				}
			}
		} catch (Exception $e) {
			 print_r('Error in getHaveDate '.$e->getTraceAsString());
		}
		return $date;
	}

	protected function analiseIDaily($data, $extra = '')
	{
		$today = date('Y-m-d');
		if (!empty($this->getDailyStatus($data['table'], $data['date']))) {
			$this->saveTmp(array_merge(
				$data,
				[
					'comment' => 'Found process in Daily.'.$extra,
					'status'  => 1
				])
			);
		} else {
			$this->saveTmp(array_merge(
				$data,
				[
					'comment' => 'No Found process in Daily.'.$extra,
					'status'  => 0
				])
			);
		}
	}

	private function getDailyStatus($table, $date)
	{
		return json_decode(json_encode(
			DailyImport::select('status')
			->where('MM_DATE', '=', $date)
			->where('table_name', '=', $table)
			->groupBy('status')
			->get()), true);
	}

	protected function getHaveExchangeId($table, $conn)
	{
		$exch = false;
		try {
			$structure = DB::reconnect($conn)->select('DESC '. $table);
			DB::disconnect($conn);
			foreach ($structure as $field) {
				if ($field->{'Field'} == 'EXCHANGE_ID') {
					$exch = true;
					break;
				}
			}
		} catch (Exception $e) {
			 print_r('Error in getHaveExchangeId '.$e->getTraceAsString());
		}
		return $exch;
	}

	protected function getDate($table, $conn, $haveDate, $haveExch)
	{
		$today = date('Y-m-d');
		if ($haveDate) {
			$sql = 'SELECT MM_DATE';
			$sql .= ' FROM '.$table;
			$sql .= ' WHERE MM_DATE != \'0000-00-00\'';
			$sql .= $haveExch ? ' AND EXCHANGE_ID != 9990' : '';
			$sql .= ' GROUP BY MM_DATE';

			$results	= DB::reconnect($conn)->select($sql);
			DB::disconnect($conn);
			$results	= json_decode(json_encode($results), true);
			$results	= array_column($results, 'MM_DATE');
		} else {
			$results	= [$today];
		}
		return $results;
	}

	protected function getAvg($table, $conn, $haveDate, $haveExch)
	{
		$avg = [];
		if ($haveDate) {
			$sql = 'SELECT count(*)/count(distinct(MM_DATE)) as avg';
			$sql .= ' FROM '.$table;
			$sql .= ' WHERE MM_DATE != \'0000-00-00\'';
			$sql .= ' AND MM_DATE >= CURRENT_DATE - interval 30 day';
			$sql .= $haveExch ? ' AND EXCHANGE_ID != 9990' : '';
			$results = DB::reconnect($conn)->select($sql);
			DB::disconnect($conn);
			$avg = $results[0]->{'avg'};
		} else {
			$avg = 1;
		}
		return $avg;
	}

	protected function getResultTable($table, $conn, $haveDate, $haveExch)
	{
		try {
			$today = date('Y-m-d');
			$extra = ['where'=>[], 'end' => ''];
			$sql  = 'SELECT ';
			$sql .= !$haveDate ? $today.' as ' : '';
			$sql .= 'MM_DATE, count(*) as row_count';
			$sql .= ' FROM '.$table;

			if ($haveDate) {
				$extra['where'][] = 'MM_DATE != \'0000-00-00\'';
				$extra['end'] = ' GROUP BY MM_DATE ORDER BY MM_DATE asc';
			}

			if ($haveExch) {
				$extra['where'][] = 'EXCHANGE_ID != 9990';
			}

			$sql .= !empty($extra['where'])
				? ' WHERE '.implode(' AND ', $extra['where']) : '';
			$sql .= $extra['end'];

			$results = DB::reconnect($conn)->select($sql);
			DB::disconnect($conn);
			return $results;
		} catch (Exception $e) {
			$this->error('Error in getResultTable '.$e->getTraceAsString());
			die;
		}
	}

	protected function insertLog($table, $conn, $missing, $type, $report, $locations)
	{
		foreach ($locations as $loc) {
			$this->saveTmp([
				'table' => $table,
				'conn' => $conn,
				'comment' => 'From old style',
				'date' => $missing,
				'location' => $loc,
				'report' => $report,
				'status' => $type,
			]);
		}
	}

	protected function validateTolerance($table, $conn, $haveDate, $day, $count, $avg, $report, $locations, $type = 1, $tolerance = 60)
	{
		$pct_diff = $avg > 0 ? abs(100-(($count/$avg)*100)) : 1;
		if ((!$haveDate && $count == 0)
			|| ($haveDate && $tolerance <= $pct_diff)) {
			$this->insertLog($table, $conn, $day, $type, $report, $locations);
		}
	}

	protected function validateMissingTmp($table, $conn, $day, $dates, $lastest, $report, $locations)
	{
		$flag	= true;
		$count	= 2;
		$today = date('Y-m-d');

		while ($flag && $day != $today) {
			$missing = date('Y-m-d', strtotime('+'.$count.' day', strtotime($lastest)));
			if (in_array($missing, $dates)) {
				$flag = false;
			} else {
				$this->insertLog($table, $conn, $missing, 0, $report, $locations);
				$count++;
			}
		}
	}

	protected function copyLogTmpToTable()
	{
		try {
			$sql = 'INSERT IGNORE INTO '.$this->config['table']
				.' SELECT * FROM open_update_table_fail_tmp';
			DB::reconnect($this->config['conn'])
				->statement($sql);
			DB::disconnect($this->config['conn']);
		} catch (Exception $e) {
			$this->error('Error in copyLogTmpToTable '.$e->getTraceAsString());
		}
	}

	protected function truncateTable()
	{
		try {
			DB::reconnect($this->config['conn'])
				->statement('TRUNCATE '.$this->config['table']);
			DB::disconnect($this->config['conn']);
		} catch (Exception $e) {
			$this->error('Error in truncateLogTable '.$e->getTraceAsString());
		}
	}

}
