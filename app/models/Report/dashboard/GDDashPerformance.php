<?php
class GDDashPerformance extends Tile
{
	public  $col = [
		'Date'			=> [
			'view' 		=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> 'DATE',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealID'		=> [
			'view'			=> 'Deal ID',
			'fieldName'		=> 'a.DEAL_ID',
			'fieldAlias'	=> 'DEAL_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'DealName'		=> [
			'view'			=> 'Deal Name',
			'fieldName'		=> 'c.NAME',
			'fieldAlias'	=> 'DEAL_NAME',
			'gDependence'	=> 'a.DEAL_ID',
			'group'			=> false,
			'join' 				=> [
				'type'		=> 'LEFT',
				'tableName'		=> 'META_DEALS c',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'DEAL_ID',
				'joinAlias'		=> 'c',
				'fieldB'		=> 'ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'ExchangeName'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'e.EXCH_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'gDependence'	=> 'a.EXCHANGE_ID',
			'group'			=> false,
			'join' 				=> [
				'type'		=> 'INNER',
				'tableName'		=> 'META_EXCHANGE e',
				'tableAlias'	=> 'a',
				'fieldA'		=> 'EXCHANGE_ID',
				'joinAlias'		=> 'e',
				'fieldB'		=> 'EXCH_ID'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'PublisherID'		=> [
			'view'			=> 'Publisher ID',
			'fieldName'		=> 'a.PUBLISHER_ID',
			'fieldAlias'	=> 'PUBLISHER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'CreativeSize'	=> [
			'view'			=> 'Creative Size',
			'fieldName'		=> 'a.CREATIVE_SIZE',
			'fieldAlias'	=> 'CREATIVE_SIZE',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Country'		=> [
			'view'			=> 'Country',
			'fieldName'		=> 'a.COUNTRY',
			'fieldAlias'	=> 'Country',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Impressions'	=> [
			'view'			=> 'Impressions',
			'fieldName'		=> 'COALESCE(sum(a.IMPRESSIONS),0)',
			'fieldAlias'	=> 'IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'OAImpressions'	=> [
			'view'			=> 'OA Impressions',
			'fieldName'		=> 'COALESCE(sum(d.IMPRESSIONS),0)',
			'fieldAlias'	=> 'OA_IMPRESSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'Clicks'	=> [
			'view'			=> 'Clicks',
			'fieldName'		=> 'COALESCE(sum(a.CLICKS),0)',
			'fieldAlias'	=> 'CLICKS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'OAClicks'	=> [
			'view'			=> 'OA Clicks',
			'fieldName'		=> 'COALESCE(sum(d.CLICKS),0)',
			'fieldAlias'	=> 'OACLICKS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'Conversions'	=> [
			'view'			=> 'Conversions',
			'fieldName'		=> 'COALESCE(sum(a.CONVERSIONS),0)',
			'fieldAlias'	=> 'CONVERSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'OAConversions'	=> [
			'view'			=> 'OA Conversions',
			'fieldName'		=> 'COALESCE(sum(d.CONVERSIONS),0)',
			'fieldAlias'	=> 'OACONVERSIONS',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'ConversionRate'	=> [
			'view'			=> 'Conversion Rate',
			'fieldName'		=> 'COALESCE((sum(a.CONVERSIONS)/sum(a.IMPRESSIONS))*100,0)',
			'fieldAlias'	=> 'CONVERSIONRATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> false
		],
		'OAConversionRate'	=> [
			'view'			=> 'OA Conversion Rate',
			'fieldName'		=> 'COALESCE((sum(d.CONVERSIONS)/sum(d.IMPRESSIONS))*100,0)',
			'fieldAlias'	=> 'OA_CONVERSIONRATE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> false
		],
		'ClickRate'	=> [
			'view'			=> 'CTR',
			'fieldName'		=> 'COALESCE((sum(a.CLICKS)/sum(a.IMPRESSIONS))*100,0)',
			'fieldAlias'	=> 'CTR',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> false
		],
		'OAClickRate'	=> [
			'view'			=> 'OA CTR',
			'fieldName'		=> 'COALESCE((sum(d.CLICKS)/sum(d.IMPRESSIONS))*100,0)',
			'fieldAlias'	=> 'OA_CTR',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'percentage5',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'GD_DASH_PERFORMANCE a LEFT JOIN GD_DASH_PERFORMANCE_OA d on (a.MM_DATE = d.MM_DATE AND a.PUBLISHER_ID = d.PUBLISHER_ID AND a.CREATIVE_SIZE = d.CREATIVE_SIZE AND a.EXCHANGE_ID = d.EXCHANGE_ID AND a.COUNTRY_ID = d.COUNTRY_ID)';

	public function options($filters)
	{
		return [
			'date_picker'	=> [
				'start'	=> Format::datePicker(2),
				'end'	=> Format::datePicker(2)
			],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		//var_dump($this->getColumnView());exit;
		$columns = $this->getColumnView();
		unset($columns['DealID']);
		unset($columns['DealName']);
		unset($columns['PublisherID']);
		unset($columns['ExchangeName']);
		unset($columns['CreativeSize']);
		unset($columns['Country']);
		return [
			'Exchange'  => Filter::getExchange(),
			'Country'	=> Filter::getCountryImpression_name_only(),
			'Columns'	=> [$columns, ['CreativeSize','Country', 'Clicks', 'OAClicks','Conversions', 'OAConversions']]
		];
	}

	public function setQuery($options)
	{
		//var_dump($options['filters']['Columns']);
		$initial_columns = $options['filters']['Columns'];
		$re_add_columns = array();
		if(isset($options['filters']['Columns'][0]))
		{
			if($options['filters']['Columns'][0] == 'Date')
			{
				array_push($re_add_columns, 'Date');
				array_push($re_add_columns, 'DealID');
				array_push($re_add_columns, 'DealName');
				array_push($re_add_columns, 'ExchangeName');
				array_push($re_add_columns, 'PublisherID');
				array_push($re_add_columns, 'CreativeSize');
				array_push($re_add_columns, 'Country');
				
				for($i=1;$i<count($options['filters']['Columns']);$i++)
				{
					array_push($re_add_columns, $options['filters']['Columns'][$i]);
				}
				$options['filters']['Columns'] = $re_add_columns;
			}
			else
			{
				array_unshift($options['filters']['Columns'], 'DealID');
				array_unshift($options['filters']['Columns'], 'DealName');
				array_unshift($options['filters']['Columns'], 'PublisherID');
				array_unshift($options['filters']['Columns'], 'ExchangeName');
				array_unshift($options['filters']['Columns'], 'CreativeSize');
				array_unshift($options['filters']['Columns'], 'Country');
			}
		}
		//var_dump($options['filters']['Columns']);
	
		$final_where = "";
		
		$this->where = [
			'Date'			=> 'a.MM_DATE  >= \''.$options['date_start'].'\' AND a.MM_DATE <= \''.$options['date_end'].'\''.$final_where
			//'Country'		=> 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')',
			//'Vendor_Type'	=> 'VENDOR_TYPE IN ('.Format::str($options['filters']['Vendor_Type']).')'
		];
		if(in_array('ExchangeName',$options['filters']['Columns']))
		{
			$this->where['Exchange'] = 'a.EXCHANGE_ID IN ('.Format::str($options['filters']['Exchange']).')';
		}

		if(in_array('Country',$options['filters']['Columns']))
		{
			$this->where['Country'] = 'a.COUNTRY IN ('.Format::str($options['filters']['Country']).')';
		}

		
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
?>