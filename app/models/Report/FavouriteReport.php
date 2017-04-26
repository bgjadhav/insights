<?php
class FavouriteReport extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'report_favourites';
	private $timestamp = false;

	public static function getUserFavourites($user_id)
	{
		return self::select("report")->
					where("user_id", "=", $user_id)->
					lists("report");

	}  
}
