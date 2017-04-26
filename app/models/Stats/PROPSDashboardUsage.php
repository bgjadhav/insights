<?php
class PROPSDashboardUsage extends Eloquent
{
	protected $connection = 'jira_prod';
	protected $table = 'DASHBOARD_USAGE';
	public $timestamps = false;
}
