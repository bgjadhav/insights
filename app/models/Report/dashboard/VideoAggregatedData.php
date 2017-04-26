<?php
class VideoAggregatedData extends Tile
{
	protected $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'MM_DATE',
			'group' 		=> true,
			'order' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.exch_name',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'  	=> 'sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'IMPRESSIONS',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'total'			=> true
		],
		'Start'			=> [
			'view'			=> 'Start',
			'fieldName'  	=> '(sum(a.START_COUNT)*100)/sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'START_COUNT',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage',
			'total'			=> true
		],
		'First'			=> [
			'view'			=> 'First Quartile',
			'fieldName'  	=> '(sum(a.FIRST_QUARTILE_COUNT)*100)/sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'FIRST_QUARTILE_COUNT',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage',
			'total'			=> true
		],
		'MidPoint'		=> [
			'view'			=> 'Mid Point',
			'fieldName'  	=> '(sum(a.MIDPOINT_COUNT)*100)/sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'MIDPOINT_COUNT',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage',
			'total'			=> true
		],
		'Third'			=> [
			'view'			=> 'Third Point',
			'fieldName'  	=> '(sum(a.THIRD_QUARTILE_COUNT)*100)/sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'THIRD_QUARTILE_COUNT',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage',
			'total'			=> true
		],
		'Complete'		=> [
			'view'			=> 'Complete',
			'fieldName'  	=> '(sum(a.COMPLETE_COUNT)*100)/sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'COMPLETE_COUNT',
			'group' 	 	=> false,
			'order' 		=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage',
			'total'			=> true
		]
	];
	protected $from = 'VIDEO_AGGREGATED_DATA a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'type'			=> ['table','chart-line'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges'	=> Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
		];

		array_walk($this->col, [&$this, 'dataColumn']);

	}

	public function getDataLine($default =[])
	{
		$key = array_search('MM_DATE', $this->group);
		unset($this->group[$key]);

		$categories = ['START_COUNT', 'FIRST_QUARTILE_COUNT', 'MIDPOINT_COUNT', 'THIRD_QUARTILE_COUNT', 'COMPLETE_COUNT'];

		$data = [
			'data'		=> [[]],
			'max'		=> 100,
			'text'		=> ' view: ',
			'scale'		=> 'percentage2',
			'format'	=> 'percentage2',
			'categories'=> ['Start', '25% Complete', '50% Complete', '75% Complete', '100% Complete']
		];

		$results = $this->data();
		foreach($results as $key => $result) {
			$id = preg_replace('/[^a-zA-Z0-9]+/', '', $result->{'TITLE'});

			if (!isset($data['data'][0][$key])) {
				$data['data'][0][$key]['name'] = $result->{'TITLE'};
			}
			foreach ($categories as $index => $cat) {
					$data['data'][0][$key]['data'][$index] = isset($result->$cat) ? (float)$result->$cat : 0;
			}
		}

		return $data;
	}
}
