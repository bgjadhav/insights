<?php
return [
	'columns' => [] ,
	'graphs' => [
		//~ 'chart-column' => [
			//~ 'values'	=> [
				//~ 'TestOne'	=> [
					//~ 'view'		=> 'Aggregate',
					//~ 'columns'	=> [],
					//~ 'categories'=> [],
					//~ 'text'		=> ['Rank', ' eCPA']
				//~ ],
				//~ 'TestTwo'	=> [
					//~ 'view'		=> 'Exchange Rank',
					//~ 'columns'	=> [
						//~ 'Aggregate'		=> ['fieldAlias' => 'X'],
						//~ 'Exchange'		=> ['fieldAlias' => 'TITLE'],
						//~ 'CPA'			=> ['fieldAlias' => 'CPA'],
						//~ 'Rank'			=> ['fieldAlias' => 'Y']
									//~ ],
					//~ 'categories'=> [],
					//~ 'text'		=> [
						//~ 'Aggregate Day',
						//~ ' Rank'
					//~ ]
				//~ ],
				//~ 'TestThree'	=> [
					//~ 'view'		=> 'Exchange eCPA',
					//~ 'columns'	=> [
						//~ 'Aggregate'		=> ['fieldAlias' => 'X'],
						//~ 'Exchange'		=> ['fieldAlias' => 'TITLE'],
						//~ 'CPA'			=> ['fieldAlias' => 'Y'],
						//~ 'Rank'			=> ['fieldAlias' => 'Rank']
									//~ ],
					//~ 'categories'=> [],
					//~ 'text'		=> [
						//~ 'Aggregate Day',
						//~ ' Rank'
					//~ ]
				//~ ],
			//~ ]
		//~ ],
		'chart-pie' => [
			'values'	=> [
				'TestOne'	=> [
					'view'		=> 'eCPA',
					'columns'	=> [
						'Aggregate'		=> ['fieldAlias' => 'DATA'],
						'Exchange'		=> ['fieldAlias' => 'TITLE'],
						'CPA'			=> ['fieldAlias' => 'Y'],
						'Rank'			=> ['fieldAlias' => 'Rank']
						],
					'text'		=> ['eCPA']
				],
				'TestTwo'	=> [
					'view'		=> 'RANK',
					'columns'	=> [
						'Aggregate'		=> ['fieldAlias' => 'DATA'],
						'Exchange'		=> ['fieldAlias' => 'TITLE'],
						'Rank'			=> ['fieldAlias' => 'Y'],
						'CPA'			=> ['fieldAlias' => 'Rank']
						],
					'text'		=> ['eCPA']
				],
			]
		],
		'chart-area' => [
			'values'	=> [
				'TestOne'	=> [
					'view'		=> 'Exchange',
					'categories'=> [],
					'typeCategorie' => 'date',
					'columns'	=> [
						'Aggregate'	=> ['fieldName'	=> 'AGGREGATE_DATE']
					],
					'text'		=> ['eCPA: <b>{point.y}</b>']
				],
				//~ 'TestTwo'	=> [
					//~ 'view'		=> 'Aggregate',
					//~ 'categories'=> [],
					//~ 'columns'	=> [
						//~ 'Aggregate'	=> ['fieldAlias' => 'TITLE'],
						//~ 'Exchange'	=> ['fieldAlias' => 'CATEGORY'],
						//~ 'CPA'		=> ['fieldAlias' => 'DATA'],
						//~ 'Rank'		=> ['fieldAlias' => 'Rank']
					//~ ],
					//~ 'text'		=> ['eCPA: <b>{point.y}</b>']
				//~ ],
			]
		]
	]
];
