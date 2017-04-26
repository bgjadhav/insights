<?php
class TopAgencies extends Tile
{
	public $col = [
		'Name'			 => [
			'view'			=> 'Name',
			'fieldName'		=> 'MA.AGENCY_NAME',
			'fieldAlias'	=> 'NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Org'   		 => [
			'view'			=> 'Org Name',
			'fieldName'		=> 'CASE WHEN MO.MASTER_ORG IS NOT NULL
										THEN MCMO.MASTER_ORG_NAME
										ELSE MO.ORG_NAME
								END',
			'fieldAlias'	=> 'ORG_NAME',
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
		'RegionName'  => [
			'view'			=> 'Region Name',
			'fieldName'		=> 'MCR.REGION_NAME',
			'fieldAlias'	=> 'REGION_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CountryName'    => [
			'view'			=> 'Country Name',
			'fieldName'		=> 'MCC.COUNTRY_NAME',
			'fieldAlias'	=> 'COUNTRY_NAME',
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
	]
	];
	protected $conn = 'warroom';
	protected $from ='PERFORMANCE_AGENCY_';

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
			'filters'	=> $filters
		];
	}

	public function filters()
	{
		return [
			'Regional_Business_Unit' => Filter::getRBU(),
			'Region' => Filter::getAgencyRegion(),
			'Country' => Filter::getAgencyCountry()
		];
	}

	public function data()
	{
		$results = parent::data();
		$data = [];
		foreach($results as $key => $result) {
			$position = $key+1;
			$org = strtolower($result->ORG_NAME);
			$image = '_img/orgs/'.str_replace(
				['/',',','.',' ', '(', ')', '&'],
				'_',
				$org != '' ? $org : strtolower($result->NAME)
			).'_60x60.png';

			array_push($data, [
				'#'				=> $position,
				'name'			=> HTML::image(
					$image,
					'',
					['class' => 'logo']
				).$result->NAME,
				'org'			=> $result->ORG_NAME,
				'region'		=> $result->REGION_NAME,
				'country'		=> $result->COUNTRY_NAME,
				'billed spend'	=> '$' . $result->BILLED_SPEND
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

		array_walk($this->col, [&$this, 'dataColumn']);
		array_push($this->join,
			'LEFT JOIN META_AGENCIES MA ON P.AGENCY_ID=MA.AGENCY_ID',
			'LEFT JOIN META_ORGS MO ON MA.ORG_ID=MO.ORG_ID',
			'LEFT JOIN META_CLIENT_MASTER_ORGS MCMO '
				.'ON MO.MASTER_ORG=MCMO.MASTER_ORG_ID',
			'LEFT JOIN META_CLIENT_REGIONS MCR ON MA.REGION=MCR.REGION_ID',
			'LEFT JOIN META_CLIENT_REGIONAL_BUSINESS_UNITS MCRBU '
			.'ON MA.REGIONAL_BUSINESS_UNIT=MCRBU.REGION_ID',
			'LEFT JOIN META_CLIENT_COUNTRIES MCC ON MA.COUNTRY=MCC.COUNTRY_ID'
		);

		array_push($this->group, 'P.AGENCY_ID');
		array_push($this->order, 'sum(P.BILLED_SPEND) DESC');

		$this->from .= $range.' P';
		$this->limit = '0,100';
		$this->where = [
			'Agency'	=> 'MA.AGENCY_NAME IS NOT NULL',
			'RBU'		=> 'MCRBU.REGION_ID IN ('
				.Format::id($options['filters']['Regional_Business_Unit']). ')',
			'Region'	=> 'MCR.`REGION_id` IN ('
				.Format::id($options['filters']['Region']).')',
			'Country'	=> 'MCC.`COUNTRY_ID` IN ('
				.Format::id($options['filters']['Country']).')'
		];
	}

	public function getColumnsView($total = false)
	{
		$this->columnsView ['columns'] =  [
			['title' => '#'],
			['title' => 'name'],
			['title' => 'org'],
			['title' => 'region'],
			['title' => 'country'],
			['title' => 'billed spend']
		];
		$this->columnsView['totals'] = $this->getTotalsView();
		foreach ($this->columnsView['totals'] as &$total) {
			$total = '$'.$total;
		}
		return $this->columnsView;
	}
}
