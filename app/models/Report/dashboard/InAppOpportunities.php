<?php
class InAppOpportunities extends Tile
{
	public $col = [
		'Date'			 => [
			'view'			=> 'Date',
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
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'	=> [
			'view'			=> 'COUNTRY',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AppID'		=> [
			'view'			=> 'App ID',
			'fieldName'		=> 'a.APP_ID',
			'fieldAlias'	=> 'APP_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'App Name'	=> [
			'view'			=> 'App Name',
			'fieldName'		=> 'CASE WHEN d.APP_NAME = \'\' THEN \'Language cannot be displayed\' WHEN d.APP_NAME is NULL THEN \'Unknown\' ELSE d.APP_NAME END',
			'fieldAlias'	=> 'APP_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.APP_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_PROD_APP_CATEGORY d',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'APP_ID',
				'joinAlias'		=> 'd',
				'fieldB'		=> 'APP_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'App OS'	=> [
			'view'			=> 'App OS',
			'fieldName'		=> 'CASE WHEN d.APP_NAME is NULL THEN \'Unknown\' ELSE d.OS END',
			'fieldAlias'	=> 'APP_OS',
			'group'			=> false,
			'gDependence'	=> 'a.APP_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_PROD_APP_CATEGORY d',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'APP_ID',
				'joinAlias'		=> 'd',
				'fieldB'		=> 'APP_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'App Category'	=> [
			'view'			=> 'App Cat.',
			'fieldName'		=> 'CASE WHEN d.CATEGORY_NAME is NULL THEN \'Unknown\' ELSE d.CATEGORY_NAME END',
			'fieldAlias'	=> 'CATEGORY_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.APP_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_PROD_APP_CATEGORY d',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'APP_ID',
				'joinAlias'		=> 'd',
				'fieldB'		=> 'APP_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		/*'SellerID'		=> [
			'view'			=> 'Seller ID',
			'fieldName'		=> 'a.SELLER_ID',
			'fieldAlias'	=> 'SELLER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
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
		'Biddables'		=> [
			'view'			=> 'Est. Biddables',
			'fieldName'		=> '(SUM(a.AUCTIONS)*1250)+(FLOOR(RAND()*(99-1+1))+1)', //
			'fieldAlias'	=> 'BIDDABLES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> 'desc',
			'total'			=> false
		]
	];
	protected $from = 'BIDDABLE_BY_INAPP a';
	protected $timeout = false;
	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(0),
				'end'	=> Format::datePicker(0)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Country'	=> Filter::getCountryImpression_name_only(),
			'Exchanges'	=> Filter::getExchange(),
			'Category' 	=> Filter::getAppCategory(),
			'OS'		=> Filter::getAppOS(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$cat_unknown='';
		$os_unknown='';

		if(in_array("Unknown", array_values($options['filters']['Category'])))
		{
			$cat_unknown = 'OR d.CATEGORY_NAME IS NULL';
			unset($options['filters']['Category']['Unknown']);
		}
		if(in_array("Unknown", array_values($options['filters']['OS'])))
		{
			$os_unknown = 'OR d.OS IS NULL';
			unset($options['filters']['OS']['Unknown']);
		}
		
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'Country'		=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Cat'			=> '(d.CATEGORY_NAME IN ('.Format::str($options['filters']['Category']).') '.$cat_unknown.')',
			'OS'			=> '(d.OS IN ('.Format::str($options['filters']['OS']).') '.$os_unknown.')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
