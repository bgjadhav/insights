<?php
class RoadmapUsageDetail extends Tile
{
	public $col = [
		'User' => [
			'view' => 'User',
			'fieldName' => 'full_name',
			'fieldAlias' => 'full_name',
			'group' => false,
			'gDependence' => 'user_id',
			'join' => false,
			'format' => false,
			'order' => 'ASC',
			'total' => false
		],
		'Roadmap' => [
			'view' => 'Roadmap',
			'fieldName' => 'SUM(roadmap)',
			'fieldAlias' => 'roadmap',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Candidate' => [
			'view' => 'Candidate',
			'fieldName' => 'SUM(candidate)',
			'fieldAlias' => 'candidate',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Requests' => [
			'view' => 'Requests',
			'fieldName' => 'SUM(requests)',
			'fieldAlias' => 'requests',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Export Full' => [
			'view' => 'Export Full',
			'fieldName' => 'SUM(export_current)',
			'fieldAlias' => 'export_current',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Export Filter' => [
			'view' => 'Export Filter',
			'fieldName' => 'SUM(export_filtered)',
			'fieldAlias' => 'export_filtered',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Share Project' => [
			'view' => 'Share Project',
			'fieldName' => 'SUM(share_project)',
			'fieldAlias' => 'share_project',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Share Filter' => [
			'view' => 'Share Filter',
			'fieldName' => 'SUM(share_filtered)',
			'fieldAlias' => 'share_filtered',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Make Requests' => [
			'view' => 'Make Requests',
			'fieldName' => 'SUM(make_request)',
			'fieldAlias' => 'make_request',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Help' => [
			'view' => 'Help',
			'fieldName' => 'SUM(help)',
			'fieldAlias' => 'help',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Comment' => [
			'view' => 'Comment',
			'fieldName' => 'SUM(add_comment)',
			'fieldAlias' => 'add_comment',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Total' => [
			'view' => 'Total',
			'fieldName' => 'SUM(roadmap + candidate + requests + export_current + export_filtered + share_project + share_filtered + make_request + help + add_comment)',
			'fieldAlias' => 'total',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		]
	];

	protected $from = 'usage_stats';

	protected $conn = 'jira_prod';

	protected $timeout = 10;

	protected $pagination = false;

	public function options($filters)
	{
		return [
			'date_picker' => [
				'start' => Format::datePicker(date('j', strtotime('yesterday'))),
				'end' => Format::datePicker(0)
			],

			//'jira_performance' => true,
			'filters' => $filters
		];
	}

	public function filters()
	{
		return [
			'Users' => FilterImp::get(new UserUsageName)
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date' => 'mm_day  >= \''.$options['date_start'].'\''
					.' AND mm_day <= \''.$options['date_end'].'\'',

			'Users' => 'user_id IN ('. Format::str($options['filters']['Users']).') ',

			'environment' => 'environment = \'production\''
		];

		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
