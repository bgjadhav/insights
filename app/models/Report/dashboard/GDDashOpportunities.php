<?php
class GDDashOpportunities extends Tile
{
	public  $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealID'		=> [
			'view'			=> 'Deal ID',
			'fieldName'		=> 'a.DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'		=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'c.NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'gDependence'	=> 'a.DEAL_ID',
			'group'			=> false,
			'join' 				=> [
				'type'		=> 'LEFT',
				'tableName'		=> 'META_DEALS c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExternalID'		=> [
			'view'			=> 'External ID',
			'fieldName'		=> 'a.EXTERNAL_ID',
			'fieldAlias'	=> 'EXTERNAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PublisherID'		=> [
			'view'			=> 'Publisher ID',
			'fieldName'		=> 'a.PUBLISHER_ID',
			'fieldAlias'	=> 'PUBLISHER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Width'		=> [
			'view'			=> 'Width',
			'fieldName'		=> 'a.WIDTH',
			'fieldAlias'	=> 'WIDTH',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Height'		=> [
			'view'			=> 'Height',
			'fieldName'		=> 'a.HEIGHT',
			'fieldAlias'	=> 'HEIGHT',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Opportunities'		=> [
			'view'			=> 'Est. Opportunities',
			'fieldName'		=> '(SUM(a.AUCTIONS)*1250)+(CAST(SUBSTRING(CONV(SUBSTRING(CAST(SHA(CONCAT(MM_DATE,EXTERNAL_ID)) AS CHAR), 1, 16), 16, 10),-2,2) AS SIGNED))*24',
			'fieldAlias'	=> 'OPPORTUNITIES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'GD_DASH_OPPORTUNITIES a';

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
		//var_dump($this->getColumnView());exit;

		return [
			//'Exchange'  => Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		//var_dump($options['filters']['Columns']);
		
		//var_dump($options['filters']['Columns']);
	
		
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\''
			//'Exchange'		=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchange']).')'
		];
		
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
?>