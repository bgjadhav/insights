<?php
class RoadmapSubscriptionController extends Controller
{
	public function subscription()
	{
		$data = $this->data($this->susbcribeInput());

		try {

			$this->storage($data);

		} catch (Exception $e) {

			$this->keepDataSendLogByEmail($data);

			return 'conflict';
		}
	}

	private function storage($data)
	{
		$this->addSubscriptionLog($data);

		$this->addOrDeletedSubscription($data);

		return 'done';
	}

	private function addOrDeletedSubscription($data)
	{
		if ($data['status'] == 'none') {

			$this->deleteSubscription($data['user_id']);

		} else {
			$this->updatedSubscription($data);
		}
	}

	private function susbcribeInput()
	{
		$susbcribe = trim(Input::get('susbcribe'));

		if (!in_array($susbcribe, ['none', 'weekly'])) {
			throw new Exception('Error input.');

		} else {
			return $susbcribe;
		}
	}

	private function data($susbcribe)
	{
		try {
			$data = [
				'user_id' => User::MMid(),
				'status' => $susbcribe,
				'created' => date('Y-m-d H:h:s'),
				'user_email' => User::email(),
				'user_full_name' => User::fullName()
			];

			return $data;

		} catch (Exception $e) {
			throw new Exception('Error data.');
		}
	}

	private function addSubscriptionLog($data)
	{
		try {

			$usage = new ProdSubscriptionLog;

			//$usage = new ProsdSubscriptionLog;  //to reproduce error case

			$usage->user_id = $data['user_id'];

			$usage->status = $data['status'];

			$usage->created = $data['created'];

			$usage->full_name = $data['user_full_name'];

			//$usage->created = $data['creaated']; //to reproduce conflict case, uncomment email

			$usage->save();

		} catch (Exception $e) {
			throw new Exception('Error save log.');
		}
	}

	private function deleteSubscription($user_id)
	{
		try {
			ProdSubscription::where('user_id', '=', $user_id)
				->delete();

		} catch (Exception $e) {
			throw new Exception('Error delete.');
		}
	}

	private function updatedSubscription($data)
	{
		if ($this->userHasSubscription($data['user_id'])) {

			$this->updateSubscription($data);

		} else {

			$this->addSubscription($data);

		}
	}

	private function userHasSubscription($user_id)
	{
		try {
			return ProdSubscription::where('user_id', '=', $user_id)
				->exists();
		} catch (Exception $e) {
			throw new Exception('Error has subscription.');
		}
	}

	private function updateSubscription($data)
	{
		try {
			ProdSubscription::where('user_id', '=', $data['user_id'])
				->update(['status' => $data['status']]);

		} catch (Exception $e) {
			throw new Exception('Error update subscription.');
		}
	}

	private function addSubscription($data)
	{
		try {
			$usage = new ProdSubscription;

			$usage->user_id = $data['user_id'];

			$usage->status = $data['status'];

			$usage->user_email = $data['user_email'];

			$usage->user_full_name = $data['user_full_name'];

			$usage->save();

		} catch (Exception $e) {
			throw new Exception('Error save subscription.');
		}
	}

	private function keepDataSendLogByEmail($data)
	{
		try {

			Mail::queue(
				'jira.product.roadmap.email.keepLog',

				[
					'data' => $data,
					'description' => ''
				],

				function ($message) use ($data) {

					$message->to('koruequiroz@mediamath.com', $data['user_full_name'])

						->cc('koruequiroz@mediamath.com', 'Product Operations') // to test

						//->cc('product-operations@mediamath.com', 'Product Operations') // to test with props

						->from('insights_noreply@mediamath.com', 'Insights')

						->subject(EmailFormat::subscription($data));
				}
			);

		} catch (Exception $e) {
			throw new Exception('Error send email.');
		}
	}
}
