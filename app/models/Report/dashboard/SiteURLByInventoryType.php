<?php
class SiteURLByInventoryType extends Tile
{
	public $col = [
		'Date'			=> [
			'view'			=> 'Date',
			'fieldName'		=> 'MM_DATE',
			'fieldAlias'	=> 'DATE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'SiteURL'			=> [
			'view'		=> 'Site URL',
			'fieldName'		=> 'SITE_URL',
			'fieldAlias'	=> 'SITE_URL',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'InventoryType'	=> [
			'view'			=> 'Inventory Type',
			'fieldName'		=> 'CASE WHEN INVENTORY_TYPE = \'1\' THEN \'Display\'
									WHEN INVENTORY_TYPE = \'2\' THEN \'Video\'
									ELSE \'Mobile\'
								END',
			'fieldAlias'	=> 'INVENTORY_TYPE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'sum(IMPRESSIONS)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> true
		],
		'MediaCost'		=> [
			'view'			=> 'Media Cost',
			'fieldName'		=> 'sum(MEDIA_COST)',
			'fieldAlias'	=> 'MEDIA_COST',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'CPM'			=> [
			'view'			=> 'CPM',
			'fieldName'		=> 'CASE WHEN sum(IMPRESSIONS) > 0 THEN (sum(MEDIA_COST)/sum(IMPRESSIONS))*1000 ELSE 0.00 END',
			'fieldAlias'	=> 'CPM',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		]
	];
	protected $from = 'SITE_URL_BY_INVENTORY_TYPE';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(),
				'end'	=> Format::datePicker()
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		return [
			'InventoryType'	=> Filter::getSiteInventory(),
			'Columns'		=> [$this->getColumnView(), ['CPM']]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Date'			=> 'MM_DATE  >= \''.$options['date_start'].'\' AND MM_DATE <= \''.$options['date_end'].'\'',
			'InventoryType'	=> 'INVENTORY_TYPE IN ('.Format::id($options['filters']['InventoryType']).')'
		];
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
?>
