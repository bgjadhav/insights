<?php
class StatsController
{

	public function fire()
	{
	}

	public function insertTrack($job, $data)
	{
		if ($job->attempts() < 2) {
			$usage = new DashboardUsage;
			$usage->MM_DATE		= date('Y-m-d H:i:s');
			$usage->REPORT_ID	= $data['report'];
			$usage->USER_ID		= $data['user']['uid'];
			$usage->FIRST_NAME	= $data['user']['uname'];
			$usage->LAST_NAME	= $data['user']['ulastn'];
			$usage->USER_TYPE	= $data['user']['utype'];
			$usage->ACTION		= $data['action'];
			$usage->ACTION_DATA	= implode(', ', $data['data']);
			$usage->SUCCESS		= $data['success'];
			$usage->save();
		}
		$job->delete();
	}

	public function queryTime($job, $data)
	{
		if ($job->attempts() < 2) {
			$qTime = new QueryTime;
			$qTime->query		= $data['query'];
			$qTime->time_taken	= Format::timeFinal($data['diff']);
			$qTime->page		= $data['page'];
			$qTime->save();
		}
		$job->delete();
	}

}
?>
