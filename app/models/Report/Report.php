<?php
class Report extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'reports';
	public $timestamps = false;
	protected $guarded = ['title'];
	protected $primaryKey = 'title';

	public function roles()
	{
		return $this->hasMany('ReportRole', 'report', 'title');
	}

	public function displays()
	{
		return $this->hasMany('ReportDisplay', 'report', 'title');
	}

	public function scopeActive($query)
	{
		return $query->where('active', '=', 1);
	}

	public function data($roles)
	{
		if (App::environment() == 'dev') {
			$this->whereIn('role', $roles)
				->with('categories')
				->get();
		} else {
			return $this
				->whereIn('active', [1])
				->where('inlive', '<=', date('Y-m-d H:i:s'))
				->whereIn('role', $roles)
				->with('categories')
				->get();
		}
	}
}
