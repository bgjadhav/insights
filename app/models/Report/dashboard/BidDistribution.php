<?php
class BidDistribution extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.EXCH_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'EXCHANGE_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'EXCH_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'BidBucketStart'=> [
			'view' 			=> 'Bid Bucket Start',
			'fieldName'		=> 'a.BUCKET_START',
			'fieldAlias'	=> 'BID_BUCKET_START',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'BidBucketEnd'=> [
			'view' 			=> 'Bid Bucket End',
			'fieldName'		=> 'a.BUCKET_END',
			'fieldAlias'	=> 'BID_BUCKET_END',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'Bids'			=> [
			'view'			=> 'Bids',
			'fieldName'		=> 'sum(a.Bids)',
			'fieldAlias'	=> 'BIDS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Wins'			=> [
			'view'			=> 'Wins',
			'fieldName'		=> 'sum(a.WINS)',
			'fieldAlias'	=> 'WINS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Losses'		=> [
			'view'			=> 'Losses',
			'fieldName'		=> 'sum(a.LOSSES)',
			'fieldAlias'	=> 'LOSSES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'WinRate'		=> [
			'view'			=> 'Win Rate',
			'fieldName'		=> 'AVG((a.WINS/(a.WINS+a.LOSSES))*100)',
			'fieldAlias'	=> 'WIN_RATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'BID_DIST a';

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
			'Columns' => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date' => 'MM_DATE >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\''
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
