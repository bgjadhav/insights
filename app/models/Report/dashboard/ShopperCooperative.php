<?php
class ShopperCooperative extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationID'=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Organization'	=> [
			'view'			=> 'Organization',
			'fieldName'		=> 'ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'ORGANIZATION_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'StrategyID'	=> [
			'view'			=> 'Strategy ID',
			'fieldName'		=> 'STRATEGY_ID',
			'fieldAlias'	=> 'STRATEGY_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Segments'	=> [
			'view'			=> 'Segment Ids',
			'fieldName'		=> 'SEGMENTS_IDS',
			'fieldAlias'	=> 'SEGMENTS_IDS',
			'group'			=> false,
			'gDependence'	=> 'AUDIENCE_VENDOR_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'SHOPPER_COOPERATIVE';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters,
		];
	}

	public function filters()
	{
		return [
			'Organization' => Filter::getOrganization(),
			'Columns'   => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'MM_DATE  >= \''
				.$options['date_start'].'\' AND MM_DATE <= \''
				.$options['date_end'].'\'',
			'OrganizationID'	=> 'ORGANIZATION_ID IN ('
				.Format::id($options['filters']['Organization']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
