<?php
class TopAdvertisersWithVertical extends Tile
{
	protected $col = [
		'OrganizationId'	=> [
			'view'			=> 'Organization Id',
			'fieldName'		=> 'a.ORGANIZATION_ID',
			'fieldAlias'	=> 'ORGANIZATION_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrganizationName'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'a.ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserId'		=> [
			'view'			=> 'Advertiser ID',
			'fieldName'		=> 'a.ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AdvertiserName'	=> [
			'view'			=> 'Advertiser Name',
			'fieldName'		=> 'a.ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Vertical'	=> [
			'view'			=> 'Vertical',
			'fieldName'		=> '(SELECT VERTICAL_NAME from META_ADVERTISER_VERTICAL d inner join META_VERTICAL e on (d.VERTICAL_ID = e.VERTICAL_ID) where d.ADVERTISER_ID = a.ADVERTISER_ID GROUP BY VERTICAL_NAME)',
			'fieldAlias'	=> 'VERTICAL',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'MediaCost'	=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'a.MEDIA_COST',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> 'desc',
			'total'			=> false
		]
	];
	protected $from = 'ADVERTISER_MEDIA_SPEND_30DAY a';

	public function options($filters)
	{
		return [
			'date_picker'	=>false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			//'Date' => 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\''
		];

		array_walk($this->col, [&$this, 'dataColumn']);

		//~ dd(print_r($this->buildQuery()));
		//~ die;
	}
}
