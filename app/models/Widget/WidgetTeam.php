<?php
class WidgetTeam extends Eloquent implements WidgetInterface {
	protected $connection = 'dashboard';
	protected $table = 'widget_team';
	public $timestamps	= false;

	public static function getData()
	{
	}
}
