<?php
class SupplyForecasting extends Tile
{
	public $col = [
		'Year'			=> [
			'view'			=> 'Year',
			'fieldName'		=> 'a.`YEAR`',
			'fieldAlias'	=> 'YEAR',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Month'			=> [
			'view'			=> 'Month',
			'fieldName'		=> 'MONTHNAME(STR_TO_DATE(a.`MONTH`, \'%m\'))',
			'fieldAlias'	=> 'MONTH',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeID'	=> [
			'view'			=> 'Exchange ID',
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
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Size'			=> [
			'view'			=> 'Size(WxH)',
			'fieldName'		=> 'CONCAT(a.WIDTH,\'x\',a.HEIGHT)',
			'fieldAlias'	=> 'SIZE',
			'group'			=> false,
			'gDependence'	=> ['a.WIDTH', 'a.HEIGHT'],
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Biddables'	=> [
			'view'			=> 'Biddables',
			'fieldName'		=> 'sum(a.BIDDABLES)',
			'fieldAlias'	=> 'BIDDABLES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $sumTotal = false;
	protected $from = 'SUPPLY_FORECASTING a';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		$aggregate = Filter::getSupplyForecastingDate();
		$exception = $aggregate;
		array_shift($exception);
		return [
			'Aggregate'	=> [$aggregate, $exception],
			'Exchanges'	=> Filter::getExchange(),
			'Country'			=> Filter::getGeo(),
			'Columns'			=> $this->getColumnView()
		];


	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'			=> 'CONCAT(MONTHNAME(STR_TO_DATE(a.`MONTH`, \'%m\')),\' \',a.`YEAR`) IN('.Format::str($options['filters']['Aggregate']).')',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Country'			=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
