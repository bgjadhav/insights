<?php
interface ProductClick
{
	public function __construct(Array $data);

	public function data();

	public function check();

	public function addOne($index);

	public function addOneIfExistInData($index);

	public function addOneIfExistIfIsTrue($index);

	public function addOneIfExistWithValue($index, $val);
}
