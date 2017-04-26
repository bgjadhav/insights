<?php
class NavigationController extends Controller
{
	public function home()
	{
		return View::make('reports.home')->with('type', 'home');
	}

	public function navigation($main, $category = null, $subcategory = null)
	{
		try  {
			return $this->category(
				$this->levelCategory($main, $category, $subcategory)
			);
		} catch (Exception $e) {
			return Redirect::to('/main');
		}
	}

	private function levelCategory($main, $category, $subcategory)
	{
		$levelCategory = [];
		foreach (['main', 'category', 'subcategory'] as $item) {
			if (!is_null(${$item})) {
				$levelCategory[$item] = ${$item};
			}
		}
		return implode('.sub.', $levelCategory);
	}

	private function countLevelCategory($category)
	{
		$count = explode('.sub.', $category);
		return count($count) -1;
	}

	private function UrlLevelCategory($category)
	{
		$count = $this->countLevelCategory($category);

		$level = '';

		for ($i=1; $i<=$count ;$i++) {
			$level .= '../';
		}
		return $level;
	}

	private function category($category)
	{
		$config = Config::get('reports/report.'.$category);
		$level = $this->UrlLevelCategory($category);

		if ($config['sub'] && !empty($config['sub'])) {
			return $this->homeCategory($category, $config, $level);
		} else {
			return $this->listReports($config, $level);
		}
	}

	private function listReports($category, $level)
	{
		$category = $this->cleanReportsByRole($category);

		try {
			$favouriteReports = FavouriteReport::getUserFavourites(Session::get('user_id'));
		} catch (Exception $e) {
			ddd('Error in list Reports.');
		}

		if (empty($category['report'])) {
			return Response::make('Unauthorized', 403);
		}

		return View::make('reports.list')
			->with('category', $category)
			->with('ulrLevel', $level)
			->with('type', $category['id'])
			->with('favourites', $favouriteReports);
	}

	private function homeCategory($category, $config, $level)
	{
		$config = $this->cleanCategoriesByRole($config);

		if (empty($config['sub'])) {
			return Response::make('Unauthorized', 403);
		}

		return View::make('reports.category')
			->with('categories', $config)
			->with('ulrLevel', $level)
			->with('type', $category);
	}

	private function cleanCategoriesByRole($config)
	{
		$newConfig = $config;
		$newConfig['sub'] = [];

		foreach ($config['sub'] as $id => $sub) {
			if (!isset($sub['sub']) || $sub['sub'] === false || !empty($sub['sub'])) {

				$subClean = $this->cleanCategoriesByRole($sub);

				if (!empty($subClean['report'])) {
					$newConfig['sub'][$id] = $subClean;
				}

			} else {
				$subClean = $this->cleanReportsByRole($sub);
				if (!empty($subClean['report'])) {
					$newConfig['sub'][$id] = $subClean;
				}
			}
		}
		return $newConfig;
	}

	private function cleanReportsByRole($category)
	{
		$newCategory = $category;
		$newCategory['report'] = [];

		foreach ($category['report'] as $report) {
			if ($this->validateAccess($report['role'])) {
				$newCategory['report'][] = $report;
			}
		}
		return $newCategory;
	}

	public function reportFirstLevel($main, $index, $report)
	{
		try {
			$config = $this->configReport($main, $index, $report);
			return $this->report($main, $main, '../../', $config);
		} catch (Exception $e) {
			return Response::make('Unauthorized', 403);
		}
	}

	public function reportCategory($main, $category, $index, $report)
	{
		$level = $this->levelCategory($main, $category, null);

		try {
			$config = $this->configReport($level, $index, $report);
			return $this->report($category, str_replace('.sub.', '/', $level), '../../../', $config);
		} catch (Exception $e) {
			return Response::make('Unauthorized', 403);
		}

	}

	public function reportSubCategory($main, $category, $subcategory, $index, $report)
	{
		$level = $this->levelCategory($main, $category, $subcategory);

		try {
			$config = $this->configReport($level, $index, $report);
			return $this->report($category, str_replace('.sub.', '/', $level), '../../../../', $config);

		} catch (Exception $e) {
			return Response::make('Unauthorized', 403);
		}

	}

	private function configReport($level, $index, $report)
	{
		$config = Config::get(
			'reports/report.'.$level.'.report'
		);

		$indexes = array_column($config, 'id');

		if (($key = array_search($report, $indexes)) !== false) {
			return Config::get(
				'reports/report.'.$level.'.report.'.$key
			);
		} else {
			throw new Exception('Unauthorized.');
		}


	}

	private function report($category, $parent, $ulrLevel, $config)
	{
		if (!$this->validateAccess($config['role']))
			return Response::make('Unauthorized', 403);

		View::addExtension('handlebars', 'php');
		return View::make('reports.tile')
			->with('type', $category)
			->with('parent', $parent)
			->with('ulrLevel', $ulrLevel)
			->with('sections', $config)
			->with('sub', true);
	}

	private function validateAccess($roles = ['MediaMath'])
	{
		return User::hasRole($roles);
	}

	private function configAllReport($level)
	{
		return Config::get(
			'reports/report.'.$level.'.report'
		);
	}

	public function closedWindow()
	{
		try {
			if (($pid = Input::get('pid')) != 0) {
				KillPidsController::killPids_process(
					[
						'pattern'	=> Session::get('user_id').$pid,
						'id'		=> false,
						'conn'		=> 'analytics'
					]
				);
			}
		} catch(Exception $e) {
		}
	}
}
?>
