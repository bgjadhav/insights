<?php
class ProductJIRAPerformance implements ProjectJIRAPerformanceInterface
{
	public function filters()
	{
		return [
			'Asignee' => FilterImp::get(new AssigneeRequestPerformance),
			'Reporter' => FilterImp::get(new ReporterRequestPerformance)
		];
	}

	public function datePicker()
	{
		return [
			'start' => Format::datePicker(date('j', strtotime('yesterday'))),
			'end' => Format::datePicker()
		];
	}

	public function projectName()
	{
		return 'product';
	}
}
