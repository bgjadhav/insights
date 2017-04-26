<?php
class FavouritesController extends Controller
{

	protected $myfavourites = [];
	public function reports()
	{
		
		$data = FavouriteReport::select('report')->where('user_id', '=', Session::get('user_id'))->get();
		$formatteddata = array();
		foreach($data as $d)
		{
			$formatteddata[] = $d->report;
		}
		
		$config = Config::get('reports/report');
		$this->formatFavouriteReport($config, $formatteddata);
		$final_data['favourites'] = $this->myfavourites;
		return View::make('reports.favourites', $final_data);
	}

	public function addReport()
	{
		try {
			
			$fav_name = Input::get('fav_name');
			$return = FavouriteReport::insert(array("report" => $fav_name, "user_id" => Session::get('user_id')));
			return Response::json(array(
					"error" => false,
					"success" => true
				));
		} catch (Exception $e) {
			return Response::json(array(
					"error" => true,
					"success" => false
				));
		}
		
			
		
	}

	public function removeReport()
	{
		try {
			
			$fav_name = Input::get('fav_name');
			$return = FavouriteReport::where("report", "=", $fav_name)
				->where("user_id", "=", Session::get('user_id'))
				->delete();
			return Response::json(array(
					"error" => false,
					"success" => true
				));
		} catch (Exception $e) {
			return Response::json(array(
					"error" => true,
					"success" => false
				));
		}
		
			
		
	}

	protected function formatFavouriteReport($array, $favourites)
	{
		foreach ($array as $key => $row) {

			if (array_key_exists('sub', $row)) {
				$this->formatFavouriteReport($row['sub'], $favourites);
			}

			if (array_key_exists('report', $row)) {
				foreach ($row['report'] as $report_key => $report) {

						if (User::hasRole($report['role']) &&
						in_array($report['title'], $favourites)) {

								$this->myfavourites[$report['title']] = $report;
								$this->myfavourites[$report['title']]['url'] = $row['url'];
								$this->myfavourites[$report['title']]['key'] = $report_key;
							}


							
							
						}
				}

			}
		}
	


	
}
