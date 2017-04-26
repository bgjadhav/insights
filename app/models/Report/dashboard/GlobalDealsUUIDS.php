<?php
class GlobalDealsUUIDS extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Inventory'		=> [
			'view' 		=> 'Inventory Type',
			'fieldName' 	=> 'INV_TYPE',
			'fieldAlias'	=> 'TITLE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'UUIDS'			=> [
			'view'			=> 'UUIDS',
			'fieldName'		=> 'SUM(UUIDS)',
			'fieldAlias'	=> 'DATA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'GLOBAL_DEALS_UUIDS';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(10),
				'end'	=> Format::datePicker()
			],
			'total'			=> false,
			'filters'		=> $filters,
			'type'			=> ['table', 'chart-line']
		];
	}

	public function filters()
	{
		return [
			'Inventory_Type' => Filter::getInventoryType()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date' 	=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Inventory'	=> 'INV_TYPE IN ('.Format::str($options['filters']['Inventory_Type']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}

	public function getDataLine($default =[])
	{
		$data = [
			'data'		=> [
				[
					['name'=>'Global Deals', 'data'=> []],
					['name'=>'Non-Global Deals', 'data'=> []]
				]
			],
			'text'		=> ' UUIDS: ',
			'max'		=> null,
			'scale'		=> 'million',
			'format'	=> 'number',
			'categories'=> []
		];

		$indexName =  array_flip(array_column($data['data'][0], 'name'));

		$results = $this->data();
		foreach($results as $result) {
			if (!in_array($result->{'DATE'}, $data['categories'])) {
				$data['categories'][] = $result->{'DATE'};
			}
			$key = $indexName[$result->{'TITLE'}];
			$cat = array_search($result->{'DATE'}, $data['categories']);
			$data['data'][0][$key]['data'][$cat] = (int)$result->{'DATA'};
		}

		return $data;
	}
}
