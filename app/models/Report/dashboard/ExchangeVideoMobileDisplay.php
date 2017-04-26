<?php
class ExchangeVideoMobileDisplay extends Tile
{
	private $sov	= [
		'sov_Impressions' => [
				'field'		=> 'Impressions',
				'delete'	=> false,
				'status'	=> false,
				'value'		=> []
		],
		'sov_MediaCost' => [
				'field'		=> 'MediaCost',
				'delete'	=> false,
				'status'	=> false,
				'value'		=> []
		]
	];
	private $level = [
		'Date' => false,
		'ChannelType'=> false
	];
	public $col 	= [
		'Date'			=> [
			'view' 			=> 'Date',
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
			'view' 			=> 'Exchange Name',
			'fieldName' 	=> 'b.exch_name',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'gDependence'	=> 'a.EXCHANGE_ID',
			'join' 			=> [
				'type'			=> 'INNER',
				'tableName' 	=> 'META_EXCHANGE b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'exch_id',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'EXCHANGE_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ChannelType'		=> [
			'view'			=> 'Channel Type',
			'fieldName'		=> 'CHANNEL_TYPE',
			'fieldAlias'	=> 'CHANNEL_TYPE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(a.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'noInView'		=> true,
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(a.MEDIA_COST)',
			'noInView'		=> true,
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'sov_Impressions'=> [
			'view'			=> 'Sov Impressions',
			'noInQuery'		=> true,
			'fieldAlias'	=> 'sov_Impressions',
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> false
		],
		'sov_MediaCost'	=> [
			'view'			=> 'Sov Media Cost',
			'noInQuery'		=> true,
			'fieldAlias'	=> 'sov_MediaCost',
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> false
		],

		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(a.IMPRESSIONS) > 0 THEN (sum(a.MEDIA_COST)/sum(a.IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'DISPLAY_MOBILE_VIDEO_STATS a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(2),
				'end'	=> Format::datePicker(2)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges'			=> Filter::getExchange(),
			'Channel_Type'	=> Filter::getBidChannel(),
			'Columns'			=> [$this->getColumnView(), ['CPM']]
		];
	}

	public function data()
	{

		$results = parent::data();
		$this->setSov();
		$base = array_column($this->col, 'fieldAlias');

		if ($this->sov['sov_Impressions']['status'] || $this->sov['sov_MediaCost']['status']){
			foreach ($results as $key => &$result) {
				$result = (array)$result;
				$result = ExchangeVideoMobileDisplay::formatSov($result, $this->sov, $this->col);
				$result = (object) ExchangeVideoMobileDisplay::baseOrder($result, $base);
			}
		}
		return $results;
	}

	public function setQuery($options)
	{
		$this->actSumTotal = $options['sumTotal'];
		$this->where = [
			'Date'		=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\'',
			'ExchangeId'=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Channel_Type'	=> 'a.CHANNEL_TYPE IN ('.Format::str($options['filters']['Channel_Type']).')'
		];
		$columns = [];
		$columnsTMP = [];

		foreach ($options['filters']['Columns'] as $colname) {
			if (in_array($colname , ['sov_Impressions', 'sov_MediaCost'])) {
				$columnsTMP[] = $colname;
			} else {
				if($colname == 'CPM'){
					$columnsTMP[] = $colname;
				} else{
					$columns[] = $colname;
				}
			}
		}
		$columns[] = 'Impressions';
		$columns[] = 'MediaCost';
		$columns = array_merge($columns, $columnsTMP);
		$columns = $this->evalSov($columns);
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
				$query['select'][] = $this->aliasField($this->col[$level]['fieldName'], $this->col[$level]['fieldAlias']);
				$query['group'][]  = $this->col[$level]['fieldName'];
			} else {
				$query['select'][] = $this->aliasField('0', $this->col[$level]['fieldAlias']);
			}
		}

		foreach ($this->sov as $sov => &$values) {
			if ($values['status']){
				$alias = $this->col[$values['field']]['fieldAlias'];
				$select = $query['select'];
				$select[] = $this->aliasField($this->col[$values['field']]['fieldName'], $alias);

				$sql  = '/*'.$this->pId.'qSov*/'.'SELECT ' . implode(', ', $select)
				.' FROM '.$this->from
				.' WHERE '.implode(' AND ', $this->where);
				$sql .= !empty($query['group']) ?' GROUP BY '. implode(', ', $query['group']) :'';

				$results = QueryService::run($sql);
				foreach ($results as $result) {
					$values['value'][$result->{'DATE'}][$result->{'CHANNEL_TYPE'}] = $result->{$alias};
				}
			}
		}
	}

	public static function formatSov($result, $sovs, $col)
	{
		$date	= isset($result['DATE']) ?$result['DATE'] :0;
		$inv	= isset($result['CHANNEL_TYPE']) ?$result['CHANNEL_TYPE'] :0;
		$resultTmp = $result;

		foreach ($sovs as $sov => $values) {
			if ($values['status']){
				$alias = $col[$values['field']]['fieldAlias'];
				$resultTmp[$sov] = ($result[$alias]/$values['value'][$date][$inv])*100;
				if ($values['delete']) {
					unset($resultTmp[$alias]);
				}
			}
		}
		return $resultTmp;
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
			'columns'	=> $this->columnsView['columns'],
			'col'		=> $this->col,
			'totals'	=> $this->columnsView['totals'],
			'queryTotal'=> $default['total'] && !empty($this->columnsView['totals'])
				? $this->dataTotal() : false,
			'pid'		=> $this->pId,
			'id'		=> $this->getIdProcess(),
			'base'		=> array_column($this->col, 'fieldAlias')
		];
		$this->setSov();
		$val['sov'] = $this->sov;
		$val['level'] = $this->level;
		$sov_imp = $this->sov['sov_Impressions']['status'] ? 1 : 0;
		$sov_mc  = $this->sov['sov_MediaCost']['status'] ? 1 : 0;
		$val['query'] .= '/*'.(int)$sov_imp.(int)$sov_mc.'*/';
		return $val;
	}

}
