<?php
return [
	'OPEN Analytics' => [
		'role'		=> 1,
		'permisions'=> [
			'OPEN',
			'MediaMath',
			'EXEC',
			'Financial',
			'Restricted'
		]
	],
	'OPEN' => [
		'role'		=> 2,
		'permisions'=> [
			'MediaMath',
			'Restricted'
		]
	],
	'MediaMath' => [
		'role'		=> 3,
		'permisions'=> []
	],
	'EXEC' => [
		'role'		=> 4,
		'permisions'=> [
			'MediaMath',
			'Financial',
			'Restricted'
		]
	]
];
