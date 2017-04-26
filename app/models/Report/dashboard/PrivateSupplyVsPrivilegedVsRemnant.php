<?php
class PrivateSupplyVsPrivilegedVsRemnant extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Category'		=> [
			'view' 		=> 'Category',
			'fieldName'		=> 'CATEGORY',
			'fieldAlias'	=> 'CATEGORY',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'InventoryType'		=> [
			'view' 	=> 'Supply Type',
			'fieldName'		=> 'SUPPLY_TYPE',
			'fieldAlias'	=> 'TITLE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(MEDIA_COST)',
			'fieldAlias'	=> 'DATA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'COMPLETE_PRIVILEGED_SUPPLY';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(10),
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters,
			'type'			=> ['table', 'chart-line']
		];
	}

	public function filters()
	{
		return [
			'Catgories'	=> Filter::getCategory(),
			'SupplyType'=> Filter::getPrivSupplyType(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Category'		=> 'CATEGORY IN ('.Format::str($options['filters']['Catgories']).')',
			'SupplyType'	=> 'SUPPLY_TYPE IN ('.Format::str($options['filters']['SupplyType']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}

	public function getDataLine($default =[])
	{
		$data = [
			'data'		=> [[]],
			'text'		=> ' Spend: ',
			'max'		=> null,
			'scale'		=> 'million',
			'format'	=> 'money',
			'categories'=> []
		];

		$results = $this->data();
		foreach($results as $result) {

			$indexName =  array_flip(array_column($data['data'][0], 'name'));
			if (!isset($indexName[$result->{'TITLE'}])) {
				$data['data'][0][]['name']	= $result->{'TITLE'};
				$indexName =  array_flip(array_column($data['data'][0], 'name'));
			}

			if (!in_array($result->{'DATE'}, $data['categories'])) {
				$data['categories'][] = $result->{'DATE'};
			}

			$key = $indexName[$result->{'TITLE'}];
			$cat = array_search($result->{'DATE'}, $data['categories']);
			$data['data'][0][$key]['data'][$cat] = (int)$result->{'DATA'};

			if ($result->{'TITLE'} == 'Open Exchanges and SSPs') {
				$data['data'][0][$key]['visible'] = false;
			}
		}

		return $data;
	}
}
