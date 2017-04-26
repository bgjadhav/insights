<?php
class ProdSubscription extends Eloquent
{
	protected $connection = 'jira_prod';
	protected $table = 'roadmap_subscription';
	public $timestamps = false;

	public static function scopeUserId($query, $user_id)
	{
		return $query->where('user_id', '=', $user_id);
	}
}
