<?php
class UserIPController
{
	/*
	 * Success
	 * 0 - internal_only
	 * 1 - Login ok
	 * 2 - Count not connect to OPEN API
	 * */

	public function fire()
	{
	}

	public function storeUserIP($job, $data)
	{
		if ($job->attempts() < 2) {

			try {

				$user = UserIP::firstOrNew([

					'user' => $data['user'],

					'user_id' => $data['user_id'],

					'ip' => $data['ip'],

					'status' => $data['status']

				]);

				$date =  date('Y-m-d H:i:s');

				if (!$user->id) {
					$user->created_at = $date;
				}

				$user->updated_at = $date;

				$user->save();

			} catch(Exception $e) {
			}
		}

		$job->delete();
	}
}
