<?php
class VerticalByCountry extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VerticalID'=> [
			'view'			=> 'Vertical ID',
			'fieldName'		=> 'a.VERTICAL_ID',
			'fieldAlias'	=> 'VERTICAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Vertical'		=> [
			'view'			=> 'Vertical',
			'fieldName'		=> 'b.VERTICAL_NAME',
			'fieldAlias'	=> 'VERTICAL',
			'group'			=> false,
			'gDependence'	=> 'a.VERTICAL_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_VERTICAL b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'VERTICAL_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'VERTICAL_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'=> [
			'view'			=> 'Country',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'Country',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'	=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'DESC',
			'total'			=> true
		]
	];
	protected $from = 'VERTICAL_BY_COUNTRY a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Vertical'  => Filter::getVertical_by_id(),
			'Country'	=> Filter::getCountryImpression_name_only(),
			'Columns'   => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'				=> 'a.MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'Vertical'	=> 'a.VERTICAL_ID IN ('
				.Format::id($options['filters']['Vertical']).')',
			'Country'		=> 'COUNTRY IN ('
				.Format::str($options['filters']['Country']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
