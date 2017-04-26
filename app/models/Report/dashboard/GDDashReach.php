<?php
class GDDashReach extends Tile
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
				'type'		=> 'INNER',
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
		'Channel'		=> [
			'view'			=> 'Channel',
			'fieldName'		=> 'b.CHANNEL_NAME',
			'fieldAlias'	=> 'CHANNEL',
			'group'			=> false,
			'gDependence'	=> 'a.CHANNEL_TYPE',
			'join'			=> [
				'type'			=> 'LEFT',
				'tableName'		=> 'META_CHANNEL_TYPE_IMPRESSION_LOG b',
				'tableAlias'	=> 'b',
				'fieldA'		=> 'CHANNEL_ID',
				'joinAlias'		=> 'a',
				'fieldB'		=> 'CHANNEL_TYPE'
			],
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
		],
		'Uniques'	=> [
			'view'			=> 'Uniques',
			'fieldName'		=> 'COALESCE(a.UNIQUES,0)',
			'fieldAlias'	=> 'UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		],
		'OAUniques'	=> [
			'view'			=> 'OA Uniques',
			'fieldName'		=> 'COALESCE(d.UNIQUES,0)',
			'fieldAlias'	=> 'OA_UNIQUES',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'number',
			'order'			=> false,
			'total'			=> false
		]
	];
	protected $from = 'GD_DASH_REACH a LEFT JOIN GD_DASH_REACH_OA d on (a.MM_DATE = d.MM_DATE AND a.PUBLISHER_ID = d.PUBLISHER_ID AND a.CREATIVE_SIZE = d.CREATIVE_SIZE AND a.EXCHANGE_ID = d.EXCHANGE_ID AND a.COUNTRY = d.COUNTRY AND a.CHANNEL_TYPE = d.CHANNEL_TYPE)';

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
		return [
			'Exchange'  => Filter::getExchange(),
			'Country'	=> Filter::getCountryImpression_name_only(),
			'Channel'   => Filter::getChannelMeta(),
			'Columns'	=> [$columns, ['CreativeSize','Country', 'Channel']]
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
			}
		}
		//var_dump($options['filters']['Columns']);
		$columns_clause = array();
		$columns_clause['DealID'] = " a.DEAL_ID in (-1) ";
		$columns_clause['PublisherID'] = " a.PUBLISHER_ID in (-1) ";
		$columns_clause['CreativeSize'] = " a.CREATIVE_SIZE in (-1) ";
		$columns_clause['ExchangeName'] = " a.EXCHANGE_ID in (-1) ";
		$columns_clause['Country'] = " a.COUNTRY in (-1) ";
		$columns_clause['Channel'] = " a.CHANNEL_TYPE in (-1) ";

		$neg_columns_clause = array();
		$neg_columns_clause['DealID'] = " a.DEAL_ID not in (-1) ";
		$neg_columns_clause['PublisherID'] = " a.PUBLISHER_ID not in (-1) ";
		$neg_columns_clause['CreativeSize'] = " a.CREATIVE_SIZE not in (-1) ";
		$neg_columns_clause['ExchangeName'] = " a.EXCHANGE_ID not in (-1) ";
		$neg_columns_clause['Country'] = " a.COUNTRY not in (-1) ";
		$neg_columns_clause['Channel'] = " a.CHANNEL_TYPE not in (-1) ";
		$final_where = "";
		foreach($columns_clause as $filter => $clause)
		{
			if(!in_array($filter,$options['filters']['Columns']))
			{
				$final_where = $final_where." AND ".$clause;
			}
			else
			{
				$final_where = $final_where." AND ".$neg_columns_clause[$filter];
			}
		}
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

		if(in_array('Channel',$options['filters']['Columns']))
		{
			$this->where['Channel'] = 'a.CHANNEL_TYPE IN ('.Format::str($options['filters']['Channel']).')';
		}
		
		array_walk($options['filters']['Columns'], [&$this, 'addDataColumn']);
	}
}
?>