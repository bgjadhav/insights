<?php
class ExchangeHealthCheckContinents extends Tile
{
	protected $from = 'COUNTRY_BY_EXCHANGE a';

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
			'Exchanges'		=> Filter::getExchange(),
			'Continents'	=> Filter::getContinents()
		];
	}

	public function data()
	{
		$results1 = parent::data();

		$data = [
			'Media Cost' => [
				'Columns' => ['Date', 'Media Cost'],
				'Yesterday' => [],
				'Last 7 Days' => []
			],
			'Impressions' => [
				'Columns' => ['Date', 'Impressions'],
				'Yesterday' => [],
				'Last 7 Days' => []
			]
		];

		foreach($results1 as $key => $result) {
			$day = $key == 0 ? 'Yesterday' : 'Last 7 Days';
			array_push($data['Media Cost'][$day], [
				$result->MM_DATE, '$' . number_format($result->MC)
			]);
			array_push($data['Impressions'][$day], [
				$result->MM_DATE, number_format($result->I)
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
			'sum(a.IMPRESSIONS) as I',
			'sum(a.MEDIA_COST) as MC'
		];
		$this->join = ['INNER JOIN avails.meta_country_extended d '
			.'ON (LOWER(a.COUNTRY) = LOWER(d.country_name))'];
		$this->group = ['a.MM_DATE'];
		$this->order = ['a.MM_DATE desc'];
	}

	public function getViewFilters()
	{
		return ['Exchange, Continent'];
	}
}
