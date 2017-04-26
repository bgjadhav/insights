<?php
class GoalBrainUsage extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'X',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationID'=> [
			'view'			=> 'Organization ID',
			'fieldName'  	=> 'ORGANIZATION_ID',
			'fieldAlias' 	=> 'ORGANIZATION_ID',
			'group' 	 	=> true,
			'join'	 	 	=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Organization'	=> [
			'view'			=> 'Organization',
			'fieldName'  	=> 'ORGANIZATION_NAME',
			'fieldAlias' 	=> 'ORGANIZATION_NAME',
			'group' 	 	=> false,
			'gDependence'	=> 'ORGANIZATION_ID',
			'join'	 	 	=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AgencyID'		=> [
			'view'			=> 'Agency ID',
			'fieldName'		=> 'AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Agency'		=> [
			'view'			=> 'Agency',
			'fieldName'		=> 'AGENCY_NAME',
			'fieldAlias'	=> 'AGENCY_NAME',
			'group'			=> false,
			'gDependence'	=> 'AGENCY_ID',
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Region'		=> [
			'view'			=> 'Region',
			'fieldName'		=> 'REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'GoalType'		=> [
			'view'			=> 'Goal Type',
			'fieldName'		=> 'GOAL_TYPE',
			'fieldAlias'	=> 'GOAL_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'BrainUsage'	=> [
			'view'			=> 'Brain Usage',
			'fieldName'		=> 'BRAIN_USAGE',
			'fieldAlias'	=> 'TITLE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Count'	=> [
			'view'			=> 'Count',
			'fieldName'		=> 'sum(COUNT_)',
			'fieldAlias'	=> 'Y',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Spend'		=> [
			'view'			=> 'Spend',
			'fieldName'		=> 'sum(SPEND)',
			'fieldAlias'	=> 'Y1',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'GOAL_BRAIN_USAGE';
	protected $categories =  ['Count', 'Spend'];
	protected $twoCharts =  true;
	protected $scale =  'percentage';
	protected $format_Y = 'toPercentage';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(4),
				'end'	=> Format::datePicker()
			],
			'type'			=> ['table', 'chart-line'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Region'		=> Filter::getBusinessUnitBrain(),
			'Columns'		=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'	=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\'',
			'Region'=> 'REGION IN ('.Format::str($options['filters']['Region']).')',
		];

		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}

	public function getDataLine($default = [])
	{
		$data	= parent::getDataLine($default = []);

		$data['text'] = [];

		array_walk($data['data'], function(&$charts, $chart) use(&$data) {
			array_walk($charts, function(&$line) use(&$data, $chart) {
				$total = array_sum($line['data']);
				array_walk($line['data'], function(&$val, $i) use($total, &$data, $chart, $line) {
					$origin = $chart > 0 ? number_format($val, 2, '.', ',') : number_format($val);
					$pref = $chart > 0 ? '$' : '';
					$val = ($val*100)/$total;
					$data['text'][$chart][$line['name']][(string)$val] = ' total: <b>'.sprintf("%.2f%%", $val).'</b> ('.$pref.$origin.')';
				});
			});
		});

		return $data;
	}
}
