<?php
class ReportRole extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'report_role';
	public $timestamps = false;

	public function widget()
	{
		return $this->belongsTo('Report');
	}

	public function myRoles($report)
	{
		return ReportRole::select('role')
			->where('report', $report)
			->get()->toArray();
	}
}
