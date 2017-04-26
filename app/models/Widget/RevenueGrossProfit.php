<?php
class RevenueGrossProfit
{
	private function getOptions()
	{
		return [
			[
				'name'			=> 'Total Revenue',
				'type'			=> 'column',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.1,
				'data'			=> []
			],
			[
				'name'			=> 'Gross Profit',
				'type'			=> 'column',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.3,
				'data'			=> []
			],
			[
				'name'			=> 'Revenue Target',
				'type'			=> 'spline',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.1,
				'data'			=> []
			],
			[
				'name'			=> 'GP Target',
				'type'			=> 'spline',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.3,
				'data'			=> []
			]
		];
	}

	private function getData()
	{
		$results = $this->prepareData();
		$data 	 = $this->getOptions();
		ksort($results);
		foreach ($results as $index => $row) {
			$data[0]['data'][$index] = 0.001;
			$data[1]['data'][$index] = 0.001;
			$data[2]['data'][$index] = 0.001;
			$data[3]['data'][$index] = 0.001;

			if ($row['TOPLINE']['ACTUALS'] > 0 ) {
				$data[0]['data'][$index] = ($row['OPEN']['ACTUALS']/$row['TOPLINE']['ACTUALS'])*100;
				$data[1]['data'][$index] = (($row['OPEN']['ACTUALS']-$row['COGS OPEN']['ACTUALS'])/$row['TOPLINE']['ACTUALS'])*100;
			}

			if ($row['OPEN']['TARGET'] > 0 && $row['TOPLINE']['TARGET'] > 0) {
				$data[2]['data'][$index] = ($row['OPEN']['TARGET']/$row['TOPLINE']['TARGET'])*100;
				$data[3]['data'][$index] = (($row['OPEN']['TARGET']-$row['COGS OPEN']['TARGET'])/$row['TOPLINE']['TARGET'])*100;
			}
		}
		return $data;
	}

	private function getCategories()
	{
		return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	}

	private function prepareData()
	{
		$res = [];
		$results	= $this->executeQuery();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
					'OPEN'		=> ['ACTUALS'=>0, 'TARGET'=>0],
					'TOPLINE'	=> ['ACTUALS'=>0, 'TARGET'=>0],
					'COGS OPEN'	=> ['ACTUALS'=>0, 'TARGET'=>0]
				];
			}
			$res[$index][$row->{'GROUP'}][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}
		return $res;
	}

	private function executeQuery()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'GROUP', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			whereRaw('`GROUP` IN ("OPEN", "TOPLINE", "COGS OPEN")')->
			groupBy('MM_MONTH', 'GROUP', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get();
	}


	public function getDataWidget()
	{
		return [
				'success'	=> true,
				'title'		=> 'YTD: Total Revenue vs Gross Profit (%)',
				'type'		=> 'chart',
				'info'		=> 'Showing actuals vs target Gross Profit and Revenue by month.',
				'chart'		=> [
					'type'			=> 'column',
					'categories'	=> $this->getCategories()
				],
				'class'		=> 'noon-blue',
				'data'		=> $this->getData()
			];
	}

}
