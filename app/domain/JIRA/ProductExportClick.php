<?php
class ProductExportClick extends ProductClickImp
{
	public function check()
	{
		foreach (['export_filtered', 'export_current'] as $index) {

			$this->addOneIfExistInData($index);
		}
	}
}
