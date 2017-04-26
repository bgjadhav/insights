<?php
class UpcastPerformanceByDay extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'MMOrganizationID'=> [
			'view'			=> 'MM Organization ID',
			'fieldName'		=> 'a.MM_ORGANIZATION_ID',
			'fieldAlias'	=> 'MM_ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'MMOrganizationName'=> [
			'view'		=> 'MM Organization Name',
			'fieldName'		=> 'c.ORGANIZATION_NAME',
			'fieldAlias'	=> 'MM_ORGANIZATION_NAME',
			'group'			=> false,
								'gDependence'	=> 'a.MM_ORGANIZATION_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> '(SELECT ORGANIZATION_ID, ORGANIZATION_NAME FROM META_CAMPAIGN GROUP BY ORGANIZATION_ID, ORGANIZATION_NAME) c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'ORGANIZATION_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'MM_ORGANIZATION_ID'
			],
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'MMAgencyID'=> [
			'view'			=> 'MM Agency ID',
			'fieldName'		=> 'a.MM_AGENCY_ID',
			'fieldAlias'	=> 'MM_AGENCY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'MMAdvertiserID'=> [
			'view'			=> 'MM Advertiser ID',
			'fieldName'		=> 'a.MM_ADVERTISER_ID',
			'fieldAlias'	=> 'MM_ADVERTISER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'CampaignID'=> [
			'view'			=> 'Campaign ID',
			'fieldName'		=> 'a.CAMPAIGN_ID',
			'fieldAlias'	=> 'CAMPAIGN_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'CampaignName'=> [
			'view'			=> 'Campaign Name',
			'fieldName'		=> 'a.CAMPAIGN_NAME',
			'fieldAlias'	=> 'CAMPAIGN_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'SupplySource'	=> [
			'view'			=> 'Supply Source',
			'fieldName'		=> 'a.SUPPLY_SOURCE',
			'fieldAlias'	=> 'SUPPLY_SOURCE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'CurrencyCode'	=> [
			'view'			=> 'Currency Code',
			'fieldName'		=> 'a.CURRENCY_CODE',
			'fieldAlias'	=> 'CURRENCY_CODE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'total'			=> true,
			'order'			=> false
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'total'			=> false,
			'order'			=> false
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'total'			=> false,
			'order'			=> false
		]
	];
	protected $from = 'UPCAST_DAILY_PERFORMANCE a';

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
			'Organization'		=> Filter::getOrganization()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'MMOrganizationID'	=> 'a.MM_ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')'
		];
		
		//array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
