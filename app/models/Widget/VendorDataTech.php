<?php
class VendorDataTech
{
	private $categories = [];
	private function getOptions()
	{
		return [
			[
				'name'			=> 'Tech',
				'data'			=> 'column',
				'data'			=> []
			],
			[
				'name'			=> 'Data',
				'type'			=> 'column',
				'data'			=> []
			]
		];
	}

	private function getData()
	{
		$res = $this->getOptions();
		$since = strtotime('-13 Months');


		$vendors = $this->executeQueryVendor($since);
		$grossBi = $this->executeQueryGrossBillables($since);
		foreach ($grossBi as $index => $row) {
			$this->categories[] = $row->{'MM_DATE'};

			$res[0]['data'][] = $row->{'INVOICE_AMOUNT'} > 0
				? $vendors[$index]->{'TECH'}/$row->{'INVOICE_AMOUNT'} *100: 0;

			$res[1]['data'][] = $row->{'INVOICE_AMOUNT'} > 0
			? $vendors[$index]->{'DATA'}/$row->{'INVOICE_AMOUNT'} *100: 0;
		}
		return $res;
	}

	private function executeQueryVendor($since)
	{
		return DB::reconnect('analytics')->
			table('KNOX_VENDOR_PACING')->
			select(DB::raw('SUBSTRING(KNOX_VENDOR_PACING.MM_DATE, 1, 7) AS MONTH'),
				DB::raw('SUM(CASE WHEN META_VENDOR_TECH_DATA.TYPE = "TECH"'
					.' THEN KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT'
					.' ELSE 0 END) TECH'),
				DB::raw('SUM(CASE WHEN META_VENDOR_TECH_DATA.TYPE = "DATA"'
				.' THEN KNOX_VENDOR_PACING.VENDOR_INVOICE_AMOUNT'
				.' ELSE 0 END) DATA'))->
			join('META_VENDOR_TECH_DATA', 'KNOX_VENDOR_PACING.VENDOR_ID',
				'=',
				'META_VENDOR_TECH_DATA.VENDOR_ID')->
			where('MM_DATE', '>=', date('Y-m-01', $since))->
			groupBy(DB::raw('SUBSTRING(KNOX_VENDOR_PACING.MM_DATE, 1, 7)'))->
			orderBy(DB::raw('SUBSTRING(KNOX_VENDOR_PACING.MM_DATE, 1, 7)'))->
			remember(Format::timeOut())->
			get();
	}

	private function executeQueryGrossBillables($since)
	{
		return DB::reconnect('analytics')->
			table('OPEN_VENDOR_ACCRUAL_TEST')->
			select('MM_DATE', 'MM_YEAR',
				'MM_MONTH', DB::raw('SUM(INVOICE_AMOUNT) as INVOICE_AMOUNT'))->
			whereRaw('( (MM_YEAR ='.date('Y').''
					.' AND MM_MONTH <='.date('n').')'
				.' OR (MM_YEAR ='.date('Y', $since)
					.' AND MM_MONTH >='.date('n', $since).') )')->
			where('ACTUALS_vs_FORECAST', '=', 'ACTUALS')->
			where('NETSUITE_VENDOR_TYPE', '=', 'Gross Billables')->
			groupBy('MM_DATE')->
			groupBy('MM_MONTH')->
			orderBy(DB::raw('LENGTH(MM_YEAR)'))->
			orderBy('MM_YEAR')->
			orderBy(DB::raw('LENGTH(MM_MONTH)'))->
			orderBy('MM_MONTH')->
			remember(Format::timeOut())->
			get();
	}

	public function getDataWidget()
	{
		return [
				'success'	=> true,
				'title'		=> 'Data/Tech Direct Revenue Breakout (%)',
				'type'		=> 'chart',
				'info'		=> 'Showing % of spend Data/Tech direct revenue breakout by month.',
				'data'		=> $this->getData(),
				'chart'		=> [
					'type'			=> 'column',
					'categories'	=> $this->categories
				],
				'class'		=> 'noon-blue'
			];
	}

}
