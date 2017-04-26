<?php
class Dialog extends Eloquent {
	protected $connection = 'dashboard';
	protected $table = 'dialog-status';
	protected $fillable = array('user_id', 'type');
}
