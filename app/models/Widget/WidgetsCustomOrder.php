<?php
class WidgetsCustomOrder extends Eloquent {
	protected $connection = 'dashboard';
	protected $table = 'widgets-order';
	protected $fillable = array('user_id', 'order');

	public function getData()
	{
		return $this->select('user_id', 'order')->get();
	}

	public function getDataUser($user)
	{
		return $this->where('user_id', '=', $user)
			->select('user_id', 'order')
			->first();
	}
}
