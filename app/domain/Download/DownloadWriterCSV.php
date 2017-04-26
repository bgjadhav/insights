<?php
use Illuminate\Filesystem\Filesystem;

class DownloadWriterCSV
{
	public static function writeOutputTmp(&$out, $config)
	{
		self::writeHeader($out, $config);
		$pdo = DB::reconnect($config['conn'])->getPdo();
		self::writeRows(
			$out,
			$pdo,
			$config['query']
		);
		$pdo = null;
		unset($pdo);
	}

	public static function writeRows(&$out, $pdo, $query)
	{
		$q = $pdo->prepare($query);
		$q->execute();
		while ($row = $q->fetch()) {
			fputcsv($out, self::clearRow($row));
		}
	}

	public static function clearRow($row)
	{
		$clear_row = [];
		array_walk($row, function($value, $key) use (&$clear_row) {
			if (is_string($key)) {
				$clear_row[] = strip_tags($value);
			}
		});
		return $clear_row;
	}

	public static function writeHeader(&$out, $config)
	{
		$header = self::getHeader($config);
		fputcsv($out, $header['columns']);
		fputcsv($out, $header['totals']);
	}

	public static function getHeader($config)
	{
		$header = self::getHeaderValues($config);
		if ($total = self::totalDownload($config)) {
			$header['totals'] = array_replace($header['totals'], $total);
		}
		return $header;
	}

	public static function getHeaderValues($config)
	{
		$header = [
			'columns'=> [],
			'totals' => []
		];
		array_walk($config['columns'], function(&$row, $key) use (&$header) {
			$header['columns'][$key] = $row['title'];
			$header['totals'][$key]	= '';
		});
		return $header;
	}

	public static function totalDownload($config)
	{
		if (!empty($config['totals'])) {
			return  self::getTotals(
				self::getResultTotals($config),
				$config
			);
		}
		return [];
	}

	public static function getResultTotals($config)
	{
		return QueryService::run(
			$config['queryTotal']['sql'],
			$config['queryTotal']['conn']
		);
	}

	public static function getTotals($results, $config)
	{
		$totals = [];
		foreach ($results as $resul) {
			foreach ($config['totals'] as $key => $tot) {
				$totals[$key] = $resul->$tot;
			}
		}
		return $totals;
	}

}
?>
