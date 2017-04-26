<?php
class OABucketsStatus extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'mm_date',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Bucket'		=> [
			'view'			=> 'Bucket',
			'fieldName'		=> 'bucket_name',
			'fieldAlias'	=> 'Bucket',
			'group'			=> true,
			'gDepende'		=> 'bucket_id',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'status'		=> [
			'view'			=> 'Status',
			'fieldName'		=> 'status',
			'fieldAlias'	=> 'status',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Time_updated'		=> [
			'view'			=> 'Time Updated',
			'fieldName'		=> 'time_updated',
			'fieldAlias'	=> 'time_updated',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $sumTotal = false;
	protected $conn = 'update_process';
	protected $from = 'data_platform_buckets_status';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Bucket'	=> Filter::bucket(),
			'Status'	=> Filter::bucketStatus(),
			'Columns'	=> $this->getColumnView()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'		=> ' mm_date  >= \''.$options['date_start'].'\' '
							.' AND mm_date <= \''.$options['date_end'].'\'',
			'Bucket'	=> 'bucket_id IN ('
							.Format::id($options['filters']['Bucket']).')',
			'Status'	=> 'status IN ('
							.Format::str($options['filters']['Status']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
