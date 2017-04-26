<?php
class ExchangeCountry extends Tile
{
	private $regions		= [];
	private $deleteCountry	= false;
	private $region			= false;
	private $baseOrder		= [];
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName' 	=> 'MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'gDependence'	=> 'EXCHANGE_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'COUNTRY',
			'fieldAlias'	=> 'COUNTRY',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Region'		=> [
			'view'			=> 'Region',
			'fieldAlias'	=> 'REGION',
			'noInQuery'		=> true,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'  	=> 'sum(IMPRESSIONS)',
			'fieldAlias' 	=> 'IMPRESSIONS',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Clicks'		=> [
			'view'			=> 'Clicks',
			'fieldName'		=> 'sum(CLICKS)',
			'fieldAlias'	=> 'CLICKS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Conversions'	=> [
			'view'			=> 'Conversions',
			'fieldName'		=> 'sum(CONVERSIONS)',
			'fieldAlias'	=> 'CONVERSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'PV_Conversion'	=> [
			'noInQuery'		=> true,
			'order'			=> false,
			'noInView'		=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0
				THEN (sum(MEDIA_COST)/sum(IMPRESSIONS))*1000
				ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPA'			=> [
			'view'			=> 'CPA',
			'fieldName'		=> 'CASE WHEN sum(CONVERSIONS) > 0
				THEN (sum(MEDIA_COST)/sum(CONVERSIONS))
				ELSE 0.00 END',
			'fieldAlias'	=> 'CPA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPC'			=> [
			'view'			=> 'CPC',
			'fieldName'		=> 'CASE WHEN sum(CLICKS) > 0
				THEN(sum(MEDIA_COST)/sum(CLICKS))
				ELSE 0.00 END',
			'fieldAlias'	=> 'CPC',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CTR'			=> [
			'view'			=> 'CTR',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0
				THEN (sum(CLICKS)/sum(IMPRESSIONS))*100
				ELSE 0 END',
			'fieldAlias'	=> 'CTR',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'COUNTRY_BY_EXCHANGE';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(2),
				'end'	=> Format::datePicker()
			],
			'type'			=> ['table', 'chart-line'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges'	=> Filter::getExchange(),
			'Country'	=> Filter::getCountryImpression_name_only(),
			'Columns'	=> [$this->getColumnView(), ['CPM','CPA', 'CPC','CTR']]
		];
	}

	public function data()
	{
		$results = parent::data();
		if ($this->region){
			foreach ($results as $key => &$result) {
				if ($this->region) {
					$result = (object)ExchangeCountry::processRegion(
									$result,
									$this->regions,
									$this->baseOrder,
									$this->deleteCountry
								);
				}
			}
		}
		return $results;
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];

		if ($options['date_end'] > '2040-01-01'
			|| $options['date_start'] > '2040-01-01') {
			$this->from .= '_NEW';
			if (in_array('Conversions', $options['filters']['Columns'])) {
				$this->col['Conversions'] = [
					'view'			=> 'Conversions',
					'fieldName'		=> 'sum(PC_ACTIVITIES)',
					'fieldAlias'	=> 'PC_CONVERSION',
					'group'			=> false,
					'order'			=> false,
					'join'			=> false,
					'format'		=> 'number',
					'total'			=> true
				];
				$this->col['PV_Conversion'] = [
					'view'			=> 'PV Conversion',
					'fieldName'		=> 'sum(PV_ACTIVITIES)',
					'fieldAlias'	=> 'PV_CONVERSION',
					'group'			=> false,
					'order'			=> false,
					'join'			=> false,
					'format'		=> 'number',
					'total'			=> true
				];
				$columns = implode('MMMMM', $options['filters']['Columns']);
				$columns = str_replace(
					'Conversions',
					'ConversionsMMMMMPV_Conversion',
					$columns
				);
				$options['filters']['Columns'] = explode('MMMMM', $columns);
			}
			$this->col['CPA'] = [
				'view'			=> 'CPA',
				'fieldName'		=> 'CASE WHEN sum(PC_ACTIVITIES)+sum(PV_ACTIVITIES) > 0
					THEN (sum(MEDIA_COST)/(sum(PC_ACTIVITIES)+sum(PV_ACTIVITIES)))
					ELSE 0.00 END',
				'fieldAlias'	=> 'CPA',
				'group'			=> false,
				'order'			=> false,
				'join'			=> false,
				'format'		=> 'money',
				'total'			=> true
			];
		}
		$this->where = [
			'Date'			=> 'MM_DATE >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('
				.Format::id($options['filters']['Exchanges']).')',
			'Country'		=> 'COUNTRY IN ('
				.Format::str($options['filters']['Country']).')',
		];

		$columns = $options['filters']['Columns'];
		if (in_array('Region', $columns)){
			$this->region = true;
			$this->regions = ExchangeCountry::regions($this->pId);
			if (!in_array('Country', $columns)) {
				$columns[] = 'Country';
				$this->deleteCountry = true;
			}
		}
		array_walk($columns, [&$this, 'addbaseOrder']);
	}

	public static function processRegion($result, $regions, $base, $delete)
	{
		$tmpResult = (array)$result;
		$tmpResult['REGION'] = isset($regions[$tmpResult['COUNTRY']])
						? $regions[$tmpResult['COUNTRY']] :'TBD';
		if ($delete) {
			unset($tmpResult['COUNTRY']);
		}
		return array_merge($base, $tmpResult);
	}

	public static function regions($pid)
	{
		$sql  = '/*'.$pid.'qR*/'.'SELECT country_name as COUNTRY,'
				.' sub_region as REGION';
		$sql  .= ' FROM avails.meta_country_extended '
			.'GROUP BY country_name, sub_region';
		$regions = [];
		$results = QueryService::run($sql);
		array_walk($results, function ($result) use (&$regions) {
			$regions[$result->{'COUNTRY'}] = $result->{'REGION'} != ''
						?$result->{'REGION'} :'TBD';
		});
		return $regions;
	}

	private function setRegions($result)
	{
		$this->regions[$result->{'COUNTRY'}] = $result->{'REGION'} != ''
												?$result->{'REGION'} :'TBD';
	}

	private function addbaseOrder($column) {
		$this->baseOrder[$this->col[$column]['fieldAlias']] = '';
		$this->addDataColumn($column);
	}

	public function getDataLine($default =[])
	{
		$this->field = [
			'MM_DATE as DATE',
			'COUNTRY as COUNTRY',
			'sum(MEDIA_COST) as MEDIA_COST'
		];
		$this->group = ['MM_DATE', 'COUNTRY'];
		return $this->getDataLineTop($this->getDataLineProcess());
	}

	private function getDataLineProcess()
	{
		$data = [
			'data'		=> [[]],
			'text'		=> ' Spend: ',
			'max'		=> null,
			'scale'		=> 'million',
			'format'	=> 'money',
			'categories'=> []
		];

		$results = $this->data();
		foreach($results as $result) {

			if (!in_array($result->{'DATE'}, $data['categories'])) {
				$data['categories'][] = $result->{'DATE'};
			}
			$cat = array_search($result->{'DATE'}, $data['categories']);

			$indexName =  array_flip(array_column($data['data'][0], 'name'));
			if (!isset($indexName[$result->{'COUNTRY'}])) {
				$data['data'][0][] = ['name' => $result->{'COUNTRY'},
									'visible' => false] ;
				$indexName = array_flip(array_column($data['data'][0], 'name'));
			}

			$key = $indexName[$result->{'COUNTRY'}];
			if(!isset($data['data'][0][$key]['data'][$cat])) {
				$data['data'][0][$key]['data'][$cat] = 0;
			}
			$data['data'][0][$key]['data'][$cat] += $result->{'MEDIA_COST'};
		}
		return $data;
	}

	private function getDataLineTop($data)
	{
		$topTen = [];
		$lastDay = array_search(max($data['categories']), $data['categories']);
		foreach ($data['data'][0] as $key => &$top) {
			foreach ($data['categories'] as $idCat => $cat) {
				if (!isset($top['data'][$idCat])) {
					$top['data'][$idCat] = 0;
				}
			}

			if (count($topTen) < 10) {
				$topTen[$key] = $top['data'][$lastDay];
				$data['data'][0][$key]['visible'] = false;
			}
			 elseif ($top['data'][$lastDay] > 0
				&& $top['data'][$lastDay] > min($topTen)) {
				$min = array_search(min($topTen), $topTen);
				$data['data'][0][$min]['visible'] = false;
				unset($topTen[$min]);

				$topTen[$key] = $top['data'][$lastDay];
				$top['visible'] = true;
			}
		}
		return $data;
	}

	public function getDataDownload($default =[])
	{
		$val = [
			'query'		=> $this->BuildQuery(),
			'conn'		=> $this->conn,
			'columns'	=> $this->columnsView['columns'],
			'totals'	=> $this->columnsView['totals'],
			'queryTotal'=> $default['total']
						&& !empty($this->columnsView['totals'])
							? $this->dataTotal()
							: false,
			'pid'		=> $this->pId,
			'id'		=> $this->getIdProcess(),
			'baseOrder'	=> $this->baseOrder,
			'deleteCountry'	=> $this->deleteCountry,
			'region'	=> $this->region
		];
		$country = $this->deleteCountry ? 0 : (
			in_array('Country', array_column(
				$this->columnsView['columns'],
				'title')
			) ? 1 : 0);
		$region =(int)$this->region ? 1 : 0;
		$val['query'] .= '/*'.(int)$country.(int)$region.'*/';
		$val['country'] = $country;
		return $val;
	}
}
