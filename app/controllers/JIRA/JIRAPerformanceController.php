<?php
class PerformanceJIRAController extends Controller
{
	public function category()
	{
		return Response::make('Unauthorized', 403);
	}

	public function index($report)
	{
		try {

			$config = $this->configReport($report);

			if (!User::hasRole($config['role'])) {
				return Response::make('Unauthorized', 403);
			}

			$performanceName = $this->performanceName($report);

			return $this->init(new $performanceName, $config);

		} catch(Exception $e ) {

			return Response::json(['success' => false, 'error' => 'Error Index.']);
		}
	}

	protected function configReport($report)
	{
		$level = 'reports/report.jira.sub.performance-metrics.report';

		$config = Config::get($level);

		$allIds = array_column($config, 'id');

		if (($key = $this->reportIndexInConfig($report, $allIds)) !== false) {

			return $config[$key];

		} else {
			throw new Exception('Unauthorized.');
		}
	}

	protected function reportIndexInConfig($report, $allIds)
	{
		return array_search($report, $allIds);
	}


	protected function performanceName($report)
	{
		$report = str_replace('-jira-', '-JIRA-', $report);

		$report = str_replace('-', ' ', $report);

		$report = ucwords($report);

		return str_replace(' ', '', $report);
	}

	protected function init(ProjectJIRAPerformanceInterface $project, $config)
	{
		View::addExtension('handlebars', 'php');

		return View::make('jira.performance.'.$project->projectName())
			->with('project', $project->projectName())
			->with('parent', 'jira')
			->with('date_picker', $project->datePicker($config))
			->with('filters', $project->filters($config))
			->with('ulrLevel', '../../../')
			->with('menu_load', Input::get('load'))
			->with('sections', $config)
			->with('sub', true);
	}


}
?>
