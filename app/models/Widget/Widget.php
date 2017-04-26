<?php
class Widget extends Eloquent implements WidgetInterface {
	protected $connection = 'dashboard';
	protected $table = 'widgets';
	protected $roles = [];

	public function categories()
	{
		return $this->hasMany('WidgetCategory', 'widget_id', 'id');
	}

	public function getData()
	{
		return $this->get();
	}

	public function getDataEnviromentRoles($active, $roles)
	{
		return $this
			->select('id', 'width', 'height', 'script', 'style', 'dateadded', 'handle', 'role')
			->whereIn('active', $active)
			->whereIn('role', $roles)
			->with('categories')
			->get();
	}
	
	public function getWidget($id)
	{
		return $this
			->select('id', 'width', 'height', 'script', 'style', 'dateadded', 'handle', 'role')
			->where('id', $id)
			->with('categories')
			->first();
	}
}
