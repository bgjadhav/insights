<?php
class FacingDomainNoClient extends Tile
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
		'SiteID'		=> [
			'view'			=> 'Site Id',
			'fieldName'		=> 'SITE_ID',
			'fieldAlias'	=> 'SITE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SiteUrl'	=> [
			'view'			=> 'Site URL',
			'fieldName'		=> 'SITE_URL',
			'fieldAlias'	=> 'SITE_URL',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'SUM(IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'	=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'SUM(MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'decimal',
			'order'			=> false,
			'total'			=> true
		],
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
	protected $from = 'FACING_DOMAIN_REPORT';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		$aggregate = Format::getYearMonth(Filter::getFacingDomainDate());
		$exception = $aggregate;
		array_shift($exception);

		return [
			'Aggregate'			=> [$aggregate, array_keys($exception)],
			'Exchanges'			=> Filter::getExchange(),
			'Country'			=> Filter::getCountryImpression(),
			'Columns'			=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$aggregate = Format::getArrayYearMonth($options['filters']['Aggregate']);

		$this->where = [
			'Year'			=> '`YEAR` IN ('.Format::id($aggregate[0]).')',
			'Month'			=> '`MONTH` IN ('.Format::id($aggregate[1]).')',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('
				.Format::id($options['filters']['Exchanges']).')',
			'Country'		=> 'COUNTRY_ID IN ('
				.Format::id($options['filters']['Country']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
