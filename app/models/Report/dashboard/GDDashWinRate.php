<?php
class GDDashWinRate extends Tile
{
	public  $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealID'		=> [
			'view'			=> 'Deal ID',
			'fieldName'		=> 'a.DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'		=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'c.NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'gDependence'	=> 'a.DEAL_ID',
			'group'			=> false,
			'join' 				=> [
				'type'		=> 'LEFT',
				'tableName'		=> 'META_DEALS c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'e.EXCH_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'gDependence'	=> 'a.EXCHANGE_ID',
			'group'			=> false,
			'join' 				=> [
				'type'		=> 'INNER',
				'tableName'		=> 'META_EXCHANGE e',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'EXCHANGE_ID',
				'joinAlias'		=> 'e',
				'fieldB'		=> 'EXCH_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PublisherID'		=> [
			'view'			=> 'Publisher ID',
			'fieldName'		=> 'a.PUBLISHER_ID',
			'fieldAlias'	=> 'PUBLISHER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'WinRate'	=> [
			'view'			=> 'Win Rate',
			'fieldName'		=> 'COALESCE((a.WINS/(a.WINS+a.LOSSES))*100,0)',
			'fieldAlias'	=> 'WINRATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage3',
			'order'			=> false,
			'total'			=> false
		],
		'OAWinRate'	=> [
			'view'			=> 'OA Win Rate',
			'fieldName'		=> 'COALESCE((d.WINS/(d.WINS+d.LOSSES))*100,0)',
			'fieldAlias'	=> 'OA_WINRATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage3',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'GD_DASH_WINRATE a LEFT JOIN GD_DASH_WINRATE_OA d on (a.MM_DATE = d.MM_DATE AND a.PUBLISHER_ID = d.PUBLISHER_ID AND a.EXCHANGE_ID = d.EXCHANGE_ID)';

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
		//var_dump($this->getColumnView());exit;

		return [
			'Exchange'  => Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		//var_dump($options['filters']['Columns']);
		
		//var_dump($options['filters']['Columns']);
	
		
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\'',
			'Exchange'		=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchange']).')'
		];
		
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
?>