<?php
class GlobalDealByAdvertiserByGeo extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName'     => 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealID'		=> [
			'view'			=> 'Deal ID',
			'fieldName'		=> 'a.DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'		=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'a.DEAL_NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.DEAL_ID',
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
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserId'		=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'a.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserName'	=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'a.ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> false,
			'gDependence'	=> 'a.COUNTRY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	//protected $totalNoJoin = true;
	protected $from = 'GLOBAL_DEAL_ADVERTISER_GEO a';

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
			'Exchanges'	=> Filter::getExchange(),
			'Organization'		=> Filter::getOrganization(),
			'Country'		=> Filter::getOrgCountry(),
			'Columns'		=> [$this->getColumnView(), ['CPM']]
		 ];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$date_str = '';
		if ($options['date_start'] == $options['date_end']) {
			$date_str = 'a.MM_DATE = \''.$options['date_start'].'\'';
		} else {
			$date_str = 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\'';
		}
		$this->where = [
			'Date'				=> $date_str,
			'OrganizationID'	=> 'a.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Country'	=> 'COUNTRY IN ('.Format::str($options['filters']['Country']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
