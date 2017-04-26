<?php

class ToplineFinancials extends Tile
{
	public $col = false;
	public $columns = [
		['title' => 'Type'],
		['title' => 'Group']
	];
	public $formats = [false, false];
	public $currentMonth;
	public $monthsLastYears;
	protected $from = 'OPEN_VENDOR_ACCRUAL';

	public function __construct()
	{
		//$year = date('y');
		$year = 15;
		//$this->currentMonth = date('M-').$year;
		$this->currentMonth = '12-2015';
		array_push($this->columns ,
			['title' => 'Jan-'.$year],
			['title' => 'Feb-'.$year],
			['title' => 'Mar-'.$year],
			['title' => 'Apr-'.$year],
			['title' => 'May-'.$year],
			['title' => 'Jun-'.$year],
			['title' => 'Jul-'.$year],
			['title' => 'Aug-'.$year],
			['title' => 'Sep-'.$year],
			['title' => 'Oct-'.$year],
			['title' => 'Nov-'.$year],
			['title' => 'Dec-'.$year]
		);
		//for 2015
		$this->monthsLastYears = [
			'Jul-14' => 87.7,
			'Aug-14' => 111.7,
			'Sep-14' => 114.3,
			'Oct-14' => 103.1,
			'Nov-14' => 118.8,
			'Dec-14' => 113.7
		];
	}

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'search'		=> false,
			'pagination'	=> false,
			'scrollY'		=> '1245px',
			'download'		=> false,
			'total'			=> false,
			'group'			=> 1,
			'order'			=> [[1, 'desc']],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return false;
	}

	public function dataQuery()
	{
		$year = date('Y');
		$year = 2015;
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL_TEST')->
			select('MM_DATE', 'MM_YEAR', 'MM_MONTH', 'NETSUITE_VENDOR_TYPE', 'ACTUALS_vs_FORECAST', 'GROUP', DB::raw('SUM(INVOICE_AMOUNT) as INVOICE_AMOUNT'))->
			where('MM_YEAR', '=', $year)->
			whereIn('ACTUALS_vs_FORECAST', ['TARGET', 'ACTUALS', 'FORECAST'])->
			groupBy('MM_DATE')->
			groupBy('NETSUITE_VENDOR_TYPE')->
			groupBy('ACTUALS_vs_FORECAST')->
			orderBy('MM_YEAR', 'ASC')->
			orderBy('MM_MONTH', 'ASC')->
			orderBy(DB::Raw("FIELD(NETSUITE_VENDOR_TYPE,'3rd Party Gross Revenues','EMS Fee','User Mapping Hosting Fee','Privileged Supply Fee','Partner Management Fee','Comarketing Fund', 'Media Discr.(COS + Exchange Trxn)','52000 - Ad Verification','55000 - AdServing Costs - Third-party','56000 - Third-party Data - Per Transact')"), 'ASC')->
			remember(60)->
			get();
	}

	public function data()
	{
		$result	= $this->dataQuery();
		$months	= $this->getMonths($result);
		$data	= $this->getFormatDataRow($months);
		$data	= $this->getFormatDataFinal($data);
		$data[]	= $this->getFormatDataTotalGrossProfit($months);
		return $data;
	}

	private function getFormatDataTotalGrossProfit($months)
	{
		$totals = ['<strong>Total Open Gross Profit</strong>', ' TOTAL'];
		$tmpForest = [];

		foreach ($months as $key => $row) {
			$total = [
					$row['<strong>Total</strong>--OPEN'][0] - $row['<strong>Total</strong>--COGS OPEN'][0],
					$row['<strong>Total</strong>--OPEN'][1] - $row['<strong>Total</strong>--COGS OPEN'][1],
					'--',
					'--'
					];
			$bYear	= date('M-y', strtotime('-12 months', strtotime($key)));
			$bMonth	= date('M-y', strtotime('-1 months', strtotime($key)));

			if ($row['<strong>Total</strong>--OPEN'][3] != '--') {
				$total[3] = $tmpForest[$key] =	$row['<strong>Total</strong>--OPEN'][3] - $row['<strong>Total</strong>--COGS OPEN'][3];
			}

			if ($row['<strong>Total</strong>--OPEN'][3] == '--'
				&& isset($this->monthsLastYears[$bYear]) &&
				strtotime($key) <= $this->currentMonth) {
				$tmpForest[$key] = ($tmpForest[$bMonth]*$this->monthsLastYears[$bYear])/100;
				$total[3] = $tmpForest[$key];
				$total[1] = '--';
			}

			$total[4] = @($total[0] / $months[$key]["Gross Billables--TOPLINE"][0]) * 100;
			$total[5] = @($total[1] / $months[$key]["Gross Billables--TOPLINE"][1]) * 100;

			$totals[] = $this->getFormatRow($total, $key);
		}
		return $totals;
	}

	private function getFormatDataFinal($data)
	{
		$final = [];
		$total = [];
		foreach ($data as $key => $row) {
			$name = explode('--', $key);
			array_unshift($row, $name[1]);
			array_unshift($row, $name[0]);
			if ($name[0] == '<strong>Total</strong>') {
				$total[] = $row;
			} else {
				$final[] = $row;
			}
		}
		foreach ($total as $tot) {
			$final[] = $tot;
		}
		return $final;
	}

	private function getFormatDataRow($months)
	{
		$data = [];
		foreach ($months as $key => &$rows) {
			foreach ($rows as $key2 => &$row) {
				$bYear	= date('M-y', strtotime('-12 months', strtotime($key)));
				$bMonth	= date('M-y', strtotime('-1 months', strtotime($key)));
				if ($row[3] == '--' && isset($this->monthsLastYears[$bYear])) {
					$row[3] = ($months[$bMonth][$key2][3] *$this->monthsLastYears[$bYear])/100;
				}

				$row[4] = @($row[0] / $months[$key]["Gross Billables--TOPLINE"][0]) * 100;
				$row[5] = @($row[1] / $months[$key]["Gross Billables--TOPLINE"][1]) * 100;

				$data[$key2][] = $this->getFormatRow($row, $key);
			}
		}
		return $data;
	}

	private function getFormatRow($row, $month)
	{

		if (!in_array($row[1], ['--', '']) && $row[0] != 0) {
			$row[2] = number_format(($row[1]*100)/$row[0], 2).'%';
		}

		$extras = [4, 5];
		foreach($extras as $e) {
			if(!array_key_exists($e, $row) || $row[$e] == 100) {
				$row[$e] = '';
			} else {
				$row[$e] = '&nbsp;(' . number_format($row[$e], 2) . '%)';
			}
		}

		$formats = [0, 1, 3];
		foreach ($formats as $f) {
			if (!in_array($row[$f], ['--', ''])) {
				$row[$f] =  '$'.number_format($row[$f], 2);
			}
		}

		if (strtotime($month) < strtotime($this->currentMonth)){
			$row[3] = '--';
		}
		$output  = '<span class="target">'.$row[0].$row[4]. '</span>'
					.'<br><span class="actual">'.$row[1].$row[5].'</span>'
					.'<br><span class="targetAverage">'.$row[2].'</span>'
					.'<br><span class="forecast">'.$row[3].'</span>';
		return $output;
	}

	private function getMonths($results)
	{
		$vs = ['TARGET' => 0, 'ACTUALS' => 1, 'FORECAST' => 3];
		foreach ($this->columns as $month) {
			$months[$month['title']] = [];
		}
		unset($months['Type']);
		unset($months['Group']);

		foreach ($results as $result) {

				$position	= $vs[$result->ACTUALS_vs_FORECAST];
				$name		= $result->NETSUITE_VENDOR_TYPE.'--'.$result->GROUP;
				if (!isset($months[$result->MM_DATE][$name])) {
					$months[$result->MM_DATE][$name] = ['--', '--', '--', '--'];
				}

				$months[$result->MM_DATE][$name][$position] = $result->INVOICE_AMOUNT;

				if ($result->GROUP != 'TOPLINE') {
					$group	= '<strong>Total</strong>'.'--'.$result->GROUP;

					if (!isset($months[$result->MM_DATE][$group])) {
						$months[$result->MM_DATE][$group] =  ['--', '--', '--', '--'];
					}

					if ($months[$result->MM_DATE][$group][$position] == '--') {
						$months[$result->MM_DATE][$group][$position] = $result->INVOICE_AMOUNT;
					} else {
						$months[$result->MM_DATE][$group][$position] += $result->INVOICE_AMOUNT;
					}
				}
		}
		return $months;
	}

	private function setMonthColumns($monthColumns)
	{
		foreach ($monthColumns as $row) {
			$this->columns[] = ['title' => $row === end($monthColumns) ? $row . '*' :$row];
		}
	}

	public function getColumnsView($total = false)
	{
		//$month = date('n');
		$month = 12;
		$this->columns[$month+1]['title'] .= '*';
		return [
			'columns' => $this->columns,
			'formats' => $this->formats,
			'totals'  => []
		];
	}

	public function setQuery($options)
	{
	}

	public function getViewFilters()
	{
		return ['default'];
	}
}
