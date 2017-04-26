<?php
class EAMTopOrganizationEMEA extends Tile
{
	public $col = [
		'Organization' => [
			'view'			=> 'Organization',
			'fieldName'		=> 'ORGANIZATION_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
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
		'PMP'		=> [
			'view'			=> 'PMP',
			'fieldName'		=> 'LAST_WEEK_PMP',
			'fieldAlias'	=> 'LAST_WEEK_PMP',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'DR'		=> [
			'view'			=> 'DR',
			'fieldName'		=> 'LAST_WEEK_DIRECT_REVENUE',
			'fieldAlias'	=> 'LAST_WEEK_DIRECT_REVENUE',
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
		],
		'TPMP'		=> [
			'view'			=> 'TPMP',
			'fieldName'		=> 'THIS_WEEK_PMP',
			'fieldAlias'	=> 'THIS_WEEK_PMP',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> false
		],
		'TDR'		=> [
			'view'			=> 'TDR',
			'fieldName'		=> 'THIS_WEEK_DIRECT_REVENUE',
			'fieldAlias'	=> 'THIS_WEEK_DIRECT_REVENUE',
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

	protected $from = 'CSO_DASHBOARD_TOPLINE_BY_ORG';

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
