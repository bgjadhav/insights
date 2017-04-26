<?php
class SharedFiltered extends Eloquent
{
	protected $connection = 'jira_prod';

	protected $table = 'share_project_filtered';

	public $timestamps = false;

	protected $fillable = ['mm_date', 'user_id', 'environment', 'process', 'issue_ids', 'full_name', 'email', 'total', 'checked'];
}
