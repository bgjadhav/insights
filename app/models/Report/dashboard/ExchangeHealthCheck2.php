<?php
class ExchangeHealthCheck2 extends Tile
{
	protected $from = 'BIDS_BY_COUNTRY_BY_EXCHANGE a';
	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'type'			=> ['small-table'],
			'download'		=> false,
			'pagination'	=> false,
			'total'			=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges' => Filter::getExchange(),
			'Continents' => Filter::getContinents()
		];
	}

	public function data()
	{
		$results2 = parent::data();

		$data = [
			'Average Bid' => [
				'Columns' => ['Date', 'Average Bid'],
				'Yesterday' => [],
				'Last 7 Days' => []
			],
			'Average Win' => [
				'Columns' => ['Date', 'Average Win'],
				'Yesterday' => [],
				'Last 7 Days' => []
			],
			'Win Rates' => [
				'Columns' => ['Date', 'WIN RATE'],
				'Yesterday' => [],
				'Last 7 Days' => []
			]
		];

		foreach ($results2 as $key => $result) {
			$day = $key == 0 ? 'Yesterday' : 'Last 7 Days';
			array_push($data['Average Bid'][$day], [
				$result->MM_DATE, '$' . number_format($result->AVGB, 2)
			]);
			array_push($data['Average Win'][$day], [
				$result->MM_DATE, '$' . number_format($result->AVGW, 2)
			]);
			array_push($data['Win Rates'][$day], [
				$result->MM_DATE, number_format(
					$result->WINS/$result->BIDS*100,
					1
				).'%'
			]);
		}
		return $data;
	}

	public function setQuery($options)
	{
		$continents = Format::str($options['filters']['Continents']);
		if (strpos($continents,'Not Yet Classified') !== false) {
			$continent = 'OR d.continent IS NULL)';
		} else {
			$continent = ')';
		}
		$continents = str_replace('Not Yet Classified', null, $continents);
		$this->where = [
			'Date'			=> 'a.MM_DATE >= CURRENT_DATE - interval 8 day',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('
				.Format::id($options['filters']['Exchanges']).')',
			'continent'		=> '(d.continent IN('.$continents.') '.$continent
		];
		$this->field = [
			'a.MM_DATE as MM_DATE',
			'sum(a.WINS+a.LOSSES) as BIDS',
			'sum(a.WINS) as WINS',
			'avg(a.AVGBID_CPM) as AVGB',
			'avg(a.AVGWIN) as AVGW'
		];
		$this->join = [
			'INNER JOIN COUNTRY_BIDDER c ON (a.COUNTRY_ID = c.BIIDER_CODE)',
			'INNER JOIN avails.meta_country_extended d '
				.'ON (LOWER(c.COUNTRY) = LOWER(d.country_name))'
		];
		$this->group = ['a.MM_DATE'];
		$this->order = ['a.MM_DATE DESC'];
	}

	public function getViewFilters()
	{
		return ['Exchange, Continent'];
	}
}
