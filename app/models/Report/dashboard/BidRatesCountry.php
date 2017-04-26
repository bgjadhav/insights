<?php
class BidRatesCountry extends Tile
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
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'b.COUNTRY_NAME',
			'fieldAlias'	=> 'Country',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_COUNTRY_IMPRESSION_LOG b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'COUNTRY',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'COUNTRY_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Biddables'		=> [
			'view'			=> 'Biddables',
			'fieldName'		=> 'SUM(a.BIDDABLES)',
			'fieldAlias'	=> 'BIDDABLES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'BiddRate'		=> [
			'view'			=> 'Bid Rate',
			'fieldName'		=> 'AVG(a.BID_RATE)*100',
			'fieldAlias'	=> 'BID_RATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'BID_BIDDABLE_RATE_COUNTRY a';

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
			'Country'	=> Filter::getCountryImpression(),
			'Exchanges'	=> Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'CountryId'		=> 'a.COUNTRY IN ('.Format::id($options['filters']['Country']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
