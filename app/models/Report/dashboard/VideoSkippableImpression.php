<?php
class VideoSkippableImpression extends Tile
{
	protected $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'MM_DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'DESC',
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.exch_name',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'b.exch_name',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationId'	=> [
			'view'		=> 'Organization Id',
			'fieldName'		=> 'c.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_CAMPAIGN c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'CAMPAIGN_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CAMPAIGN_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Organization'	=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'c.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
								'gDependence'	=> 'c.ORGANIZATION_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_CAMPAIGN c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'CAMPAIGN_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CAMPAIGN_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserId'	=> [
			'view'			=> 'Advertiser Id',
			'fieldName'		=> 'c.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_CAMPAIGN c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'CAMPAIGN_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CAMPAIGN_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Advertiser'	=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'c.ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
								'gDependence'	=> 'c.ADVERTISER_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_CAMPAIGN c',
				'tableAlias'	=> 'c',
				'fieldA'		=> 'CAMPAIGN_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CAMPAIGN_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CreativeId'	=> [
			'view'			=> 'Creative Id',
			'fieldName'		=> 'a.CREATIVE_ID',
			'fieldAlias'	=> 'CREATIVE_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Skippable'			=> [
			'view'			=> 'Skippable Impression',
			'fieldName'  	=> 'sum(a.SKIPPABLEIMPRESSION_COUNT)',
			'fieldAlias' 	=> 'SKIPPABLEIMPRESSION_COUNT',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> 'DESC',
			'total'			=> true
		]
	];
	protected $from = 'VIDEO_AGGREGATED_DATA a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(3),
				'end'	=> Format::datePicker()
			],
			'type'			=> ['table','chart-line'],
			'hide' 			=> ['chart-line' => ['Exchanges']],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges'	=> Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}

	public function getDataLine($default =[])
	{
		$this->field = ['a.MM_DATE as X', 'b.exch_name as TITLE', 'sum(a.SKIPPABLEIMPRESSION_COUNT) as Y'];
		$this->where = ['Date' =>$this->where['Date']];
		$this->group = ['b.exch_name', 'a.MM_DATE'];
		$this->order = '';
		$this->limit = '';
		return parent::getDataLine();
	}
}
