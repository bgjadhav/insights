<?php
class DirectRevenueProfitMargin
{
	private function getOptions()
	{
		return [
			[
				'name'			=> 'Profit Margin',
				'type'			=> 'column',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.1,
				'data'			=> []
			],
			[
				'name'			=> 'Profit Margin Target',
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

			if ($row['GROSS']['ACTUALS'] > 0 ) {
				$data[0]['data'][$index] =  (1-($row['OPEN']['ACTUALS']/$row['GROSS']['ACTUALS']))*100;
			}

			if ($row['OPEN']['TARGET'] > 0 && $row['GROSS']['TARGET'] > 0) {
				$data[1]['data'][$index] = (1-($row['OPEN']['TARGET']/$row['GROSS']['TARGET']))*100;
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

		$results	= $this->executeQueryCogs();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
					'OPEN'		=> ['ACTUALS'=>0, 'TARGET'=>0],
					'GROSS'		=> ['ACTUALS'=>0, 'TARGET'=>0]
				];
			}
			$res[$index]['OPEN'][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}

		$results	= $this->executeQueryReveneus();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
					'OPEN'		=> ['ACTUALS'=>0, 'TARGET'=>0],
					'GROSS'		=> ['ACTUALS'=>0, 'TARGET'=>0]
				];
			}
			$res[$index]['GROSS'][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}
		return $res;
	}

	private function executeQueryCogs()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('`GROUP` = "COGS OPEN"')->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			groupBy('MM_MONTH', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get();
	}

	private function executeQueryReveneus()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('NETSUITE_VENDOR_TYPE = "3rd Party Gross Revenues"')->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			groupBy('MM_MONTH', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get();
	}

	public function getDataWidget()
	{
		return [
				'success'	=> true,
				'title'		=> 'Direct Revenue Profit Margin (%)',
				'type'		=> 'chart',
				'info'		=> 'Showing actuals vs target for Direct Revenue Profit Margin by month.',
				'chart'		=> [
					'type'			=> 'column',
					'categories'	=> $this->getCategories()
				],
				'class'		=> 'noon-blue',
				'data'		=> $this->getData()
			];
	}
}
