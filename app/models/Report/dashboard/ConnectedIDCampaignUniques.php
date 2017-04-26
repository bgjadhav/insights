<?php
class ConnectedIDCampaignUniques extends Tile
{
	protected $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationId'	=> [
			'view'			=> 'Organization Id',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationName'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'b.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.ORGANIZATION_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> '(SELECT ORGANIZATION_ID, ORGANIZATION_NAME FROM META_CAMPAIGN GROUP BY ORGANIZATION_ID, ORGANIZATION_NAME) b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'ORGANIZATION_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'ORGANIZATION_ID'
								],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CampaignId'	=> [
			'view'			=> 'Campaign Id',
			'fieldName'		=> 'a.CAMPAIGN_ID',
			'fieldAlias'	=> 'CAMPAIGN_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CampaignName'		=> [
			'view'			=> 'Campaign Name',
			'fieldName'		=> 'c.CAMPAIGN_NAME',
			'fieldAlias'	=> 'CAMPAIGN_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.CAMPAIGN_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CAMPAIGN c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'CAMPAIGN_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CAMPAIGN_ID'
								],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyType'		=> [
			'view'			=> 'Strategy Type',
			'fieldName'		=> 'a.STRATEGY_TYPE',
			'fieldAlias'	=> 'STRATEGY_TYPE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CookieExclMobile'	=> [
			'view'			=> 'Cookie excl Mobile',
			'fieldName'		=> 'sum(a.GROUP_A_UNIQUES)',
			'fieldAlias'	=> 'GROUP_A_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'CookieInclMobile'	=> [
			'view'			=> 'Cookie incl Mobile',
			'fieldName'		=> 'sum(a.GROUP_B_UNIQUES)',
			'fieldAlias'	=> 'GROUP_B_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Cookieless'	=> [
			'view'			=> 'Cookieless',
			'fieldName'		=> 'sum(a.GROUP_C_UNIQUES)',
			'fieldAlias'	=> 'GROUP_C_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'CrossDevice'	=> [
			'view'			=> 'Cross Device',
			'fieldName'		=> 'sum(a.GROUP_D_UNIQUES)',
			'fieldAlias'	=> 'GROUP_D_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'LiftCookieInclMobile'	=> [
			'view'			=> 'Lift % Cookie incl Mobile',
			'fieldName'		=> 'COALESCE((((sum(a.GROUP_B_UNIQUES))/sum(a.GROUP_A_UNIQUES)))*100,0)',
			'fieldAlias'	=> 'LIFTA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		],
		'LiftCookieless'	=> [
			'view'			=> 'Lift % Cookieless',
			'fieldName'		=> 'COALESCE((sum(a.GROUP_C_UNIQUES))/(sum(a.GROUP_A_UNIQUES))*100,0)',
			'fieldAlias'	=> 'LIFTB',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		],
		'LiftCrossDevice'	=> [
			'view'			=> 'Lift % Cross Device',
			'fieldName'		=> 'COALESCE((sum(a.GROUP_D_UNIQUES))/(sum(a.GROUP_A_UNIQUES))*100,0)',
			'fieldAlias'	=> 'LIFTC',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		],
		'LiftTotal'	=> [
			'view'			=> 'Lift % Total',
			'fieldName'		=> 'COALESCE((((sum(a.GROUP_D_UNIQUES)+sum(a.GROUP_C_UNIQUES)+sum(a.GROUP_B_UNIQUES))/(sum(a.GROUP_A_UNIQUES)))*100),0)',
			'fieldAlias'	=> 'LIFTTOTAL',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		]

	];
	protected $from = 'CONNECTEDID_DERIVED_CAMPAIGN_UNIQUES a';
	//protected $sumTotal = true;

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Organization'		=> Filter::getOrganization(),
			'StrategyType'		=> Filter::getStrategyType(),
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		//$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'				=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'StrategyType'		=> 'a.STRATEGY_TYPE IN ('.Format::str($options['filters']['StrategyType']).')'
		];

		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);

		//~ dd(print_r($this->buildQuery()));
		//~ die;
	}
}
