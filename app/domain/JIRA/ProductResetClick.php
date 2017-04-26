<?php
class ProductResetClick extends ProductClickImp
{
	public function check()
	{
		$this->addOneIfExistIfIsTrue('reset');
	}
}
