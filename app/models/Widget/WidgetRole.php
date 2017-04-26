<?php
class WidgetRole extends Eloquent implements RoleInterface {
	protected $connection = 'dashboard';
	protected $table = 'widgets';
	public $timestamps	= false;


	public function getData()
	{
		return $this->select('role')
			->groupBy('role')->get()->lists('role');
	}
}
