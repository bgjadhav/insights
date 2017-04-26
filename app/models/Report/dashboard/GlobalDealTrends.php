<?php
class GlobalDealTrends extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'MM_DATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'EXCHANGE_NAME',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'EXCHANGE_ID',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias' 	=> 'EXCHANGE_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Status'		=> [
			'view'			=> 'STATUS',
			'fieldName'		=> 'STATUS',
			'fieldAlias'	=> 'STATUS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Data'			=> [
			'view'			=> 'MEDIA_COST',
			'fieldName'		=> 'DATA',
			'fieldAlias'	=> 'DATA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Critic'		=> [
			'view'			=> 'DISCREPANCIES',
			'fieldName'		=> 'CRITIC',
			'fieldAlias'	=> 'LABEL',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $timeout = false;
	protected $from = 'EXCHANGE_DEAL_TEN_DAY_TRENDS';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'pagination'	=> false,
			'download'		=> false,
			'total'			=> false,
			'type'			=> ['chart-area']
		];
	}

	public function filters()
	{
		return false;
	}

	public function data()
	{
		$results = parent::data();
		$data = [];
		foreach ($results as $key => $result) {
			$data[$result->EXCHANGE_NAME] = [
				'title'  => $result->{'EXCHANGE_NAME'},
				'status' => $result->{'STATUS'},
				'critic' => $result->{'CRITIC'},
				'data'   => unserialize($result->{'DATA'})
			];
		}
		return $data;
	}

	public function setQuery($options)
	{
		array_walk($this->col, [&$this, 'dataColumn']);
		$this->where = [
			'Date'			=> 'MM_DATE = CURDATE() - INTERVAL 1 DAY',
			'ExchangeId'	=> 'EXCHANGE_NAME > \'\''
		];
	}

	public function getDataArea($default =[])
	{
		$results = parent::data();
		$categories = [];
		for ($i=9; $i>=0; $i--) {
			$categories[] = date(
				'j M',
				strtotime('-'.$i.' days', strtotime('yesterday'))
			);
		}

		$data = [
			'data'	=> [],
			'texts' => ['Spend($): <b>{point.y}</b>'],
			'categories' => []
		];

		foreach ($results as $key => $result) {
			$id = preg_replace('/[^a-zA-Z0-9]+/', '', $result->{'TITLE'});
			$values = unserialize($result->{'DATA'});

			$data['data'][$id]['title']	= $result->{'TITLE'};
			$data['data'][$id]['status']= $result->{'STATUS'};
			$data['data'][$id]['critic']= $result->{'LABEL'};
			$data['data'][$id]['data'] = $values;
			$data['categories'][$id] = $categories;
		}
		return $data;
	}
}
