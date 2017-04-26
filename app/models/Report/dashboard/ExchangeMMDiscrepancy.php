<?php
class ExchangeMMDiscrepancy extends Tile
{
	public $col = [
		'ExchangeName'			=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'ExchangeImpressions'		=> [
			'view'			=> 'Exchange Impressions',
			'fieldName'  	=> 'EXCHANGE_IMPRESSIONS',
			'fieldAlias' 	=> 'EXCHANGE_IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'MMImpressions'		=> [
			'view'			=> 'MM Impressions',
			'fieldName'  	=> 'MM_IMPRESSIONS',
			'fieldAlias' 	=> 'MM_IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'ImpressionsDiff'			=> [
			'view'			=> 'Impressions Diff (%)',
			'fieldName'		=> 'IMPRESSION_DIFF',
			'fieldAlias'	=> 'IMPRESSION_DIFF',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeClearCost'		=> [
			'view'			=> 'Exchange Clear Cost',
			'fieldName'  	=> 'EXCHANGE_CLEAR_COST',
			'fieldAlias' 	=> 'EXCHANGE_CLEAR_COST',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'MMClearCost'		=> [
			'view'			=> 'MM Clear Cost',
			'fieldName'  	=> 'MM_MEDIA_COST',
			'fieldAlias' 	=> 'MM_MEDIA_COST',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'ClearCostDiff'			=> [
			'view'			=> 'Clear Cost Diff.',
			'fieldName'		=> 'CLEAR_COST_DIFF',
			'fieldAlias'	=> 'CLEAR_COST_DIFF',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'kamaldeep.EXCHANGE_MM_DISCREPANCY';
	protected $timeout = false;
	public function options($filters)
	{
		return [
			'date_picker'	=> false
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
