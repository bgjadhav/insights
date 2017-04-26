<?php
class UsageStats extends Eloquent
{
	protected $connection = 'jira_prod';

	protected $table = 'usage_stats';

	public $timestamps = false;

	protected $fillable = [
		'mm_day',
		'user_id',
		'environment',
		'full_name',
		'email',
		'roadmap',
		'candidate',
		'request',
		'shares',
		'share_project',
		'share_filtered',
		'exports',
		'export_full',
		'export_filtered',
		'link_reset',
		'link_name',
		'link_phase',
		'button_alert',
		'button_help',
		'button_share',
		'button_export',
		'button_make_request',
		'open_detail',
		'open_gear',
		'gear_comment',
		'gear_follow',
		'gear_share',
		'gear_views',
		'e_comments',
		'e_follow',
		'e_views',
		'relevant_actions',
		'total'
	];
}
