<?php
class EAMTopAdvertiser extends Tile
{
	public $col = [
		'Country' => [
			'view'			=> 'Country',
			'fieldName'		=> 'COUNTRY_NAME',
			'fieldAlias'	=> 'COUNTRY_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Advertiser'	=> [
			'view'			=> 'Advertiser',
			'fieldName'		=> 'ADVERTISER_NAME',
			'fieldAlias'	=> 'ADVERTISER_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'P1'		=> [
			'view'			=> 'P1',
			'fieldName'		=> 'LAST_WEEK_TOTAL_SPEND',
			'fieldAlias'	=> 'LAST_WEEK_TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'P2'		=> [
			'view'			=> 'P2',
			'fieldName'		=> 'THIS_WEEK_TOTAL_SPEND',
			'fieldAlias'	=> 'THIS_WEEK_TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'WoW'		=> [
			'view'			=> 'WoW',
			'fieldName'		=> '% Change',
			'fieldAlias'	=> 'WoW',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> false
		]
	];

	protected $from = 'CSO_DASHBOARD_TOP_ADVERTISERS_COUNTRY';

	public function options($filters)
	{
		return [
			'date_picker' => false
		];
	}

	public function filters()
	{
		return [];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Group' => 'GROUP_ID = '. $options['filters']['GROUP_ID']
		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
