<?php
class ExchangeRankeCPA extends Tile
{
	protected $col = [
		'Aggregate'		=> [
			'view' 			=> 'Aggregate Date',
			'fieldName' 	=> 'concat(AGGREGATE_DATE, concat(" to ", AGGREGATE_DATE + interval 6 day))',
			'fieldAlias' 	=> 'CATEGORY',
			'group' 		=> true,
			'join'			=> false,
			'format'		=> false,
			'order' 		=> 'ASC',
			'total'			=> false
			],
		'ExchangeId'	=> [
			'view'			=> 'Exchange Id',
			'fieldName'		=> 'EXCHANGE_ID',
			'fieldAlias'	=> 'EXCHANGE_ID',
			'group'			=> true,
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
			],
		'Exchange'		=> [
			'view'			=> 'Exchange Name',
			'fieldName'		=> 'EXCHANGE_NAME',
			'fieldAlias'	=> 'TITLE',
			'group'			=> false,
			'gDependence'	=> 'EXCHANGE_ID',
			'join'			=> false,
			'format'		=> false,
			'order'			=> false,
			'total'			=> false
			],
		'CPA'			=> [
			'view'			=> 'eCPA',
			'fieldName'		=> 'AVG(eCPA)',
			'fieldAlias'	=> 'DATA',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'money',
			'order'			=> false,
			'total'			=> true
			],
		'Rank'			=> [
			'view'			=> 'Rank',
			'fieldName'		=> 'RANK',
			'fieldAlias'	=> 'Rank',
			'group'			=> false,
			'join'			=> false,
			'format'		=> 'ordinal',
			'order'			=> 'ASC',
			'total'			=> false
		]
	];
	protected $from = 'EXCHANGE_BY_RANK_eCPA';

	public function options($filters)
	{
		return [
			'date_picker'	=> false,
			'type'			=> ['table', 'chart-area'],
			'filters'		=> $filters
		];
	}

	public function filters()
	{
		$ignore = $aggregate = Filter::getAggregateDate();
		array_pop($ignore);
		array_pop($ignore);
		array_pop($ignore);
		array_pop($ignore);
		return [
			'Aggregate'	=> array(array_reverse($aggregate), array_keys($ignore)),
			'Exchanges'	=> Filter::getExchange()
		];
	}

	public function setQuery($options)
	{
		$this->loadConfigGraph($options['type'], $options['optionType']);

		$this->where = [
			'Date'			=> 'AGGREGATE_DATE IN('.Format::str($options['filters']['Aggregate']).')',
			'ExchangeId'	=> 'EXCHANGE_ID IN ('.Format::id($options['filters']['Exchanges']).')'
		];
		array_walk($this->col, [&$this, 'dataColumn']);

	}
}
