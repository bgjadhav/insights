<?php
class DealDailyHealthCheck extends Tile
{
	public $col = [
		'DealType'			 => [
			'view'			=> 'Deal Type',
			'fieldName'		=> 'a.DEALTYPE',
			'fieldAlias'	=> 'DEAL_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealID'			 => [
			'view'			=> 'Deal ID',
			'fieldName'		=> 'a.DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'			 => [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'a.DEAL_NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealExternalID'		=> [
			'view'			=> 'Deal External ID',
			'fieldName'		=> 'a.EXTERNAL_DEAL_ID',
			'fieldAlias'	=> 'EXTERNAL_DEAL_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Price'	=> [
			'view'			=> 'Price',
			'fieldName'		=> 'a.PRICE',
			'fieldAlias'	=> 'PRICE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'StartDate'			 => [
			'view'			=> 'Start Date',
			'fieldName'		=> 'a.STARTDATE',
			'fieldAlias'	=> 'STARTDATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'MediaCost2Days'	=> [
			'view'			=> 'Media Cost 2 days ago',
			'fieldName'		=> 'a.MEDIA_COST_2DAYSAGO',
			'fieldAlias'	=> 'MEDIA_COST_2DAYSAGO',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		/*'WinRate2Days'	=> [
			'view'			=> 'Win Rate 2 days ago',
			'fieldName'		=> 'COALESCE(SELECT BIDS FROM BIDS_BY_DEAL b WHERE a.DEAL_ID = b.DEAL_ID and b.MM_DATE = CURRENT_DATE - interval 2 day limit 1)))*100,0.00)',
			'fieldAlias'	=> 'WIN_RATE_2DAYSAGO',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],*/
		'MediaCost1Days'	=> [
			'view'			=> 'Media Cost yesterday',
			'fieldName'		=> 'a.MEDIA_COST_YESTERDAY',
			'fieldAlias'	=> 'MEDIA_COST_YESTERDAY',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		/*'WinRate1Days'	=> [
			'view'			=> 'Win Rate yesterday',
			'fieldName'		=> 'COALESCE((a.IMPRESSIONS_YESTERDAY/((SELECT (b.AUCTIONS*1250)+(CAST(SUBSTRING(CONV(SUBSTRING(CAST(SHA(CONCAT(EXTERNAL_ID)) AS CHAR), 1, 16), 16, 10),-2,2) AS SIGNED)) FROM BIDDABLE_BY_DEALS b WHERE a.EXTERNAL_DEAL_ID = b.EXTERNAL_ID and b.MM_DATE = CURRENT_DATE - interval 1 day limit 1)))*100,0.00)',
			'fieldAlias'	=> 'WIN_RATE_YESTERDAY',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],*/
		'PercentChange'	=> [
			'view'			=> 'Change',
			'fieldName'		=> '(100-(a.PERCENT_CHANGE*100))*-1',
			'fieldAlias'	=> 'PERCENT_CHANGE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage2',
			'order'			=> false,
			'total'			=> false
		],
		'Flag'	=> [
			'view'			=> 'STATUS',
			'fieldName'		=> 'a.FLAG',
			'fieldAlias'	=> 'FLAG',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'DEALS_DAILY_HEALTHCHECK_V2 a';
	protected $timeout = false;
	public function options($filters)
	{
		return [
			'date_picker'	=> [
				//'start'	=> Format::datePicker(1),
				//'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'DealType'	=> Filter::getDealHealthType(),
			'Status' 	=> Filter::getDealHealthStatus() 
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'DealType'		=> 'a.DEALTYPE IN ('.Format::str($options['filters']['DealType']).')',
			'Status'		=> 'a.FLAG IN ('.Format::str($options['filters']['Status']).')',

		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
