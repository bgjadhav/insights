<?php
class BidsClearRatioExchange extends Tile
{
	protected $col = array(
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> 'ASC',
			'total'			=> false
		],
		'Ratio'			=> [
			'view'			=> 'Ratio',
			'fieldName'		=> 'RATIO',
			'fieldAlias'	=> 'X',
			'group'			=> true,
			'join'			=> false,
			'format'		=> 'decimal',
			'order'			=> 'ASC',
			'total'			=> true
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'SUM(IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Percentage'	=> [
			'view'			=> '% Impression',
			'fieldName'		=> '0',
			'fieldAlias'	=> 'Y',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> false
		]
	);
	protected $from = 'BIDS_CLEAR_RATIO_BY_EXCHANGE';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'type'			=> ['chart-column', 'table'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges' => Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		$this->loadConfigGraph($options['type'], $options['optionType']);
		array_walk($this->col, [&$this, 'dataColumn']);
		$this->where = [
			'Date'	=> 'MM_DATE  >= \''.$options['date_start']
				.'\' AND MM_DATE <= \''.$options['date_end']
				.'\' AND ratio <= 1',
			'Exch'	=> 'EXCHANGE_ID IN ('
				.Format::id($options['filters']['Exchanges']).')'
		];
	}

	public function data()
	{
		$results = parent::data();
		$sum_imps = [];

		foreach ($results as $result) {
			if (!isset($sum_imps[$result->{'ID'}])) {
				$sum_imps[$result->{'ID'}] = 0;
			}
			$sum_imps[$result->{'ID'}] += $result->{'IMPRESSIONS'};
		}

		foreach ($results as &$result) {
			if ($result->{'IMPRESSIONS'} != 0) {
				$result->{'Y'} = ($result->{'IMPRESSIONS'}/$sum_imps[$result->{'ID'}])*100;
			}
			$result->{'X'} = (string)number_format($result->{'X'}, 1, '.', '');
		}
		return $results;
	}
}
