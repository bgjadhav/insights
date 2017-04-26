<?php
use Illuminate\Filesystem\Filesystem;

class DownloadGenericCSV implements DownloadInterface
{
	public static function output(&$out, $config)
	{
		set_time_limit(0);
		ini_set('max_execution_time', 0);
		DownloadWriterCSV::writeOutputTmp($out, $config);
		fclose($out);
	}
}
?>
