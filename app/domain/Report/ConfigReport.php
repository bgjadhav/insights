<?php
class ConfigReport {

	public static function classesDisplay()
	{
		$results = ReportDisplay::select('class')->get()->toArray();
		$results = array_column($results, 'class');
		return $results;
	}

	public static function typeReport()
	{
		return [
			'chart-line' => 'chart-area',
			'chart-line' => 'chart-column',
			'chart-line' => 'chart-line',
			'chart-multi' => 'chart-multi',
			'chart-line' => 'chart-pie',
			'download' => 'download',
			'filter' => 'filter',
			'mix' => 'mix',
			'small-table' => 'small-table',
			'table' => 'table',
			'view' => 'view'
		];
	}

	public static function reports()
	{
		return (object)array_merge(
			array_combine(
				self::classesDisplay(),
				self::classesDisplay()
				)
			, [
				'media Pipeline' => 'Pipeline',
				'Competitive Intel' => 'Competitive Intel'
			]
		);
	}

	public static function getDate($options, $date)
	{
		foreach (['date_start', 'date_end'] as $i => $d ) {
			if (!isset($options[$d])) {
				$options[$d] = isset($date[$i])
					? $date[$i]
					: date('Y-m-d',strtotime('yesterday'));
			}
		}
		return $options;
	}

	public static function getType($options)
	{
		$options['type'] = isset($options['type'])
								? $options['type'][0]
								: 'table';
		return $options;
	}

	public static function getPicker($options)
	{
		$date = ['start', 'end'];
		array_walk($date, function ($dType) use (&$options) {
			if (isset($options['date_picker'][$dType])) {
				$options['date_'.$dType] = date(
					'Y-m-d',
					strtotime($options['date_picker'][$dType])
				);
			}
		});
		return $options;
	}

	public static function getRange($options)
	{
		if (isset($options['range_selector'])
			&& $options['range_selector'] !== false) {
			$options['range_selector'] = 'LAST_7_DAYS';
		}
		return $options;
	}

	public static function getPods($options)
	{
		if (isset($options['pods'])) {
			$pods = [];
			array_walk_recursive($options['pods'], function($pod, $i) use(&$pods) {
				if ($i == 'name') {
					$pods[] = $pod;
				}
			});
			$options['filters']['pods'] = $pods;
		}
		return $options;
	}

	public static function getCheckboxesDevicePods($options)
	{
		if (isset($options['checkboxes']['device'])) {
			$options['filters']['device'] = array_values(
				$options['checkboxes']['device']
			);
		}
		return $options;
	}

	public static function getCheckboxesInvtypePods($options)
	{
		if (isset($options['checkboxes']['invtype'])) {
			$options['filters']['invtype'] = array_values(
				$options['checkboxes']['invtype']
			);
		}
		return $options;
	}

	public static function forceInitRepo($options, $pid)
	{
		$options['optionType']	= '';
		$options['type']		= isset($options['type'])
									? $options['type']
									: 'table';
		$options['pid']			= $pid;
		$options['page']		= 0;
		$options['sumTotal']	= 1;
		return $options;
	}

	public static function getFilter($options)
	{
		if (isset($options['filters']) && $options['filters'] !== false) {
			array_walk($options['filters'], function (&$value){
				$default = $value;
				if (isset($value[1]) && is_array($value[1])) {
					array_walk($value[1], function ($toDelete) use(&$value) {
						unset($value[0][$toDelete]);
					});
					$default = $value[0];
				}
				$value = array_keys(array_filter($default));
				array_walk($value, function (&$val) {
					$val = preg_replace('/^xx/', '', $val);
				});
			});
		}
		return $options;
	}

	public static function tables() {

		$config = self::classesDisplay();
		$data = [];

		array_walk($config, function($report) use (&$data) {
			if (class_exists($report)) {
				$tile = new $report();
				$t = Format::clearDataBaseFrom(
					Format::clearAlias($tile->getFrom())
				);
				$data[$t] = $t;
			}
		});
		asort($data);
		return $data;
	}

	public static function reportsJIRA()
	{
		return [
			'PipelineMedia'		=> 'PipelineMedia',
			'PipelineData'		=> 'PipelineData',
			'CompetitiveIntel'	=> 'CompetitiveIntel',
			'roadmap'			=> 'Roadmap',
			'candidates'			=> 'Candidates',
			'requests'			=> 'Requests'
		];
	}

	public static function methods()
	{
		return Config::get('reports/controller.methods');
	}

	public static function base()
	{
		return Config::get('reports/controller.options');
	}


	public static function validateOptionsTile($options, $date, $pid)
	{
		$options = self::getPicker($options);
		$options = self::getRange($options);
		$options = self::getFilter($options);
		$options = self::cleanFilters($options);
		$options = self::getType($options);
		$options = self::getDate($options, $date);
		$options = self::getPods($options);
		$options = self::getCheckboxesDevicePods($options);
		$options = self::getCheckboxesInvtypePods($options);
		$options = self::forceInitRepo($options, $pid);
		return $options;
	}

	public static function cleanFilters($options)
	{
		$final_option = $options;

		if (isset($options['filters'])) {

			$final_option['filters'] = [];

			foreach($options['filters'] as $name_filter => $item) {

				$clean_name_filter = str_replace(' ', '_', $name_filter);

				$final_option['filters'][$clean_name_filter] = $item;
			}
		}

		return $final_option;
	}


}
