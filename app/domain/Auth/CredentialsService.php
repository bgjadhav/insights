<?php
class CredentialsService
{
	public static function cookie()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::loginUrl());
		curl_setopt($ch, CURLOPT_HTTPHEADER, CookieService::cookieOpen());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$output = curl_exec($ch);
		curl_close($ch);
		return $ouput;
	}

	public static function user()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::loginUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, UserService::userPass());
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	public static function delete()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::logoutUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_POSTFIELDS, CookieService::cookieUser());
		$output = curl_exec($ch);
		curl_close($ch);
	}

	public static function fullUrl()
	{
		return 'https://open.mediamath.com/api';
	}

	public static function loginUrl()
	{
		return self::fullUrl().'/auth';
	}

	public static function logoutUrl()
	{
		return self::fullUrl().'/logoutOpenUser';
	}
}
?>
