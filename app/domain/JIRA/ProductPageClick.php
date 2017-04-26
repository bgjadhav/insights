<?php
class ProductPageClick extends ProductClickImp
{
	public function check()
	{
		$this->addOne($this->data['report']);
	}

}
