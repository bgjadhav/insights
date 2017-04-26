<?php
class FacingApp extends Tile
{
	public $col = [
		'Year'			=> [
			'view'			=> 'Year',
			'fieldName'		=> '`YEAR`',
			'fieldAlias'	=> 'YEAR',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Month'			=> [
			'view'			=> 'Month',
			'fieldName'		=> '`MONTH`',
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
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'	=> [
			'view'			=> 'Exchange',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> false,
			'gDependence'	=> 'COUNTRY_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'AppID'		=> [
			'view'			=> 'App ID/Site URL',
			'fieldName'		=> 'a.APP_ID_SITE_URL',
			'fieldAlias'	=> 'APP_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		/*'App Name'	=> [
			'view'			=> 'App Name',
			'fieldName'		=> 'CASE WHEN a.CHANNEL_ID not in (8,9) THEN \'Not In-App\' WHEN d.APP_NAME is NULL THEN \'Unknown\' ELSE d.APP_NAME END',
			'fieldAlias'	=> 'APP_NAME',
			'group'			=> true,
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
			'order'			=> 'ASC',
			'total'			=> false
		],
		'App Category'	=> [
			'view'			=> 'App Cat.',
			'fieldName'		=> 'CASE WHEN a.CHANNEL_ID not in (8,9) THEN \'Not In-App\' WHEN d.CATEGORY_NAME is NULL THEN \'Unknown\' ELSE d.CATEGORY_NAME END',
			'fieldAlias'	=> 'CATEGORY_NAME',
			'group'			=> true,
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
			'order'			=> 'ASC',
			'total'			=> false
		],*/
		/*'SellerID'	=> [
			'view'			=> 'Seller ID',
			'fieldName'		=> 'SELLER_ID',
			'fieldAlias'	=> 'SELLER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],*/
		
		'PerCountry'	=> [
			'view'			=> '% Country',
			'fieldName'		=> 'PER_COUNTRY',
			'fieldAlias'	=> 'PER_COUNTRY',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> false
		],
		'CPM'	=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CPM',
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
			'filters'		=> $filters,
			'total' => false,
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
		//print_r($aggregate);
		//die;
		
		$this->where = [
			'Year'			=> '`YEAR` IN ('.Format::id($aggregate[0]).')',
			'Month'			=> '`MONTH` IN ('.Format::id($aggregate[1]).')',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('
				.Format::id($options['filters']['Exchanges']).')',
			'Country'		=> 'COUNTRY_ID IN ('
				.Format::id($options['filters']['Country']).')',
			'Channel'		=> 'a.CHANNEL_ID IN ('
				.Format::id($options['filters']['Channel']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
