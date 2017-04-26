<?php
class RoadmapDownloadController extends DownloadController
{
	public function download()
	{

		try {

			if (Input::get('download') == 'current') {

				return Response::json([
					$this->fullDownload()
				]);


			} elseif (Input::get('download') == 'filtered') {
				return Response::json([
					$this->filtersDownload()
				]);


			} else {
				return [
					'error' => true,
					'success' => false,
					'data' => 'No data'
				];
			}

		} catch (Exception $e) {
			return [
				'error' => true,
				'success' => false,
				'data' => 'No data'
			];
		}
	}

	protected function fullDownload()
	{
		try {

			return $this->start(
				'Jira',
				'RoadmapAndCandidateAll',
				RoadmapFullDownload::config()
			);

		} catch(Exception $e) {
			return [
				'error' => true,
				'success' => false,
				'data' => 'Error'
			];
		}
	}

	protected function filtersDownload()
	{
		try {

			return $this->start(
				'Jira',
				'RoadmapAndCandidateFilters',
				RoadmapFiltersDownload::config()
			);

		} catch(Exception $e) {
			return [
				'error' => true,
				'success' => false,
				'data' => $e->getTraceAsString()
			];
		}
	}

	public function start($category, $reportName, $custom = false)
	{
		try {
			if ($custom === false) {
				$data = $this->data($category, $reportName);
			} else {
				$data = $custom;
			}

			if (!isset($data['extension'])) {
				$data['extension'] = 'csv';
			}

			$status = DownloadService::statusFile(
				$data['file'],
				true,
				$data['extension']
			);

			if ($status != 'ready') {
				Queue::push('DownloadService@createFileDownload', $data);
				//(new DownloadService)->createFileDownload(false, $data);
			}

			return [
				'file' => $data['file'],
				'status' => $status,
				'success' => true,
				'pidD' => $data['pattern'],
				'report'=> $data['report']
			];

		} catch(Exception $e) {
			return [
				'error' => true,
				'success' => false,
				'data' => $e->getTraceAsString()
			];
		}
	}


}
