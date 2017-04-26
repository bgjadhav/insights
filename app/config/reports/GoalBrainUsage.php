<?php
return [
	'columns' => [] ,
	'graphs' => [
		'chart-pie' => [
			'values'	=> [
				'TestOne'	=> [
					'view'		=> 'Count',
					'columns'	=> [
						'Date'			=> ['fieldAlias' => 'DATA'],
						'BrainUsage'	=> ['fieldAlias' => 'TITLE'],
						'Count'			=> ['fieldAlias' => 'Y'],
						],
					'text'		=> ['Count']
				],
				'TestTwo'	=> [
					'view'		=> 'Spend',
					'columns'	=> [
						'Date'			=> ['fieldAlias' => 'DATA'],
						'BrainUsage'	=> ['fieldAlias' => 'TITLE'],
						'Spend'			=> ['fieldAlias' => 'Y'],
						'Count'			=> ['fieldAlias' => 'Y1'],
						],
					'text'		=> ['Spend']
				]
			]
		]
	]
];
