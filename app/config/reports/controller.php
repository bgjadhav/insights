<?php
return [
	'methods' => [
		'table'			=> 'getDataTablePagination',
		'export'		=> 'getDataTable',
		'download'		=> 'getDataDownload',
		'chart-area'	=> 'getDataArea',
		'chart-column'	=> 'getDataColumn',
		'small-table'	=> 'getSmallTable',
		'chart-pie'		=> 'getDataPie',
		'chart-line'	=> 'getDataLine',
		'mix'			=> 'getDataMix',
		true			=> 'error'
	],
	'options' => [
		'type'				=> ['table'],
		'date_picker'		=> true,
		'range_selector'	=> false,
		'column_selector'	=> false,
		'optionType'		=> [],
		'search'			=> true,
		'scrollY' 			=> true,
		'hide'				=> [],
		'pagination'		=> true,
		'download'			=> true,
		'total'				=> true,
		'group'				=> 0,
		'filters'			=> false,
		'uniqueFilter'		=> []
	]
];
