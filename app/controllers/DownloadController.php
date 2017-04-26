<?php
class DownloadController extends Controller
{
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

			$status = DownloadService::statusFile($data['file'], true, $data['extension']);

			if ($status != 'ready') {
				Queue::push('DownloadService@createFileDownload', $data);
			}

			return [
				'file'	=> $data['file'],
				'status'=> $status,
				'pidD'	=> $data['pattern'],
				'report'=> $data['report']
			];

		} catch(Exception $e) {
			echo Response::json(
				Format::dashError(false, 'Error download occured.', 'Error Download Start')
			);
			die();
		}
	}

	protected function getPattern($pid)
	{
		$filters = FilterReport::get(null);
		return Session::get('user_id').$pid;
	}

	public function askReady($extension = 'csv')
	{
		$file = DownloadService::getInputNameFile();
		return [
			'file' => $file,
			'status' => DownloadService::statusFile($file, true, $extension),
			'report' => DownloadService::getInputReport()
		];
	}

	public function file($extension = 'csv')
	{
		return Response::download(
			DownloadService::getPathFile(DownloadService::getInputNameFile(), $extension),
			DownloadService::getInputReport().'_'.date('YmdHis').'.'.$extension
		);
	}

	protected function data($category, $report)
	{
		$filters = FilterReport::get(null);
		$data = ReportsController::dataReport(
			$category,
			$report,
			$filters,
			null,
			false
		);
		$data['file'] = Format::nameCache($data['query']);
		$data['report'] = $report;
		$data['user'] = User::basicInfo();
		$data['pattern'] = $this->getPattern($filters['pid']);
		return $data;
	}
}
?>
