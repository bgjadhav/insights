<?php
class CleanMetaConcated implements FilterInterface
{
	public static function filter($option)
	{
		$output = [];
		foreach ($option as $value) {

			$items = explode(',', $value);

			foreach($items as $item) {
				$item = trim($item);
				if ($item != '') {
					$output[strtolower($item)] =  $item;
				}
			}
		}

		ksort($output);
		return $output;
	}
}
?>
