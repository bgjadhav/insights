<?php
class SearchReportsController extends Controller
{
	private $search_results = [];
	private $search_query;

	private function search_inner($config)
	{
		foreach ($config as $key => $row) {

			if (array_key_exists('sub', $row)) {
				$this->search_inner($row['sub']);
			}

			if (array_key_exists('report', $row)) {

				foreach ($row['report'] as $report_key => $report) {

					if (User::hasRole($report['role']) &&
					strpos(strtolower($report['title']), $this->search_query) !== false) {

						if (!array_key_exists($key, $this->search_results)) {
							$this->search_results[$key] = [
								'url' => $row['url'],
								'name' => $row['name'],
								'icon' => $row['icon'],
								'results' => []
							];
						}

						$report['key'] = $report_key;
						array_push($this->search_results[$key]['results'], $report);
					}

				}
			}
		}
	}

	public function search()
	{
		$this->search_query = trim(strtolower(Input::get('search')));

		if ($this->search_query != '') {
			$config = Config::get('reports/report');

			unset($config['jira']);

			$this->search_inner($config);
		}

		return $this->view();
	}

	private function view()
	{
		$favouriteReports = FavouriteReport::getUserFavourites(Session::get('user_id'));
		return View::make('reports.search')
			->with('results', $this->search_results)
			->with('favourites', $favouriteReports);
	}
}
?>
