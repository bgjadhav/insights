<?php
class DomainCountryVerticals extends Tile
{
	public $col = [
		'VERTICAL'		=> [
			'view'			=> 'VERTICAL',
			'fieldName'		=> 'b.VERTICAL',
			'fieldAlias'	=> 'VERTICAL',
			'group'			=> false,
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_DOMAIN b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DOMAIN',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'DOMAIN'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'COUNTRY'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'REGION'		=> [
			'view'			=> 'REGION',
			'fieldName'		=> 'c.REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> false,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_COUNTRY c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'COUNTRY',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'COUNTRY'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DOMAIN'		=> [
			'view'			=> 'Domain',
			'fieldName'		=> 'a.DOMAIN',
			'fieldAlias'	=> 'DOMAIN',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $conn = 'domain_verticals';
	protected $from = 'DOMAIN_BY_COUNTRY_BY_WEEK a';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'total'			=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Vertical'	=> Filter::getVertical(),
			'Country'	=> Filter::getCountry(),
			'Region'	=> Filter::getDomainRegion()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Country'	=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')',
			'Region'	=> 'c.REGION IN ('.Format::str($options['filters']['Region']).')',
			'Vertical'	=> 'b.VERTICAL IN ('.Format::str($options['filters']['Vertical']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
