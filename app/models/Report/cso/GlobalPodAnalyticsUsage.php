<?php
class GlobalPodAnalyticsUsage extends Tile
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
		'MediaType'			=> [
			'view'			=> 'Media Type',
			'fieldName'		=> 'CASE WHEN a.TYPE = \'D\' THEN \'Display\' WHEN a.TYPE = \'M\' THEN \'Mobile\' ELSE \'Video\' END',
			'fieldAlias'	=> 'TYPE',
			'group'			=> true,
			'join'			=> false,
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
			'order'			=> false,
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'SUM(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'order'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'TotalSpend'	=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'SUM(a.TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
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
			'Organization'	=> Filter::getPodsOrganization('all'),
			'Media_Type'	=> Filter::getMediaTypePods(),
			'Inventory_Type'=> Filter::getInvTypePods(),
			'Columns'		=> $this->getColumnView()
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
			'Media_Type'=> 'a.TYPE IN ('.Format::str($options['filters']['Media_Type']).')',
			'InvType'	=> 'a.INV_TYPE IN ('.Format::str($options['filters']['Inventory_Type']).')',
			'Pods'		=> 'b.POD_NAME IN ('.Format::str($options['filters']['pods']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
