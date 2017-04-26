<?php
class UniqueDealsBySupply
{
	protected $filters;
	protected $config = 'widgets/uniquedealsbysupplytype';
	protected $first;
	protected $position;

	public function _contruct($filters)
	{
		$this->filters = $filters;
	}


	public function widget()
	{
		$data = [];
		$month_year = null;

		$cust_filter = $this->initCustomFilter();

		$date = $this->getDateAndStorageFirstAndPosition($cust_filter);

		$cust_filter['Date']['selected'] = [$date => $date];

		$data = $this->data($date);

		//~
		dd(print_r($this->prepareQuery($date)));
		die;

		return Response::json(
			array_merge_recursive(
				Config::get($this->config.'.widget'),
				['filters' => $cust_filter],
				['data' => $data],
				['first' => $this->first],
				['Date_position' => $this->position]
			)
		);
	}


	private function initCustomFilter()
	{
		$activeDealMY = Filter::activeDealMonthYear();

		$cust_filter = [
			'Date'		=> [
				'data' => [],
				'selected' => ''
			]
		];

		foreach ($activeDealMY as $act) {
			$index = $act->{'YEAR'}.'-'.$act->{'MONTH'};
			$cust_filter['Date']['data'][$index]
				= Format::textToDateYearMonth($index, true, true, true);
		}

		return $cust_filter;
	}

	private function getDateAndStorageFirstAndPosition($cust_filter)
	{
		if (!isset($this->filters['Date']) || empty($this->filters['Date'])) {
			$date = Format::yearMonth();
			$this->first = true;
			$count = count($cust_filter['Date']['data']);
			$this->position = $count >= 3 ? 'third'
				: ($count == 2 ? 'second' : 'first');
		} else {
			$date = $this->filters['Date'];
			$this->first = false;
			$this->position = $this->filters['Date_position'];
		}

		return  $date;
	}

	private function prepareQuery($date)
	{
		$month_year = array_combine(
			['year', 'month'],
			explode('-', $date)
		);

		// Prepare query
		$sql = Config::get($this->config.'.db.sql');
		$sql = str_replace(
			'%fields',
			implode(', ', array_merge(
				[Config::get($this->config.'.db.field.main')],
				array_keys(Config::get($this->config.'.db.field.base'))
			)),
			$sql
		);
		$sql = str_replace('%month', $month_year['month'], $sql);
		$sql = str_replace('%year', $month_year['year'], $sql);

		return $sql;
	}

	private function data($date)
	{
		$data = [];

		try {
			$pdo = DB::reconnect(Config::get($this->config.'.db.conn'))->getPdo();
			$q = $pdo->prepare($this->prepareQuery($date));
			$q->execute();

			//@todo cache query
			while ($row = $q->fetch()) {
				$field = $row[Config::get($this->config.'.db.field.main')];

				$data[$field]['data'][] = array_intersect_key(
					$row,
					Config::get($this->config.'.db.field.base')
				);
			}
			DB::disconnect(Config::get($this->config.'.db.conn'));

		} catch(Exception $e) {
			return [];
		}

		return $data;
	}
}
