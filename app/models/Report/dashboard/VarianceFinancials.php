<?php
class VarianceFinancials extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Month',
			'fieldName' 	=> 'MONTH',
			'fieldAlias' 	=> 'MONTH',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Type'			=> [
			'view'			=> 'Partner',
			'fieldName' 	=> 'PARTNER',
			'fieldAlias'	=> 'PARTNER',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Excepted'		=> [
			'view'			=> 'Excepted Invoice',
			'fieldName' 	=> 'SUM(EXPECTED)',
			'fieldAlias'	=> 'EXPECTED',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Actual'		=> [
			'view'			=> 'Actual Invoice',
			'fieldName' 	=> 'SUM(ACTUAL)',
			'fieldAlias'	=> 'Actual',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Variance'		=> [
			'view'			=> 'Variance',
			'fieldName' 	=> 'CASE WHEN EXPECTED = 0 THEN EXPECTED ELSE ACTUAL/EXPECTED END',
			'fieldAlias'	=> 'Variance',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $from = 'OPEN_ACCRUAL_AND_VARIANCE';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'scrollY' 		=> '1480px',
			'pagination'	=> false,
			'uniqueFilter'	=> ['Month', 'Partner'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Month'		=> $this->getFilterDate(),
			'Partner'	=> [['SUPPLY' => 'SUPPLY', 'VENDOR' => 'VENDOR'], ['VENDOR']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Month'=> 'MONTH IN ('.Format::str($options['filters']['Month']).')',
			'Type'	=> 'TYPE IN ('.Format::str($options['filters']['Partner']).')'
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

}

