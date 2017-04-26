<?php
class Revenue3rdParty
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
				'name'			=> 'Revenue Target',
				'type'			=> 'spline',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.1,
				'data'			=> []
			]
		];
	}

	private function getData()
	{
		$data 	 = $this->getOptions();
		$results = $this->prepareData();
		ksort($results);
		foreach ($results as $index => $row) {
			$data[0]['data'][$index] = 0.001;
			$data[1]['data'][$index] = 0.001;

			if ($row['TOPLINE']['ACTUALS'] > 0 ) {
				$data[0]['data'][$index] = ($row['OPEN']['ACTUALS']/$row['TOPLINE']['ACTUALS'])*100;
			}

			if ($row['OPEN']['TARGET'] > 0 && $row['TOPLINE']['TARGET'] > 0) {
				$data[1]['data'][$index] = ($row['OPEN']['TARGET']/$row['TOPLINE']['TARGET'])*100;
			}
		}
		return $data;
	}

	private function getCategories()
	{
		$months = [date("M")];
		for ($i = 1; $i <= 11; $i++) {
			array_unshift($months, date("M", strtotime( date( 'Y-m-01' )." -$i months")));
		}
		return $months;
//		return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	}

	private function prepareData()
	{
		$res = [];

		$results	= $this->executeQueryRevenues();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
					'OPEN'		=> ['ACTUALS'=>0, 'TARGET'=>0],
					'TOPLINE'	=> ['ACTUALS'=>0, 'TARGET'=>0]
				];
			}
			$res[$index]['OPEN'][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}

		$results	= $this->executeQueryBillables();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
					'OPEN'		=> ['ACTUALS'=>0, 'TARGET'=>0],
					'TOPLINE'	=> ['ACTUALS'=>0, 'TARGET'=>0]
				];
			}
			$res[$index]['TOPLINE'][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}
		return $res;
	}

	private function executeQueryRevenues()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('`GROUP` = "OPEN"')->
			whereRaw('NETSUITE_VENDOR_TYPE = "3rd Party Gross Revenues"')->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			groupBy('MM_MONTH', 'MM_YEAR', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get(12);
	}

	private function executeQueryBillables()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('`GROUP` = "TOPLINE"')->
			whereRaw('NETSUITE_VENDOR_TYPE = "Gross Billables"')->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			groupBy('MM_MONTH', 'MM_YEAR', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get(12);
	}

	public function getDataWidget()
	{
		return [
				'success'	=> true,
				'title'		=> '3rd Party (Direct) Revenue (%)',
				'type'		=> 'chart',
				'info'		=> 'Showing actuals vs target for 3rd party direct revenue by month.',
				'chart'		=> [
					'type'			=> 'column',
					'categories'	=> $this->getCategories()
				],
				'class'		=> 'noon-blue',
				'data'		=> $this->getData()
			];
	}

}
