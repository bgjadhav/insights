<?php
class SharedProject extends Eloquent
{
	protected $connection = 'jira_prod';

	protected $table = 'share_project';

	public $timestamps = false;

	protected $fillable = ['mm_date', 'user_id', 'environment', 'process', 'issue_id', 'full_name', 'email', 'total'];
}
