<?php

class JiraController extends Controller
{

	protected $username = 'bdalgliesh';
	protected $password = 'Badger54';
	protected $j_url = 'https://open-apps.mediamath.com/apps-qa/services/jira';
	protected $raw_url = 'https://issues.mediamath.com/rest/api/2/';
	protected $j_token;

	protected function postCurl($method, $data)
	{
		$data = json_encode($data);

		$ch = curl_init($this->raw_url.$method);

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data))
		);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POST,TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);

		if ($response === false) {
			echo 'Curl error: ' . curl_error($ch);
		}

		curl_close($ch);

		return $response;
	}

	protected function OpenAppsJiraPostCurl($method, $data)
	{
		$ch = curl_init();

		$data = json_encode($data);

		curl_setopt($ch, CURLOPT_URL, $this->j_url.$method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST,TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data))
		);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		$response = curl_exec($ch);
		curl_close($ch);
		return($response);
	}

	protected function jiraLoginCurl($data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->j_url.'/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST,TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

		$response = curl_exec($ch);
		curl_close($ch);

		return($response);
	}

	protected function issueId()
	{
		return Input::get('issue_id');
	}

	protected function userId()
	{
		return trim(str_replace('@mediamath.com', '', Session::get('email_address')));
	}
}
