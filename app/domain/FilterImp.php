<?php
class FilterImp
{
	public static function get(FilterInterface $filterName, $option = [])
	{
		return $filterName::filter($option);
	}
}
?>
