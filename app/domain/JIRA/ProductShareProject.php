<?php
class ProductShareProject extends ProductClickImp
{

	public function check()
	{
		$result = $this->addOneIfExistInData('share_project');

		if ($result === true) {
			$this->storageCounterShare();
		}
	}

	private function storageCounterShare()
	{
		$share = SharedProject::firstOrNew([

			'mm_date' => $this->data['day'],

			'user_id' => $this->data['user']['uid'],

			'environment' =>  $this->data['environment'],

			'process' =>  'project',

			'issue_id' => $this->data['data']['tid']
		]);

		if (!$share->id) {

			$share->total = 1;

		} else {
			$share->total = $share->total + 1;
		}

		$share->full_name = trim($this->data['user']['uname'].' '.$this->data['user']['ulastn']);

		$share->email = $this->data['user']['email'];

		$share->save();
	}
}
