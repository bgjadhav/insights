<?php

class PmapRev extends Tile
{
	public $col = [
		'ticket'	=> [
			'view'			=> 'Ticket',
			'fieldName' 	
				=> 'concat("<a href=\"https://issues.mediamath.com/browse/",ticket_id,"\" class=\"info\" target=\"_blank\"></a> "," ",ticket)',
			'fieldAlias' 	=> 'ticket',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'supply_type'	=> [
			'view'			=> 'Supply Type',
			'fieldName' 	=> 'group_concat(supply_type separator "<br />")',
			'fieldAlias' 	=> 'supply_type',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Media'		=> [
			'view'			=> 'Media Cost',
			'fieldName' 	=> 'group_concat(media_cost separator "<br />")',
			'fieldAlias'	=> 'Media',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> 'group_money',
			'order'			=> false,
			'total'			=> false
		]
	];

	protected $from = 'pmap_aggregation';
	protected $conn = 'pmap_rev';
	protected $timeout = false;

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			// 'uniqueFilter'	=> ['Days'],
			'range_selector'	=> [
				'7 Days',
				'30 Days',
				'90 Days',
				'180 Days',
			],
			'filters'		=> $filters
		];
	}

	public function filters() {
		return [
			// 'Supply_Type' => Filter::BreakoutJIRAPMAP(),
			// 'Columns' => $this->getColumnView(),
			// 'Days' 	=> Filter::DaysPmapRev()
		];
	}

	public function setQuery($options)
	{
		// $this->where = [
		// 	'Supply_Type'		=> 'supply_type IN ('.Format::str($options['filters']['Supply_Type']).')'
		// ];

		$val = [
			'7_days' 	=> 7,
			'30_days' 	=> 30,
			'90_days' 	=> 90,
			'180_days' 	=> 180,
		];

		$this->where = [
			'Days'	=> 'days IN ('.Format::str([$val[$options['range_selector']]]).')'
		];

		//$this->limit = 10;
		array_walk($this->col, [&$this, 'dataColumn']);
		// array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
		// dd($this->data());
	}
}
