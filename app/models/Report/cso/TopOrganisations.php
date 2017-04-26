<?php
class TopOrganisations extends Tile
{
	public $col = [
		'Name'			 => [
			'view'		=> 'Name',
			'fieldName'		=> 'CASE WHEN MO.MASTER_ORG IS NOT NULL
									THEN MCMO.MASTER_ORG_NAME
									ELSE MO.ORG_NAME
								END',
			'fieldAlias'	=> 'NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'TwitterHandle'	=> [
			'view'			=> 'Twitter Handle',
			'fieldName'		=> 'CASE WHEN MO.MASTER_ORG IS NOT NULL
									THEN MCMO.TWITTER_HANDLE
									ELSE MO.TWITTER_HANDLE
								END',
			'fieldAlias'	=> 'TWITTER_HANDLE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Master'		=> [
			'view'			=> 'Is Master Org',
			'fieldName'		=> 'CASE WHEN MCMO.MASTER_ORG_NAME IS NOT NULL
									THEN \'YES\'
									ELSE \'NO\'
								END',
			'fieldAlias'	=> 'IS_MASTER_ORG',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'TotalSpend'	=> [
			'view'			=> 'Total Spend',
			'fieldName'		=> 'format(sum(P.TOTAL_SPEND),0)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'BilledSpend'	=> [
			'view'			=> 'Billed Spend',
			'fieldName'		=> 'format(sum(P.BILLED_SPEND),0)',
			'fieldAlias'	=> 'BILLED_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'SparkLine'		=> [
			'view'			=> 'Spark Line',
			'fieldName'		=> 'CASE WHEN MO.MASTER_ORG IS NOT NULL
									THEN PMOS.BILLED_SPEND_SPARK
									ELSE POS.BILLED_SPEND_SPARK
								END',
			'fieldAlias'	=> 'SPARK_LINE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
	]
	];
	protected $from = 'PERFORMANCE_ORG_';
	protected $conn = 'warroom';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'download'		=> false,
			'pagination'	=> false,
			'range_selector'	=> [
				'Yesterday',
				'Last 7 Days',
				'This Month',
				'Last Month'
			],
			'filters'		=> $filters,
			'extras'		=> [
				'org_updates'	=> User::hasRole(['OrgUpdate']),
				'user_id'		=> Session::get('user_id')
			]
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
		foreach($results as $key => $result) {
			$position = $key+1;
			$image = '_img/orgs/'.str_replace(
				['/',',','.',' ', '(', ')', '&']
				,'_',
				strtolower($result->NAME)
			).'_60x60.png';
			array_push($data, [
				'#'				=> $position,
				'21 day spark'	=> '<span class="sparkline" data-values="'
					.trim($result->SPARK_LINE).'"></span>',
				'name'			=> HTML::image(
					$image,
					'',
					['class' => 'logo']
				).' '.$result->NAME,
				'billed spend'	=> '$'.$result->BILLED_SPEND
			]);
		}
		return $data;
	}

	public function setQuery($options)
	{
		if($options['range_selector']) {
			$range = strtoupper($options['range_selector']);
		} else {
			$range = 'LAST_7_DAYS';
		}
		$this->from .= $range.' P';
		$this->limit = '0,100';
		$this->where = ['Name'	=> 'MO.ORG_NAME IS NOT NULL'];
		array_walk($this->col, [&$this, 'dataColumn']);
		array_push($this->join,
			'LEFT JOIN META_ORGS MO ON P.ORG_ID=MO.ORG_ID',
			'LEFT JOIN META_CLIENT_MASTER_ORGS MCMO '
				.'ON MO.MASTER_ORG=MCMO.MASTER_ORG_ID',
			'LEFT JOIN PERFORMANCE_ORG_SPARKLINES POS '
				.'ON MO.ORG_ID=POS.ORG_ID AND MO.MASTER_ORG IS NULL',
			'LEFT JOIN PERFORMANCE_MASTERORG_SPARKLINES PMOS '
				.'ON MO.MASTER_ORG=PMOS.MASTER_ORG_ID '
					.'AND MO.MASTER_ORG IS NOT NULL'
		);
		array_push($this->group, 'NAME');
		array_push($this->order, 'sum(P.BILLED_SPEND) DESC');
	}

	public function getColumnsView($total = false)
	{
		$this->columnsView['columns'] = [
			['title' => '#'],
			['title' => '21 day spark'],
			['title' => 'name'],
			['title' => 'billed spend']
		];
		$this->columnsView['totals'] = $this->getTotalsView();
		foreach ($this->columnsView['totals'] as &$total) {
			$total = '$'.$total;
		}

		return $this->columnsView;
	}
}
