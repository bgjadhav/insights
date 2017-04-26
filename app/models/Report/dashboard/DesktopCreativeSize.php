<?php
class DesktopCreativeSize extends Tile
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
		'ExchangeID'		=> [
			'view'			=> 'Exchange ID',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'gDependence'	=> 'a.EXCHANGE_ID',
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
			'gDependence'	=> 'a.CHANNEL_ID',
			'group'			=> false,
			'join'					=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_CHANNEL_TYPE_IMPRESSION_LOG b',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'CHANNEL_ID',
				'joinAlias'		=> 'b',
				'fieldB'		=> 'CHANNEL_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Width'		=> [
			'view'			=> 'Width',
			'fieldName'		=> 'a.WIDTH',
			'fieldAlias'	=> 'Width',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Height'		=> [
			'view'			=> 'Height',
			'fieldName'		=> 'a.HEIGHT',
			'fieldAlias'	=> 'Height',
			'group'			=> true,
			'join' 			=> false,
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
	protected $from = 'CHANNEL_CREATIVE_SIZE a';

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
			'Exchanges'			=> 	Filter::getExchange(),
			//'Channel'			=> Filter::getRawDesktopDisplayChannel(),
			'Columns'			=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Channel'	=> 'a.CHANNEL_ID IN (1)'
			];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
