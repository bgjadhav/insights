<?php
class EmailController extends Controller
{

	// duplicate of index, will be changed to display only certain widgets
	public function emails()
	{
	 	try {
			$order = DB::reconnect('dashboard')->
				table('widgets-order')->
				select('user_id', 'order')->
				where('user_id', '=', Session::get('user_id'))->
				remember(Format::timeOut())->
				first();
			DB::disconnect('dashboard');
		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
	 	try {
			$widgets = DB::reconnect('dashboard')->
				table('widgets')->
				select('id', 'width', 'height', 'script', 'style')->
				where('active', '=', '1')->
				remember(Format::timeOut())->
				get();
			DB::disconnect('dashboard');
		} catch(Exception $e) {
			return Response::json(array('success' => false, 'error' => 'A database error occured.'));
		}
		foreach($widgets as $key=>$widget) {
			$width = '';
			$height = '';
			if($widget->width == '2') {
				$width = 'double';
			} else if($widget->width == '3') {
				$width = 'double';
			}
			if($widget->height == '2') {
				$height = 'tall';
			}
			$widgets[$key]->width = $width;
			$widgets[$key]->height = $height;
		}

		$data = array();
		if($order) {
			$rows = json_decode($order->order);
			foreach($rows as $row) {
				if(isset($widgets[$row-1])) {
					array_push($data, $widgets[$row-1]);
				}
			}
			// check if there are new widgets
			foreach($widgets as $key=>$widget) {
				$matched = false;
				foreach($data as $row) {
					if($widget->id == $row->id) {
						$matched = true;
					}
				}
				if(!$matched) {
					array_push($data, $widget);
				}
			}
		} else {
			$data = $widgets;
		}

		return View::make('tiles', array('widgets' => $data));
	}

	public function sendemail()
	{
		$response = exec('phantomjs ../screenshots.js');

		if($response == 'success')
		{
			Mail::send('emails.daily', array(), function($message)
			{
				//Ben Dalgliesh <bdalgliesh@mediamath.com>
				$message->to('esmith@mediamath.com', 'Elian Smith')->subject('Insights');
			});
		}

		return View::make('emails.daily');
	}

}
