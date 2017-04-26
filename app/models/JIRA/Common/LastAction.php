<?php
class LastAction extends Eloquent
{
	protected $connection = 'jira_prod';
	protected $table = 'last_action';

	public $timestamps = false;
}
