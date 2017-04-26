<?php
class ReportDisplay extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'report_display';
	public $timestamps = false;

	public function widget()
	{
		return $this->belongsTo('Report');
	}

	public function myDisplays($report)
	{
		return ReportDisplay::select('class', DB::raw('CASE WHEN ownTitle <> \'\' THEN ownTitle ELSE report END as `title`'), 'weight')
			->where('report', $report)
			->orderBy('weight', 'ASC')
			->get()->toArray();
	}
}
