<?php
class ExchangeChannelRankeCPA extends Tile
{
	private $regions		= [];
	private $deleteCountry	= false;
	private $region			= false;
	private $baseOrder		= [];
	public $col = [
		'Date'			=> [
			'view' 			=> 'Date',
			'fieldName' 	=> 'a.MM_DATE',
			'fieldAlias' 	=> '`DATE`',
			'group' 		=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'a.EXCHANGE_NAME',
			'fieldAlias'	=> 'EXCHANGE_NAME',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'ChannelType'		=> [
			'view'			=> 'Channel Type',
			'fieldName'		=> 'a.C_TYPE',
			'fieldAlias'	=> 'CHANNEL_TYPE',
			'group'			=> false,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> true
		],
		'CPA'			=> [
			'view'			=> 'eCPA',
			'fieldName'		=> 'a.eCPA',
			'fieldAlias'	=> 'eCPA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
		],
		'Rank'			=> [
			'view'			=> 'Rank',
			'fieldName'		=> 'IF(MM_DATE != @current_date OR C_TYPE != @current_type, greatest(least(0, greatest(length(@current_date:= MM_DATE),length(@current_type:= C_TYPE))),@rownum:=1), @rownum:=@rownum+1)',
			'fieldAlias'	=> 'RANK',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'ordinal',
			'order'			=> false,
			'total'			=> true
		]
	];

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'total'			=> false,
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		$ignore = $aggregate = Filter::getAggregateDate(true);
		array_pop($ignore);
		return [
			'Aggregate'	=> [array_reverse($aggregate), array_keys($ignore)]
		];
	}

	public function setQuery($options)
	{
		/*$this->where = [
						'Date'			=> 'a.MM_DATE IN('.Format::str($options['filters']['Aggregate']).')',
						'ExchangeId'	=> 'a.EXCHANGE_ID IN ('.Format::str($options['filters']['Exchanges']).')'
						);*/
		$this->from = '(
					  SELECT concat(concat(STR_TO_DATE(concat(YEARWEEK(MM_DATE,7),\' Monday\'),\'%X%V %W\'), \' to \'), STR_TO_DATE(concat(YEARWEEK(MM_DATE,7),\' Monday\'),\'%X%V %W\')+ interval 6 day)  as MM_DATE,
					  EXCHANGE_ID,
					  EXCHANGE_NAME,
					  CASE
					  WHEN CHANNEL_TYPE in (1)
					  THEN \'Display\'
					  WHEN CHANNEL_TYPE in (2)
					  THEN \'Video\'
					  WHEN CHANNEL_TYPE in (4,5,8,9)
					  THEN \'Mobile\'
					  WHEN EXCHANGE_ID in (23)
					  THEN \'FBX\'
					  END as C_TYPE,
					  (sum(MEDIA_COST)/sum(CONVERSIONS)) as eCPA
					  FROM CPA_BY_EXCHANGE_BY_CHANNEL
					  WHERE EXCHANGE_ID not in (9990,23) AND concat(concat(STR_TO_DATE(concat(YEARWEEK(MM_DATE,7),\' Monday\'),\'%X%V %W\'), \' to \'), STR_TO_DATE(concat(YEARWEEK(MM_DATE,7),\' Monday\'),\'%X%V %W\')+ interval 6 day) IN('.Format::str($options['filters']['Aggregate']).')
					  GROUP BY concat(concat(STR_TO_DATE(concat(YEARWEEK(MM_DATE,7),\' Monday\'),\'%X%V %W\'), \' to \'), STR_TO_DATE(concat(YEARWEEK(MM_DATE,7),\' Monday\'),\'%X%V %W\')+ interval 6 day), EXCHANGE_ID,EXCHANGE_NAME, CASE
					  WHEN CHANNEL_TYPE in (1)
					  THEN \'Display\'
					  WHEN CHANNEL_TYPE in (2)
					  THEN \'Video\'
					  WHEN CHANNEL_TYPE in (4, 5, 8, 9)
					  THEN \'Mobile\'
					  WHEN EXCHANGE_ID in (23)
					  THEN \'FBX\'
					  END
					  ORDER BY YEARWEEK(MM_DATE,0), C_TYPE, eCPA asc) a,
						(SELECT @rownum:=0,@rownum2:=0,@rownum3:=0, @current_date:=\'\') r';
		array_walk($this->col, [&$this, 'dataColumn']);
	}

}
