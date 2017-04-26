<?php
class MenuReport
{
	protected $menu = [];
	protected $url = [
		'nobody' => '/analytics'
	];
	protected $reports = [
		'nobody' => []
	];

	public function menu()
	{

		$this->mainCategories();

		$this->deleteNobodyCategory();

		$this->validateCategoryByEnvoriment();

		$this->menu = $this->appendDetails($this->menu);

		$this->menu = $this->cleanWithoutReportOrSubcategories($this->menu);

		return $this->menu;
	}

	protected function mainCategories()
	{
		$this->menu = $this->level((new Category)->levelCategory('nobody'));
	}

	protected function cleanWithoutReportOrSubcategories($menu)
	{
		foreach ($menu as $index => $content) {
			if (!isset($content['sub']) && !isset($content['report'])) {
				unset($menu[$index]);
			}
		}
		return $menu;
	}

	protected function appendDetails($menu)
	{
		$allCategories = [];

		foreach ($menu as $catName => $content) {

			$id = Format::toUrl($catName);

			$item = (new Category)->reportCategory($catName);

			$detail = $this->appendCategoryDetails($catName, $item);

			$detail = $this->appendReportDetails(
				$detail,
				$item
			);

			$detail = $this->appendSubCategoryDetails($detail);

			$detail['role'] = $this->appendRoles($detail);

			$detail['id'] = $id;

			$allCategories[$id] = $detail;

		}
		return $allCategories;
	}


	protected function appendCategoryDetails($catName, $detail)
	{
		$cat = $this->appendCategoryName($catName);

		$cat = $this->appendCategoryIcon($cat, $cat['name']);

		$cat = $this->appendCategoryParent($cat, $detail[0]['parent']);

		$this->appendCategoryUrlStorage($cat['name'], $detail[0]['parent']);

		$cat = $this->appendCategoryUrl($cat);

		$cat = $this->appendCategoryWeight($cat, $detail[0]['weight']);

		return $cat;
	}

	protected function appendCategoryName($cat)
	{
		return ['name' => $cat];
	}

	protected function appendCategoryParent($cat, $parent)
	{
		$cat['parent'] = [
			'name' => $parent,
			'url' => $this->url[$parent]
		];
		return $cat;
	}

	protected function appendCategoryWeight($cat, $weight)
	{
		$cat['weight'] = (int)$weight;
		return $cat;
	}

	protected function appendCategoryUrl($cat)
	{
		$cat['url'] = $this->url[$cat['name']];
		return $cat;
	}

	protected function appendCategoryIcon($cat, $name)
	{
		$cat['icon'] = Format::toUrl($name).'.png';
		return $cat;
	}

	protected function appendCategoryUrlStorage($cat, $parent)
	{
		$this->url[$cat] = $this->url[$parent].'/'.Format::toUrl($cat);
	}


	protected function appendSubCategoryDetails($cat)
	{
		$subCategories = $this->level((new Category)->levelCategory($cat['name']));

		if (!empty($subCategories)) {
			$cat['sub'] = $this->appendDetails($subCategories);
		} else {
			$cat['sub'] = [];
		}
		return $cat;
	}

	protected function appendRoles($detail)
	{
		$newRoles = [];

		if (!empty($detail['sub'])) {

			foreach ($detail['sub'] as $sub) {

				if (!empty($sub['role'])) {
					foreach($sub['role'] as $role) {
						$newRoles[$role] = $role;
					}
				}

				if (!empty($sub['report'])) {
					$reportRoles = array_column(['report'], 'role');
					foreach($reportRoles as $roles) {
						foreach($roles as $role) {
							$newRoles[$role] = $role;
						}
					}
				}
			}
		}

		if (!empty($detail['report'])) {
			$reportRoles = array_column($detail['report'], 'role');
			foreach($reportRoles as $roles) {
				foreach($roles as $role) {
					$newRoles[$role] = $role;
				}
			}
		}
		return $newRoles;
	}



	protected function appendReportDetails($cat, $detail)
	{
		$this->reports[$cat['name']] = [];

		foreach ($detail as $info) {
			try {

				$cat['report'] = $this->appendParentReports(
					$cat['parent']['name'],
					$this->infoReport($info['report'], $cat['parent']['name'])
				);

				$this->reports[$cat['name']] = $cat['report'];

				if (empty($cat['report'])) {
					unset($cat['report']);
				}

			} catch(Exception $e) {
			}
		}
		return $cat;
	}

	protected function infoReport($infoReports, $catName)
	{
		$allReports = $infoReports;

		// reset index
		$infoReports = [];

		if (!empty($allReports)) {
			foreach ($allReports as $report) {

				$report = $this->forceDataReport($report);

				$report = $this->matchingReport($catName, $report);

				$id = Format::toUrl($report['title']);

				$report['id'] = $id;
				$report['icon'] = $id;

				$infoReports[] = $report;
			}
		}
		return $infoReports;
	}

	protected function appendParentReports($parent, $reports)
	{
		try {
			foreach ($this->reports[$parent] as $index => $content) {
				$reports[] = $content;
			}
			return $reports;
		} catch(Exception $e) {
			return $reports;
		}
	}

	protected function forceDataReport($report)
	{
		$report = $this->forceDataTypesIntegerInReport($report);

		$report = $this->forceDeletePivotUseless($report);

		return $report;
	}

	protected function forceDataTypesIntegerInReport($report)
	{
		$fields = ['active', 'noBack'];
		foreach ($fields as $field) {
			$report[$field] = (int) $report[$field];
		}
		return $report;
	}

	protected function forceDeletePivotUseless($report)
	{
		try {
			unset($report['pivot']);
			return $report;
		} catch(Exception $e) {
			return $report;
		}
	}

	protected function matchingReport($cat, $report)
	{
		$report = $this->appendReportDependencias($report);
		return $report;
	}

	protected function appendReportDependencias($report)
	{
		$report['display'] = $this->appendReportDisplay($report['title']);
		$report['mainTitle'] = $this->appendMainTitle($report);
		$report['role'] = $this->appendReportRole($report['title']);
		return $report;
	}

	protected function appendMainTitle($report)
	{
		$titles = array_column($report['display'], 'title');
		return in_array($report['title'], $titles) ? false : $report['title'];
	}

	protected function appendReportDisplay($report)
	{
		$result = [];
		$displays = (new ReportDisplay)->myDisplays($report);
		try {
			foreach($displays as $display) {
				$result[Format::toUrl($display['class'])]
				= $this->forceTypeDataDisplayDetails($display);
			}
		} catch(Exception $e) {
		}
		return $result;
	}

	protected function appendReportRole($report)
	{
		$roles = (new ReportRole)->myRoles($report);
		$roles = $this->keysLevel($roles, 'role');
		return empty($roles) ? ['CoreOPENAnalyst'] : $roles;
	}

	protected function forceTypeDataDisplayDetails($display)
	{
		$display['weight'] = (int) $display['weight'];
		return $display;
	}

	protected function cleanEmptyCategory($menu)
	{
		return array_filter($menu);
	}

	protected function deleteNobodyCategory()
	{
		try {
			unset($this->menu['nobody']);
		} catch(Exception $e) {
		}
	}

	protected function validateCategoryByEnvoriment()
	{
		try {
			if (App::environment() == 'pro') {
				unset($this->menu['Test']);
			}
		} catch(Exception $e) {
		}
	}

	protected function level($data, $id='name')
	{
		try {
			return array_combine(
				$this->keysLevel($data, $id),
				$this->valuesLevel(count($data))
			);
		} catch(Exception $e) {
			return [];
		}
	}

	protected function valuesLevel($total)
	{
		return array_fill(0, $total, []);
	}

	protected function keysLevel($data, $id)
	{
		return array_column($data, $id);
	}
}
