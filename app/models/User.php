<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/*
 * @Todo unification with the news roles
 * */
class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	public static function basicInfo()
	{
		return [
			'uid' => Session::get('user_id'),
			'uname'	=> Session::get('first_name'),
			'ulastn'=> Session::get('last_name'),
			'email'=> Session::get('user_email'),
			'utype' => Session::get('user_type')
		];
	}

	public static function MMId()
	{
		return Session::get('user_id');
	}

	public static function email()
	{
		return trim(Session::get('email_address'));
	}

	public static function fullName()
	{
		return trim(Session::get('first_name').' '.Session::get('last_name'));
	}

	public static function hasRole($roles)
	{

		if (self::isAdmin()) {
			return true;
		}

		if (!Session::get('user_roles') || Session::get('user_roles') == [] || Session::get('user_roles') == '') {
			SessionService::tmpUpdateRoles();
		}

		foreach ($roles as $role) {
			if (in_array($role, Session::get('user_roles'))) {
				return true;
			}
		}

		return false;
	}

	private static function isAdmin()
	{
		return in_array('Admin', Session::get('user_roles'));
	}

	public static function isKathia()
	{
		return Session::get('user_id') == 1616;
	}

}
