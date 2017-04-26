<?php
class MostSharedProject extends Tile
{
	public $col = [
		'User' => [
			'view' => 'User',
			'fieldName' => 'a.full_name',
			'fieldAlias' => 'full_name',
			'group' => false,
			'gDependence' => 'user_id',
			'join' => false,
			'format' => false,
			'order' => 'ASC',
			'total' => false
		],
		'Project' => [
			'view' => 'Project Name',
			'fieldName' => 'summary',
			'fieldAlias' => 'summary',
			'group' => false,
			'join' => [
				'type' => 'INNER',
				'tableName' => 'roadmap_product_issues e',
				'tableAlias' => 'a',
				'fieldA' => 'EXCHANGE_ID',
				'joinAlias' => 'e',
				'fieldB' => 'EXCH_ID'
			],
			'format' => 'number',
			'order' => false,
			'total' => true
		],
		'Total' => [
			'view' => 'Total Shares',
			'fieldName' => 'SUM(total)',
			'fieldAlias' => 'total',
			'group' => false,
			'join' => false,
			'format' => 'number',
			'order' => false,
			'total' => true
		]
	];

	protected $from = 'share_project a';

	protected $conn = 'jira_prod';

	protected $timeout = 10;

	protected $pagination = false;

	public function options($filters)
	{
		return [
			'date_picker' => [
				'start' => Format::datePicker(date('j', strtotime('yesterday'))),
				'end' => Format::datePicker()
			],
			//'jira_performance' => true,
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
			'Date' => 'mm_day  >= \''.$options['date_start'].'\''
					.' AND mm_day <= \''.$options['date_end'].'\''
		];

		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
