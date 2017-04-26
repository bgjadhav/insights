
<?php
class PROPSStatsController
{

	public function fire($job, $data)
	{
		if ($job->attempts() < 2) {

			$data = self::apendDate($data);

			self::track($data);

			Event::fire(
				'roadmap.counter',

				serialize($data)
			);

		}

		$job->delete();
	}

	protected static function apendDate($data)
	{
		$data['date'] = date('Y-m-d H:i:s');

		$data['day'] = date('Y-m-d', strtotime($data['date']));

		return $data;
	}


	protected static function track($data)
	{
		$usage = new PROPSDashboardUsage;

		$usage->MM_DATE = $data['date'];

		$usage->MM_DAY = $data['day'];

		$usage->REPORT_ID = $data['report'];

		$usage->USER_ID = $data['user']['uid'];

		$usage->FIRST_NAME = $data['user']['uname'];

		$usage->LAST_NAME = $data['user']['ulastn'];

		$usage->USER_TYPE = $data['user']['utype'];

		$usage->EMAIL = $data['user']['email'];

		$usage->ACTION = $data['action'];

		$usage->ACTION_DATA = implode(', ', $data['data']);

		$usage->ENVIRONMENT = $data['environment'];

		$usage->SUCCESS = $data['success'];

		$usage->save();
	}
}
