<?php
class BidablesByInventory extends Tile
{
	public $col = [
		'Date'		=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'MM_DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'EXCHANGE'	=> [
			'view' 			=> 'EXCHANGE',
			'fieldName' 	=> 'EXCHANGE',
			'fieldAlias'	=> 'EXCHANGE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Inventory'	=> [
			'view' 			=> 'Inventory Type',
			'fieldName' 	=> 'CHANNEL_TYPE',
			'fieldAlias'	=> 'INV_TYPE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Biddables'	=> [
			'view' 			=> 'Biddables',
			'fieldName' 	=> 'SUM(ROUND(BIDDABLES))',
			'fieldAlias'	=> 'BIDDABLES',
			'join'			=> false,
			'group' 		=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'GRAFANA_BIDABLES_BY_DAY';

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
			'Inventory'	=> Filter::getBidChannel(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Inventory'	=> 'CHANNEL_TYPE IN ('.Format::str($options['filters']['Inventory']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
