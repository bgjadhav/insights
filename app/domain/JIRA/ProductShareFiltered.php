<?php
class ProductShareFiltered extends ProductClickImp
{
	public function check()
	{
		$result = $this->addOneIfExistInData('share_filtered');

		if ($result === true) {

			$this->storageCounterShare();

		}
	}

	private function storageCounterShare()
	{
		$share = new SharedFiltered;

		$share->mm_date = $this->data['day'];

		$share->user_id = $this->data['user']['uid'];

		$share->environment = $this->data['environment'];

		$share->process = 'filtered';

		$share->issue_ids = $this->data['data']['tids'];

		$share->full_name = trim($this->data['user']['uname'].' '.$this->data['user']['ulastn']);

		$share->email = $this->data['user']['email'];

		$share->checked = 0;

		$share->save();
	}
}
