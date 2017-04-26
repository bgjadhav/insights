<?php
class Mobile extends Tile
{
	public $col = [
		'Date'			 => [
			'view'		=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.exch_name',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
								'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationId'	=> [
			'view'		=> 'Organization Id',
			'fieldName'		=> 'c.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'LEFT',
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
		'Organization'	=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'c.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
								'gDependence'	=> 'c.ORGANIZATION_ID',
			'join'			=> [
				'type'			=> 'LEFT',
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
		'AdvertiserId'	=> [
			'view'			=> 'Advertiser Id',
			'fieldName'		=> 'c.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'LEFT',
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
		'Advertiser'	=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'c.ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
								'gDependence'	=> 'c.ADVERTISER_ID',
			'join'			=> [
				'type'			=> 'LEFT',
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
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
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
			'fieldName'		=> 'sum(a.MEDIA_COST)/(sum(a.IMPRESSIONS)/1000)',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'MOBILE_BY_ADVERTISER a';

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
			'Exchanges'		=> Filter::getExchange(),
			'Organization'	=> Filter::getOrganization(),
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
				'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
				'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
				'OrganizationID' => 'c.ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}

}
