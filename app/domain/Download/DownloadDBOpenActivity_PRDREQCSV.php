<?php
use Illuminate\Filesystem\Filesystem;

class DownloadDBOpenActivity_PRDREQCSV extends DBOpenActivity_PRDREQ implements DownloadInterface
{
	public function __construct($pid=false)
	{
		$this->token = date('his');
		$this->pId = 'download';
	}

	public static function output(&$out, $config)
	{
		set_time_limit(0);
		ini_set('max_execution_time', 0);

		$data = self::getResults($config);


		$header = [
			'columns'=> [],
			'totals' => []
		];

		array_walk($config['columns'], function(&$row, $key) use (&$header) {
			$header['columns'][$key]= $row['title'];
			$header['totals'][$key] = '';
		});

		$data['totals'][2] = JiraF::translateToHourMinutesSeconds($data['totals'][2]);
		$data['totals'][3] =  JiraF::translateToHourMinutesSeconds($data['totals'][3]);
		$header['totals'] = array_replace($header['totals'], $data['totals']);

		$data['results'] = json_decode(
			json_encode($data['results']),
			true
		);

		fputcsv($out, $header['columns']);
		fputcsv($out, $header['totals']);



		$header['totals'] = $data['results'];

		foreach ($data['results'] as $row) {
			$clear_row = [];

			array_walk($row, function($value, $key) use (&$clear_row) {

				if (in_array($key, ['AVERAGE_TIME_OPEN_WEEK', 'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK'])) {
					$value = JiraF::translateToHourMinutesSeconds($value);
				}

				$clear_row[] = strip_tags($value);

			});

			fputcsv($out, $clear_row);
		}


		fclose($out);
	}

	protected static function getResults($config)
	{
		$results = QueryService::run(
			$config['query'],
			$config['conn'],
			false
		);

		return self::prepareDataDownload($results, $config);
	}


	protected static function prepareDataDownload($results, $config)
	{
		$indexT = [
			2 => 'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK',
			3 => 'AVERAGE_TIME_OPEN_WEEK',
			4 => 'OPENED',
			5 => 'CLOSED',
			6 => 'PERCENT_CLOSED',
		];

		$items = [
			'open'	=> 0,
			'first' => 0,
			'count' => 0
		];

		$indexTF = [
			2 => 'first',
			3 => 'open',
			6 => 'count',
		];
		$tmp_Totals = array_fill(2, 6, 0);

		$total_week_assignee = [];

		array_walk($results, function(&$val)
			use (&$items, $indexTF, $indexT, $config, &$tmp_Totals, &$total_week_assignee) {

			$original = $val->{'ASSIGNEE'};

			$where = self::extractWhere($config['query']);
			$where = self::replaceAssigneeInWhere($where, $val->{'ASSIGNEE'});

			if (!isset($total_week_assignee[$val->{'ASSIGNEE'}])) {
				$total_week_assignee[$val->{'ASSIGNEE'}] = self::getDataWeekDays('roadmap_prod_req_issues a', $where, $config['conn']);
			};

			$val = self::assignVal($val, $total_week_assignee[$val->{'ASSIGNEE'}]);

			$val->{'ASSIGNEE'} = utf8_decode($val->{'ASSIGNEE'});

			$index = [
				'AVERAGE_TIME_OPEN_WEEK' => 'open',
				'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK' => 'first'
			];

			foreach ($index as $i => $v) {
				if ($val->{$i} > 0) {
					$items[$v]++;
				}
			}
			$items['count']++;

			$tmp_Totals = JiraF::assignTotal($val, $tmp_Totals, $indexT);
		});

		$tmp_Totals = JiraF::assignFinalcTotal($items, $tmp_Totals, $indexTF);

		return [
			'totals' => $tmp_Totals,
			'results' => $results
		];
	}

	protected static function replaceAssigneeInWhere($where, $assignee)
	{

		$base = explode('AND a.assignee IN (', $where);

		$second_part = explode(' AND a.first_component', $base[1]);

		$rebuillWhere = $base[0].' AND a.assignee ='.Format::str([$assignee]).' AND a.first_component'.$second_part[1];

		return [$rebuillWhere];
	}

	protected static function extractWhere($query)
	{
		$where = explode('WHERE', $query);
		$where = explode('GROUP', $where[1]);
		return $where[0];
	}
}
?>
