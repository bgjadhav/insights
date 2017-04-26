<?php
class ProductDetailAndGearClick extends ProductClickImp
{
	public function check()
	{
		foreach (['open_detail', 'open_gear', 'comment', 'follow', 'view', 'add_comment', 'follow_be_watcher', 'view_go_jira'] as $index) {

			$this->addOneIfExistInData($index);
		}
	}
}
