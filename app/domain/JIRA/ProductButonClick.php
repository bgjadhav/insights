<?php
class ProductButonClick extends ProductClickImp
{
	public function check()
	{
		foreach (['alert', 'export_open', 'make_request', 'help'] as $index) {

			$this->addOneIfExistInData($index);
		}
	}
}
