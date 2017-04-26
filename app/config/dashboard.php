<?php
return array(
	/*'product-analytics' => array(
		array(
			'title' => 'Product Analytics',
			'icon'  => '7.png',
			'tiles' => array(
				array(
					'title'			=> 'Product Usage Dashboard',
					'description'	=> '',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'GlobalPodAnalytics',
						'GlobalPodAnalyticsUsage',
						'GlobalPodAnalyticsOrgAdv',
						'GlobalPodAnalyticsCampaign'
					)
				)
			)
		)
	),*/
	'cso' => array(
		array(
			'title' => 'General',
			'icon'  => '1.png',
			'tiles' => array(
				array(
					'title'		 	=> 'Top Organisations Agencies',
					'description'	=> 'Top Organisations Agencies',
					'role' 			=> 'MediaMath',
					'display' 		=> array(
						'TopOrganisations',
						'TopAgencies'
					)
				),
				array(
					'title'		 	=> 'Top Advertisers With Vertical (Last 30 days)',
					'description'	=> 'Showing all advertisers with their vertical and media cost for the last 30 days',
					'role' 			=> 'MediaMath',
					'display' 		=> array(
						'TopAdvertisersWithVertical'
					)
				),
				array(
					'title'		 	=> 'Total Events By Advertiser by Day',
					'description'	=> 'Showing all total event (pixel loads + clicks + conversions + imps) by advertiser by day',
					'role' 			=> 'MediaMath',
					'display' 		=> array(
						'EventsByAdvertiserByDay'
					)
				),
				array(
					'title'		 	=> 'Total DMP Events By Advertiser by Day',
					'description'	=> 'Showing all total event (pixel loads + clicks + imps) by advertiser by DMP enabled by day',
					'role' 			=> 'MediaMath',
					'display' 		=> array(
						'DMPAdaptiveSegments'
					)
				),
				array(
					'title'		 	=> 'Decisioning and Opto Usage by Region',
					'description'	=> 'Showing count and spend',
					'role' 			=> 'MediaMath',
					'display' 		=> array(
						'GoalBrainUsage'
					)
				),
				array(
					'title' 		=> 'Pixel Loads by Advertiser by Day',
					'description' 	=> 'Showing sum of pixel fires by org, agency, advertiser, pixel by day',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'PixelLoadsByDay'
					)
				),
				array(
					'title' 		=> 'Vertical by Country by Day',
					'description' 	=> 'Showing Impressions and Media Cost by Advertiser Vertical by Country',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'VerticalByCountry'
					)
				),
				array(
					'title' 		=> 'T1 Organization Performance',
					'description' 	=> 'Showing performance metrics down to a campaign/exchange level by day',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'OrganizationAggregatedPerformance'
					)
				),
				array(
					'title' 		=> 'Channel By Organization',
					'description' 	=> 'Showing performance metrics at an Organization level for Channel',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'ChannelByClient'
					)
				)
			)
		)
	),
	'data' => array(
		array(
			'title' => 'Data/Tech Vendor Reports',
			'icon'  => '1.png',
			'tiles' => array(
				array(
					'title'			=> 'Audience Vendor Segment Usage',
					'description'	=> 'Showing count of strategies targeting each segment per vendor',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'VendorSegmentUsage'
					)
				),
				array(
					'title'			=> 'Vendor Billed Impression Percentage Report',
					'description'	=> 'Showing proportion of vendor billed impressions vs total impressions',
					'role'			=> 'OPEN',
					'display'		=> array(
						'VendorImpPCT'
					)
				),
				array(
					'title'			=> 'Vendor Gross/Net Revenue Performance',
					'description'	=> 'Showing vendor Gross/Net/Invoice amounts',
					'role'			=> 'OPEN',
					'display'		=> array(
						'VendorGrossNetRevPerformance'
					)
				),
				array(
					'title'			=> 'Data and Tech Vendor Impressions',
					'description'	=> 'Showing Impression Volumes by Vendor Type',
					'role'			=> 'OPEN',
					'display'		=> array(
						'DataTechVendorImpressions'
					)
				),
				array(
					'title'			=> 'Data and Tech Vendor Direct Revenue SOV',
					'description'	=> 'Showing Direct Revenue share of voice by Vendor Type',
					'role'			=> 'OPEN',
					'display'		=> array(
						'DataTechPercentage'
					)
				),
				array(
					'title'			=> 'Top Ten Vendor Performance by Strategy',
					'description'	=> 'Showing spend and impressions for Strategies using top ten vendors',
					'role'			=> 'OPEN',
					'display'		=> array(
						'VendorPerformanceByStrategy'
					)
				),
				array(
					'title'			=> 'Audience Data Provider - Declared vs Actual Uniques',
					'description'	=> 'Showing uniques by segment, comparison of uniques declared by an audience partner vs actual uniques available',
					'role'			=> 'OPEN',
					'display'		=> array(
						'DataPartnerSegment'
					)
				),
				array(
					'title'			=> 'Vendor by Organization - Performance By Creative (Last 3 Months)',
					'description'	=> 'Showing spend metrics by vendor by organization',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'VendorCreatives'
					)
				),
				array(
					'title'			=> 'Data/Tech Health Check',
					'description'	=> 'Showing spend, impressions, active entities by advertiser and organization',
					'role'			=> 'OPEN',
					'display'		=> array(
						'DataTechHealthCheck'
					)
				),
				array(
					'title'			=> 'Data/Tech Agency Geo Performance',
					'description'	=> 'Showing performance metrics for vendor for any Agency/Org registered as country',
					'role'			=> 'OPEN',
					'display'		=> array(
						'VendorOrgAgencyGeo'
					)
				),
				array(
					'title'			=> 'Helix KPI Tracking',
					'mainTitle'		=> 'Helix KPI Tracking',
					'description'	=> 'Showing performance metrics for vendor for any Agency/Org registered as country',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'AdroitPixelTargeting',
						'ShopperCooperative'
					)
				),
				array(
					'title'			=> 'Audience Management',
					'description'	=> 'Showing spend by Advertiser on different types of pixel (data, event, audience, adaptive)',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'AudienceManagement'
					)
				)
			)
		)
	),
	'media' => array(
		array(
			'title' => 'Health Check Reports',
			'icon'  => '1.png',
			'tiles' => array(
				array(
					'title'			=> 'Exchange Health Check',
					'description'	=> 'Overall view of Supply Partners, including Right Brain data and Media Cost',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'ExchangeHealthCheckContinents',
						'ExchangeHealthCheckInventory',
						'ExchangeHealthCheck2'
					)
				),
				array(
					'title'			=> 'Exchange Match Rates by Day',
					'description'	=> 'Showing Impression and User Match Rates',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'ExchangeMatchRates'
					)
				),
				array(
					'title'			=> 'Supply Forecasting Report',
					'description'	=> 'Showing Biddables by Creative Size by Country by Exchange by Month (with biddables > 100,000)',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'SupplyForecasting'
					)
				),
				array(
					'title'			=> 'Bid Rates by Exchange',
					'description'	=> 'Showing Bids/Biddables as a % by day by Exchange',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'BidRates'
					)
				),
				array(
					'title'			=> 'Bid Rates by Exchange by Country',
					'description'	=> 'Showing Bids/Biddables as a % by day by Exchange by Country',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'BidRatesCountry'
					)
				),
				array(
					'title'			=> 'Biddable by Inventory Type',
					'description'	=> 'Showing Biddables by Inventory Type by Day',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'BidablesByInventory'
					)
				),
				array(
					'title'			=> 'Estimated Opportunities by Region',
					'description'	=> 'Showing sampled opportunites for Regions by day to give an indication of volume',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'RegionOpportunities'
					)
				),
				array(
					'title'			=> 'Exchange 10 day Trends',
					'description' 	=> 'Exchange 10 day Trends',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'ExchangeTrends'
					)
				),
				array(
					'title' 		=> 'Global Deal Exchange 10 day Trends',
					'description' 	=> 'Global Deal 10 day Trends by Exchange',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'GlobalDealTrends'
					)
				),
				array(
					'title' 		=> 'Win Rate and Avg. Bid by Site URL by Day',
					'description' 	=> 'Showing Wins, Losses, Bids, Bid rate by exchange by site (top 50k) per day',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'WinRateBySite'
					)
				),
				array(
					'title' 		=> 'Uniques Crossover by Supply Type',
					'description' 	=> 'Showing uniques by supply type, and percentage of uniques that were hit by variations of supply type',
					'role'			=> 'MediaMath',
					'display' 		=> array(
						'UniqueCrossoverBySupply'
					)
				),
				array(
					'title'			=> 'Client Facing Domain Report',
					'description'	=> 'Showing CPM and percentage Country by month (where sites have > 5000 imps)',
					'role'			=> 'MediaMath',
					'display'		=> array(
									'FacingDomain'
					)
				),
				array(
					'title'			=> 'Non-Facing Domain Report (INTERNAL ONLY)',
					'description'	=> 'Showing Impressions, Media Cost, CPM and percentage Country by month (where sites have > 5000 imps)',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'FacingDomainNoClient'
					)
				),
				array(
					'title'			=> 'ConnectedID Reports',
					'description'	=> 'Showing a collection of Connected ID Reports',
					'role'			=> 'ConnectedID',
					'display'		=> array(
										'ConnectedIDUniques',
										'ConnectedIDImpressions',
										'ConnectedIDCampaignUniques',
										'ConnectedIDCampaignImpressions'
					)
				)
			)
		),
		array(
			'title'	=> 'Spend and Impression Reports',
			'icon'	=> '2.png',
			'tiles'	=> array(
				array(
					'title'			=> 'Spend by Exchange by Country',
					'description'	=> 'Showing Media Cost, Imps, Clicks and Conversions',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'ExchangeCountry'
					)
				),
				array(
					'title'			=> 'Spend by Exchange by Creative Size',
					'description'	=> 'Showing Media Cost and Impressions and CPM',
					'role'			=> 'OPEN',
					'display'		=> array(
						'ExchangeCreative'
					)
				),
				array(
					'title'			=> 'Video/Mobile/Display - Exchange',
					'description'	=> 'Showing ,Imps, Media Cost, CPM and Share of Voice %',
					'role'			=> 'OPEN',
					'display'		=> array(
						'ExchangeVideoMobileDisplay'
					)
				),
				array(
					'title' 		=> 'Global Performance by Business Unit',
					'description'	=> 'Spend and Impressions broken down by Organizational Business Unit, Region, and Area.',
					'role' 			=> 'OPEN',
					'display' 		=> array(
						'GlobalPerformanceByBusinessUnit'
					)
				),
				array(
					'title' 		=> 'Organization Performance by Country',
					'description' 	=> 'Showing spend and impressions for Orgs by Country',
					'role' 			=> 'MediaMath',
					'display' 		=> array(
						'OrganizationPerformanceByCountry'
					)
				),
				array(
					'title'			=> 'Supply Type Performance by Advertiser',
					'description'	=> 'Showing spend and impressions for Advertisers by Supply Type',
					'role' 			=> 'OPEN',
					'display' 		=> array(
						'SupplyTypePerformanceByAdvertiser'
					)
				),
				array(
					'title'			=> 'eBay Site Performance Global Deal vs PMP-E vs OPEN Auction',
					'description'	=> 'Showing Media Cost and Impressions for each Inventory type',
					'role'			=> 'OPEN',
					'display'		=> array(
						'eBayPerformanceGlobalDeal'
					)
				),
				array(
					'title' 		=> 'iAds Performance by Advertiser',
					'description'	=> 'Showing spend and impressions for iAd Advertisers',
					'role' 			=> 'OPEN',
					'display'		=> array(
						'iAdsPerformanceByAdvertiser'
					)
				),
				/*array(
					'title'			=> 'Spend by Exchange by Country by Channel',
					'description'	=> 'Showing Media Cost, Imps.',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'ExchangeCountryChannel'
					)
				),*/
				array(
					'title'			=> 'Exchange Rank by eCPA',
					'description'	=> 'Showing Rank by eCPA.',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'ExchangeRankeCPA'
					)
				),
				/*array(
					'title'			=> 'Exchange Rank by Channel by eCPA',
					'description'	=> 'Showing Rank by eCPA by Exchange by Channel.',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'ExchangeChannelRankeCPA'
					)
				),*/
				array(
					'title'			=> 'Video Completion Rates',
					'description'	=> 'Showing completion rates by exchange by day.',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'VideoAggregatedData'
					)
				),
				array(
					'title'			=> 'Video Skippable Impression',
					'description'	=> 'Showing Video skippable impression by exchange by day.',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'VideoSkippableImpression'
					)
				),
				array(
					'title'			=> 'Upcast Performance by Campaign by Day',
					'description'	=> 'Showing impressions and media cost by Upcast campaign (with MM advertiser ID) by day.',
					'role'			=> 'MediaMath',
					'display'		=> array(
										'UpcastPerformanceByDay'
					)
				),
				array(
					'title'			=> 'Channel by Country',
					'description'	=> 'Showing Media Cost, Imps, Spend.',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'CountryChannel'
					)
				)
			)
		),
		array(
			'title' => 'URL Reporting',
			'icon'  => '3.png',
			'tiles' => array(
				array(
					'title'			=> 'SSL vs Non-SSL',
					'description'	=> 'Showing Media Cost and Impressions',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'SslVsNonSsl'
					)
				),
				array(
					'title'			=> 'Site URL by Inventory Type',
					'description'	=> 'Showing Media Cost and Impressions for Sites by Inventory Type (Display, Video)',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'SiteURLByInventoryType'
					)
				),
				array(
					'title'			=> 'Site URL by Exchange by Country',
					'description'	=> 'Showing Media Cost and Impressions for Sites by Exchange by Country, for top 10,000 sites per day',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'SiteByCountryByExchange'
					)
				),
				array(
					'title'			=> 'Top 50 Site URLs by Exchange by Country',
					'description'	=> 'Showing Media Cost and Impressions for Sites by Exchange by Country, for top 50 sites per exchange per day',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'top50site'
					)
				)/*,
				array(
					'title'			=> 'Domain Verticals by Country/Region',
					'description'	=> 'Showing Media Cost and Impressions. This data is a sample based on what could be verticalised and should be used to source targeting availability and not for actual performance metrics',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'DomainCountryVerticals'
					)
				)*/
			)
		),
		array(
			'title' => 'Right Brain Reporting',
			'icon'  => '4.png',
			'tiles' => array(
				array(
					'title' 		=> 'Bid Distribution by Exchange',
					'role'			=> 'OPEN',
					'description' 	=> 'Showing Bids, Wins, Losses, Win Rate, by Bid Bucket by Exchange.',
					'display' 		=> array(
						'BidDistribution'
					)
				),
				array(
					'title'			=> 'Right Brain Data by Exchange by Country by Day',
					'role'			=> 'OPEN',
					'description'	=> 'Showing Bids, Wins, Losses, Win Rate, Avg. Bid (CPM) and Avg. Win',
					'display'		=> array(
						'RightBrainExchangeCountryDay'
					)
				)
			)
		),
		array(
			'title' => 'Deal Reporting',
			'icon'  => '5.png',
			'tiles' => array(
				array(
					'title'			=> 'Deal Daily Health Check',
					'description'	=> 'Showing spend health indicators and metadata per deal (PMP-E/Global Deal)',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'DealDailyHealthCheck'
					)
				),
				array(
					'title'			=> 'Estimated Opportunities by Deal ID/External ID',
					'description'	=> 'Showing sampled opportunites for Deal IDs by day to give an indication of volume',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'DealOpportunities'
					)
				),
				array(
					'title'			=> 'PMP-E by Exchange by Strategy',
					'description'	=> 'Showing Media Cost,Impressions, for active PMP-E deals down to a strategy level',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'PMPEExchangeByStrategy'
					)
				),
				array(
					'title'			=> 'Global Deals UUIDS',
					'description'	=> 'Day by day breakout of Reach against Global Deals and Non-Global Deals',
					'role'			=> 'OPEN',
					'display'		=> array(
						'GlobalDealsUUIDS'
					)
				),
				array(
					'title' 		=> 'Global Deals by Advertiser',
					'description'	=> 'Showing Media Cost,Impressions and Conversions',
					'role'			=> 'OPEN',
					'display' 		=> array(
						'GlobalDealByAdvertiser'
					)
				),
				array(
					'title' 		=> 'Global Deals by Advertiser By Country',
					'description'	=> 'Showing Media Cost,Impressions by exchange, country, advertser and deal',
					'role'			=> 'OPEN',
					'display' 		=> array(
						'GlobalDealByAdvertiserByGeo'
					)
				),
				array(
					'title' 		=> 'Global Deals by Country',
					'description'	=> 'Showing Media Cost and Impressions by country for Global Deals only',
					'role'			=> 'OPEN',
					'display' 		=> array(
						'GlobalDealByGeo'
					)
				),
				array(
					'title'			=> 'PMP-E by Exchange by Country (incl Global Deals)',
					'description'	=> 'Showing Media Cost,Impressions, for active PMP-E deals down to a Advertiser level with Country and Exchange',
					'role'			=> 'Restricted',
					'display'		=> array(
						'PMPEByExchangeByCountry'
					)
				),
				array(
					'title'			=> 'Open Marketplace vs PMP vs Remnant',
					'description'	=> 'Showing Media Cost,Impressions, breaking out Exchange by PMP, Open Marketplace and Other',
					'role'			=> 'OPEN',
					'display'		=> array(
						'OpenMarketplaceVsPMPVsRemnant'
					)
				),
				array(
					'title'			=> 'Privileged Supply vs Private Supply vs Remnant',
					'description'	=> 'Showing metrics for full breakout of Privileged supply and private supply against Remnant Inventory',
					'role'			=> 'Restricted',
					'display'		=> array(
						'PrivateSupplyVsPrivilegedVsRemnant'
					)
				),
				/*array(
					'title'			=> 'Global Deal by Exchange',
					'description'	=> 'Showing Media Cost,Impressions, etc. by topline Exchange with number of distinct deals',
					'role'			=> 'Restricted',
					'display'		=> array(
						'GlobalDealByExchange'
					)
				),*/
				array(
					'title'			=> 'Global Deal by Exchange',
					'description'	=> 'Showing Media Cost,Impressions, etc. by topline Exchange with number of distinct deals',
					'role'			=> 'Restricted',
					'display'		=> array(
						'GlobalDealByExchange'
					)
				),
				array(
					'title'			=> 'PMP-D Deals by Strategy by Country',
					'role'			=> 'OPEN',
					'description'	=> 'Showing Media Cost,Impressions, etc. by Strategy and Country for PMP-D',
					'display'		=> array(
						'PMPDPerformanceByStrategyByCountry'
					)
				),
				array(
					'title'			=> 'PMP-E Average Bid Price by Strategy',
					'description'	=> 'Showing Avg bid price for active PMP-E deals down to a strategy level',
					'role'			=> 'OPEN',
					'display'		=> array(
						'PMPEAverageBidByStrategy'
					)
				),
				array(
					'title'			=> 'Automated Guaranteed Performance by Strategy',
					'description'	=> 'Showing spend and impressions for AG Strategies with Clicks and Convs',
					'role'			=> 'OPEN',
					'display'		=> array(
						'AutomatedGuaranteedPerformanceByStrategy'
					)
				),
				array(
					'title'			=> 'PMP-E by Exchange',
					'description'	=> 'Showing metrics for active PMP-E deals topline by exchange',
					'role'			=> 'OPEN',
					'display'		=> array(
						'PMPEExchange'
					)
				),
				array(
					'title'			=> 'PMP-E by Exchange by Country (excl Global Deals)',
					'description'	=> 'Showing metrics for active PMP-E deals topline by exchange, excluding Global Deals',
					'role'			=> 'OPEN',
					'display'		=> array(
						'PMPEExchangebyCountryExcDeals'
					)
				),
				array(
					'title'			=> 'Global Deal ID Performance',
					'description'	=> 'Showing Media Cost,Impressions and Conversions',
					'role'			=> 'OPEN',
					'display'		=> array(
						'GlobalDealIDPerformance'
					)
				)
			)
		),
		array(
			'title' => 'Mobile Reporting',
			'icon'  => '6.png',
			'tiles' => array(
				array(
					'title'			=> 'Mobile Performance',
					'description'	=> 'Showing Imps, Media Cost and CPM',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'Mobile'
					)
				),
				array(
					'title'			=> 'Web vs In-App',
					'description'	=> 'Showing Imps, Media Cost CPM and Share of Voice %',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'WebVsInApp'
					)
				),
				array(
					'title'			=> 'Mobile Dashboard',
					'description'	=> 'Showing Imps, Media Cost across a range of mobile reports',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'MobileTopAdvertisers',
						'MobileChannelBreakout',
						'MobileChannelByCountry'
					)
				)
			)
		),
		array(
			'title' => 'Performance Comparison',
			'icon'  => '7.png',
			'tiles' => array(
				array(
					'title'			=> 'Supply Type Performance Comparison - Last 180 days',
					'description'	=> 'Showing performance comparison for FLPAR, PMP, RTB',
					'role'			=> 'OPEN',
					'display'		=> array(
						'SupplyComparison'
					)
				),
				array(
					'title'			=> 'Akamai/Adroit Batch processing logs',
					'description'	=> 'Showing lines processed by Log Group and Customer',
					'role'			=> 'OPEN',
					'display'		=> array(
						'AkamaiAdroitBatchProcessingLogs'
					)
				),
				array(
					'title'			=> 'APN Seller Performance by Country Report',
					'description'	=> 'Showing spend and impressions for APN Sellers by day',
					'role'			=> 'OPEN',
					'display'		=> array(
						'ApnSellerPerformance'
					)
				),
				array(
					'title'			=> 'Bid to Clear Price Ratio by Exchange',
					'description'	=> 'Showing, by exchange, the relationship between bid price and clear price split into ratio buckets',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'BidsClearRatioExchange'
					)
				)
			)
		),
		array(
			'title' => 'Partner Discrepancy',
			'icon'  => '8.png',
			'tiles' => array(
				array(
					'title'			=> 'Adap TV External Performance vs MM Adap TV Internal Performance',
					'description'	=> 'Showing spend and impressions for ATV by day vs MM Spend and Impressions for ATV',
					'role'			=> 'OPEN',
					'display'		=> array(
						'AdapTVMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'AdMeta External Performance vs MM AdMeta Internal Performance',
					'description'	=> 'Showing spend and impressions for AMT by day vs MM Spend and Impressions for AMT',
					'role'			=> 'OPEN',
					'display'		=> array(
						'AdMetaMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'AOL External Performance vs MM AOL Internal Performance',
					'description'	=> 'Showing spend and impressions for AOL by day vs MM Spend and Impressions for AOL',
					'role'			=> 'OPEN',
					'display'		=> array(
						'AOLMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'AppNexus External Performance vs MM AppNexus Internal Performance',
					'description'	=> 'Showing spend and impressions for APN by day vs MM Spend and Impressions for APN, MAX',
					'role'			=> 'OPEN',
					'display'		=> array(
						'APNMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Geniee External Performance vs MM Geniee Internal Performance',
					'description'	=> 'Showing spend and impressions for GEN by day vs MM Spend and Impressions for GEN',
					'role'			=> 'OPEN',
					'display'		=> array(
						'GenieeMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Improve Digital External Performance vs MM Improve Digital Internal Performance',
					'description'	=> 'Showing spend and impressions for IPV by day vs MM Spend and Impressions for IPV',
					'role'			=> 'OPEN',
					'display'		=> array(
						'ImproveMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Index External Performance vs MM Index Internal Performance',
					'description'	=> 'Showing spend and impressions for INX by day vs MM Spend and Impressions for INX',
					'role'			=> 'OPEN',
					'display'		=> array(
						'IndexMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'LiveIntent External Performance vs MM LiveIntent Internal Performance',
					'description'	=> 'Showing spend and impressions for LVI by day vs MM Spend and Impressions for LVI',
					'role'			=> 'OPEN',
					'display'		=> array(
						'LiveIntentMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Liverail External Performance vs MM Liverail Internal Performance',
					'description'	=> 'Showing spend and impressions for LIV by day vs MM Spend and Impressions for LIV',
					'role'			=> 'OPEN',
					'display'		=> array(
						'LiverailMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Nexage External Performance vs MM Nexage Internal Performance',
					'description'	=> 'Showing spend and impressions for NXG by day vs MM Spend and Impressions for NXG',
					'role'			=> 'OPEN',
					'display'		=> array(
						'NexageMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Pubmatic External Performance vs MM Pubmatic Internal Performance',
					'description'	=> 'Showing spend and impressions for PUB by day vs MM Spend and Impressions for PUB',
					'role'			=> 'OPEN',
					'display'		=> array(
						'PubmaticMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'PulsePoint External Performance vs MM PulsePoint Internal Performance',
					'description'	=> 'Showing spend and impressions for CTW by day vs MM Spend and Impressions for CTW',
					'role'			=> 'OPEN',
					'display'		=> array(
						'PulsepointMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Rubicon External Performance vs MM Rubicon Internal Performance',
					'description'	=> 'Showing spend and impressions for RUC by day vs MM Spend and Impressions for RUC',
					'role'			=> 'OPEN',
					'display'		=> array(
						'RubiconMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'SmartAdServer External Performance vs MM SmartAdServer Internal Performance',
					'description'	=> 'Showing spend and impressions for SAS by day vs MM Spend and Impressions for SAS',
					'role'			=> 'OPEN',
					'display'		=> array(
						'SmartAdServerMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Sonobi External Performance vs MM Sonobi Internal Performance',
					'description'	=> 'Showing spend and impressions for SON by day vs MM Spend and Impressions for SON',
					'role'			=> 'OPEN',
					'display'		=> array(
						'SonobiMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'Sovrn External Performance vs MM Sovrn Internal Performance',
					'description'	=> 'Showing spend and impressions for SVN by day vs MM Spend and Impressions for SVN',
					'role'			=> 'OPEN',
					'display'		=> array(
						'SovrnMMDiscrepancy'
					)
				),
				array(
					'title'			=> 'SpotXchange External Performance vs MM SpotXchange Internal Performance',
					'description'	=> 'Showing spend and impressions for SXC by day vs MM Spend and Impressions for SXC',
					'role'			=> 'OPEN',
					'display'		=> array(
						'SpotXchangeMMDiscrepancy'
					)
				)
			)
		)
		/*,
		array(
			'title' => 'Jira Reporting Performance',
			'icon'  => '7.png',
			'tiles' => array(
			array(
			 'title'			=> 'Jira Reporting Performance',
			 'description'	=> 'Jira Reporting Performance',
			 'role'			=> 'OPEN',
			 'display'		=> array(
			 'DBOpenActivity',
			 'DBOpenActivitySubType',
			 'DBOpenedSubType'
			)
			)
			)
		)*/
	),
	'open-financials' => array(
		array(
			'title' => 'Open Financials',
			'icon'  => '7.png',
			'tiles' => array(
				array(
					'title'			=> 'Topline Financials',
					'description'	=> 'Showing topline actual spend, forecasting and target numbers for '.date('Y'),
					'role'			=> 'Financial',
					'display'		=> array(
						'ToplineFinancials'
					)
				),
				//~ array(
					//~ 'title'			=> 'Financial Accruals',
					//~ 'description'	=> 'Showing expected invoice, actual invoice and accrual of vendor or supply for '.date('Y'),
					//~ 'role'			=> 'Financial',
					//~ 'display'		=> array(
						//~ 'AccrualsFinancials'
					//~ )
				//~ ),
				//~ array(
					//~ 'title'			=> 'Variance Report',
					//~ 'description'	=> 'Showing expected invoice, actual invoice and variance of vendor or supply for '.date('Y'),
					//~ 'role'			=> 'Financial',
					//~ 'display'		=> array(
						//~ 'VarianceFinancials'
					//~ )
				//~ )
			)
		)
	),
	'open-analytics' => array(
		array(
			'title' => 'Open Analytics',
			'icon'  => '4.png',
			'tiles' => array(
				array(
					'title'			=> 'Bucket Status',
					'description'	=> 'Showing status of the buscket',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OABucketsStatus'
					)
				),
				array(
					'title'			=> 'Daily Report',
					'description'	=> 'Showing the status of the daily report',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OADailyImport'
					)
				),
				array(
					'title'			=> 'Track',
					'description'	=> 'Showing track Tiles',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OATracks'
					)
				),
				array(
					'title'			=> 'Diagnostic',
					'description'	=> 'Showing diagnostic of the missing data from Daily report',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OADiagnostic'
					)
				),
				array(
					'title'			=> 'Sum Tables',
					'description'	=> 'Showing the status of the bucket process',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OASumTables'
					)
				),
				array(
					'title'			=> 'Slow queries',
					'description'	=> 'Showing which queries spend more that one minute',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OAQueryTime'
					)
				),
				array(
					'title'			=> 'SearchJIRA',
					'description'	=> 'Showing searchs in JIRA menu',
					'role'			=> 'CoreOPENAnalyst',
					'display'		=> array(
						'OASearchJIRA'
					)
				)
			)
		)
	),
	'jira' => array(
		array(
			'title' => 'Jira',
			'icon'  => '7.png',
			'tiles' => array(
				array(
					'title'			=> 'Commercialization JIRA Performance',
					'mainTitle'		=> 'Commercialization JIRA Performance',
					'description'	=> 'Commercialization JIRA Performance',
					'noBack'		=> true,
					'role'			=> 'MediaMath',
					'display'		=> array(
						'DBOpenActivity',
						'DBOpenActivitySubType',
						'DBOpenedSubType'
					)
				)
			)
		),
		array(
			'title' => 'PMAP Revenue',
			'icon'  => '7.png',
			'tiles' => array(
				array(
					'title'			=> 'PMAP Revenue',
					'description'	=> 'PMAP Revenue',
					'role'			=> 'MediaMath',
					'display'		=> array(
						'PmapRev'
					)
				)
			)
		)
	),
);
