<?php
use Illuminate\Filesystem\Filesystem;

class DownloadWebVsInAppCSV implements DownloadInterface
{
	public static function output(&$out, $config)
	{
		set_time_limit(0);
		ini_set('max_execution_time', 0);
		$header = [
			'columns'=> [],
			'totals' => []
		];
		array_walk($config['columns'], function(&$row, $key) use (&$header) {
			$header['columns'][$key]= $row['title'];
			$header['totals'][$key]	= '';
		});
		if ($total = DownloadWriterCSV::totalDownload($config)) {
			$header['totals'] = array_replace($header['totals'], $total);
		}
		fputcsv($out, $header['columns']);
		fputcsv($out, $header['totals']);

		$pdo = DB::reconnect($config['conn'])->getPdo();
		$q = $pdo->prepare($config['query']);
		$q->execute();
		while ($row = $q->fetch()) {
			if ($config['sov']['sov_Impressions']['status'] || $config['sov']['sov_Media_Cost']['status']){
				$row = WebVsInApp::formatSov($row, $config['sov'], $config['col']);;
			}
			$clear_row = [];

			array_walk($row, function($value, $key) use (&$clear_row, $config) {
				if (is_string($key)) {
					$clear_row[] = strip_tags($value);
				}
			});
			fputcsv($out, $clear_row);
		}
		$pdo = null;
		unset($pdo);
		fclose($out);
	}
}
?>
