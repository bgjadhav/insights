<?php
class ProdSubscriptionLog extends Eloquent
{
	protected $connection = 'jira_prod';
	protected $table = 'roadmap_subscription_log';
	public $timestamps = false;

	public static function scopeUserId($query, $user_id)
	{
		return $query->where('user_id', '=', $user_id);
	}

	public static function scopeStatus($query, $status)
	{
		return $query->where('status', '=', $status);
	}
}
