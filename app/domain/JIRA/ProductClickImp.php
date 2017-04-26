<?php
class ProductClickImp implements ProductClick
{
	protected $data;

	public function __construct(Array $data)
	{
		$this->data = $data;
	}

	public function check()
	{
		$this->addOne('total');
	}

	public function data()
	{
		return $this->data;
	}

	public function addOne($index)
	{
		$this->data['clicked'][$index]++;

		return true;
	}

	public function addOneIfExistInData($index)
	{
		if (isset($this->data['data'][$index]) ) {

			$this->addOne($index);
			return true;
		}

		return false;
	}

	public function addOneIfExistIfIsTrue($index)
	{
		if (isset($this->data['data'][$index])
			&& $this->data['data'][$index] == 'true' ) {

			$this->data['clicked'][$index]++;

			return true;
		}

		return false;
	}

	public function addOneIfExistWithValue($index, $val)
	{
		if (isset($this->data['data'][$index])
			&& $this->data['data'][$index] == $val ) {

			$this->data['clicked'][$index]++;

			return true;
		}

		return false;
	}

}
