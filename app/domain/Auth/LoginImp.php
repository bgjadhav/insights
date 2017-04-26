<?php
class LoginImp implements LoginInterface
{
	public function logout()
	{
		try {

			CredentialsService::delete();
			$cookie = Cookie::forget(CookieService::cookieName());
			CookieService::emptyOpenSession();
			Session::flush();

			return $cookie;

		} catch (Exception $e) {
			ErrorService::standard($e->getMessage());
		}
	}

	public function login()
	{
		try {
			$credentials = json_decode(CredentialsService::user());

			UserService::check($credentials);

			SessionService::user($credentials);
			SessionService::dialogs($credentials);
			SessionService::roles($credentials);

			CookieService::setOPENSession($credentials);

			Queue::push('UserIPController@storeUserIP',
				[
					'user' => $credentials->user->email_address,
					'user_id' => $credentials->user_id,
					'ip' => Request::getClientIp(),
					'status' => $credentials->user->user_role
				]
			);

		} catch (Exception $e) {
			$credentials = [
				'success' => false,
				'error' => 'Error login'
			];
		}

		return $credentials;
	}
}
