<?php
class ExchangeHealthCheckInventory extends Tile
{
	protected $from = 'DISPLAY_VIDEO_MOBILE_STATS a';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'type'			=> ['small-table'],
			'pagination'	=> false,
			'download'	=> false,
			'total'			=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Exchanges' => Filter::getExchange(),
			'Inventory_Type' => [Filter::getInventory(),[2,3]]
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
				$result->MM_DATE, '$' . number_format($result->MC, 2)
			]);
			array_push($data['Impressions'][$day], [
				$result->MM_DATE, number_format($result->I)
			]);
		}
		return $data;
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'a.MM_DATE >= CURRENT_DATE - interval 8 day',
			'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')',
			'Inventory'		=> 'a.INVENTORY_TYPE IN('.Format::id($options['filters']['Inventory_Type']).')'
		];
		$this->field = [
			'a.MM_DATE as MM_DATE',
			'sum(a.IMPRESSIONS) as I',
			'sum(a.MEDIA_COST) as MC'
		];
		$this->group = ['a.MM_DATE'];
		$this->order = ['a.MM_DATE desc'];
	}

	public function getViewFilters()
	{
		return ['Exchange, Inventory'];
	}
}
