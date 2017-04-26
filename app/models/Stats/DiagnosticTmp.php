<?php
class DiagnosticTmp extends Eloquent
{
	protected $connection = 'update_process';
	protected $table = 'open_update_table_fail_tmp';
	public $timestamps = false;
}
