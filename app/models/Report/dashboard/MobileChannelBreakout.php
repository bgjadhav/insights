<?php
class MobileChannelBreakout extends Tile
{
	public $col = [
		'Date'			=> [
			'view'		=> 'Date',
			'fieldName'		=> 'a.MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationID'		=> [
			'view'			=> 'Organization ID',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationName'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'gDependence'	=> 'ORGANIZATION_ID',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ChannelType'		=> [
			'view'			=> 'Channel Type',
			'fieldName'		=> 'b.CHANNEL_NAME',
			'fieldAlias'	=> 'CHANNEL_NAME',
			'gDependence'	=> 'CHANNEL_TYPE',
			'group'			=> false,
			'join'					=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CHANNEL_TYPE_IMPRESSION_LOG b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'CHANNEL_TYPE',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'CHANNEL_ID'
			],
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
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'desc',
			'total'			=> true
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'MOBILE_DASHBOARD a';

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
			'Organization'		=> Filter::getOrganization(),
			'Channel'			=> Filter::getRawMobileChannel(),
			'Columns'	=> [$this->getColumnView(), ['CPM']]
		];
	}

	public function setQuery($options)
	{
		$date_str = "";
		if ($options['date_start'] == $options['date_end']) {
			$date_str = 'MM_DATE = \''.$options['date_start'].'\'';
		} else {
			$date_str = 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end']. '\'';
		}
		$this->where = [
			'Date'			=> $date_str,
			'OrganizationID'	=> 'ORGANIZATION_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Channel'	=> 'a.CHANNEL_TYPE IN ('.Format::id($options['filters']['Channel']).')'
			];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
