<?php
class KnoxAggregationTopline extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MONTH_YEAR',
			'fieldAlias'	=> 'MONTH_YEAR',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'OrgName'			=> [
			'view'			=> 'Org Name',
			'fieldName'		=> 'ORG_NAME',
			'fieldAlias'	=> 'ORG_NAME',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'Name'			=> [
			'view'			=> 'Name',
			'fieldName'		=> 'NAME',
			'fieldAlias'	=> 'NAME',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'Parent'			=> [
			'view'			=> 'Parent',
			'fieldName'		=> 'PARENT',
			'fieldAlias'	=> 'PARENT',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'OverallBilledSpend'=> [
			'view'			=> 'Overall Billed Spend',
			'fieldName'		=> 'OVERALL_BILLED_SPEND_ACCOUNTING_FOR_SOCIAL',
			'fieldAlias'	=> 'OVERALL_BILLED_SPEND_ACCOUNTING_FOR_SOCIAL',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'group_money',
			'total'			=> false,
			'order'			=> false
		],
		'AdjustedDirectRevenue'=> [
			'view'			=> 'Adjusted Direct Revenue',
			'fieldName'		=> 'ADJUSTED_DIRECT_REVENUE',
			'fieldAlias'	=> 'ADJUSTED_DIRECT_REVENUE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'group_money',
			'total'			=> false,
			'order'			=> false
		],
		'AdjustedBilledSpend'=> [
			'view'			=> 'Adjusted Billed Spend',
			'fieldName'		=> 'ADJUSTED_BILLED_SPEND',
			'fieldAlias'	=> 'ADJUSTED_BILLED_SPEND',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'group_money',
			'total'			=> false,
			'order'			=> false
		],
		'AdjustedTotalSpend'=> [
			'view'			=> 'Adjusted Total Spend',
			'fieldName'		=> 'ADJUSTED_TOTAL_SPEND',
			'fieldAlias'	=> 'ADJUSTED_TOTAL_SPEND',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'group_money',
			'total'			=> false,
			'order'			=> false
		],
		'TotalAdCost'			=> [
			'view'			=> 'Total Ad Cost',
			'fieldName'		=> 'TOTAL_AD_COST',
			'fieldAlias'	=> 'TOTAL_AD_COST',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'group_money',
			'total'			=> false,
			'order'			=> false
		],
		'PlatformAccessFee'	=> [
			'view'			=> 'Platform Access Fee',
			'fieldName'		=> 'PLATFORM_ACCESS_FEE',
			'fieldAlias'	=> 'PLATFORM_ACCESS_FEE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'group_money',
			'total'			=> false,
			'order'			=> false
		],
	];
	protected $from = 'KNOX_AGGREGATION_TOPLINE';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters' => $filters
		];
	}

	public function filters()
	{
		//!ddd(Filter::getAudienceAdoptionOrgs());
		return [
			// 'Organization' => Filter::getAudienceAdoptionOrgs(),
			// 'Segment Type' => ['audience' => 'Audience', 'data' => 'Data', 'dynamic' => 'Dynamic', 'event' => 'Event'],
		];
		
	}

	public function setQuery($options)
	{
		// 	'Organization'	=> 'a.org_id IN ('.Format::id($options['filters']['Organization']).')',
		// 	'Segment Type'	=> 'a.SEGMENT_TYPE IN ('.Format::str($options['filters']['Segment_Type']).')',

		$this->where = [
			'Date'	=> 'MM_DATE >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\'',
		];

		array_walk($this->col, [&$this, 'dataColumn']);
		// !ddd($this->buildQuery());
	}
}
