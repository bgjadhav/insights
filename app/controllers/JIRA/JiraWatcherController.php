<?php

class JiraWatcherController extends JiraController implements JiraFunctionallityInterface
{
	public function __construct()
	{
		try {


			$response = $this->jiraLoginCurl(
				[
					'username' => $this->username,
					'password' => $this->password
				]
			);

			$response = json_decode($response);

			$this->j_token = $response->session_id;

		} catch (Exception $e) {
			return Response::json(['result'=> 'error']);
		}
	}

	public function add()
	{
		if (!empty($this->j_token)) {

			try {

				$response = $this->OpenAppsJiraPostCurl(

					'/watchers?jira_session_id='.$this->j_token,

					[
						'key' => $this->issueId(),
						'user' => $this->userId()
					]

				);

				return $this->statusReponse($response);

			} catch (Exception $e) {
				return Response::json(['result'=> 'error']);
			}

		} else {
			return Response::json(['result'=> 'error']);
		}
	}

	private function statusReponse($response)
	{
		if ($this->isDone($response)) {
			return Response::json(['result'=> 'OK']);

		} elseif($this->doesntHavePermission($response)) {
			return Response::json(['result'=> 'error']);

		} else {
			return Response::json(['result'=> 'error']);
		}
	}

	private function isDone($response)
	{
		return $response == '' ? true : false;
	}

	private function doesntHavePermission($response)
	{
		return strpos($response, 'does not have permission to view this issue') !== false ? true : false;
	}
}
