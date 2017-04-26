<?php
class KillPidsController
{
	public function fire()
	{
	}

	public function killPids($job, $data)
	{
		if ($job->attempts() < 2) {
			if ($data['pattern'] !='') {
				self::killPids_process($data);
			}
		}
		$job->delete();
	}

	public static function killPids_process($data)
	{
		$pattern = isset($data['report']) ?$data['pattern'].$data['report'] :$data['pattern'];
		$sql = self::killPids_query($pattern, $data['id']);

		$query	= DB::reconnect($data['conn'])->select(DB::raw($sql));
		DB::disconnect($data['conn']);

		$toKill	= [];
		$delay	= true;
		foreach ($query as $q) {
			$idQuery = explode('*/', $q->INFO);
			if ($data['id'] > $idQuery[0].'*/' || $data['id'] === false) {
				$toKill[] = $q->ID;
			} elseif ($delay) {
				//If There is an other new query greater than this id with the same pattern
				// means this id is older as well
				$toKill[] = $data['id'];
				$delay = false;
			}
		}

		if (!empty($toKill)) {
			foreach ($toKill as $id) {
				try {
					DB::reconnect($data['conn'])->statement('KILL QUERY '. $id);
					DB::disconnect($data['conn']);

				} catch(Exception $e) {
				}
			}
		}
	}

	public static function killPids_query($p, $i=false)
	{
		$sql = 'SELECT ID, INFO FROM information_schema.processlist WHERE INFO LIKE \'/*'.$p.'%\'';
		if ($i!=false) $sql .= ' AND INFO NOT LIKE \''.$i.'%\'';
		return $sql;
	}

}
?>
