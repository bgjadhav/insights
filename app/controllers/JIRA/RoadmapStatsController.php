<?php
class RoadmapStatsController extends Controller
{
	public function index()
	{
		return View::make('jira.stats.index')->with('project', 'stats');
	}

	public function wrapperjson($representation, $reportName)
	{
		$report = new $reportName();
		try {
			$data = array_replace(
				ConfigReport::base(),
				$report->options($report->filters())
			);
			$data['pid'] = time();
			$data['report_status'] = StatusReport::get($reportName);

			return Response::json($data);

		} catch(Exception $e) {
			echo Response::json(
				Format::dashError(false, 'Wrapper error occured.')
			);
			die();
		}
	}

	public function datajson($representation, $reportName, $filters = null, $manual = null, $extras = true)
	{
		return self::dataReport(
				$representation,
				$reportName,
				FilterReport::get($filters),
				$manual,
				$extras
		);
	}

	public static function dataReport($representation, $reportName, $filters = null, $manual = null, $extras = true)
	{
		$pattern = Session::get('user_id').$filters['pid'];
		$report = new $reportName($pattern);

		TrackController::analizePidsReport($report, $pattern, $reportName, $manual);

		$report->setQuery($filters);

		$options = FilterReport::options(
			ConfigReport::base(),
			$report->options(false),
			$filters
		);

		if ($extras) {
			return array_merge(
				self::tData($filters, $report, $options),
				self::tDataExtra($report, $filters, $reportName, $options)
			);
		} else {
			return self::tData($filters, $report, $options);
		}
	}

	private static function tData($filters, $report, $options)
	{
		$methods = ConfigReport::methods();
		if (($method = $methods[$filters['type']]) != 'error') {
			return $report->$method($options);
		} else {
			self::datajsonError();
		}
	}

	private static function tDataExtra($report, $filters, $reportName, $options)
	{
		return [
			'ufView' => $report->getViewFilters(),
			'notice' => StatusReport::getNotice(
				Format::clearAlias($report->getFrom()),
				$report->getConnection(),
				$filters,
				$reportName,
				$report->getSumTotal()
			),
			'legend' => LegendReport::getLegend(
				$reportName,
				$filters['type']
			)
		];
	}

	private static function datajsonError()
	{
		echo Response::json(
			Format::dashError(false, 'Error with type of view.', 'Type no valid')
		);
		die();
	}
}
?>
