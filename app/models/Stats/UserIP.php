<?php
class UserIP extends Eloquent
{
	protected $connection = 'dashboard';

	protected $table = 'USER_IP';

	public $timestamps = false;

	protected $fillable = ['user', 'user_id', 'ip', 'status', 'created_at', 'updated_at'];

}
