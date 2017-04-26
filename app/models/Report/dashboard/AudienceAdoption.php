<?php
class AudienceAdoption extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'OrgId'			=> [
			'view'			=> 'Org Id',
			'fieldName'		=> 'ORG_ID',
			'fieldAlias'	=> 'ORG_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'Org'			=> [
			'view'			=> 'Org',
			'fieldName'		=> 'ORG',
			'fieldAlias'	=> 'ORG',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'AgencyId'			=> [
			'view'			=> 'Agency Id',
			'fieldName'		=> 'a.AGENCY_ID',
			'fieldAlias'	=> 'AGENCY_ID',
			'group'			=> true,
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_AGENCY_GEO ag',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'AGENCY_ID',
				'joinAlias'		=> 'ag',
				'fieldB'		=> 'AGENCY_ID'
			],
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],

		'Agency'			=> [
			'view'			=> 'Agency',
			'fieldName'		=> 'AGENCY',
			'fieldAlias'	=> 'AGENCY',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'AdvertiserId'			=> [
			'view'			=> 'Advertiser Id',
			'fieldName'		=> 'ADVERTISER_ID',
			'fieldAlias'	=> 'ADVERTISER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'Advertiser'			=> [
			'view'			=> 'Advertiser',
			'fieldName'		=> 'ADVERTISER',
			'fieldAlias'	=> 'ADVERTISER',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'Region'			=> [
			'view'			=> 'Region',
			'fieldName'		=> 'ag.REGION',
			'fieldAlias'	=> 'REGION',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'SegmentType'			=> [
			'view'			=> 'Segment Type',
			'fieldName'		=> 'SEGMENT_TYPE',
			'fieldAlias'	=> 'SEGMENT_TYPE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'AdvertisersCount'			=> [
			'view'			=> 'Segments/Pixels Targeted',
			'fieldName'		=> 'ADVERTISERS_COUNT',
			'fieldAlias'	=> 'ADVERTISERS_COUNT',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
	];
	protected $from = 'AUDIENCE_ADOPTION a';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(1),
				'end'	=> Format::datePicker(1)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		//!ddd(Filter::getAudienceAdoptionOrgs());
		return [
			'Organization' => Filter::getAudienceAdoptionOrgs(),
			'Segment Type' => ['audience' => 'Audience', 'data' => 'Data', 'dynamic' => 'Dynamic', 'event' => 'Event'],
		];
		
	}

	public function setQuery($options)
	{
		$this->where = [
			'Organization'	=> 'a.org_id IN ('.Format::id($options['filters']['Organization']).')',
			'Segment Type'	=> 'a.SEGMENT_TYPE IN ('.Format::str($options['filters']['Segment_Type']).')',
			'Date'			=> 'a.MM_DATE >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end']. '\'',
		];

		array_walk($this->col, [&$this, 'dataColumn']);
		// !ddd($this->buildQuery());
	}
}
