<?php
class ChannelByClient extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'OrganizationID'		=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'OrganizationID',
			'gDependence'	=> false,
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'OrganizationNAME'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'OrganizationNAME',
			'gDependence'	=> 'a.ORGANIZATION_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'Region'		=> [
			'view'			=> 'Region',
			'fieldName'		=> 'a.REGION',
			'fieldAlias'	=> 'Region',
			'gDependence'	=> 'false',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'Channel'		=> [
			'view'			=> 'Channel',
			'fieldName'		=> 'c.CHANNEL_NAME',
			'fieldAlias'	=> 'CHANNEL_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.CHANNEL_TYPE',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CHANNEL_TYPE_IMPRESSION_LOG c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'CHANNEL_TYPE',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'CHANNEL_ID'
			],
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'  	=> 'sum(a.IMPRESSIONS)',
			'fieldAlias' 	=> 'IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'EstBilledSpend'	=> [
			'view'			=> 'Est. Billed Spend',
			'fieldName'		=> 'sum(a.BILLED_SPEND)',
			'fieldAlias'	=> 'BILLED_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Spend'			=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'sum(a.TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'CSO_DASHBOARD a';

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
			'Organization' => Filter::getOrganization(),
			'Region' => Filter::getOrgCountry(),
			'Channel' => Filter::getChannelMeta(false),
			'Columns' => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date' => 'MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'OrganizationID' => 'ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Channel' => 'CHANNEL_ID IN ('.Format::id($options['filters']['Channel']).')',
			'Region' => 'REGION IN ('.Format::str($options['filters']['Region']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);

		//dd(print_r($this->buildQuery()));
		//die;
	}
}
