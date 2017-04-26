<?php
class UserLastLogin extends Tile
{
	public $col = [
		'User' => [
			'view' => 'User',
			'fieldName' => 'CONCAT(FIRST_NAME, \' \', LAST_NAME)',
			'fieldAlias' => 'full_name',
			'group' => false,
			'gDependence' => 'USER_ID',
			'join' => false,
			'format' => false,
			'order' => false,
			'total' => false
		],
		'Date' => [
			'view' => 'Date',
			'fieldName' => 'DATE_FORMAT(MAX(MM_DATE - INTERVAL 6 HOUR), \'%Y-%m-%d\')',
			'fieldAlias' => 'MM_DATE',
			'group' => false,
			'join' => false,
			'format' => false,
			'order' => 'DESC',
			'total' => false
		],
		'Hour' => [
			'view' => 'Time',
			'fieldName' => 'CONCAT(DATE_FORMAT(MAX(MM_DATE - INTERVAL 6 HOUR), \'%H-%i-%s\'), \' EST\')',
			'fieldAlias' => 'MM_HOUR',
			'group' => false,
			'join' => false,
			'format' => false,
			'order' => 'DESC',
			'total' => false
		]
	];

	protected $from = 'DASHBOARD_USAGE';

	protected $conn = 'jira_prod';

	protected $timeout = 10;

	protected $pagination = false;

	public function options($filters)
	{
		return [
			'date_picker' => false,

			'total' => false,

			'filters' => $filters
		];
	}

	public function filters()
	{
		return [];
	}

	public function setQuery($options)
	{
		$this->where = [
			'ENVIRONMENT' => 'ENVIRONMENT = \'production\''
		];

		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
