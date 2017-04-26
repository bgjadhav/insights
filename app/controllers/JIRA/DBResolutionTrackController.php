<?php
class DBResolutionTrackController extends Controller implements TrackInterface
{
	public function sendTrack($representation, $report)
	{
		try {
			Queue::push('PROPSStatsController',
				[
				'report'=> $report,
				'user' 	=> User::basicInfo(),
				'action'=> $representation,
				'environment'=> Enviroment::name(),
				'data'	=> (array)Input::get('data'),
				'success'=> Input::get('success')
				]
			);
		} catch(Exception $e) {

		}
	}

	public function track($representation)
	{
		$this->sendTrack($representation, 'DBResolutionAnalytics');
	}
}
