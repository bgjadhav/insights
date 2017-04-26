<?php
/*@Todo add to table and compile with generic file*/
return [

	'exception' => [
		'ExchangeHealthCheck2',
		'ExchangeHealthCheckInventory',
		'ExchangeHealthCheckContinents',
		'ExchangeMMDiscrepancy',
		'DataPartnerSegment',
		'OAQueryTime',
		'SupplyForecasting',
		'VendorCreatives',
		'ToplineFinancials',
		'AkamaiAdroitBatchProcessingLogs',
		'ExchangeRankeCPA',
		'SupplyComparison',
		'FacingDomain',
		'FacingApp',
		'FacingDomainNoClient',
		'FacingAppNoClient',
		'TopAgencies',
		'TopOrganisations',
		'OATracks',
		'TopAdvertisersWithVertical',
		'DBOpenedSubType',
		'DBOpenActivitySubType',
		'DBOpenActivity',
		'DBOpenActivity_PRDREQ',
		'DBResolutionAnalytics',
		'RolesByUserAndByTeam',
		'RolesByReport',
		'RolesByWidget',
		'DealDailyHealthCheck',
		'PmapRev',
		'RoadmapUsageDetail',
		'AlertSubscribers',
		'AlertUnsubscribers',
		'UserLastLogin',
		'MostSharedProject',
		'ItunesAppMapping'
	],

	'warning' => [
		'iAdsPerformanceByAdvertiser',
		'AutomatedGuaranteedPerformanceByStrategy'
	],

	'aggregate' => [
		'DomainCountryVerticals' => [
			'aggregate' => 'AGGREGATE_DATE'
		]
	],

	'legend' => [
		'ExchangeCountryChannel' => [
			'table' => [
				[
					'title'		=> '(*)',
					'options'	=>  [
						'Display'	=> 'Display',
						'Video'		=> 'Video',
						'Mobile'	=> 'Mobile display, mobile video'
										.' (Web and in-app)',
						'Other'		=> 'Search, email, Newsfeed (FBX)'
					]
				]
			]
		],
		'ExchangeCountry' => [
			'chart-line' => [
				[
					'title'		=> '',
					'options'	=>  [
						''=> '<div class="info"><span>For default this graph'
								.' shows the top ten countries</span></div>'
					]
				]
			]
		],
		'AudienceManagement' => [
			'table' => [
				[
					'title'		=> '',
					'options'	=>  [
						''=> '<div class="info"><span>Spend totals include some duplication'
								.' due to multiple pixels being applied to 1 strategy</span></div>'
					]
				]
			]
		],
		'ToplineFinancials' => [
			'table' => [
				[
					'title'		=>'(*)',
					'options'	=>  [
						'' => '<div style="text-align: right;">'
							.'<span style="display:inline;" class="target">'
							.'Target</span>'
							.'<br><span style="display:inline;" class="actual">'
							.'Actuals</span>'.
							'<br><span style="display:inline;" '
								.'class="targetAverage">% of Target</span>'.
							'<br><span style="display:inline;" '
								.'class="forecast">Forecast</span>'.
							'<div>'
					]
				]
			]
		],
		'GlobalPodAnalytics' => [
			'chart-line' => [
				[
					'title'=>'Please Note:',
					'options' =>  [
						''	=> 'The data contained within this page is not '
							.'to be used for billing.'
							.' It is for trend analysis only, as it relies '
							.'on accurate meta data passed from the exchanges.'
							.' <span id="GlobalPodAnalyticsNote">x</span>'
					]
				]
			]
		],
		'RolesByUserAndByTeam' => [
			'table' => [
				[
					'title'		=>'(*)',
					'options'	=>  [
						'' => '<div style="text-align: right;">Basic permission for each user is MediaMath<div>'
					]
				]
			]
		],
	],

];
