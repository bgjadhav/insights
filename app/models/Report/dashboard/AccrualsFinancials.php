<?php
class AccrualsFinancials extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Month',
			'fieldName'		=> 'a.MONTH',
			'fieldAlias'	=> 'MONTH',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Type' => [
			'view'			=> 'Partner',
			'fieldName' 	=> 'a.PARTNER',
			'fieldAlias'	=> 'PARTNER',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Excepted'		=> [
			'view'			=> 'Excepted Invoice',
			'fieldName'		=> 'SUM(a.EXPECTED)',
			'fieldAlias'	=> 'EXPECTED',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Actual'		=> [
			'view'			=> 'Actual Invoice',
			'fieldName'		=> 'SUM(a.ACTUAL)',
			'fieldAlias'	=> 'Actual',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Accrual'		=> [
			'view'			=> 'Accrual',
			'fieldName'		=> 'CASE WHEN b.PERCENTAGE is null
								THEN 0 ELSE b.PERCENTAGE END',
			'fieldAlias'	=> 'Accrual',
			'group' 		=> false,
			'format'		=> 'percentage5',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_ACCRUAL_PERCENTAGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'PARTNER',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'PARTNER'
			],
			'order'			=> false,
			'total'			=> false
		],
		'Percentage'	=> [
			'view'			=> 'Accrual Amount',
			'fieldName' 	=> 'a.EXPECTED * CASE WHEN b.PERCENTAGE is null
								THEN 0 ELSE b.PERCENTAGE END',
			'fieldAlias'	=> 'Percentage',
			'group' 		=> false,
			'format'		=> 'money',
			'join' 			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_ACCRUAL_PERCENTAGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'PARTNER',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'PARTNER'
			],
			'order'			=> false,
			'total'			=> false
		]
	];
	public $from =  'OPEN_ACCRUAL_AND_VARIANCE a';
	protected $timeout = false;

	public function options($filters)
	{
		return [
			'date_picker'		=> false,
			'scrollY'			=> '1480px',
			'pagination'		=> false,
			'column_selector'	=> false,
			'uniqueFilter'		=> ['Month', 'Partner'],
			'filters'			=> $filters
		];
	}

	public function filters()
	{
		return [
			'Month'		=> $this->getFilterDate(),
			'Partner'	=> [
				['SUPPLY' => 'SUPPLY', 'VENDOR' => 'VENDOR'],
				['VENDOR']
			]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Month'=> 'a.MONTH IN ('
				.Format::str($options['filters']['Month']).')',
			'Type'	=> 'a.TYPE IN ('
				.Format::str($options['filters']['Partner']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}

	private function getFilterDate()
	{
		$date = ['init' => '1412121600', 'current' => strtotime('now')];
		$output = [];

		while ($date['init'] <= $date['current']) {
			$month = date('M-y', $date['init']);
			$output[$month]	= $month;
			$date['init']	= strtotime('+1 months', $date['init']);
		}

		$noCurrentMonth = $output;
		array_pop($noCurrentMonth);
		return [array_reverse($output), array_keys($noCurrentMonth)];
	}

	public function getDataTablePagination($default =[])
	{
		return $this->getDataTable($default);
	}

	public function getDataTable($default =[])
	{
		$data = [];
		$data['data'] = $this->data();
		$data['options'] = $default;
		$getColumnsView = $this->getColumnsView($data['options']['total']);
		$getColumnsView['totals'][5] = 0;

		if (!empty($data['data'])){
			foreach ($data['data'] as $key => $result) {
				$getColumnsView['totals'][5] += $result->Percentage;
				$data['data'][$key] = array_values((array)$result);
			}
			$data = array_merge($data, $getColumnsView);
		}

		return $data;
	}

}
