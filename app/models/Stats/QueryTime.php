<?php
class QueryTime extends Eloquent
{
	protected $connection = 'dashboard';
	protected $table = 'query-time';
	public $timestamps = false;
}
