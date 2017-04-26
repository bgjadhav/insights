<?php
class EAMToplineByRegion extends Tile
{
	public $col = [
		'Region' => [
			'view'			=> 'Region',
			'fieldName'		=> 'REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Channel' => [
			'view'			=> 'Channel',
			'fieldName'		=> 'CHANNEL_TYPE',
			'fieldAlias'	=> 'CHANNEL_TYPE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'TS'		=> [
			'view'			=> 'TS',
			'fieldName'		=> 'LAST_WEEK_TOTAL_SPEND',
			'fieldAlias'	=> 'LAST_WEEK_TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'TTS'		=> [
			'view'			=> 'TTS',
			'fieldName'		=> 'THIS_WEEK_TOTAL_SPEND',
			'fieldAlias'	=> 'THIS_WEEK_TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		]
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
