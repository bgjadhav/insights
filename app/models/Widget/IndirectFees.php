<?php
class IndirectFees
{
	private function getOptions()
	{
		return [
			'Partner Management Fee' => [
				'name'	=> 'Partner Management',
				'y'		=> 0.000
			],
			'Privileged Supply Fee' => [
				'name'	=> 'Privileged Supply',
				'y'		=> 0.000
			],
			'EMS Fee' => [
				'name'	=> 'EMS Fee',
				'y'		=> 0.000
			],
			'Comarketing Fund' => [
				'name'	=> 'Comarketing Fund',
				'y'		=> 0.000
			],
			'User Mapping Hosting Fee' => [
				'name'	=> 'User Mapping Hosting Fee',
				'y'		=> 0.000
			]
		];
	}

	private function getData()
	{
		$data = $this->getOptions();
		$result	= $this->executeQueryPreferredSupply();
		foreach ($result as $row) {
			$data[$row->NETSUITE_VENDOR_TYPE]['y'] = $row->INV > 0 ? (float)number_format($row->INV, 2, '.', '') :0.000;
		}
		return array_values($data);
	}

	private function executeQueryPreferredSupply()
	{
		$lastMonth = date('M-y',strtotime('-1 days', strtotime(date('Y-m-01'))));

		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL')->
			select('NETSUITE_VENDOR_TYPE', DB::raw('SUM(INVOICE_AMOUNT) as INV'))->
			where('MM_DATE', '=', $lastMonth)->
			whereRaw('NETSUITE_VENDOR_TYPE IN ("EMS Fee", "User Mapping Hosting Fee", "Privileged Supply Fee", "Partner Management Fee", "Comarketing Fund")')->
			whereRaw('ACTUALS_vs_FORECAST = "ACTUALS"')->
			groupBy('NETSUITE_VENDOR_TYPE')->
			remember(Format::timeOut())->
			get();
	}

	public function getDataWidget()
	{
		return [
				'success'	=> true,
				'title'		=> 'Indirect Fees<br><i>(last Month)</i>',
				'type'		=> 'chart',
				'info'		=> 'Showing actuals Indirect Fees of the last month.',
				'class'		=> 'noon-blue',
				'chart'		=> [
					'type'	=> 'pie'
				],
				'tooltip'	=> [
					'pointFormat'	=> 'Fees'
				],
				'data'	=> $this->getData(),
			];
	}

}
