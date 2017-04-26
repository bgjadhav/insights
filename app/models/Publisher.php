<?php
class Publisher extends Eloquent
{
	protected $connection = 'publisher_tool';
	protected $table = 'publisher_data.publishers';
/*
	public function labels() {
		return $this->hasMany('MetaMarketInsightsLabel', 'issue_id', 'issue_id');
	}

	public function components() {
		return $this->hasMany('MetaMarketInsightsComponent', 'issue_id', 'issue_id');
	}*/

}
