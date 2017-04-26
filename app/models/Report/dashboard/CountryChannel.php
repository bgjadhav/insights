<?php
class CountryChannel extends Tile
{
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName' 	=> 'MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		//~ 'ChannelId'	=> [
			//~ 'view'			=> 'Channel Id',
			//~ 'fieldName'		=> 'CHANNEL_ID',
			//~ 'fieldAlias'	=> 'CHANNEL_ID',
			//~ 'group'			=> true,
			//~ 'join'			=> false,
			//~ 'format'		=> false,
			//~ 'order'			=> false,
			//~ 'total'			=> false
		//~ ],
		'Channel'		=> [
			'view'			=> 'Channel',
			'fieldName'		=> 'CHANNEL_NAME',
			'fieldAlias'	=> 'CHANNEL_NAME',
			'group'			=> false,
			'gDependence'	=> 'CHANNEL_ID',
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		//~ 'CountryId'	=> [
			//~ 'view'			=> 'Country Id',
			//~ 'fieldName'		=> 'COUNTRY_ID',
			//~ 'fieldAlias'	=> 'COUNTRY_ID',
			//~ 'group'			=> true,
			//~ 'join'			=> false,
			//~ 'format'		=> false,
			//~ 'order'			=> false,
			//~ 'total'			=> false
		//~ ],
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'gDependence'	=> 'COUNTRY_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'Region'	=> [
			'view'			=> 'Region',
			'fieldName'		=> 'c.REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_COUNTRY_WORLD_REGION c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'COUNTRY',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'COUNTRY'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'  	=> 'sum(IMPRESSIONS)',
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
		'Spend'			=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'sum(TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
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
	protected $from = 'CHANNEL_BY_COUNTRY a';

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
			'Channel'	=> Filter::getChannelMeta(false),
			'Country'	=> Filter::getCountryImpression(),
			'Exchange'	=> Filter::getExchange(),
			'Columns'	=> [$this->getColumnView(), ['CPM', 'ExchangeId', 'Exchange']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('.Format::id($options['filters']['Exchange']).')',
			'Country'		=> 'COUNTRY_ID IN ('.Format::str($options['filters']['Country']).')',
			'Channel'		=> 'CHANNEL_ID IN ('.Format::str($options['filters']['Channel']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
