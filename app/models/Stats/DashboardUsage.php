<?php
class DashboardUsage extends Eloquent
{
	protected $connection = 'usage_write';
	protected $table = 'DASHBOARD_USAGE';
	public $timestamps = false;
}
