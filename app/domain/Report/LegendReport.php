<?php
class LegendReport
{
	public static function getConfig($tile)
	{
		$config	= Config::get('reports/info.legend');
		return isset($config[$tile]) ? $config[$tile] : false;
	}

	public static function getLegend($tile, $type)
	{
		$config = self::getConfig($tile);
		if ( ($config = self::getConfig($tile)) !== false
			&& isset($config[$type])) {
			return $config[$type];
		}
		return '';
	}
}
