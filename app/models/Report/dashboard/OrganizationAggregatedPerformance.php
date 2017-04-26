<?php
class OrganizationAggregatedPerformance extends Tile
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
		'Month'			=> [
			'view'		=> 'Month',
			'fieldName'		=> 'MONTH',
			'fieldAlias'	=> 'MONTH',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Year'			=> [
			'view'		=> 'Year',
			'fieldName'		=> 'YEAR',
			'fieldAlias'	=> 'YEAR',
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
		'AgencyID'		=> [
			'view'			=> 'Agency ID',
			'fieldName'		=> 'AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AgencyName'		=> [
			'view'			=> 'Agency Name',
			'fieldName'		=> 'AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'gDependence'	=> 'AGENCY_ID',
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
		'Region'		=> [
			'view'			=> 'Region',
			'fieldName'		=> 'REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeID'		=> [
			'view'			=> 'Exchange ID',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'gDependence'	=> 'EXCHANGE_ID',
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
		'Clicks'		=> [
			'view'			=> 'Clicks',
			'fieldName'		=> 'sum(CLICKS)',
			'fieldAlias'	=> 'CLICKS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'PVConversions'	=> [
			'view'			=> 'PV Conversions',
			'fieldName'		=> 'sum(PV_ACTIVITIES)',
			'fieldAlias'	=> 'PV_CONVERSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'PCConversions'	=> [
			'view'			=> 'PC Conversions',
			'fieldName'		=> 'sum(PC_ACTIVITIES)',
			'fieldAlias'	=> 'PC_CONVERSIONS',
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
			'order'			=> false,
			'total'			=> true
		],
		'MMDataCost'		=> [
			'view'			=> 'MM Data Cost',
			'fieldName'		=> 'sum(MM_DATA_COST)',
			'fieldAlias'	=> 'MM_DATA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'UDIDataCost'		=> [
			'view'			=> 'UDI Data Cost',
			'fieldName'		=> 'sum(UDI_DATA_COST)',
			'fieldAlias'	=> 'UDI_DATA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'BilledSpend'		=> [
			'view'			=> 'Billed Spend',
			'fieldName'		=> 'sum(BILLED_SPEND)',
			'fieldAlias'	=> 'BILLED_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'TotalSpend'		=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'sum(TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Revenue'		=> [
			'view'			=> 'Revenue',
			'fieldName'		=> 'sum(REVENUE_AMOUNT)',
			'fieldAlias'	=> 'REVENUE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0 THEN (sum(MEDIA_COST)/sum(IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPA'			=> [
			'view'			=> 'CPA',
			'fieldName'		=> 'CASE WHEN sum(PC_ACTIVITIES)+sum(PV_ACTIVITIES) > 0 THEN (sum(MEDIA_COST)/(sum(PC_ACTIVITIES)+sum(PV_ACTIVITIES)))) ELSE 0.00 END',
			'fieldAlias'	=> 'CPA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPC'			=> [
			'view'			=> 'CPC',
			'fieldName'		=> 'CASE WHEN sum(CLICKS) > 0 THEN(sum(MEDIA_COST)/sum(CLICKS)) ELSE 0.00 END',
			'fieldAlias'	=> 'CPC',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CTR'			=> [
			'view'			=> 'CTR',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0 THEN (sum(CLICKS)/sum(IMPRESSIONS))*100 ELSE 0 END',
			'fieldAlias'	=> 'CTR',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'ORG_AGGREGATED_PERFORMANCE';

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
			'Exchanges'	=> Filter::getExchange(),
			//'Advertiser'		=> Filter::getAdvertiser(),
			'Columns'	=> [$this->getColumnView(), ['ExchangeID', 'ExchangeName','MMDataCost', 'UDIDataCost', 'BilledSpend', 'TotalSpend', 'Revenue','CPM','CPA','CPC','CTR']]
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
			'ExchangeId'	=> 'EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')'//,
			//'AdvertiserID'	=> 'ADVERTISER_ID IN ('.Format::id($options['filters']['Advertiser']).')'
			];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
