<?php
class KnoxChannelReport extends Tile
{
	public $col = [
		'Date'			=> [
			'view'		=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationID'		=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationName'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'gDependence'	=> 'ORGANIZATION_ID',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserID'		=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserName'		=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'gDependence'	=> 'ADVERTISER_ID',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CampaignID'		=> [
			'view'			=> 'Campaign ID',
			'fieldName'		=> 'CAMPAIGN_ID',
			'fieldAlias'	=> 'CAMPAIGN_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CampaignName'		=> [
			'view'			=> 'Campaign Name',
			'fieldName'		=> 'CAMPAIGN_NAME',
			'fieldAlias'	=> 'CAMPAIGN_NAME',
			'gDependence'	=> 'CAMPAIGN_ID',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Channel'		=> [
			'view'			=> 'Channel',
			'fieldName'		=> 'CHANNEL',
			'fieldAlias'	=> 'CHANNEL',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Social'		=> [
			'view'			=> 'Social',
			'fieldName'		=> 'CASE WHEN SOCIAL = \'t\' THEN \'Social\' ELSE \'Non-Social\' END',
			'fieldAlias'	=> 'SOCIAL',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Mobile'		=> [
			'view'			=> 'Mobile',
			'fieldName'		=> 'CASE WHEN MOBILE = \'t\' THEN \'Mobile\' ELSE \'Non-Mobile\' END',
			'fieldAlias'	=> 'MOBILE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'desc',
			'total'			=> true
		],
		'TotalSpend'		=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'sum(TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'desc',
			'total'			=> true
		],
		'BilledSpend'		=> [
			'view'			=> 'Billed Spend',
			'fieldName'		=> 'sum(BILLED_SPEND)',
			'fieldAlias'	=> 'BILLED_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'desc',
			'total'			=> true
		],
		'DirectRevenue'		=> [
			'view'			=> 'Direct Revenue',
			'fieldName'		=> 'sum(DIRECT_REVENUE)',
			'fieldAlias'	=> 'DIRECT_REVENUE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'desc',
			'total'			=> true
		]
	];
	protected $from = 'KNOX_CHANNEL_REPORT';

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
			'Organization'		=> Filter::getOrganization(),
			'Channel'		=> Filter::getKnoxOverallChannel(),
			'Mobile'			=> Filter::getKnoxMobileChannel(),
			'Social'			=> Filter::getKnoxSocialChannel(),
			'Columns'			=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$date_str = "";
		if ($options['date_start'] == $options['date_end']) {
			$date_str = 'MM_DATE = \''.$options['date_start'].'\'';
		} else {
			$date_str = 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\'';
		}
		$this->where = [
			'Date'			=> $date_str,
			'OrganizationID'	=> 'ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Channel'	=> 'CHANNEL IN ('.Format::str($options['filters']['Channel']).')',
			'Mob'	=> 'MOBILE IN ('.Format::str($options['filters']['Mobile']).')',
			'Soc'	=> 'Social IN ('.Format::str($options['filters']['Social']).')'
			];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
