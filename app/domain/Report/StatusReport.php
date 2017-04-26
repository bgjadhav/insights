<?php
class StatusReport
{
	public static function getNotice($table, $conn, $opt, $tile, $flag = true, $format = true)
	{
		if ($conn == 'warroom') return self::getOptions();

		$data = [
			'output' => self::getOptions()
		];

		if (!($config = self::isAggregate($tile))) {

			//Check init
			if (self::isException($tile) === false) {
				$data = self::getStatusInit(
					$table,
					self::checkOpt(
						$tile,
						ConfigReport::getPicker($opt)
					),
					true,
					true,
					$conn
				);

				// Check data in diagnostic table
				if (!empty($result = self::getErrorData(
					$table,
					$data['date']
				))) {
					$data['output'] =  array_replace_recursive(
						$data['output'],
						self::getWarning(
							array_values($result),
							$format
						),
						['status'  => 'error']
					);
				}
			}

		} else {
			$output	= array_replace_recursive(
				self::getOptions(),
				self::aggregate($table, $conn, $config)
			);
		}

		$status = self::isWarning($tile)
			? 'warning' : $data['output']['status'];

		return [
			'status' => $status,
			'title' => self::getTitle(
						$data['output']['status']
					),
			'message' => self::formatMessage(
						$status,
						$data['output']['message']
			)
		];
	}

	public static function formatMessage($status, $message)
	{
		$output = implode(' ', $message);
		if ($status == 'warning') {
			$output =  str_replace(
				'We are experiencing data import failures on ',
				'Despite there being no data, '
					.'the report is accurate for the time range ',
				$output
			);
		}
		return $output;
	}

	public static function getErrorData($table, $date)
	{
		$sums = new OADiagnostic();
		$sums->setQuery([
			'date_start'=> $date['date_start'],
			'date_end'	=> $date['date_end'],
			'filters'	=> [
				'Table'		=> [$table],
				'Columns'	=> ['Date']
			]
		]);
		$results = json_decode(json_encode($sums->data()), true);
		return (array)array_column($results, 'DATE');
	}

	public static function getStatus($table, $conn, $opt, $tile, $flag = true, $format = true)
	{
		if ($flag === false || $conn == 'warroom')
			return self::getOptions();

		if (!($config = self::isAggregate($tile))) {
			$output = array_replace_recursive(
				self::getOptions(),
				self::getStatusTable(
					$table,
					self::checkOpt(
						$tile,
						ConfigReport::getPicker($opt)
					),
					$format
				)
			);
		} else {
			$output	= array_replace_recursive(
				self::getOptions(),
				self::aggregate($table, $conn, $config)
			);
		}
		$output['message'] = implode(' ', $output['message']);
		$output['title']   = self::getTitle($output['status']);

		return $output;
	}

	public static function getStatusInit($table, $date, $format, $force = false, $conn = null)
	{
		$init = self::getInitSum($table);
		if ($init === null && $force) {
			$init = self::getInit($table, $conn);
		}

		$output = array_replace_recursive (
			self::getOptions(),
			self::checkInit($table, $date, $init, $format)
		);

		$date = self::getDate($date, $init);

		return [
			'date'	=> $date,
			'output'=> $output
		];
	}

	public static function getStatusTable($table, $date, $format)
	{
		$data = self::getStatusInit($table, $date, $format);

		if ($data['output']['status'] != 'error' &&
			($analysis = self::getAnalysis($table, $data['date']))) {
				return array_replace_recursive(
					$data['output'],
					self::validateDiff(
						$table,
						$analysis,
						$data['date'],
						$format
					)
				);
		}
		return $data['output'];
	}

	public static function getDate($date, $init)
	{
		if ($init !== null ) {
			if ( $date['date_start'] < $init->{'MM_DATE'}) {
				$date['date_start'] = $init->{'MM_DATE'};
			}
		}
		return $date;
	}

	public static function getAnalysis($table, $date)
	{
		if (!empty($results = json_decode(
				self::getData($table, $date),
				true))) {
			return (array)array_column($results, 'DATE');
		}
		return false;
	}

	public static function getTitle($status)
	{
		$title = [
			'info' => 'Hey!',
			'warning'=> 'Warning!',
			'error'=> 'Alert:'
		];
		return $title[$status];
	}

	public static function getData($table, $date)
	{
		$sums = new OASumTables();
		$sums->setQuery([
			'date_start'=> $date['date_start'],
			'date_end'	=> $date['date_end'],
			'filters'	=> [
				'Table'		=> [$table],
				'Columns'	=> ['Date']
			]
		]);
		return json_encode($sums->data());
	}

	public static function getOptions()
	{
		return [
			'status'	=> 'info',
			'message'	=> ['']
		];
	}

	public static function getInitSum($table)
	{
		return DB::reconnect('update_process')
			->table('open_update_sum_tables')
			->select('MM_DATE')
			->where('MM_DATE', '!=', '0000-00-00')
			->where('DB_TABLE', '=', $table)
			->orderBy('MM_DATE', 'ASC')
			->first();
	}

	public static function getInit($table, $conn)
	{
		if (DiagnosticCommand::getHaveDate($table, $conn)) {
		return DB::reconnect($conn)
			->table($table)
			->select('MM_DATE')
			->where('MM_DATE', '!=', '0000-00-00')
			->orderBy('MM_DATE', 'ASC')
			->first();
		} else {
			return null;
		}
	}

	public static function checkInit($table, $date, $init, $format)
	{
		if ($init === null
			|| ($end = self::controlDate(
				$date['date_end'],
				$init->{'MM_DATE'}
				))
			|| ($start = self::controlDate(
				$date['date_start'],
				$init->{'MM_DATE'}
				))
			) {
				return [
					'message' => [
						'init' => $format ? ('This Report has no data'
							. ($init !== null
								? ' before '.Format::getDays(
									[$init->{'MM_DATE'}]
								)
								:'')
							. '.') : 'noData'
					],
					'status' => $init === null || $end
						? 'error'
						: 'info'
				];
		}
		return [];
	}

	public static function controlDate($date, $init)
	{
		return $date < $init;
	}

	public static function validateDiff($table, $dates, $filter, $format)
	{
		$init = $filter['date_start'];
		$result = [];

		do {
			$result[] = $init;
			$init = date('Y-m-d', strtotime('+1 days', strtotime($init)));
		} while ($init <= $filter['date_end']);

		if (!empty($result = array_diff($result, $dates))
			&& !empty($result = self::isReadyToday(
						$table,
						$result
				))) {
				return array_replace_recursive(
					self::getWarning(
						array_values($result),
						$format
					),
					['status'  => 'error']
				);
		}
		return [];
	}

	public static function getWarning($dates, $format)
	{
		return [
			'message'	=> [
				'warning' => $format ? self::getMessageFail($dates)
					: Format::id($dates)
			],
			'status'	=> 'warning'
		];
	}

	public static function isReadyToday($table, $dates)
	{
		if (in_array(($yesterday = date('Y-m-d', strtotime('yesterday'))), $dates)
			) {
				if (empty(self::getTodayStatus(
					$table, date('Y-m-d')
					))) {
					unset($dates[array_search($yesterday, $dates)]);
				}
		}
		return $dates;
	}

	public static function getTodayStatus($table, $date)
	{
		$sums = new OADailyImport();
		$sums->setQuery([
			'date_start'=> $date,
			'date_end'	=> $date,
			'filters'	=> [
				'Table'		=> [$table],
				'Status'	=> Filter::update(),
				'Columns'	=> ['status', 'table']
			]
		]);
		return $sums->data();
	}

	public static function getMessageFail($date)
	{
		return 'We are experiencing data import failures on '
			.Format::getDays($date).'.';
	}

	public static function checkOpt($tile, $opt)
	{
		$date = [];
		$yesterday = date('Y-m-d',strtotime('-1 days'));

		if (self::isException($tile)) {
			$date['date_start']	= date(
				'Y-m-d',
				strtotime('-6 days', strtotime($yesterday))
			);
			$date['date_end']	= $yesterday;
		} elseif (self::checkDate($opt['date_start'])) {
			$date['date_start']	= $opt['date_start'];
			$date['date_end']	= $opt['date_end'];
		} else {
			$date['date_start']	= $yesterday;
			$date['date_end']	= $yesterday;
		}
		return $date;
	}

	public static function isException($tile)
	{
		return in_array($tile, Config::get('reports/info.exception'));
	}

	public static function isWarning($tile)
	{
		return in_array($tile, Config::get('reports/info.warning'));
	}

	public static function checkDate($date)
	{
		return trim($date) != '' && $date !== false;
	}

	public static function aggregate($table, $conn, $config)
	{
		$aggregate = self::minimumDate(
			$table,
			$conn,
			$config['aggregate']
		);
		return [
			'message' => [
				'This Report has the last aggegate date from '
					.$aggregate->{$config['aggregate']}.'.'
			]
		];
	}

	public static function minimumDate($table, $conn, $field='MM_DATE')
	{
		return DB::reconnect($conn)
			->table($table)
			->select($field)
			->where($field, '!=', '0000-00-00')
			->orderBy($field, 'ASC')
			->first();
	}

	public static function isAggregate($tile)
	{
		$config = Config::get('reports/info.aggregate');
		return isset($config[$tile]) ? $config[$tile] : false;
	}

	public static function get($name)
	{
		$data = DB::reconnect('dashboard')->
			table('report-status')->
			select('active')->
			where('report', $name)->
			where('active', 0)->
			first();
		if (count($data) > 0) {
			return 0;
		} else {
			return 1;
		}
	}

}
