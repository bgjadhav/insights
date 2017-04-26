<?php
class ItunesAppMapping extends Tile
{
	public $col = [
		'AppID'			 => [
			'view'			=> 'App ID',
			'fieldName'		=> 'APP_ID',
			'fieldAlias'	=> 'APP_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'AppName'	=> [
			'view'			=> 'App Name',
			'fieldName'		=> 'CASE WHEN APP_NAME = \'\' THEN \'Language cannot be displayed\' ELSE APP_NAME END',
			'fieldAlias'	=> 'APP_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OS'	=> [
			'view'			=> 'OS',
			'fieldName'		=> 'OS',
			'fieldAlias'	=> 'OS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CategoryID'		=> [
			'view'			=> 'Category ID',
			'fieldName'		=> 'CASE WHEN CATEGORY_ID = 0 THEN \'N/A\' ELSE CATEGORY_ID END',
			'fieldAlias'	=> 'CATEGORY_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CategoryName'		=> [
			'view'			=> 'Category Name',
			'fieldName'		=> "TRIM(BOTH ' ' FROM replace(CATEGORY_NAME, '\'',''))",
			'fieldAlias'	=> 'CATEGORY_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'META_PROD_APP_CATEGORY';
	protected $timeout = false;
	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'Category'			=> Filter::getAppCategory(),
			'OS'				=> Filter::getAppOS()
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Category'		=> 'CATEGORY_NAME IN ('
				.Format::str($options['filters']['Category']).')',
			'OS'		=> 'OS IN ('
				.Format::str($options['filters']['OS']).')'

		];
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
