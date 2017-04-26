<?php
class Enviroment
{

	public static function name()
	{
		if (App::environment('production')) {
			return 'production';

		} elseif(App::environment('local')) {
			return 'local';

		} elseif(App::environment('dev')) {
			return 'dev';

		} else {
			return 'other';
		}
	}
}
?>
