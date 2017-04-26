<?php
use Illuminate\Filesystem\Filesystem;

class DownloadExchangeCountryCSV implements DownloadInterface
{
	public static function output(&$out, $config)
	{
		set_time_limit(0);
		ini_set('max_execution_time', 0);

		$header = [
			'columns'=> [],
			'totals' => []
		];
		array_walk($config['columns'], function(&$row, $key) use (&$header, $config) {
			$val = true;
			if ($row['title'] == 'Country' && $config['country'] == false) {
				$val = false;
			}
			if ($row['title'] == 'Region' && $config['region'] == false) {
				$val = false;
			}
			if ($val) {
				$header['columns'][$key]= $row['title'];
				$header['totals'][$key]	= '';
			}
		});

		if ($total = DownloadWriterCSV::totalDownload($config)) {
			$header['totals'] = array_replace($header['totals'], $total);
		}
		fputcsv($out, $header['columns']);
		fputcsv($out, $header['totals']);


		$regions = [];
		if ($config['region']) {
			$regions = ExchangeCountry::regions($config['pid']);
		}

		$pdo = DB::reconnect($config['conn'])->getPdo();
		$q = $pdo->prepare($config['query']);
		$q->execute();
		while ($row = $q->fetch()) {
			$clear_row = [];
			if ($config['region']) {
				$row = ExchangeCountry::processRegion(
					$row,
					$regions,
					$config['baseOrder'],
					$config['deleteCountry']
				);
			}
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
