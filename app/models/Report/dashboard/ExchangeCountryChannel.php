<?php
class ExchangeCountryChannel extends Tile
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
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
		],
		'Channel'		=> [
			'view'			=> 'Channel',
			'fieldName'		=> 'CHANNEL_TYPE',
			'fieldAlias'	=> 'CHANNEL_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
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
		],/*
		'Clicks'		=> [
			'view'			=> 'Clicks',
			'fieldName'		=> 'sum(CLICKS)',
			'fieldAlias'	=> 'CLICKS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number'
								),
		'PC'			=> [
			'view'			=> 'PC Conversion',
			'fieldName'		=> 'sum(PC_ACTIVITIES)',
			'fieldAlias'	=> 'PC_CONVERSION',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number'
								),
		'PV'			=> [
			'view'			=> 'PV Conversion',
			'fieldName'		=> 'sum(PV_ACTIVITIES)',
			'fieldAlias'	=> 'PV_CONVERSION',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number'
								),*/
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
		],/*
		'CPA'			=> [
			'view'			=> 'CPA',
			'fieldName'		=> 'CASE WHEN sum(PC_ACTIVITIES)+sum(PV_ACTIVITIES) > 0 THEN (sum(MEDIA_COST)/(sum(PC_ACTIVITIES)+sum(PV_ACTIVITIES))) ELSE 0.00 END',
			'fieldAlias'	=> 'CPA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money'
								),
		'CPC'			=> [
			'view'			=> 'CPC',
			'fieldName'		=> 'CASE WHEN sum(CLICKS) > 0 THEN(sum(MEDIA_COST)/sum(CLICKS)) ELSE 0.00 END',
			'fieldAlias'	=> 'CPC',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money'
								),
		'CTR'			=> [
			'view'			=> 'CTR',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0 THEN (sum(CLICKS)/sum(IMPRESSIONS))*100 ELSE 0 END',
			'fieldAlias'	=> 'CTR',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5'
								)*/
	];
	protected $from = 'CHANNEL_TYPE_BY_COUNTRY';

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
			'Exchanges'	=> Filter::getExchange(),
			'Country'	=> Filter::getCountryImpression_name_only(),
			'Channel(*)'=> Filter::getChannel(false),
			//'Columns'	=>  [$this->getColumnView(), ['CPM','CPA','CPC','CTR'))
			'Columns'	=>  [$this->getColumnView(), ['CPM']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Country'		=> 'COUNTRY IN ('.Format::str($options['filters']['Country']).')',
			'Channel'		=> 'CHANNEL_TYPE IN ('.Format::str($options['filters']['Channel(*)']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
