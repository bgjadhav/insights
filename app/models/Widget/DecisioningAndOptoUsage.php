<?php
class DecisioningAndOptoUsage
{
	public function widget()
	{
		$results = $this->resultFormated();

		$data = $this->confiData($results);

		$data = $this->apendGoals($results, $data);

		$data = $this->appendCategories($data);

		$data = $this->appendTabs($data);

		return $data;
	}

	private function confiData($results)
	{
		return [
			'data' => [
				[// count
					[
						'name' => 'Any Opto',
						'dashStyle' => 'dash',
						'data' => []
					],

					[
						'name' => 'Right Brain',
						'dashStyle' => 'Solid',
						'data' => []
					]
				],
				[// spend
					[
						'name' => 'Any Opto',
						'dashStyle' => 'dash',
						'data' => []
					],
					[
						'name' => 'Right Brain',
						'dashStyle' => 'Solid',
						'data' => []
					],
				]
			],
			'success' => true,
			'title' => 'Decisioning and Opto Usage',
			'type' => 'chart',
			'info' => 'Showing the count of and spend against strategies using brain optimisation in Terminal One by month',
			'chart' => array(
				'type' => 'line',
				'cut' => false,
				'categories' => array_values(array_unique(array_column($results, 'date'))),
				'tabs' => [
					'Count',
					'Spend'
				],
				'formats' => [
					'number',
					'money'
				],
			),
			'more_charts' => true
		];
	}

	private function apendGoals($results, $data)
	{
		array_walk($results, function($row) use (&$data) {

			$goal = $row['type'] == 'Right Brain' ? (int)1 : (int)0;
			$cat = array_search($row['date'], $data['chart']['categories']);

			$data['data'][0][$goal]['data'][$cat] = (int)$row['count'];
			$data['data'][1][$goal]['data'][$cat] = (float)$row['billed_spend'];
		});

		return $data;
	}

	private function appendCategories($data)
	{
		array_walk($data['chart']['categories'], function(&$cat) {
			$cat = date('M', strtotime($cat.'-01'));
		});
		return $data;
	}

	private function appendTabs($data)
	{
		array_walk($data['chart']['tabs'], function($tab, $id) use(&$data) {
			array_walk($data['data'][$id], function(&$val) use($tab) {
				$val['name'] .= ' media '. strtolower($tab);
			});
		});
		return $data;
	}

	private function results()
	{
		return DB::reconnect('dashboard')
			->table('decisioning_and_opto_usage')
			->select('date', 'type', 'count', 'billed_spend')
			->where('date', '>=', $this->dateFilter())
			->where('date', '>=', '2015-11')
			->orderBy('date', 'asc')
			->remember(Format::timeOut())
			->get();
	}

	private function dateFilter()
	{
		return (date('Y', strtotime('-1 days'))-1)
			. date('-m-', strtotime('-1 days'));
	}

	private function resultFormated()
	{
		$results = $this->results();
		return json_decode(json_encode($results), true);
	}
}
