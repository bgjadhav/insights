<?php
class AkamaiAdroitBatchProcessingLogs extends Tile
{
	public $col = [
		'AMBER_ODIN'	=> [
			'view'			=> 'Akamai/Adroit',
			'fieldName'		=> 'AMBER_ODIN',
			'fieldAlias'	=> 'AKAMAI_ADROIT',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'LOG_GROUP_ID'	=> [
			'view'			=> 'Log Group ID',
			'fieldName'		=> 'LOG_GROUP_ID',
			'fieldAlias'	=> 'LOG_GROUP_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ADVERTISER_ID'	=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CUSTOMER_ID'	=> [
			'view'			=> 'Customer ID',
			'fieldName'		=> 'CUSTOMER_ID',
			'fieldAlias'	=> 'CUSTOMER_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CUSTOMER_NAME'	=> [
			'view'			=> 'Customer Name',
			'fieldName'		=> 'CUSTOMER_NAME',
			'fieldAlias'	=> 'CUSTOMER_NAME',
			'group'			=> false,
			'gDependence'	=> 'CUSTOMER_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CPCODE'		=> [
			'view'			=> 'CP Code',
			'fieldName'		=> 'CPCODE',
			'fieldAlias'	=> 'CPCODE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'LINES'			=> [
			'view'			=> 'Lines Processed',
			'fieldName'		=> 'LINES_PROCESSED',
			'fieldName'		=> 'SUM(LINES_PROCESSED)',
			'fieldAlias'	=> 'LINES_PROCESSED',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'AMBER_ODIN_DATA';

	public function options($filters)
	{
		return [
			'date_picker'		=> false,
			'filters'			=> $filters
		];
	}

	public function filters()
	{
		return [
			'Columns' => $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
