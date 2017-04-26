<?php
class ReportCategory extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'report_category';
	public $timestamps = false;

	public function reports()
	{
		return $this->belongsTo('Report');
	}
}
