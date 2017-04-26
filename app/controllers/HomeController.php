<?php
class HomeController extends Controller
{

	public function index()
	{
		$data['redirect'] = Session::get('url', URL::to('/'));

		if (UserService::validLogin()) {
			return Redirect::to('/main');
		} else {
			return View::make('login', $data);
		}
	}

	public function login()
	{
		return Response::json((new LoginImp)->login());
	}

	public function logout()
	{
		return Redirect::to('/')
			->withCookie((new LoginImp)->logout());
	}

	public function set_dialog()
	{
		SessionService::updateDialogs();
	}
}
