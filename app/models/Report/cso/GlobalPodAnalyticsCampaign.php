<?php
class GlobalPodAnalyticsCampaign extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'PodName'		=> [
			'view'			=> 'Pod Name',
			'fieldName'		=> 'b.POD_NAME',
			'fieldAlias'	=> 'POD_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Organization'	=> [
			'view'			=> 'Organization',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> true,
			'gDependence'	=> 'a.ORGANIZATION_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'CampaignID'	=> [
			'view'			=> 'Campaign ID',
			'fieldName'		=> 'a.CAMPAIGN_ID',
			'fieldAlias'	=> 'CAMPAIGN_ID',
			'group'			=> true,
			'gDependence'	=> 'a.CAMPAIGN_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'CampaignName'	=> [
			'view'			=> 'Campaign Name',
			'fieldName'		=> 'c.CAMPAIGN_NAME',
			'fieldAlias'	=> 'CAMPAIGN_NAME',
			'group'			=> true,
			'gDependence'	=> 'a.CAMPAIGN_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CAMPAIGN c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'CAMPAIGN_ID',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'CAMPAIGN_ID'
			],
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Inventory'		=> [
			'view'			=> 'Inventory',
			'fieldName'		=> 'a.INV_TYPE',
			'fieldAlias'	=> 'INV_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'SUM(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'order'			=> false,
			'format'		=> 'number',
			'total'			=> false
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'SUM(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'order'			=> false,
			'format'		=> 'money',
			'total'			=> false
		],
		'TotalSpend'	=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'SUM(a.TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'order'			=> false,
			'format'		=> 'money',
			'total'			=> false
		]
	];
	protected $from = 'CLIENT_POD_PRODUCT_USAGE a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'pods'			=> $filters ? Filter::getPodsRegionGroup() : false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Organization'		=> Filter::getPodsOrganization('all'),
			'Inventory_Type'	=> Filter::getInvTypePods(),
			'Columns'			=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->join['pods'] = 'INNER JOIN META_PS_PODS_CAMPAIGN b'
			.' ON a.ORGANIZATION_ID=b.ORGANIZATION_ID'
			.' AND a.CAMPAIGN_ID=b.CAMPAIGN_ID';

		$this->where = [
			'Date'		=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\'',
			'Org'		=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'InvType'	=> 'a.INV_TYPE IN ('.Format::str($options['filters']['Inventory_Type']).')',
			'Pods'		=> 'b.POD_NAME IN ('.Format::str($options['filters']['pods']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
