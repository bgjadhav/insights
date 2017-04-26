<?php
class OpenMarketplaceVsPMPVsRemnant extends Tile
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
		'ExchangeId'		=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join' 			=> false,
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
			'order'			=> false,
			'total'			=> false
		],
		'PDImpressions'	=> [
			'view'			=> 'PMP-D Impressions',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'PMP-D\', `IMPRESSIONS`, 0))',
			'fieldAlias'	=> 'PMP_D_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'PDMediaCost'	=> [
			'view'			=> 'PMP-D Media Cost',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'PMP-D\', `MEDIA_COST`, 0))',
			'fieldAlias'	=> 'PMP_D_MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'PEImpressions'	=> [
			'view'			=> 'PMP-E Impressions',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'PMP-E\', `IMPRESSIONS`, 0))',
			'fieldAlias'	=> 'PMP_E_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'PEMediaCost'	=> [
			'view'			=> 'PMP-E Media Cost',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'PMP-E\', `MEDIA_COST`, 0))',
			'fieldAlias'	=> 'PMP_E_MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'OImpressions'	=> [
			'view'			=> 'Open Marketplace Impressions',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'Priv Exch\', `IMPRESSIONS`, 0))',
			'fieldAlias'	=> 'OPEN_MARKETPLACE_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'OMediaCost'	=> [
			'view'			=> 'Open Marketplace Media Cost',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'Priv Exch\', `MEDIA_COST`, 0))',
			'fieldAlias'	=> 'OPEN_MARKETPLACE_MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'RImpressions'	=> [
			'view'			=> 'Remnant Impressions',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'Remnant\', `IMPRESSIONS`, 0))',
			'fieldAlias'	=> 'REMNANT_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'RMediaCost'	=> [
			'view'			=> 'Remnant Media Cost',
			'fieldName'		=> 'SUM(IF(PRIV_TYPE = \'Remnant\', `MEDIA_COST`, 0))',
			'fieldAlias'	=> 'REMNANT_MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'PRIVILEGED_SUPPLY';

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
			'Exchanges' => Filter::getExchange(),
			'Supply_Type' => Filter::getOPENSupplyType(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'       => 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId' => 'EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Supply Type' => 'PRIV_TYPE IN ('.Format::str($options['filters']['Supply_Type']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
