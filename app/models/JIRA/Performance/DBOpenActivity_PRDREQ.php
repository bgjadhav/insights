<?php
class DBOpenActivity_PRDREQ extends Tile
{
	public $col = [
		'Asignee'	=> [
			'view'			=> 'Assignee',
			'fieldName'		=> 'a.assignee',
			'fieldAlias'	=> 'ASSIGNEE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'IssueType'		=> [
			'view'			=> 'Product Category',
			'fieldName' 	=> 'a.first_component',
			'fieldAlias' 	=> 'ISSUETYPE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'AvgTimeFirst'		=> [
			'view'			=> 'Avg Time to First Response',
			'fieldName'		=> '0',
			'fieldAlias'	=> 'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'hoursDay',
			'order'			=> false,
			'total'			=> false
		],
		'AvgTimeOpen'	=> [
			'view'			=> 'Avg Time Open',
			'fieldName'		=> '0',
			'fieldAlias'	=> 'AVERAGE_TIME_OPEN_WEEK',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'hoursDay',
			'order'			=> false,
			'total'			=> false
		],
		'Opened'		=> [
			'view'			=> 'Opened',
			'fieldName' 	=> 'CONCAT(COUNT(*), \'-\', SUM(CASE WHEN a.seconds_between_creation_updated <> 0 THEN 0 ELSE 1 END))',
			'fieldAlias'	=> 'OPENED',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'Closed'		=> [
			'view'			=> 'Closed',
			'fieldName'		=> 'SUM(CASE WHEN a.status = \'Closed\' THEN 1 ELSE 0 END)',
			'fieldAlias'	=> 'CLOSED',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'PerClosed'		=> [
			'view'			=> '%Closed',
			'fieldName'		=> '(SUM(CASE WHEN a.status = \'Closed\' THEN 1 ELSE 0 END)/(SUM(CASE WHEN a.status != \'Closed\' THEN 1 ELSE 0 END)+SUM(CASE WHEN a.status = \'Closed\' THEN 1 ELSE 0 END)))*100',
			'fieldAlias'	=> 'PERCENT_CLOSED',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $tmp_Totals = [];
	protected $from = 'roadmap_prod_req_issues a';
	protected $conn = 'jira_prod';
	protected $timeout = 100;

	public function __construct($pid=false)
	{
		$this->tmp_Totals = array_fill(2, 6, 0);
		parent::__construct($pid);
	}

	public function options($filters)
	{
		return [
			'date_picker' => [
				'start' => Format::datePicker(date('j', strtotime('yesterday'))),
				'end' => Format::datePicker()
			],
			'jira_performance' => true,
			'filters' => $filters
		];
	}

	public function filters()
	{
		return [
			'Assignee' => FilterImp::get(new AssigneeRequestPerformance),
			'Product Category' => FilterImp::get(new ProductCategoryRequestsPerformance),
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'force' => 'a.issue_id > 0 ',
			'validate' => 'a.validate = 1',
			'Date' => 'a.created  >= \''.$options['date_start'].' 00:00:00\''
				.' AND a.CREATED <= \''.$options['date_end'].' 23:59:59\'',
		];

		$this->where['Assignee'] = 'a.assignee IN ('. Format::str($options['filters']['Assignee']).') ';
		$this->where['Product_Category'] = 'a.first_component IN ('. Format::str($options['filters']['Product_Category']).') ';

		array_walk($this->col, [&$this, 'dataColumn']);

	}

	public function data()
	{
		$this->limit = '';
		$results = parent::data();

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

		$total_week_assignee = [];

		array_walk($results, function(&$val)
			use (&$items, $indexTF, $indexT, &$total_week_assignee) {

			$original = $val->{'ASSIGNEE'};

			$where = $this->where;
			$where['Assignee'] = 'a.assignee = ('.Format::str([$val->{'ASSIGNEE'}]).') ';

			if (!isset($total_week_assignee[$val->{'ASSIGNEE'}])) {
				$total_week_assignee[$val->{'ASSIGNEE'}] = self::getDataWeekDays($this->from, $where, $this->conn);
			}

			$val = $this->assignVal($val, $total_week_assignee[$val->{'ASSIGNEE'}]);

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

			$this->tmp_Totals = JiraF::assignTotal($val, $this->tmp_Totals, $indexT);
		});

		$this->tmp_Totals = JiraF::assignFinalcTotal($items, $this->tmp_Totals, $indexTF);
		return $results;
	}

	protected function getTotalsView()
	{
		return $this->tmp_Totals;
	}


	protected static function getDataWeekDays($from, $where, $conn)
	{
		return [
			'CLOSED' => JiraF::getWeekDaysTime(
				QueryService::run(
					self::getQueryClosed($from, $where),
					$conn,
					false
				),
				'CLOSED'
			),
			'FIRST' => JiraF::getWeekDaysTime(
				QueryService::run(
					self::getQueryFirstResponse($from, $where),
					$conn,
					false
				),
				'FIRST'
			)
		];
	}

	public static function getQueryClosed($from, $where)
	{
		return  'SELECT a.issue_id, a.created as CREATED,'
				.'a.first_component as ISSUETYPE,'
				.'a.seconds_between_creation_updated as CLOSED_SECONDS'
				. ' FROM '.$from.' '
				.' WHERE '.implode(' AND ', $where)
				.' AND a.status = \'Closed\'';
	}

	public static function getQueryFirstResponse($from, $where)
	{
		return 'SELECT a.issue_id, a.created as CREATED,'
				.'a.first_component as ISSUETYPE,'
				.'a.seconds_between_creation_first_response as FIRST_SECONDS'
				. ' FROM '.$from.' '
				.' WHERE '.implode(' AND ', $where)
				.'AND a.seconds_between_creation_updated > 0';
	}


	protected static function assignVal($val, $data_week=[])
	{
		$counts = explode('-', $val->{'OPENED'});

		$val->{'OPENED'} = $counts[0];

		if (!empty($data_week)) {
			if (!isset($data_week['FIRST'][$val->{'ISSUETYPE'}])) {
				$data_week['FIRST'][$val->{'ISSUETYPE'}] = 0;
			}

			if (!isset($data_week['CLOSED'][$val->{'ISSUETYPE'}])) {
				$data_week['CLOSED'][$val->{'ISSUETYPE'}] = 0;
			}

			if ($counts[0] > 0 && $data_week['FIRST'][$val->{'ISSUETYPE'}] > 0) {

				$val->{'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK'} = $data_week['FIRST'][$val->{'ISSUETYPE'}];
				$val->{'AVERAGE_TIME_OPEN_WEEK'} = $data_week['CLOSED'][$val->{'ISSUETYPE'}];


				$rest = $counts[0] - $counts[1];

				if ($rest > 0) {
					$val->{'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK'} = $val->{'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK'}/($counts[0] - $counts[1]);

				} else {
					$val->{'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK'} = $val->{'AVERAGE_TIME_TO_FIRST_RESPONSE_WEEK'}/$counts[0];
				}

				$val->{'AVERAGE_TIME_OPEN_WEEK'} = $val->{'CLOSED'} > 0 ?
					$val->{'AVERAGE_TIME_OPEN_WEEK'}/$val->{'CLOSED'}
					: 0;
			}
		}

		return $val;
	}


}
