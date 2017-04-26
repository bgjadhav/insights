<?php
class SearchTerm extends Eloquent
{
	protected $connection = 'jira_prod';
	
	protected $table = 'search_report_date';

	public $timestamps = false;

	protected $fillable = ['mm_date', 'word', 'report', 'total'];
}
