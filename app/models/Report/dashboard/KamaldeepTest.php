<?php
class KamaldeepTest extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'			=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'		=> [
			'view'			=> 'Impressions',
			'fieldName'  	=> 'SUM(IMPRESSIONS)',
			'fieldAlias' 	=> 'IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'  	=> 'SUM(MEDIA_COST)',
			'fieldAlias' 	=> 'MEDIA_COST',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'KAMALDEEP_TEST';
	protected $timeout = false;
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
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'	=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\''
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
