<?php
class DataPartnerSegment extends Tile
{
	public $col = [
		'Name'	=> [
			'view'			=> 'Name',
			'fieldName'		=> 'b.NAME',
			'fieldAlias'	=> 'NAME',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PartnerSegmentID'	=> [
			'view'			=> 'PartnerSegmentID',
			'fieldName'		=> 'a.code',
			'fieldAlias'	=> 'PARTNER_SEGMENT_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'NamespaceCode'	=> [
			'view'			=> 'Namespace_Code',
			'fieldName'		=> 'b.NAMESPACE_CODE',
			'fieldAlias'	=> 'NAMESPACE_CODE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'BidderCode'	=> [
			'view'			=> 'Bidder_Code',
			'fieldName'		=> 'b.BIDDER_CODE',
			'fieldAlias'	=> 'BIDDER_CODE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'VendorID'	=> [
			'view'			=> 'Vendor_ID',
			'fieldName'		=> 'b.VENDOR_ID',
			'fieldAlias'	=> 'VENDOR_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'FullPath'		=> [
			'view'			=> 'Full_Path',
			'fieldName'		=> 'a.full_path',
			'fieldAlias'	=> 'FULL_PATH',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'RetailCPM'		=> [
			'view'			=> 'Retail_CPM',
			'fieldName'		=> 'SUM(a.retail_cpm)',
			'fieldAlias'	=> 'RETAIL_CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'DeclaredUniques'=> [
			'view'			=> 'Declared_Uniques',
			'fieldName'		=> 'sum(a.declared_uniques)',
			'fieldAlias'	=> 'DECLARED_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'30DayUniques'	=> [
			'view'			=> 'Actual_Uniques',
			'fieldName'		=> 'sum(a.30_day_uniques)',
			'fieldAlias'	=> 'ACTUAL_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'Difference'	=> [
			'view'			=> 'Difference',
			'fieldName'		=> 'CASE
								WHEN sum(a.declared_uniques) < sum(a.30_day_uniques) THEN 100
								WHEN sum(a.declared_uniques) > 0 THEN ABS(100-(sum(a.30_day_uniques)/sum(a.declared_uniques))*100) ELSE 0 END',
			'fieldAlias'	=> 'DIFFERENCE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $conn = 'audience_segments';
	protected $from = 'audience_segments a';

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
			'Vendors' => Filter::getDataVendor(),
			'Columns' => [$this->getColumnView(), ['NamespaceCode', 'BidderCode', 'VendorID']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Vendors'	=> 'b.name IN ('.Format::str($options['filters']['Vendors']).')',
			'refreshed_on'	=> 'a.refreshed_on >= CURRENT_DATE - interval 7 day',
			'notNull'	=> 'a.declared_uniques is not null'
		];
		$this->join = [
			'INNER JOIN audience_segments.audience_vendors b'
			. ' ON a.AUDIENCE_VENDOR_ID=b.ID'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}

	public function dataTotal()
	{
		if (($index = array_search(
				'SUM(a.retail_cpm)',
				$this->columnsView['totals']
			)) !== false) {
			$this->columnsView['totals'][$index] = 'AVG(a.retail_cpm)';
		}
		return parent::dataTotal();
	}

	public function getDataDownload($default = [])
	{
		if (($index = array_search(
				'SUM(a.retail_cpm)',
				$this->columnsView['totals']
			)) !== false) {
			$this->columnsView['totals'][$index] = 'AVG(a.retail_cpm)';
		}
		return parent::getDataDownload($default);
	}

}
