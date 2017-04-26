<?php
class PreferredIndirectRevenue
{
	private function getOptions()
	{
		return [
			[
				'name'			=> 'Total Indirect Revenue',
				'type'			=> 'column',
				'pointPadding'	=> 0.2,
				'pointPlacement'=> 0.1,
				'data'			=> []
			],
			[
				'name'			=> 'Indirect Revenue Target',
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
				$data[0]['data'][$index] = ($row['SUPPLY']['ACTUALS']/$row['TOPLINE']['ACTUALS'])*100;
			} else {
				$data[0]['data'][$index] = null;
			}

			if ($row['SUPPLY']['TARGET'] > 0 && $row['TOPLINE']['TARGET'] > 0) {
				$data[1]['data'][$index] = ($row['SUPPLY']['TARGET']/$row['TOPLINE']['TARGET'])*100;
			} else {
				$data[1]['data'][$index] = null;
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

		$results	= $this->executeQueryPreferredSupply();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
								'SUPPLY'	=> ['ACTUALS'=>0, 'TARGET'=>0],
								'TOPLINE'	=> ['ACTUALS'=>0, 'TARGET'=>0]
								];
			}
			$res[$index]['SUPPLY'][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}

		$results	= $this->executeQueryBillables();
		foreach ($results as $row) {
			$index = $row->{'MM_MONTH'} - 1 ;
			if (!isset($res[$index])){
				$res[$index] = [
								'SUPPLY'	=> ['ACTUALS'=>0, 'TARGET'=>0],
								'TOPLINE'	=> ['ACTUALS'=>0, 'TARGET'=>0]
								];
			}
			$res[$index]['TOPLINE'][$row->{'ACTUALS_vs_FORECAST'}] += $row->{'INVA'};
		}
		return $res;
	}

	private function executeQueryPreferredSupply()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('NETSUITE_VENDOR_TYPE IN ("EMS Fee", "User Mapping Hosting Fee", "Privileged Supply Fee", "Partner Management Fee", "Comarketing Fund")')->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			groupBy('MM_MONTH', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get();
	}

	private function executeQueryBillables()
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('MM_MONTH', 'ACTUALS_vs_FORECAST', DB::raw('SUM(INVOICE_AMOUNT) as INVA'))->
			whereRaw('`GROUP` = "TOPLINE"')->
			whereRaw('NETSUITE_VENDOR_TYPE = "Gross Billables"')->
			whereRaw('ACTUALS_vs_FORECAST IN ("ACTUALS", "TARGET")')->
			groupBy('MM_MONTH', 'ACTUALS_vs_FORECAST')->
			remember(Format::timeOut())->
			get();
	}


	public function getDataWidget()
	{
		return [
				'success'	=> true,
				'title'		=> 'Preferred (Indirect) Revenue (%)',
				'type'		=> 'chart',
				'info'		=> 'Showing actuals vs target for Preferred Indirect Revenue by month.',
				'chart'		=> [
					'type'			=> 'column',
					'categories'	=> $this->getCategories()
				],
				'class'		=> 'noon-blue',
				'data'		=> $this->getData()
			];
	}
}
