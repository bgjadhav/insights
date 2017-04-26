<?php
class DataTechHealthCheck extends Tile
{
	private $timeFrame = '';
	protected $from = 'DATA_TECH_DASHBOARD';
	private $vendor = [];
	private $months = [];
	private $target = 0;
	private $mtarget = 0;

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'search'		=> false,
			'pagination'	=> false,
			'scrollY'		=> '1245px',
			'type'			=> ['mix'],
			'download'		=> false,
			'total'			=> false,
			'group'			=> 1,
			'uniqueFilter'	=> ['Timeframe', 'Partner'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Timeframe' => [
				[
				'MTD'		=> 'MTD',
				'Trailing30'=> 'Trailing30',
				'Trailing7' => 'Trailing7',
				],
				[
				'Trailing30',
				'Trailing7'
				]
			],
			'Partner' => $this->getVendorDataTech()
		];
	}

	public function setQuery($options)
	{
		$vendor = Filter::getVendorDataTech();
		$this->setTimeFrame($options['filters']['Timeframe'][0]);
		$this->setTarget($options['filters']['Partner']);
		$this->setVendor($options['filters']['Partner']);
		$this->where = [
			'VENDOR_ID'	=> 'VENDOR_ID IN ('.Format::id($this->vendor).')',
			'MM_DATE'	=> 'MM_DATE >= \''.$this->getMinDate(). '\' AND MM_DATE < \'' .date('Y-m-d').'\''
		];
	}

	public function buildQuery($default = true, $fields = [])
	{
		return $this->getIdProcess().'SELECT MM_DATE, VENDOR_ID, VENDOR_NAME, ADVERTISER_ID,'
		. 'ADVERTISER_NAME, ORGANIZATION_ID, ORGANIZATION_NAME,'
		. 'SUM(IMP_COUNT) as IMP_COUNT, SUM(VENDOR_COST) as VENDOR_COST '
		. ' FROM '.$this->from .' WHERE '.implode(' AND ', $this->where)
		. ' GROUP BY MM_DATE, VENDOR_ID, ADVERTISER_ID, ORGANIZATION_ID';
	}

	private function getMinDate()
	{
		return min(date('Y-m-d', strtotime('-30 days')), $this->timeFrame);
	}

	private function getVendorDataTech()
	{
		$vendor = Filter::getVendorDataTech();
		$disabled = [];
		$active = [];

		array_walk($vendor, function($value, $key) use (&$active) {
			$value = trim($value);
			$active[$value] = !isset($active[$value]) ?'xx'.$key :$active[$value].'&'.$key;
		});

		$disabled = $active;
		if (isset($disabled['AddThis'])) unset($disabled['AddThis']);
		else  array_shift($disabled);

		$active = array_flip($active);
		asort($active);

		return [
			$active,
			$disabled
		];
	}

	private function setTimeFrame($timeframe)
	{
		switch ($timeframe) {
			case 'MTD' :
				$this->timeFrame = date('Y-m-01', strtotime('-1 days'));
			break;
			case 'Trailing30':
				$this->timeFrame = date('Y-m-d', strtotime('-30 days'));
			break;
			default:
				$this->timeFrame = date('Y-m-d', strtotime('-7 days'));
		}
	}

	private function setTarget($partner)
	{
		if (in_array(632, $partner)) {
			$this->target = 2400;
			$this->mtarget = 70000;
		}
	}

	private function setVendor($partner)
	{
		$this->vendor = explode('&', Format::id($partner));
	}

	public function getDataMix()
	{
		$lastWeek = Format::lastWeek();
		$output = $this->getOutputMix($lastWeek);
		return ['data' => [
			'Overall' => [
				'type' => 'basictable',
				'full' => false,
				'table' => array_merge(
					['title' => 'Overall Usage and Spend'],
					['columns' => ['Category', 'Impressions', 'Spend']],
					$output['Overall'],
					['totals' => [1 => 0, 2 => 0]]
				),
				'formats' => [1 => 'number', 2 => 'money']
			],
			'Entities' => [
				'type' => 'basictable',
				'full' => false,
				'table' => array_merge(
					['title' => 'Active Entities'],
					['columns' => ['Category', 'Organisations', 'Advertisers']],
					$output['Entities'],
					['totals' => [1 => 0, 2 => 0]]
				),
				'formats' => [1 => 'number', 2 => 'number']
			],
			'TopAdv' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
						$output['topAdv'],
						['title' => 'Top 5 Advertisers - Trailing 7 Day Spend'],
						['columns' => array_merge(['Advertiser'], $lastWeek, ['T7D', 'MTD'])],
						['totals' => false]
				),
				'formats' => [1 => 'money', 2 => 'money', 3 => 'money',
							4 => 'money', 5 => 'money', 6 => 'money', 7 => 'money',
							8 => 'money', 9 => 'money']
			],
			'TopOrg' => [
				'type' => 'basictable',
				'full' => true,
				'table' => array_merge(
						$output['topOrg'],
						['title' => 'Top 5 Organisations - Trailing 7 Day Spend'],
						['columns' => array_merge(['Organisation'], $lastWeek, ['T7D', 'MTD'])],
						['totals' => false]
				),
				'formats' => [1 => 'money', 2 => 'money', 3 => 'money',
							4 => 'money', 5 => 'money', 6 => 'money', 7 => 'money',
							8 => 'money', 9 => 'money']
			],
			'Trailing7' => [
				'id'		=> 'Trailing7',
				'title'		=> 'Trailing 7 Days Revenue Vs Target',
				'type'		=> 'stack',
				'info'		=> 'Showing % of spend Data/Tech direct revenue breakout by month.',
				'data'		=> $output['trailing7'],
				'categories' => $lastWeek
			],
			'TrailingMonths' => [
				'id'		=> 'TrailingMonths',
				'title'		=> 'Trailing 6 Months Revenue Vs Target',
				'type'		=> 'stack',
				'info'		=> 'Showing % of spend Data/Tech direct revenue breakout by month.',
				'data'		=> $this->getDataMonth(),
				'categories' => $this->months
			]
		]];
	}

	private function getOutputMix($lastWeek)
	{
		$dates = [
			'mtd' => date('Y-m-01', strtotime('-1 days')),
			'top7' => date('Y-m-d', strtotime('-7 days'))
		];
		$tops = [
			'ADVERTISER_NAME' => 'topAdv',
			'ORGANIZATION_NAME' => 'topOrg'
		];
		$output = $data = $topOrg = $topAdv = [];
		$results = parent::data();
		foreach ($results as $row) {
			$key = array_search($row->{'VENDOR_ID'}, $this->vendor);
			if ($row->{'MM_DATE'} >= $this->timeFrame) {
				$this->setOverral($output, $key, $row);
				$this->setEntities($output, $key, $row);
			}
			if (in_array($row->{'MM_DATE'}, $lastWeek)) {
				$this->setTops($topAdv, $topOrg, $row, $tops);
				$this->setTrailing7($lastWeek, $row, $output, $key);
			}
			$this->setDataTop($dates, $data, $row, $tops);
		}
		unset($results);
		$this->setSumEntities($output);
		$output = array_merge($output, $this->getTops(
			$tops,
			$topOrg,
			$topAdv,
			$data
		));
		unset($data);
		unset($topOrg);
		unset($topAdv);
		return $output;
	}

	private function setSumEntities(&$output)
	{
		foreach ($this->vendor as $key => $id) {
			$output['Entities']['rows'][$key][1] = array_sum(
				$output['Entities']['rows'][$key][1]
			);
			$output['Entities']['rows'][$key][2] = array_sum(
				$output['Entities']['rows'][$key][2]
			);
		}
	}

	private function setTrailing7($lastWeek, $row, &$output, $key)
	{
		if (!isset($output['trailing7'])) {
			$output['trailing7'] = [
				0 => [
					'data' => [],
					'name' => ''
				],
				1 => [
					'data' => [],
					'name' => ''
				],
				2 => [
					'data' =>  [$this->target],
					'name' => 'Daily Target'
				]
			];
		}
		if (($key_week = array_search($row->{'MM_DATE'}, $lastWeek)) !== false) {
			if (isset($output['trailing7'][$key]['data'][$key_week])) {
				$output['trailing7'][$key]['data'][$key_week] += $row->{'VENDOR_COST'};
			} else {
				$output['trailing7'][$key]['name'] = $row->{'VENDOR_NAME'};
				$output['trailing7'][$key]['data'][$key_week] = (double)$row->{'VENDOR_COST'};
			}
		}
	}

	private function setDataTop($dates, &$data, $row, $tops)
	{
		array_walk($dates, function($date, $kDate) use (&$data, $row, $tops) {
			if ($row->{'MM_DATE'} >= $date) {
				array_walk($tops, function($top, $kTop) use (&$data, $row, $kDate) {
					if (isset($data[$kDate][$top][$row->{$kTop}][$row->{'MM_DATE'}])) {
						$data[$kDate][$top][$row->{$kTop}][$row->{'MM_DATE'}] += $row->{'VENDOR_COST'};
					} else {
						$data[$kDate][$top][$row->{$kTop}][$row->{'MM_DATE'}] = $row->{'VENDOR_COST'};
					}
				});
			}
		});
	}

	private function setOverral(&$output, $key, $row)
	{
		if (isset($output['Overall']['rows'][$key])) {
			$output['Overall']['rows'][$key][1] += $row->{'IMP_COUNT'};
			$output['Overall']['rows'][$key][2] += $row->{'VENDOR_COST'};
		} else {
			$output['Overall']['rows'][$key] = [
				0 => $row->{'VENDOR_NAME'},
				1 => $row->{'IMP_COUNT'},
				2 => $row->{'VENDOR_COST'}
			];
		}
	}

	private function setEntities(&$output, $key, $row)
	{
		if (!isset($output['Entities']['rows'][$key])) {
			$output['Entities']['rows'][$key] = [
				0 => $row->{'VENDOR_NAME'},
				1 => [$row->{'ORGANIZATION_ID'} => 1],
				2 => [$row->{'ADVERTISER_ID'} => 1]
			];
		} else  {
			$output['Entities']['rows'][$key][1][$row->{'ORGANIZATION_ID'}] = 1;
			$output['Entities']['rows'][$key][2][$row->{'ADVERTISER_ID'}] = 1;
		}
	}

	private function setTops(&$topAdv, &$topOrg, $row, $tops)
	{
		array_walk($tops, function($top, $kTop) use (&$topAdv, &$topOrg, $row) {
			$val = $row->{'VENDOR_COST'};
			if (isset(${$top}[$row->{$kTop}])) {
				$val += ${$top}[$row->{$kTop}];
			}
			${$top}[$row->{$kTop}] = $val;
		});
	}

	private function getTops($tops, $topOrg, $topAdv, $data)
	{
		$output = [];
		arsort($topOrg);
		arsort($topAdv);
		foreach ($tops as $top) {
			$i = 0;
			foreach (${$top} as $key => $t_val) {
				$output[$top]['rows'][$i] = [
					0 => $key,
					1 => 0,
					2 => 0,
					3 => 0,
					4 => 0,
					5 => 0,
					6 => 0,
					7 => 0,
					8 => isset($data['top7'][$top][$key])
						? array_sum($data['top7'][$top][$key]) : 0,
					9 => isset($data['mtd'][$top][$key])
						? array_sum($data['mtd'][$top][$key]) : 0
				];
				$i_date = 1;
				if (isset($data['top7'][$top][$key])) {
					foreach ($data['top7'][$top][$key] as $date => $val) {
						$output[$top]['rows'][$i][$i_date] = (double)$val;
						$i_date++;
					}
				}
				$i++;
				if ($i == 5) {break;}
			}
		}
		return $output;
	}

	private function getDataMonth()
	{
		$results = QueryService::run(
			$this->getIdProcess()
			.'SELECT SUBSTRING(MM_DATE, 6, 2) as MONTH, VENDOR_ID, VENDOR_NAME'
			. ', SUM(IMP_COUNT) as IMP_COUNT, SUM(VENDOR_COST) as VENDOR_COST '
			. ' FROM '.$this->from
			.' WHERE '.$this->where['VENDOR_ID']
			. ' AND MM_DATE >='.date('Y-m-01', strtotime('-6 months'))
			. ' AND MM_DATE < \'' .date('Y-m-d').'\''
			. ' GROUP BY SUBSTRING(MM_DATE, 6, 2), VENDOR_ID'
			. ' ORDER BY VENDOR_NAME, MM_DATE',
			$this->conn
		);

		$data = [
			0 => [
				'data' => [],
				'name' => ''
			],
			1 => [
				'data' => [],
				'name' => ''
			],
			2 => [
				'data' => [$this->mtarget],
				'name' => 'Target'
			]
		];
		foreach ($results as $row) {
			$month = (int)$row->{'MONTH'};
			$this->months[] = $month;
			$this->months = array_unique($this->months);
			$key = array_search($row->{'VENDOR_ID'}, $this->vendor);
			$key_month = array_search($month, $this->months);
			if (isset($data[$key]['data'][$key_month])) {
				$data[$key]['data'][$key_month] += $row->{'VENDOR_COST'};
			} else {
				$data[$key]['name'] = $row->{'VENDOR_NAME'};
				$data[$key]['data'][$key_month] = (double)$row->{'VENDOR_COST'};
			}
		}

		foreach ($this->months as &$m) {
			$m = date('M', strtotime('2015-'.$m.'-01'));
		}
		return $data;
	}

}
