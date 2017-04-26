<?php
class SessionService
{
	public static function user($credentials)
	{
		Session::put('logged_in', true);
		Session::put(CookieService::cookieName(), $credentials->open_session);
		Session::put('session_expires', $credentials->session_expires);
		Session::put('session_expires_timestamp', $credentials->session_expires_timestamp);
		Session::put('user_type', $credentials->user->user_type);
		Session::put('user_role', $credentials->user->user_role);
		Session::put('user_id', $credentials->user_id);
		Session::put('t1_id', $credentials->t1_id);
		Session::put('first_name', $credentials->user->first_name);
		Session::put('last_name', $credentials->user->last_name);
		Session::put('email_address', $credentials->user->email_address);
		Session::put('user_email', $credentials->user->email_address);
		Session::put('user_logo', $credentials->user->logo_url);
		Session::put('timezone_id', $credentials->user->time_zone);
		Session::put('timezone', $credentials->user->timezone);
	}

	public static function dialogs($credentials)
	{
		$dialogs_array = [];
		$dialogs = Dialog::select('type')->where('user_id', $credentials->user->user_id)->get();

		foreach ($dialogs as $results) {
			array_push($dialogs_array, $results->type);
		}

		Session::set('dialogs', $dialogs_array);
	}

	public static function updateDialogs()
	{
		$type = Input::get('type');

		if ($type) {
			$dialogs_array = Session::get('dialogs');
			array_push($dialogs_array, $type);
			Session::put('dialogs', $dialogs_array);

			Dialog::insert(array(
				'user_id' => Session::get('user_id'),
				'type' => $type
			));
		}
	}


	public static function roles($credentials)
	{
		Session::put('user_roles', Roles::byUser($credentials->user->user_id));
	}

	public static function tmpUpdateRoles()
	{
		Session::put('user_roles', Roles::byCurrentUser());
	}

	public static function currentDisplayNameOrEmail()
	{
		$name = trim(Session::get('first_name').' '. Session::get('last_name'));
		return $name != '' ? $name : Session::get('email_address');
	}
}
?>
