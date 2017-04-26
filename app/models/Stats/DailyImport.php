<?php
class DailyImport extends Eloquent
{
	protected $connection = 'update_process';
	protected $table = 'open_daily_import';
}
