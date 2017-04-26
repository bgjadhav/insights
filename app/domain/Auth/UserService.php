<?php
class UserService
{
	public static function userPass()
	{
		return [
			'username' => Input::get('username'),
			'password' => ' '.str_replace('$', '\$', Input::get('password'))
		];
	}

	public static function noSessionExpired()
	{
		return Session::get('session_expires_timestamp') > time();
	}

	public static function validLogin()
	{
		return Session::get(CookieService::cookieName())
			&& self::noSessionExpired();
	}

	public static function check($user)
	{
		if ($user->success === false) {
			throw new Exception('Count not connect to OPEN API.');
		} elseif ($user->user->user_type > 2) {
			throw new Exception('internal_only');
		}
	}
}
?>
