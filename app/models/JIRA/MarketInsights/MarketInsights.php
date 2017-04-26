<?php
class MarketInsights extends Eloquent
{
	protected $connection = 'jira_intel';
	protected $table = 'issues';

	public function labels() {
		return $this->hasMany('MetaMarketInsightsLabel', 'issue_id', 'issue_id');
	}

	public function components() {
		return $this->hasMany('MetaMarketInsightsComponent', 'issue_id', 'issue_id');
	}

}
