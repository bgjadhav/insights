<?php
class DBResolutionAnalytics extends Tile
{
	public $col = [
		'Assignee'	=> [
			'view'			=> 'Assignee',
			'fieldName'		=> 'a.assignee',
			'fieldAlias'	=> 'ASSIGNEE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
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
		'AdHocFix'		=> [
			'view'			=> 'AdHoc',
			'fieldName' 	=> 'SUM(IF(a.candidate_consid = \'Ad Hoc Fix\', 1, 0))',
			'fieldAlias'	=> 'AdHoc',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'Backlog'		=> [
			'view'			=> 'Backlog',
			'fieldName' 	=> 'SUM(IF(a.candidate_consid = \'Backlog\', 1, 0))',
			'fieldAlias'	=> 'Backlog',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'Candidate'		=> [
			'view'			=> 'Candidate',
			'fieldName' 	=> 'SUM(IF(a.candidate_consid = \'Candidate\', 1, 0))',
			'fieldAlias'	=> 'Candidate',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'Existing Feature' => [
			'view'			=> 'Existing Feature',
			'fieldName' 	=> 'SUM(IF(a.candidate_consid = \'Existing Feature\', 1, 0))',
			'fieldAlias'	=> 'ExistingFeature',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'Roadmap'		=> [
			'view'			=> 'Roadmap',
			'fieldName' 	=> 'SUM(IF(a.candidate_consid = \'Roadmap\', 1, 0))',
			'fieldAlias'	=> 'Roadmap',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'NA'		=> [
			'view'			=> 'NA',
			'fieldName' 	=> 'SUM(IF(a.candidate_consid = \'N/A\', 1, 0))',
			'fieldAlias'	=> 'NA',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		]
	];

	protected $from = 'roadmap_prod_req_issues a';
	protected $conn = 'jira_prod';
	protected $timeout = 100;

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
			'Product Category' => FilterImp::get(new ProductCategoryRequestsPerformance)
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'validate' => 'a.validate = 1',
			'force2' => 'a.issue_id > 0 ',
			'types' => 'a.candidate_consid IN (\'Ad Hoc Fix\', \'Backlog\', \'Candidate\', \'Existing Feature\', \'Roadmap\', \'N/A\')',
			'Date' => 'a.created  >= \''.$options['date_start'].' 00:00:00\''
					.' AND a.created <= \''.$options['date_end'].' 23:59:59\''
		];

		$this->where['Asignee'] = 'a.assignee IN ('. Format::str($options['filters']['Assignee']).') ';
		$this->where['PBU'] = 'a.first_component IN ('. Format::str($options['filters']['Product_Category']).') ';

		array_walk($this->col, [&$this, 'dataColumn']);
	}

	public function data()
	{
		$results = parent::data();

		array_walk($results, function(&$val) {
			$val->{'ASSIGNEE'} = utf8_decode($val->{'ASSIGNEE'});
		});
		return $results;
	}
}
