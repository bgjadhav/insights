<?php
class Category extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'categories';
	public $timestamps = false;
	protected $guarded = ['name'];
	protected $primaryKey = 'name';

	public function report()
	{
		if (App::environment() == 'dev') {
			return $this->belongsToMany('Report', 'report_category', 'category', 'report')
				->orderBy('weight', 'ASC')
				->orderBy('title', 'ASC');
		} else {
			return $this->belongsToMany('Report', 'report_category', 'category', 'report')
				->whereIn('active', [1])
				->where('inlive', '<=', date('Y-m-d H:i:s'))
				->orderBy('weight', 'ASC')
				->orderBy('title', 'ASC');
		}
	}

	public function scopeOwnOrParent($query, $parent)
	{
		return $query->whereRaw('(name =\''.$parent.'\' or parent =\''.$parent.'\')');
	}

	public function scopeCategoryParent($query, $parent)
	{
		return $query->whereParent($parent);
	}

	public function scopeCategory($query, $category)
	{
		return $query->whereName($category);
	}

	public function children()
	{
		return $this->hasMany('Category', 'parent', 'name');
	}

	public function reportCategory($category)
	{
		return self::category($category)
			->with('report')
			->orderBy('weight', 'ASC')
			->get()->toArray();
	}

	public function levelCategory($category)
	{
		return self::select('name')
			->categoryParent($category)
			->orderBy('weight', 'ASC')
			->get()->toArray();
	}

	public function testLEVEL()
	{
		return self::select('name')
			->whereRaw('name =\'Media\'')
			->orderBy('weight', 'ASC')
			->get()->toArray();
	}
}
