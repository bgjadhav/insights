<?php
class AdxPublishers extends Tile
{
	public $col = [
		'Publisher_Id'			=> [
			'view'			=> 'Publisher Id',
			'fieldName'		=> 'publisherProfileId',
			'fieldAlias'	=> 'publisherProfileId',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Display_Name'		=> [
			'view'			=> 'Display Name',
			'fieldName'  	=> 'displayName',
			'fieldAlias' 	=> 'displayName',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'LogoUrl'		=> [
			'view'			=> 'Logo Url',
			'fieldName'  	=> 'logoUrl',
			'fieldAlias' 	=> 'logoUrl',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DirectDealsContact'			=> [
			'view'			=> 'DirectDeals Contact',
			'fieldName'		=> 'directDealsContact',
			'fieldAlias'	=> 'directDealsContact',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ProgrammaticDealsContact'		=> [
			'view'			=> 'Programmatic Deals Contact',
			'fieldName'  	=> 'programmaticDealsContact',
			'fieldAlias' 	=> 'programmaticDealsContact',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SamplePageUrl'		=> [
			'view'			=> 'Sample Page Url',
			'fieldName'  	=> 'samplePageUrl',
			'fieldAlias' 	=> 'samplePageUrl',
			'group' 	 	=> false,
			'join'	 	 	=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]
		/*
		,
		'Overview'			=> [
			'view'			=> 'Overview',
			'fieldName'		=> 'overview',
			'fieldAlias' 	=> 'overview',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		]*/
	];
	protected $from = 'kamaldeep.publishers';
	protected $timeout = false;
	public function options($filters)
	{
		return [
			'date_picker'	=> false
		];
	}

	public function filters()
	{
		return [];
	}

	public function setQuery($options)
	{
		array_walk($this->col, [&$this, 'dataColumn']);
	}
}
