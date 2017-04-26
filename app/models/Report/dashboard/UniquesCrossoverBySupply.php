<?php
class UniqueCrossoverBySupply extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SupplyCrossover'		=> [
			'view'			=> 'Supply Crossover',
			'fieldName'		=> 'CROSSOVER',
			'fieldAlias'	=> false,
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Uniques'		=> [
			'view'			=> 'Uniques',
			'fieldName'		=> 'UNIQUES',
			'fieldAlias'	=> false,
			'group'			=> true,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'%ofTotal'	=> [
			'view'			=> '% of Total Uniques',
			'fieldName'  	=> 'PERCENT_OF_TOTAL',
			'fieldAlias' 	=> 'PERC_TOTAL',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'%ofOA'	=> [
			'view'			=> '% of Total OA Uniques',
			'fieldName'  	=> 'PERCENT_OF_OA_TOTAL',
			'fieldAlias' 	=> 'PERC_OA',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'%ofPMPE'	=> [
			'view'			=> '% of Total PMPE Uniques',
			'fieldName'  	=> 'PERCENT_OF_PMPE_TOTAL',
			'fieldAlias' 	=> 'PERC_PMPE',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'%ofPMPD'	=> [
			'view'			=> '% of Total PMPD Uniques',
			'fieldName'  	=> 'PERCENT_OF_PMPD_TOTAL',
			'fieldAlias' 	=> 'PERC_PMPD',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'%ofGD'	=> [
			'view'			=> '% of Total Global Deal Uniques',
			'fieldName'  	=> 'PERCENT_OF_GD_TOTAL',
			'fieldAlias' 	=> 'PERC_GD',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
	];
	protected $from = 'UNIQUE_CROSSOVER_BY_SUPPLY_TYPE';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'total' => false,
			//,
			//'filters'		=> $filters
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
			'Date'	=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\''
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
