<?php

return [

	'widget' => [
		'success'	=> true,
		'reverse'	=> true,
		'title'		=> '<span style="font-weight: bold !important;">Unique Deals <span class="uniqueSuply">By Supply</span></span>',
		'type'		=> 'table-compare',
		'noSwitch'	=> true,
		'filters' 	=> [
			'Date'		=> [
				'data' => [
				],
				'checked' => 'OnlyOne',
				'type'    => 'button',
			]
		],
		'addTextAdditional'	=> [
			'ACTIVE_DEALS'	=> 'UNIQUES',
			'IMPRESSIONS'	=> 'IMPRESSSIONS',
			'CLICKS'		=> 'CLICKS',
			'CONVERISONS'	=> 'CONVERSIONS',
			'MEDIA_COST'	=> 'MEDIA COST'
		],
		'info'		=> 'Showing info by month.',
		'nototal'	=> true,
		'addSpan'	=> true,
		'table' => [
			'thead' => false,
			'headings' => [
				'',
				'UNIQUES',
				'IMPRESSSIONS',
				'CLICKS',
				'CONVERSIONS',
				'MEDIA COST'
			],
			'columns' => [
				0 => [
					'bold' => true,
					'size' => '16px',
					'align' => 'right'
					],
				1 => [
					'bold' => true,
					'align' => 'left'
					],
				2 => [
					'bold' => true,
					'align' => 'left'
					],
				3 => [
					'bold' => true,
					'align' => 'left'
					],
				4 => [
					'bold' => true,
					'align' => 'left'
					],
				5 => [
					'bold' => true,
					'align' => 'left'
					]
				]
		],
		'format' => [
			'ACTIVE_DEALS'	=> 'number',
			'IMPRESSIONS'	=> 'number',
			'CLICKS'		=> 'number',
			'CONVERISONS'	=> 'number',
			'MEDIA_COST'	=> 'money'
		],
		'data'		=> []
	],

	'db' => [
		'conn' => 'analytics',

		'sql' => 'SELECT %fields'
				.' FROM ACTIVE_DEALS_BY_DAY'
				.' WHERE MONTH=%month'
				.' AND YEAR=%year'
				.' AND REGION <>\'Global\''
				.' ORDER BY DEAL_TYPE, REGION',

		'field' => [
			'main' => 'REGION',
			'base' => [
				'DEAL_TYPE'		=> 0,
				'ACTIVE_DEALS'	=> 0,
				'IMPRESSIONS'	=> 0,
				'CLICKS'		=> 0,
				'CONVERISONS'	=> 0,
				'MEDIA_COST'	=> 0
			]
		],

		'date' => 'SELECT MONTH, YEAR'
				.' FROM ACTIVE_DEALS_BY_DAY'
				.' GROUP BY MONTH, YEAR'
				.' ORDER BY MONTH, YEAR'
	],
];
