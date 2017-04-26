<?php
use Illuminate\Filesystem\Filesystem;

class DownloadService
{
	public function fire($job, $config)
	{
	}

	public function createFileDownload($job, $config)
	{
		if ($job->attempts() <= 4) {

			sleep($job->attempts() * 1);


			set_time_limit(0);
			ini_set('max_execution_time', 0);

			self::prepareDownload($config['file'], $config['extension']);

			self::downloadFileByType($config);

			self::renameTmpFile($config['file'], $config['extension']);

		} elseif ($job->attempts() == 5) {
			self::forceEndDownload($config['file'], $config['extension']);
			$job->delete();
		}

		$job->delete();
	}


	private static function forceEndDownload($file, $extension = 'csv')
	{
		$fileError = self::nameFileError($file);

		if (!self::existFile($fileError, $extension)) {
			fopen(self::getPathFile($fileError, $extension), 'w');
			fclose(self::getPathFile($fileError, $extension));
		}
	}


	public static function prepareDownload($file, $extension = 'csv')
	{
		$files = new \Illuminate\Filesystem\Filesystem;

		$fileError = self::nameFileError($file);

		if (self::existFile($fileError, $extension)) {
			$files->delete(self::getPathFile($fileError, $extension));
		}
	}

	public static function existFile($file, $extension = 'csv')
	{
		$files = new \Illuminate\Filesystem\Filesystem;
		return $files->exists(self::getPathFile($file, $extension));
	}

	public static function getPathFile($file, $extension = 'csv')
	{
		return storage_path().'/files/'.$file.'.'.$extension;
	}


	public static function downloadFileByType($config)
	{
		if ($config['extension'] == 'xls') {
			$out = $config['file'];
		} else {
			$out = self::openPathFile($config['file'], $config['extension']);
		}

		$classDownload = self::getClassNameDownload($config);

		$classDownload::output($out, $config);
	}

	public static function openPathFile($file, $extension = 'csv')
	{
		return fopen(self::getPathFile('tmp__'.$file, $extension), 'w');
	}

	public static function getClassNameDownload($config)
	{
		$classDownload = 'Download'.$config['report'].strtoupper($config['extension']);

		if (!class_exists($classDownload)) {
			$classDownload = 'DownloadGeneric'.strtoupper($config['extension']);
		}

		return $classDownload;
	}


	public static function renameTmpFile($file, $extension = 'csv')
	{
		return rename(
			self::getPathFile('tmp__'.$file, $extension),
			self::getPathFile($file, $extension)
		);
	}


	public static function statusFile($file, $error = false, $extension = 'csv')
	{
		if (self::existFile($file, $extension)) {
			return 'ready';

		} elseif ($error && self::existFile(self::nameFileError($file), $extension)) {
			return 'error';

		} else {
			return 'wait';
		}
	}


	public static function getInputNameFile()
	{
		return Input::get('file');
	}

	public static function getInputReport()
	{
		return Input::get('report');
	}

	public static function nameFileError($file)
	{
		return $file.'_error';
	}

}
?>
