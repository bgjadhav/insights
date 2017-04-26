<?php
class WebVsInApp extends Tile
{
	private $sov	= [
		'sov_Impressions' => [
			'field'		=> 'Impressions',
			'delete'	=> false,
			'status'	=> false,
			'value'		=> []
		],
		'sov_Media_Cost' => [
			'field'		=> 'MediaCost',
			'delete'	=> false,
			'status'	=> false,
			'value'		=> []
		]
	];
	private $level = [
		'Date' => false,
		'WebVsInApp'=> false
	];
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'a.EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName' 	=> 'b.exch_name',
			'fieldAlias' 	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'WebVsInApp'	=> [
			'view'			=> 'WEB VS IN-APP',
			'fieldName'		=> 'a.WEB_VS_INAPP',
			'fieldAlias'	=> 'WEB_VS_INAPP',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true,
			'sov'			=> false
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true,
			'sov'			=> false
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0
							THEN (sum(a.MEDIA_COST)/sum(a.IMPRESSIONS))*1000
							ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'sov_Impressions'=> [
			'view'			=> 'Sov Impressions',
			'fieldAlias'	=> 'Sov_Impressions',
			'noInQuery'		=> true,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> false
		],
		'sov_Media_Cost'	=> [
			'view'			=> 'Sov Media Cost',
			'fieldAlias'	=> 'Sov_Media_Cost',
			'noInQuery'		=> true,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> false
		]
	];
	private $newView = [];
	protected $from = 'WEB_VS_INAPP a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges'		=> [Filter::getExchange(), [23]],
			'Web_Vs_InApp'	=> Filter::getWebVsInApp(),
			'Columns'		=> [$this->getColumnView(), ['CPM']]
		];
	}

	public function data()
	{
		$results = parent::data();
		$this->setSov();
		$this->columnsView = WebVsInApp::clearColumnView(
			$this->newView,
			$this->columnsView,
			$this->col
		);
		if ($this->sov['sov_Impressions']['status']
			|| $this->sov['sov_Media_Cost']['status']) {
			foreach ($results as $key => &$result) {
				if ($this->sov['sov_Impressions']['status']
					|| $this->sov['sov_Media_Cost']['status']) {
					$result = WebVsInApp::formatSov(
						(array)$result,
						$this->sov,
						$this->col
					);
				}
			}
		}
		return $results;
	}

	public static function clearColumnView($new, $old, $col)
	{
		$tmpCol = [];
		foreach ($new as $val) {
			$tmpCol[] = $col[$val]['view'];
		}
		$col = array_column($col, 'view');
		$tmp = ['columns' => [], 'formats' => [], 'totals' => []];
		foreach ($old['columns'] as $key => $val) {
			if (in_array($val['title'], $tmpCol)) {
				$tmp['columns'][$key] = $val;

				if (isset($old['formats'][$key]))
				{
					$tmp['formats'][$key] = $old['formats'][$key];
				}

				if (isset($old['totals'][$key]))
				{
					$tmp['totals'][$key] = $old['totals'][$key];
				}
			}
		}
		return $tmp;
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=>  'a.MM_DATE  >= \''.$options['date_start']
						.'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('
						.Format::id($options['filters']['Exchanges']).')',
			'Web Vs InApp'	=> 'a.WEB_VS_INAPP IN ('
						.Format::str($options['filters']['Web_Vs_InApp']).')',
			'Default'		=> 'a.WEB_VS_INAPP > \'\''
		];
		$this->newView = $options['filters']['Columns'];
		$columns = $this->evalSov($options['filters']['Columns']);
		array_walk($columns, [&$this, 'addDataColumn']);
	}

	private function evalSov($columns)
	{
		foreach ($this->sov as $sov => &$values) {
			if (in_array($sov, $columns)){
				$values['status'] = true;
				if (!in_array($values['field'], $columns)) {
					$columns[] = $values['field'];
					$values['delete'] = true;
				}
			}
		}

		foreach ($this->level as $level => &$val) {
			if (in_array($level, $columns)) {
				$val = true;
			}
		}
		return $columns;
	}

	private function setSov()
	{
		$query = ['select'=>[], 'group'=>[]];
		foreach ($this->level as $level => $val) {
			if ($val) {
				$query['select'][] = $this->aliasField(
					$this->col[$level]['fieldName'],
					$this->col[$level]['fieldAlias']
				);
				$query['group'][]  = $this->col[$level]['fieldName'];
			} else {
				$query['select'][] = $this->aliasField(
					'0',
					$this->col[$level]['fieldAlias']
				);
			}
		}
		foreach ($this->sov as $sov => &$values) {
			if ($values['status']){
				$alias = $this->col[$values['field']]['fieldAlias'];
				$select = $query['select'];
				$select[] = $this->aliasField(
					$this->col[$values['field']]['fieldName'],
					$alias
				);
				$sql  = '/*'.$this->pId.'qSov*/'.'SELECT '.implode(', ', $select)
				.' FROM '.$this->from
				.' WHERE '.implode(' AND ', $this->where);
				$sql .= !empty($query['group'])
						?' GROUP BY '. implode(', ', $query['group']) :'';

				$results = QueryService::run($sql);
				foreach ($results as $result) {
					$values['value'][$result->{'DATE'}][$result->{'WEB_VS_INAPP'}] = $result->{$alias};
				}
			}
		}
	}

	public static function formatSov($result, $sovs, $col)
	{
		$date	= isset($result['DATE']) ?$result['DATE'] :0;
		$web	= isset($result['WEB_VS_INAPP']) ?$result['WEB_VS_INAPP'] :0;

		foreach ($sovs as $sov => $values) {
			if ($values['status']) {
				$alias = $col[$values['field']]['fieldAlias'];
				$result[$sov] = number_format(
					($result[$alias]/$values['value'][$date][$web])*100, 0
				);
				if ($values['delete']) {
					unset($result[$alias]);
				}
			}
		}
		return $result;
	}

	public static function baseOrder($result, $base)
	{
		$resultTmp = [];
		foreach ($base as $index) {
			if ( isset($result[$index]) ) {
				$resultTmp[$index] = $result[$index];
			}
		}
		return $resultTmp;
	}

	public function getDataDownload($default =[])
	{
		$val = [
			'query'		=> $this->BuildQuery(),
			'conn'		=> $this->conn,
			'col'		=> $this->col,
			'queryTotal'=> $default['total']
							&& !empty($this->columnsView['totals'])
								? $this->dataTotal() : false,
			'pid'		=> $this->pId,
			'id'		=> $this->getIdProcess(),
			'base'		=> array_column($this->col, 'fieldAlias')
		];
		$this->setSov();
		$this->columnsView = WebVsInApp::clearColumnView(
			$this->newView,
			$this->columnsView,
			$this->col
		);
		$val['sov'] = $this->sov;
		$val['level'] = $this->level;
		$val['columns'] = $this->columnsView['columns'];
		$val['totals'] = $this->columnsView['totals'];
		$sov_imp = $this->sov['sov_Impressions']['status'] ? 1 : 0;
		$sov_mc  = $this->sov['sov_Media_Cost']['status'] ? 1 : 0;
		$val['query'] .= '/*'.(int)$sov_imp.(int)$sov_mc.'*/';
		return $val;
	}

}
