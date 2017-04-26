<?php
class TrackController extends Controller implements TrackInterface
{
	public function sendTrack($representation, $report)
	{
		if ( App::environment('production') && !User::hasRole(['Insights']) ) {
			$methods = Config::get('reports/controller.methods');

			if ($methods[$representation] != 'error') {
				Queue::push('StatsController@insertTrack', [
					'report'=> $report,
					'user' 	=> User::basicInfo(),
					'action'=> $representation,
					'data'	=> (array)Input::get('data'),
					'success'=> Input::get('success')
					]
				);
			}
		}
	}

	public static function analizePidsReport($report, $pattern, $reportName, $manual = null)
	{
		if ($manual!=true) {
			Queue::push('KillPidsController@killPids', [
					'conn'		=> $report->getConnection(),
					'id'		=> $report->getIdProcess(),
					'pattern'	=> $pattern,
					'report'	=> $reportName
				]
			);
		}
	}

}
?>
