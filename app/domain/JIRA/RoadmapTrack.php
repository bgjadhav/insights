<?php
class RoadmapTrack implements TrackInterface
{
	public function sendTrack($representation, $report)
	{
		try {

			$data = $this->data($representation, $report);

			Queue::push('PROPSStatsController', $data);


		} catch(Exception $e) {
		}
	}

	protected function data($representation, $report)
	{
		return [
			'report'=> $report,
			'user' 	=> User::basicInfo(),
			'action'=> $representation,
			'environment'=> Enviroment::name(),
			'data'	=> $this->fullInputs(),
			'success'=> 1
		];
	}

	protected function fullInputs()
	{
		return (array)http_build_query((array)Input::all());
	}
}
