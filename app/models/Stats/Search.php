<?php
class Search extends Eloquent
{
	protected $connection = 'dashboard';

	protected $table = 'SEARCH_STATS';

	public $timestamps = false;

	protected $fillable = ['MM_DATE', 'WORD', 'REPORT', 'USER_', 'COUNT_'];
}
