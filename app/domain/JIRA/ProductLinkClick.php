<?php
class ProductLinkClick extends ProductClickImp
{
	public function check()
	{
		foreach (['link_to_ticket', 'link_to_phase'] as $index) {

			$this->addOneIfExistInData($index);
		}
	}
}
