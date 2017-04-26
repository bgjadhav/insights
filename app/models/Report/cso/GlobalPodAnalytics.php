<?php
class GlobalPodAnalytics extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'X',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Inventory'		=> [
			'view'			=> 'Inventory',
			'fieldName'		=> 'a.INV_TYPE',
			'fieldAlias'	=> 'TITLE',
			'group'			=> true,
			'join'			=> false,
			'order'			=> 'ASC',
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'SUM(a.IMPRESSIONS)',
			'fieldAlias'	=> 'Y',
			'group'			=> false,
			'join'			=> false,
			'order'			=> false,
			'format'		=> 'number',
			'total'			=> false
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'SUM(a.MEDIA_COST)',
			'fieldAlias'	=> 'Y1',
			'group'			=> false,
			'join'			=> false,
			'order'			=> false,
			'format'		=> 'money',
			'total'			=> false
		]
	];
	protected $from = 'CLIENT_POD_PRODUCT_USAGE a';
	protected $categories = ['Impressions', 'Media Cost'];
	protected $twoCharts =  true;

	public function options($filters)
	{
		return [
			'search'		=> false,
			'date_picker'	=> false,
			'pagination'	=> false,
			'pods'			=> $filters ? Filter::getPodsRegionGroup() :false,
			'checkboxes'	=> $filters ? [
				'device'  => Filter::getMediaTypePods(),
				'invtype' => Filter::getInvTypePods()
			] : false,
			'type'			=> ['chart-line'],
			'download'		=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Organization'	=> Filter::getPodsOrganization('all')
		];
	}

	public function setQuery($options)
	{
		$this->join['pods'] = 'INNER JOIN META_PS_PODS_CAMPAIGN b'
			.' ON a.ORGANIZATION_ID=b.ORGANIZATION_ID'
			.' AND a.CAMPAIGN_ID=b.CAMPAIGN_ID';

		$this->where = [
			'Date'	=> 'a.MM_DATE >= \''.date('Y-m-d', strtotime('-16 days'))
					.'\' AND a.MM_DATE <= \''.date('Y-m-d', strtotime('-1 days')).'\'',
			'Org'	=> 'a.ORGANIZATION_ID IN ('.Format::str($options['filters']['Organization']).')',
			'type'	=> 'a.TYPE IN ('.Format::str($options['filters']['device']).')',
			'inv'	=> 'a.INV_TYPE IN ('.Format::str($options['filters']['invtype']).')',
			'Pods'	=> 'b.POD_NAME IN ('.Format::str($options['filters']['pods']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
