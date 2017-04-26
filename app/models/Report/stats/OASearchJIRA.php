<?php
class OASearchJIRA extends Tile
{
	public $col = [
		'Date'		=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'MM_DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Word'		=> [
			'view'			=> 'Word',
			'fieldName'		=> 'WORD',
			'fieldAlias'	=> 'WORD',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Report'		=> [
			'view'			=> 'Report Name',
			'fieldName'		=> 'REPORT',
			'fieldAlias'	=> 'REPORT',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'User'	=> [
			'view'			=> 'User',
			'fieldName'		=> 'USER_',
			'fieldAlias'	=> 'USER_',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Total'	=> [
			'view'			=> 'Total',
			'fieldName'		=> 'SUM(COUNT_)',
			'fieldAlias'	=> 'COUNT_',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $from = 'SEARCH_STATS';
	protected $conn = 'dashboard';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(0),
				'end'	=> Format::datePicker(0)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			//'Reports'	=> ConfigReport::reportsJIRA(),
			//~ 'Words_start_by'=> array_merge(
				//~ array_combine(range(0, 9), range(0, 9)),
				//~ array_combine(range('A', 'Z'), range('A', 'Z')),
				//~ ['characters' => 'Characters (., -, *, ...)']
			//~ ),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> ' MM_DATE  >= \''.$options['date_start'].'\' '
						.' AND MM_DATE <= \''.$options['date_end'].'\'',
			//~ 'Reports'	=> 'REPORT IN ('
						//~ .Format::str($options['filters']['Reports']).')'
		];

		//~ if (($key = array_search('characters', $options['filters']['Words_start_by'])) !== false) {
			//~ unset($options['filters']['Words_start_by'][$key]);
			//~ $this->where['Word'][] = ' WORD REGEXP \'^[^0-9A-Za-z]\'';
		//~ }

		//~ if (!empty($options['filters']['Words_start_by'])) {
			//~ $this->where['Word'][] = 'WORD REGEXP \''
				//~ .RegexMySQL::filter(
					//~ implode(' ', $options['filters']['Words_start_by']),
					//~ true
				//~ ).'\'';
		//~ }
		//~ $this->where['Word'] = '('. implode(' OR ', $this->where['Word']) .')';

		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);

	}
}
