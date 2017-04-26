<?php
class WidgetsControllerEdit extends WidgetsController
{

	function open_revenue_target() {
		$results = $this->open_revenue_target_data();

		$form = Form::open(array('url' => 'widgets/open-revenue-target/edit'));

		$form .= '<fieldset>';
			$form .= Form::label('month');
			$form .= Form::label('actual');
			$form .= Form::label('target');
			$form .= Form::label('low_forecast');
			$form .= Form::label('high_forecast');
		$form .= '</fieldset>';

		foreach($results as $row) {
			$form .= '<fieldset>';
				$form .= Form::label('month[]', $row->month);
				$form .= Form::hidden('month[]', $row->month);
				$form .= Form::text('actual[]', $row->actual, array('placeholder' => 'Actual'));
				$form .= Form::text('target[]', $row->target, array('placeholder' => 'Target'));
				$form .= Form::text('low_forecast[]', $row->low_forecast, array('placeholder' => 'Low Forecast'));
				$form .= Form::text('high_forecast[]', $row->high_forecast, array('placeholder' => 'High Forecast'));
			$form .= '</fieldset>';
		}

		$form .= Form::button('add', array('class' => 'add'));
		$form .= Form::submit('update');

		$form .= Form::close();

		echo $form;
	}

	function open_revenue_target_edit() {

		foreach(Input::get('month') as $key=>$row) {
			try {
				$results = DB::reconnect('dashboard')->
					table('open-revenue')->
					insert(array(
						'month' => Input::get('month')[$key],
						'actual' => Input::get('actual')[$key],
						'target' => Input::get('target')[$key],
						'low_forecast' => Input::get('low_forecast')[$key],
						'high_forecast' =>Input::get('high_forecast')[$key]
					));
				DB::disconnect('dashboard');

			} catch(Exception $e) {
				return Response::json(array('success' => false, 'error' => 'A database error occured.'));
			}
		};
	}
}
