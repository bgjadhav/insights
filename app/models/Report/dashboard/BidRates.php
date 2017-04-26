<?php
class BidRates extends Tile
{
	public $col = [
		'Date'			 => [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Biddables'		=> [
			'view'			=> 'Biddables',
			'fieldName'		=> 'SUM(BIDDABLES)',
			'fieldAlias'	=> 'BIDDABLES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'BiddRate'		=> [
			'view'			=> 'Bid Rate',
			'fieldName'		=> 'AVG(BID_RATE)*100',
			'fieldAlias'	=> 'BID_RATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'BID_BIDDABLE_RATE';

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
			'Exchanges' => Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId'=> 'EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
