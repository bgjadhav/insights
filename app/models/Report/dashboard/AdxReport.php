<?php
class AdxReport extends Tile
{
	public $col = [
		'Name'			=> [
			'view'			=> 'Name',
			'fieldName'		=> 'a.NAME',
			'fieldAlias'	=> 'NAME',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'ProposalId'			=> [
			'view'			=> 'Proposal Id',
			'fieldName'		=> 'a.PROPOSAL_ID',
			'fieldAlias'	=> 'PROPOSAL_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'ExternalDealId'			=> [
			'view'			=> 'External Deal Id',
			'fieldName'		=> 'a.EXTERNAL_DEAL_ID',
			'fieldAlias'	=> 'EXTERNAL_DEAL_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		// 'HasBuyerPaused'			=> [
		// 	'view'			=> 'Buyer Paused',
		// 	'fieldName'		=> 'a.HAS_BUYER_PAUSED',
		// 	'fieldAlias'	=> 'HAS_BUYER_PAUSED',
		// 	'group'			=> true,
		// 	'join' 			=> false,
		// 	'format'		=> false,
		// 	'total'			=> false,
		// 	'order'			=> false
		// ],
		// 'HasSellerPaused'			=> [
		// 	'view'			=> 'Seller Paused',
		// 	'fieldName'		=> 'a.HAS_SELLER_PAUSED',
		// 	'fieldAlias'	=> 'HAS_SELLER_PAUSED',
		// 	'group'			=> true,
		// 	'join' 			=> false,
		// 	'format'		=> false,
		// 	'total'			=> false,
		// 	'order'			=> false
		// ],
		'Type'			=> [
			'view'			=> 'Type',
			'fieldName'		=> 'a.TYPE',
			'fieldAlias'	=> 'TYPE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'GuaranteedImpressions'			=> [
			'view'			=> 'Guaranteed Impressions',
			'fieldName'		=> 'a.GUARANTEED_IMPRESSIONS',
			'fieldAlias'	=> 'GUARANTEED_IMPRESSIONS',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'GuaranteedLooks'			=> [
			'view'			=> 'Guaranteed Looks',
			'fieldName'		=> 'a.GUARANTEED_LOOKS',
			'fieldAlias'	=> 'GUARANTEED_LOOKS',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'PricingType'			=> [
			'view'			=> 'Pricing Type',
			'fieldName'		=> 'a.PRICING_TYPE',
			'fieldAlias'	=> 'PRICING_TYPE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'MicrosAmount'			=> [
			'view'			=> 'Rate',
			'fieldName'		=> 'a.MICROS_AMOUNT',
			'fieldAlias'	=> 'MICROS_AMOUNT',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> 'money',
			'total'			=> false,
			'order'			=> false
		],
		'Currency'			=> [
			'view'			=> 'Currency',
			'fieldName'		=> 'a.CURRENCY',
			'fieldAlias'	=> 'CURRENCY',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'FlightStart'			=> [
			'view'			=> 'Flight Start',
			'fieldName'		=> 'a.FLIGHT_START',
			'fieldAlias'	=> 'FLIGHT_START',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'FlightEnd'			=> [
			'view'			=> 'Flight End',
			'fieldName'		=> 'a.FLIGHT_END',
			'fieldAlias'	=> 'FLIGHT_END',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'IsSetupComplete'			=> [
			'view'			=> 'Is Setup Complete',
			'fieldName'		=> 'a.IS_SETUP_COMPLETE',
			'fieldAlias'	=> 'IS_SETUP_COMPLETE',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'BuyerId'			=> [
			'view'			=> 'Buyer Id',
			'fieldName'		=> 'a.BUYER_ID',
			'fieldAlias'	=> 'BUYER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
		'BilledBuyerId'			=> [
			'view'			=> 'Billed Buyer Id',
			'fieldName'		=> 'a.BILLED_BUYER_ID',
			'fieldAlias'	=> 'BILLED_BUYER_ID',
			'group'			=> true,
			'join' 			=> false,
			'format'		=> false,
			'total'			=> false,
			'order'			=> false
		],
	];
	protected $from = 'ADX_API a';

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
			'Buyer' 	=> Filter::getAdxBuyerIds(),
			'Type'		=> [
				'Non Guaranteed Auction Terms' => 'Non Guaranteed Auction Terms',
				'Guaranteed Fixed Price Terms' => 'Guaranteed Fixed Price Terms',
				'Non Guaranteed Fixed Price Terms' => 'Non Guaranteed Fixed Price Terms',
			]
		];
	}

	public function setQuery($options)
	{
		$this->where = [
			'Type'	=> 'a.TYPE IN ('.Format::str($options['filters']['Type']).')',
			'Buyer ID'	=> 'a.buyer_id IN ('.Format::id($options['filters']['Buyer']).')',
		];
		// !ddd($this->buildQuery());
		array_walk($this->col, [&$this, 'dataColumn']);

	}
}
