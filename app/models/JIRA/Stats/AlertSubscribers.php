<?php
class AlertSubscribers extends Tile
{
	public $col = [
		'User' => [
			'view' => 'User',
			'fieldName' => 'user_full_name',
			'fieldAlias' => 'user_full_name',
			'group' => false,
			'join' => false,
			'format' => false,
			'order' => 'ASC',
			'total' => false
		],
		'created' => [
			'view' => 'Date',
			'fieldName' => 'created',
			'fieldAlias' => 'created',
			'group' => false,
			'join' => false,
			'format' => false,
			'order' => false,
			'total' => false
		]
	];

	protected $from = 'roadmap_subscription';

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
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
