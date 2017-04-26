<?php
/*@todo delete class every error in the own class */
class ErrorService
{
	public static function standard($message)
	{
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo Response::json(Format::dashError(false, 'A database error occured.', $message));
		die();
	}
}
?>
