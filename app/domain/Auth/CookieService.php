<?php
class CookieService
{

	public static function cookieName()
	{
		return 'open_session';
	}

	public static function setOPENSession($credentials)
	{
		setcookie(
			self::cookieName(),
			$credentials->open_session,
			$credentials->session_expires_timestamp,
			'/',
			'.mediamath.com'
		); // expires in 24 hours.

		$_COOKIE[self::cookieName()] =  Session::get(self::cookieName());
	}

	public static function emptyOpenSession()
	{
		setcookie(
			self::cookieName(),
			null,
			time()-3600,
			'/',
			'mediamath.com',
			1
		);
		unset($_COOKIE[self::cookieName()]);
	}

	public static function cookieSessionActive()
	{
		$cookie_name = self::cookieName();

		return !empty($_COOKIE[$cookie_name])
			? $_COOKIE[$cookie_name]
			: Session::get($cookie_name);
	}

	public static function updateOpenSession()
	{
		$cookie_name = self::cookieName();

		if (!empty(Session::get($cookie_name))) {
			$_COOKIE[$cookie_name] = Session::get($cookie_name);

		} else {
			unset($_COOKIE[$cookie_name]);
		}
	}

	public static function cookieOpen()
	{
		return [
			'Cookie: '
			.self::cookieName()
			.'='
			.self::cookieSessionActive()
		];
	}

	public static function cookieUser()
	{
		return ['user_session' => self::cookieSessionActive()];
	}

}
?>
