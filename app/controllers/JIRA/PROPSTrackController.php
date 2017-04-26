<?php
class PROPSTrackController extends Controller implements TrackInterface
{
	public function sendTrack($representation, $report)
	{

		try {
			$data = [
				'report'=> $report,
				'user' 	=> User::basicInfo(),
				'action'=> $representation,
				'environment'=> Enviroment::name(),
				'data'	=> (array)Input::get('data'),
				'success'=> Input::get('success')
			];

			Queue::push('PROPSStatsController', $data);

		} catch(Exception $e) {
		}
	}

}
