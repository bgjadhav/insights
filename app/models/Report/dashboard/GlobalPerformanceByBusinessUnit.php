<?php
class GlobalPerformanceByBusinessUnit extends Tile
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
		'Geo'			=> [
			'view'			=> 'Geo',
			'fieldName'		=> 'GEO',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Region'		=> [
			'view'			=> 'Region',
			'fieldName'		=> 'REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'BusinessUnit'	=> [
			'view'			=> 'Business Unit',
			'fieldName'		=> 'BUSINESS_UNIT',
			'fieldAlias'	=> 'BUSINESS_UNIT',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(IMPRESSIONS)',
			'fieldAlias' 	=> 'IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0 THEN (sum(MEDIA_COST)/sum(IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'COUNTRY_BUSSINES_UNIT';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Country'	=> Filter::getGeo(),
			'Region'	=> Filter::getRegion(),
			'Columns'	=> [$this->getColumnView(), ['CPM']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Country'	=> 'GEO IN ('.Format::str($options['filters']['Country']).')',
			'Region'	=> 'REGION IN ('.Format::str($options['filters']['Region']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
