<?php
class RegionOpportunities extends Tile
{
	public $col = [
		'Date'			 => [
			'view'			=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'	=> [
			'view'			=> 'COUNTRY_NAME',
			'fieldName'		=> 'a.COUNTRY_NAME',
			'fieldAlias'	=> 'COUNTRY_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'REGION'		=> [
			'view'			=> 'Region Name',
			'fieldName'		=> 'b.REGION_NAME',
			'fieldAlias'	=> 'REGION_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.REGION',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_REGION b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'REGION',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'REGION_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Biddables'		=> [
			'view'			=> 'Est. Biddables',
			'fieldName'		=> '(SUM(a.AUCTIONS)*1250)+(CAST(SUBSTRING(CONV(SUBSTRING(CAST(SHA(CONCAT(REGION)) AS CHAR), 1, 16), 16, 10),-2,2) AS SIGNED))',
			'fieldAlias'	=> 'BIDDABLES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'BIDDABLE_BY_REGION a';
	protected $timeout = false;
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
			'Country'	=> Filter::getCountryImpression_name_only(),
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'Country'		=> 'a.COUNTRY_NAME IN ('.Format::str($options['filters']['Country']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
