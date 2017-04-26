<?php
class VendorCreatives extends Tile
{
	public  $col = [
		'OrgID'			=> [
			'view'			=> 'Organization ID',
			'fieldName' 	=> 'cp.ORG_ID',
			'fieldAlias' 	=> 'ORGANIZATION_ID',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'OrgName'		=> [
			'view'			=> 'Organization Name',
			'fieldName'		=> 'org.ORG_NAME',
			'fieldAlias'	=> 'ORGANIZATION_NAME',
			'group'			=> false,
			'gDependence'	=> 'cp.ORG_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'META_ORGS org',
				'tableAlias'	=> 'cp',
				'fieldA'		=> 'ORG_ID',
				'joinAlias'		=> 'org',
				'fieldB'		=> 'ORG_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorID'		=> [
			'view'			=> 'Vendor ID',
			'fieldName'		=> 'mcvm.VENDOR_ID',
			'fieldAlias'	=> 'VENDOR_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorName'		=> [
			'view'		=> 'Vendor Name',
			'fieldName'		=> 'mcv.VENDOR_NAME',
			'fieldAlias'	=> 'VENDOR_NAME',
			'group'			=> false,
			'gDependence'	=> 'cp.CREATIVE_ID',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'partner_dashboard.META_CREATIVE_VENDORS mcv',
				'tableAlias'	=> 'mcvm',
				'fieldA'		=> 'VENDOR_ID',
				'joinAlias'		=> 'mcv',
				'fieldB'		=> 'VENDOR_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorType'		=> [
			'view'			=> 'Vendor Type',
			'fieldName'		=> 'b.VENDOR_TYPE',
			'fieldAlias'	=> 'VENDOR_TYPE',
			'group'			=> false,
			'gDependence'	=> 'mcvm.VENDOR_ID',
			'join'			=> [
				'type'			=> 'INNER',
				'tableName'		=> 'partner_dashboard.META_VENDOR_FULL b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'VENDOR_ID',
				'joinAlias'		=> 'mcvm',
				'fieldB'		=> 'VENDOR_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Creatives'		=> [
			'view'			=> 'Creatives',
			'fieldName'		=> 'count(distinct mcvm.CREATIVE_ID)',
			'fieldAlias'	=> 'CREATIVES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'SampleCreativeID'	=> [
			'view'		=> 'Sample Creative ID',
			'fieldName'		=> 'mcvm.CREATIVE_ID',
			'fieldAlias'	=> 'SAMPLE_CREATIVE_ID',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(cp.IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(cp.MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'TotalSpend'		=> [
			'view'		=> 'Total Spend',
			'fieldName'		=> 'sum(cp.TOTAL_SPEND)',
			'fieldAlias'	=> 'TOTAL_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'BilledSpend'		=> [
			'view'		=> 'Billed Spend',
			'fieldName'		=> 'sum(cp.BILLED_SPEND)',
			'fieldAlias'	=> 'BILLED_SPEND',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $conn = 'warroom';
	protected $from = 'PERFORMANCE_CREATIVE_BY_MONTH cp';


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
			'Organization'	=> Filter::getOrganization(),
			'Vendors'		=> Filter::getVendorMeta(),
			'Columns'		=> [$this->getColumnView(), ['SampleCreativeID']]

		];
	}

	public function setQuery($options)
	{
		$current_month_number = date('n');
		$last_month = date('n', strtotime('-1 months'));
		$two_month = date('n', strtotime('-2 months'));

		$this->where = [
			'Vendors'	=> 'mcv.VENDOR_ID IN ('.Format::id($options['filters']['Vendors']).')',
			'OrgID'		=> 'cp.ORG_ID IN ('.Format::id($options['filters']['Organization']).')',
			'Date'		=> 'cp.MONTH in ('.$current_month_number.','.$last_month.','.$two_month.')'
		];
		$this->join = ['LEFT JOIN partner_dashboard.META_CREATIVE_VENDOR_MATCHES mcvm ON (cp.CREATIVE_ID = mcvm.CREATIVE_ID)'];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
