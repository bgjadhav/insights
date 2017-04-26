<?php
abstract class Tile
{
	protected $from			= '';
	protected $limit		= '';
	protected $timeout		= null;
	protected $conn			= 'analytics';
	protected $field		= [];
	protected $join			= [];
	protected $where		= [];
	protected $group		= [];
	protected $sumTotal		= true;
	protected $actSumTotal	= false;
	protected $order		= [];
	protected $col			= [];
	protected $categories	= [];
	protected $graphs		= ['UFView' => []];
	protected $test;
	protected $totalNoJoin	= false;
	protected $pId			= false;
	protected $token		= 0;
	public $view			= [];
	public $options			= [];
	public $columnsView		= ['columns' => [], 'formats' => [], 'totals' => []];

	abstract public function options($filters);
	abstract public function filters();
	abstract public function setQuery($options);

	public function __construct($pid=false)
	{
		$this->token	= date('his');
		$this->pId		= $pid.get_class($this);
	}

	public function buildQuery($default = true, $fields = [])
	{
		$sql  = $this->getIdProcess();
		$sql .='SELECT ';
		$sql .= $default ? implode(',', $this->field) : implode(',', $fields);

		if ($default == false && $this->actSumTotal && $this->sumTotal) {
			$ta = $this->tableAlias();
			$sql .= ' FROM open_update_sum_tables '.$ta['alias'];
			$sql .= ' WHERE '.$this->where['Date'].' AND DB_TABLE = \''.$ta['table'].'\'';
		} else {
			$sql .= ' FROM '.$this->from.' ';
			$sql .= $default == false && $this->totalNoJoin ? '' : implode(' ', $this->join);
			$sql .= !empty($this->where) ?' WHERE '.implode(' AND ', $this->where) :' ';

			if ($default) {
				$sql .= !empty($this->group) ?' GROUP BY '.implode(',', $this->group):' ';
				$sql .= !empty($this->order) ?' ORDER BY '.implode(',', $this->order):' ';
				$sql .= $this->limit != '' ?' LIMIT '.$this->limit : '';
			}
		}
		//~ if ($default) {
			//~ dd(print_r($sql));
			//~ die;
		//~ }
		return $sql;
	}

	private function tableAlias()
	{
		if (($pos = strrpos($this->from, ' ')) !== false) {
			return [
				'table' => substr($this->from, 0, $pos),
				'alias' => substr($this->from, $pos+1),
			];
		} else {
			return [
				'table' => $this->from,
				'alias' => ''
			];
		}
	}

	public function getIdProcess()
	{
		return $this->pId != false ? '/*'.$this->pId.$this->token.'*/':'';
	}

	protected function dataColumn($field)
	{
		if (!isset($field['noInQuery'])) {
			$this->appendQuery($field);
		}
		$this->appendView($field);
	}

	protected function aliasField($fieldName, $fieldAlias)
	{
		return $fieldName.' as '.$fieldAlias;
	}

	protected function appendJoin($join)
	{
		$on = !isset($join['LongOn'])
			? $join['tableAlias'].'.'.$join['fieldA']
				.'='.$join['joinAlias'].'.'.$join['fieldB']
			:$join['LongOn'];
		return $join['type'].' JOIN '.$join['tableName'].' ON '.$on;
	}

	protected function appendOrder($field)
	{
		$order  = $field['fieldName'];
		$order .= $field['order'] != '' ? ' '.$field['order'] :'';
		array_push($this->order, $order);
	}

	protected function appendQuery($field)
	{
		$fieldName = !$field['fieldAlias'] ? $field['fieldName']
						: $this->aliasField(
							$field['fieldName'],
							$field['fieldAlias']
						);
		array_push($this->field, $fieldName);

		if ($field['group'] && !in_array($field['fieldName'], $this->group)) {
			array_push($this->group, $field['fieldName']);
		} elseif (isset($field['gDependence'])) {
			foreach ((array)$field['gDependence'] as $gDependence) {
				if (!in_array($gDependence, $this->group)) {
					array_push($this->group, $gDependence);
				}
			}
		}

		if ($field['order']) {
			$this->appendOrder($field);
		}

		if ($field['join'] && !isset($this->join[$field['join']['tableName']]) ) {
			$this->join[$field['join']['tableName']] = $this->appendJoin($field['join']);
		}
	}

	protected function getColumnView($view = true)
	{
		$columnList = [];
		foreach ($this->col as $column => $val) {
			if ($view && !isset($val['noInView'])) {
				$columnList[$column] = $val['view'];
			}
		}
		return $columnList;
	}

	protected function addDataColumn($column)
	{
		$this->dataColumn($this->col[$column]);
	}

	public function getColumnsView($total = false)
	{
		$this->columnsView['totals'] = $total ?$this->getTotalsView() :[];
		return $this->columnsView;
	}

	protected function getTotalsView()
	{
		$totals = [];
		if (!empty($this->columnsView['totals'])) {
			$data = $this->dataTotal();
			$results = QueryService::run(
				$data['sql'],
				$data['conn'],
				$this->timeout
			);
			foreach ($results as $resul) {
				foreach ($this->columnsView['totals'] as $key => $tot) {
					$totals[$key] = $resul->$tot;
				}
			}
		}
		return $totals;
	}

	protected function appendView($field)
	{
		$this->columnsView['columns'][] = ['title' => $field['view']];
		$key = max(array_keys($this->columnsView['columns']));
		if ($field['format'] !== false) {
			$this->columnsView['formats'][$key] = $field['format'];
		}

		if ($field['total']) {
			$this->columnsView['totals'][$key] = $field['fieldName'];
		}
	}

	public function data()
	{
		return QueryService::run(
			$this->buildQuery(),
			$this->conn,
			$this->timeout
		);
	}

	protected function dataTotal()
	{
		return [
			'sql'	=> $this->buildQuery(false, $this->columnsView['totals']),
			'conn'	=> $this->actSumTotal && $this->sumTotal
				?'update_process' :$this->conn
		];
	}

	public function getConnection()
	{
		return $this->conn;
	}

	public function getTimeOut()
	{
		return $this->timeout;
	}

	public function getFrom()
	{
		return $this->from;
	}

	public function getSumTotal()
	{
		return $this->sumTotal;
	}

	public function getDataTablePagination($default = [])
	{
		if ($default['pagination']) {
			$this->limit = ($default['page']*100).',100';
		}
		return $this->getDataTable($default);
	}

	public function getDataTable($default = [])
	{
		$data = [
			'data' => $this->data(),
			'options' => $default
		];

		if (!empty($data['data'])) {
			foreach ($data['data'] as $key => $result) {
				$data['data'][$key] = array_values(
					array_map(function($v) {
						return (is_null($v)) ? '' : $v;
					}, array_values((array)$result))
				);
			}
			$data = array_merge($data,
				$this->getColumnsView($data['options']['total'])
			);
		}

		return $data;
	}

	public function getDataDownload($default = [])
	{
		return [
			'query'		=> $this->buildQuery(),
			'conn'		=> $this->conn,
			'columns'	=> $this->columnsView['columns'],
			'totals'	=> $this->columnsView['totals'],
			'queryTotal'=> $default['total']
							&& !empty($this->columnsView['totals'])
								? $this->dataTotal() : false,
			'id'		=> $this->getIdProcess()
		];
	}

	public function getSmallTable($default = [])
	{
		$results = $this->data();
		foreach ($results as $key => &$result) {
			$result = (array)$result;
		}
		return ['data' => $results];
	}

	public function getDataArea($default = [])
	{
		$results = $this->data();
		$categories = empty($this->graphs['values']['categories']) ? true : false;
		$data = ['data' => [],
				'texts' => $this->graphs['values']['text'],
				'categories' => $categories==true ? [] : $this->graphs['values']['categories']];

		foreach($results as $key => $result) {
			$id = preg_replace('/[^a-zA-Z0-9]+/', '', $result->{'TITLE'});

			$formatCategory = $result->{'CATEGORY'};

			if ($categories && !in_array($result->{'CATEGORY'}, $data['categories'])) {
				if (isset($this->graphs['values']['typeCategorie']) && $this->graphs['values']['typeCategorie'] == 'date') {
					$formatCategory = date('j M', strtotime($result->{'CATEGORY'}));
				}
				$data['categories'][$id][] = $formatCategory;
			}

			$data['data'][$id]['title']	= $result->{'TITLE'};
			$data['data'][$id]['status']= isset($result->{'STATUS'}) ? $result->{'STATUS'} : 'GREEN';
			$data['data'][$id]['critic']= isset($result->{'LABEL'}) ? $result->{'LABEL'} : '';
			if (!isset($data['data'][$id]['data'])) {
				$data['data'][$id]['data'] = [];
			}

			$index = array_search($formatCategory, $data['categories'][$id]);
			$data['data'][$id]['data'][$index] = $result->{'DATA'};
		}
		return $data;
	}

	public function getDataColumn($default = [])
	{
		$results = $this->data();
		if (empty($this->graphs['values']['categories'])) {
			foreach ($results as $result) {
				if (!in_array($result->{'X'}, $this->graphs['values']['categories'])) {
					array_push($this->graphs['values']['categories'], $result->{'X'});
				}
			}
			sort($this->graphs['values']['categories']);
		}

		$data = ['data' => [], 'categories' => $this->graphs['values']['categories']];
		foreach ($results as $result) {
			$id = preg_replace('/[^a-zA-Z0-9]+/', '', $result->{'TITLE'});
			$data['data'][$id]['title'] = $result->{'TITLE'};

			if (!isset($data['data'][$id]['values'])) {
				foreach ($this->graphs['values']['categories'] as $i => $c) {
					$data['data'][$id]['values'][$i] = [(string)$c, 0];
				}
			}

			$index = array_search($result->{'X'}, $this->graphs['values']['categories']);
			$data['data'][$id]['values'][$index][1] = (float)$result->{'Y'};
		}
		$data['texts'] = $this->graphs['values']['text'];
		return $data;
	}

	public function getDataPie($default = [])
	{
		$results = $this->data();
		$data = ['data' => []];
		foreach($results as $result) {
			$id = preg_replace('/[^a-zA-Z0-9]+/', '', $result->{'TITLE'});
			$data['data'][$id]['title'] = $result->{'TITLE'};
			$data['data'][$id]['values'][] = [$result->{'DATA'} , (float)$result->{'Y'}];
		}
		$data['texts'] = $this->graphs['values']['text'];
		return $data;
	}

	public function getDataLine($default = [])
	{
		$results	= json_decode(json_encode($this->data()), true);
		$data = [
			'data'		=> [[]],
			'max'		=> isset($this->max_L) ? $this->max_L : null,
			'text'		=>  isset($this->text_L) ? $this->text_L : ' total: ',
			'scale'		=> isset($this->scale) ? $this->scale : 'million',
			'title'		=> $this->categories,
			'two_charts'=> isset($this->twoCharts) ? $this->twoCharts : false,
			'format'	=> isset($this->format_L) ? $this->format_L : 'number',
			'format_Y'	=> isset($this->format_Y) ? $this->format_Y : 'number',
			'format_categ'=> isset($this->formatCateg) ? $this->formatCateg : false,
			'categories'=> (array)array_unique(array_column($results, 'X'))
		];
		sort($data['categories']);
		$titles = array_unique(array_column($results, 'TITLE'));
		sort($titles);
		$titles = array_flip($titles);

		$lengh = [0];
		if (!empty($results[0])) {
			foreach ($results[0] as $c => $val) {
				if ($c[0] == 'Y' && is_numeric(substr($c , 1))) {
					$lengh[] = substr($c , 1);
				}
			}

			array_walk($results, function($result) use (&$data, $titles, $lengh){
			$key = $titles[$result['TITLE']];

				foreach ($lengh as $i) {
					if (!isset($data['data'][$i][$key])) {
						$data['data'][$i][$key]['name'] = $result['TITLE'];
						$data['data'][$i][$key]['dashStyle'] = 'Solid';
						foreach ($data['categories'] as $index => $cat) {
							//$data['data'][$i][$key]['origin'][$index] = (float)0;
							$data['data'][$i][$key]['data'][$index] = (float)0;
						}
					}
					$cat = array_search($result['X'], $data['categories']);
					$y = $i >= 1 ? 'Y'.$i : 'Y';
					$data['data'][$i][$key]['data'][$cat] += (float)$result[$y];
					//$data['data'][$i][$key]['origin'][$cat] += (float)$result[$y];
				}
			});
		}

		return $data;
	}

	public function getViewFilters()
	{
		return $this->graphs['UFView'];
	}

	protected function loadConfigGraph($type = '', $optionsT = '')
	{
		if (!in_array($type, ['table', 'export', 'download'])) {
			$config = Config::get('reports/'.get_class($this));
			$this->graphs = $config['graphs'][$type];

			//List View filters
			foreach ($this->graphs['values'] as $i => $ufview) {
				$this->graphs['UFView'][$i] = $ufview['view'];
			}

			//Current filter
			$this->graphs['values'] = $optionsT == false
					? array_shift($this->graphs['values'])
					: $this->graphs['values'][$optionsT];

			//Overwrite columns
			if (!empty($this->graphs['values']['columns'])) {
				$this->col = array_replace_recursive($this->col, $this->graphs['values']['columns']);
			}
		}
	}
}
?>
