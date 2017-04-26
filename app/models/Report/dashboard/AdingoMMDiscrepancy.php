<?php
class AdingoMMDiscrepancy extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'APNImpressions'		=> [
			'view'			=> 'ADN Impressions',
			'fieldName'  	=> 'SUM(ADINGO_IMPRESSIONS)',
			'fieldAlias' 	=> 'ADN_IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MMImpressions'		=> [
			'view'			=> 'MM Impressions',
			'fieldName'  	=> 'SUM(MM_IMPRESSIONS)',
			'fieldAlias' 	=> 'MM_IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'ImpressionsDiff'			=> [
			'view'			=> 'Impressions Diff.',
			'fieldName'		=> 'COALESCE(CASE WHEN sum(ADINGO_IMPRESSIONS) > sum(MM_IMPRESSIONS) THEN 100-((sum(MM_IMPRESSIONS)/sum(ADINGO_IMPRESSIONS))*100) ELSE (100-((sum(ADINGO_IMPRESSIONS)/sum(MM_IMPRESSIONS))*100))*-1 END,0)',
			'fieldAlias'	=> 'IMPRESSIONS_DIFF',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> true
		],
		'APNClearCost'		=> [
			'view'			=> 'ADN Clear Cost',
			'fieldName'  	=> 'SUM(ADINGO_MEDIA_COST)',
			'fieldAlias' 	=> 'ADN_CLEAR_COST',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'MMClearCost'		=> [
			'view'			=> 'MM Clear Cost',
			'fieldName'  	=> 'SUM(MM_MEDIA_COST)',
			'fieldAlias' 	=> 'MM_CLEAR_COST',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'ClearCostDiff'			=> [
			'view'			=> 'Clear Cost Diff.',
			'fieldName'		=> 'COALESCE(CASE WHEN sum(ADINGO_MEDIA_COST) > sum(MM_MEDIA_COST) THEN 100-((sum(MM_MEDIA_COST)/sum(ADINGO_MEDIA_COST))*100) ELSE (100-((sum(ADINGO_MEDIA_COST)/sum(MM_MEDIA_COST))*100))*-1 END,0)',
			'fieldAlias'	=> 'CLEAR_COST_DIFF',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'ADINGO_MM_DISCREPANCY';
	protected $timeout = false;
	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(2),
				'end'	=> Format::datePicker(2)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'	=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\''
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
