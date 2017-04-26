<?php
class FacingAppNoClient extends Tile
{
	public $col = [
		'Year'			=> [
			'view'			=> 'Year',
			'fieldName'		=> 'a.`YEAR`',
			'fieldAlias'	=> 'YEAR',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Month'			=> [
			'view'			=> 'Month',
			'fieldName'		=> 'a.`MONTH`',
			'fieldAlias'	=> 'MONTH',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'month',
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Channel'	=> [
			'view'			=> 'Channel Name',
			'fieldName'		=> 'c.CHANNEL_NAME',
			'fieldAlias'	=> 'CHANNEL_NAME',
			'group'			=> true,
			'gDependence'	=> 'a.CHANNEL_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CHANNEL_TYPE_IMPRESSION_LOG c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'CHANNEL_ID',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'CHANNEL_ID'
			],
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'ExchangeID'		=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'	=> [
			'view'			=> 'Exchange',
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
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
			'order'			=> 'ASC',
			'total'			=> false
		],
		'AppID'		=> [
			'view'			=> 'App ID / Site URL',
			'fieldName'		=> 'a.APP_ID_SITE_URL',
			'fieldAlias'	=> 'APP_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'App OS'	=> [
			'view'			=> 'App OS',
			'fieldName'		=> 'CASE WHEN a.CHANNEL_ID not in (8,9) THEN \'Not In-App\' WHEN d.APP_NAME is NULL THEN \'Unknown\' ELSE d.OS END',
			'fieldAlias'	=> 'APP_OS',
			'group'			=> false,
			'gDependence'	=> 'a.APP_ID_SITE_URL',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_PROD_APP_CATEGORY d',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'APP_ID_SITE_URL',
				'joinAlias'		=> 'd',
				'fieldB'		=> 'APP_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'App Name'	=> [
			'view'			=> 'App Name',
			'fieldName'		=> 'CASE WHEN a.CHANNEL_ID not in (8,9) THEN \'Not In-App\' WHEN d.APP_NAME is NULL THEN \'Unknown\' WHEN d.APP_NAME = \'\' THEN \'Language cannot be displayed\' ELSE d.APP_NAME END',
			'fieldAlias'	=> 'APP_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.APP_ID_SITE_URL',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_PROD_APP_CATEGORY d',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'APP_ID_SITE_URL',
				'joinAlias'		=> 'd',
				'fieldB'		=> 'APP_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'App Category'	=> [
			'view'			=> 'App Cat.',
			'fieldName'		=> 'CASE WHEN a.CHANNEL_ID not in (8,9) THEN \'Not In-App\' WHEN d.CATEGORY_NAME is NULL THEN \'Unknown\' ELSE d.CATEGORY_NAME END',
			'fieldAlias'	=> 'CATEGORY_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.APP_ID_SITE_URL',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_PROD_APP_CATEGORY d',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'APP_ID_SITE_URL',
				'joinAlias'		=> 'd',
				'fieldB'		=> 'APP_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		/*'SellerID'	=> [
			'view'			=> 'Seller ID',
			'fieldName'		=> 'a.SELLER_ID',
			'fieldAlias'	=> 'SELLER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'APNSeller'	=> [
			'view'			=> 'APN Seller Name',
			'fieldName'		=> 'CASE WHEN a.EXCHANGE_ID = 13 then COALESCE(b.SELLER_NAME, \'Unknown Seller\') ELSE \'Not APN Seller ID\' END',
			'fieldAlias'	=> 'SELLER_NAME',
			'group'			=> true,
			'gDependence'	=> 'a.SELLER_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_APN_SELLER b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'SELLER_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'SELLER_ID'
			],
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],*/
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'SUM(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'	=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'SUM(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'PerCountry'	=> [
			'view'			=> '% Country',
			'fieldName'		=> 'a.PER_COUNTRY',
			'fieldAlias'	=> 'PER_COUNTRY',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> false
		],
		'CPM'	=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'a.CPM',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $sumTotal = false;
	protected $from = 'FACING_APP_REPORT a';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		$aggregate = Format::getYearMonth(Filter::getFacingAppDate());
		$exception = $aggregate;
		array_shift($exception);

		return [
			'Aggregate'			=> [$aggregate, array_keys($exception)],
			'Exchanges'			=> Filter::getExchange(),
			'Country'			=> Filter::getCountryImpression(),
			'Channel'			=> Filter::getMainChannelMeta(),
			'Columns'			=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$aggregate = Format::getArrayYearMonth($options['filters']['Aggregate']);

		$this->where = [
			'Year'			=> 'a.`YEAR` IN ('.Format::id($aggregate[0]).')',
			'Month'			=> 'a.`MONTH` IN ('.Format::id($aggregate[1]).')',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('
				.Format::id($options['filters']['Exchanges']).')',
			'Country'		=> 'a.COUNTRY_ID IN ('
				.Format::id($options['filters']['Country']).')',
			'Channel'		=> 'a.CHANNEL_ID IN ('
				.Format::id($options['filters']['Channel']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
