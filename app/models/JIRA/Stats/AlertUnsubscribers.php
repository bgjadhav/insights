<?php
class AlertUnsubscribers extends Tile
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
		'created' => [
			'view' => 'Date',
			'fieldName' => 'MAX(created)',
			'fieldAlias' => 'created',
			'group' => false,
			'join' => false,
			'format' => false,
			'order' => false,
			'total' => false
		]
	];

	protected $from = 'roadmap_subscription_log';

	protected $conn = 'jira_prod';

	protected $timeout = 10;

	protected $pagination = false;

	public function options($filters)
	{
		return [
			'date_picker' => false,

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
			'user_id' => 'user_id NOT IN (SELECT user_id FROM roadmap_subscription)',

			'status' => 'status  = \'none\''
		];

		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
