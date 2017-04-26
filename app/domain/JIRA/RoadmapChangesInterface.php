<?php
interface RoadmapChangesInterface
{
	public static function rawQuery($date, $end_date=false);

	public static function whereEndDate($end_date=false);
}
