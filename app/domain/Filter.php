<?php
class Filter
{
	public static function getOrganization()
	{
		return DB::connection('analytics')->
			table('META_CAMPAIGN')->
			select(DB::raw('concat("xx", ORGANIZATION_ID) as ORGANIZATION_ID'), 'ORGANIZATION_NAME')->
			groupBy('ORGANIZATION_ID', 'ORGANIZATION_NAME')->
			orderBy(DB::raw('lower(ORGANIZATION_NAME)'), 'ASC')->
			remember(Format::timeOut())->
			lists('ORGANIZATION_NAME', 'ORGANIZATION_ID');
	}

	public static function getDMPEnabled()
	{
		return ['1' => 'Enabled', '0' => 'Not Enabled', '-1' => 'N/A'];
	}

	public static function getPods()
	{
		return DB::connection('analytics')->
			table('META_PS_PODS_CAMPAIGN')->
			select('POD_NAME', 'POD_NAME')->
			groupBy('POD_NAME', 'POD_NAME')->
			orderBy('POD_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('POD_NAME', 'POD_NAME');
	}

	public static function getPodsOrganization($pods=false)
	{
		$where =  '';
		if ($pods != 'all') {
			$where = 'WHERE b.POD_NAME IN ('.Format::str($pods).')';
		}
		return DB::connection('analytics')->
			table('META_CAMPAIGN')->
			select(DB::raw('concat("xx", ORGANIZATION_ID) as ORGANIZATION_ID'), 'ORGANIZATION_NAME')->
			whereRaw('ORGANIZATION_ID IN (SELECT b.ORGANIZATION_ID from META_PS_PODS_CAMPAIGN b '.$where.' GROUP BY b.ORGANIZATION_ID)')->
			groupBy('ORGANIZATION_ID', 'ORGANIZATION_NAME')->
			orderBy('ORGANIZATION_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('ORGANIZATION_NAME', 'ORGANIZATION_ID');
	}

	public static function getPodsRegion()
	{
		return DB::connection('analytics')->
			table('META_PS_PODS_CAMPAIGN')->
			select('POD_NAME', 'REGION')->
			groupBy('POD_NAME')->
			orderBy('POD_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('REGION', 'POD_NAME');
	}

	public static function getPodsRegionGroup()
	{
		$pod_groups = [];
		$pods = self::getPodsRegion();
		foreach($pods as $key => $value) {
			if(!array_key_exists($value, $pod_groups)) {
				$pod_groups[$value] = [];
			}
			array_push($pod_groups[$value], array(
				'name' => $key,
				'nice' => strtolower(str_replace(' ', '-', $key))
			));
		}
		return $pod_groups;
	}

	public static function getMediaTypePods()
	{
		return ['D' => 'Display', 'M' => 'Mobile', 'V' => 'Video'];
	}


	public static function getSupplyForecastingDate()
	{
		return DB::connection('analytics')->
			table('SUPPLY_FORECASTING')->
			select(DB::raw('CONCAT(MONTHNAME(STR_TO_DATE(`MONTH`, "%m"))," ",`YEAR`)'))->
			groupBy(DB::raw('CONCAT(MONTHNAME(STR_TO_DATE(`MONTH`, "%m"))," ",`YEAR`)'))->
			orderBy(DB::raw('STR_TO_DATE(`YEAR`, "%Y") desc,STR_TO_DATE(`MONTH`, "%m")'), 'DESC')->
			remember(Format::timeOut())->
			lists('CONCAT(MONTHNAME(STR_TO_DATE(`MONTH`, "%m"))," ",`YEAR`)', 'CONCAT(MONTHNAME(STR_TO_DATE(`MONTH`, "%m"))," ",`YEAR`)');
	}

	public static function getFacingDomainDate()
	{
		return DB::connection('analytics')->
			table('FACING_DOMAIN_REPORT')->
			select(DB::raw('CONCAT(`YEAR`, "-",`MONTH`) as Y_m' ))->
			groupBy('YEAR', 'MONTH')->
			orderBy('YEAR', 'DESC')->
			orderBy('MONTH', 'DESC')->
			remember(Format::timeOut())->
			lists('Y_m', 'Y_m');
	}

	public static function getFacingAppDate()
	{
		return DB::connection('analytics')->
			table('FACING_APP_REPORT')->
			select(DB::raw('CONCAT(`YEAR`, "-",`MONTH`) as Y_m' ))->
			groupBy('YEAR', 'MONTH')->
			orderBy('YEAR', 'DESC')->
			orderBy('MONTH', 'DESC')->
			remember(Format::timeOut())->
			lists('Y_m', 'Y_m');
	}

	public static function getInvTypePods()
	{
		return [
			'Global_Deal' => 'Global Deal',
			'OA' => 'Open Auction',
			'PMP-D' => 'PMP-D',
			'PMP-E' => 'PMP-E'
		];
	}

/*	public static function getPixelTypes()
	{
		return DB::connection('analytics')->
			table('AUDIENCE_MANAGEMENT')->
			select('PIXEL_TYPE', 'PIXEL_TYPE')->
			orderBy('PIXEL_TYPE', 'ASC')->
			remember(Format::timeOut())->
			lists('PIXEL_TYPE', 'PIXEL_TYPE');
	}*/

	public static function getPixelTypes()
	{
		return [
			'all' => 'all',
			'data' => 'data',
			'adaptive' => 'adaptive',
			'event' => 'event',
			'audience' => 'audience',
			'event - dfa'=> 'event - dfa',
			'event - iframe'=> 'event - iframe',
			'event - image'=> 'event - image',
			'event - js'=> 'event - js',
			'event - uat'=> 'event - uat',
		];
	}

	public static function getStrategyType()
	{
		return [
			'AUD+GBO' => 'AUD+GBO',
			'REM' => 'REM'
		];
	}

	public static function getExchange()
	{
		return DB::connection('analytics')->
			table('META_EXCHANGE')->
			select(DB::raw('concat("xx", EXCH_ID) as EXCH_ID'), 'EXCH_NAME')->
			orderBy('EXCH_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('EXCH_NAME', 'EXCH_ID');
	}

	public static function getRawMobileChannel()
	{
		return ['4' => 'Mobile Display (web)', '5' => 'Mobile Video (web)', '8' => 'Mobile Display (in-app)', '9' => 'Mobile Video (in-app)'];
	}

	public static function getRawDesktopDisplayChannel()
	{
		return ['1' => 'Display'];
	}

	public static function getKnoxMobileChannel()
	{
		return ['t' => 'Mobile', 'f' => 'Non-Mobile'];
	}

	public static function getKnoxOverallChannel()
	{
		return ['DISPLAY' => 'Display', 'VIDEO' => 'Video'];
	}

	public static function getKnoxSocialChannel()
	{
		return ['t' => 'Social', 'f' => 'Non-Social'];
	}

	public static function getAdvertiser()
	{
		return DB::connection('analytics')->
			table('META_CAMPAIGN')->
			select('ADVERTISER_ID', 'ADVERTISER_NAME')->
			groupBy('ADVERTISER_ID', 'ADVERTISER_NAME')->
			remember(Format::timeOut())->
			lists('ADVERTISER_NAME', 'ADVERTISER_ID');
	}

	public static function getAgency()
	{
		return DB::connection('analytics')->
			table('META_CAMPAIGN')->
			select('AGENCY_ID', 'AGENCY_NAME')->
			groupBy('AGENCY_ID', 'AGENCY_NAME')->
			remember(Format::timeOut())->
			lists('AGENCY_NAME', 'AGENCY_ID');
	}

	public static function getCountryExtended()
	{
		return DB::connection('analytics')->
			table('avails.meta_country_extended')->
			select('country_name')->
			groupBy('country_name')->
			orderBy('country_name')->
			remember(Format::timeOut())->
			lists('country_name','country_name');
	}

	public static function getCountryImpression_name_only()
	{
		return DB::connection('analytics')->
			table('META_COUNTRY_IMPRESSION_LOG')->
			select('COUNTRY_NAME')->
			groupBy('COUNTRY_NAME')->
			orderBy('COUNTRY_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('COUNTRY_NAME','COUNTRY_NAME');
	}

	public static function getCountryImpression()
	{
		return DB::connection('analytics')->
			table('META_COUNTRY_IMPRESSION_LOG')->
			select(DB::raw('concat("xx", COUNTRY_ID) as COUNTRY_ID'), 'COUNTRY_NAME')->
			groupBy('COUNTRY_ID','COUNTRY_NAME')->
			orderBy('COUNTRY_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('COUNTRY_NAME', 'COUNTRY_ID');
	}

	public static function getAgencyCountry()
	{
		return DB::connection('warroom')->
			table('META_AGENCIES')->
			select('COUNTRY_NAME', 'COUNTRY_ID')->
			join('META_CLIENT_COUNTRIES', 'META_AGENCIES.COUNTRY', '=', 'META_CLIENT_COUNTRIES.COUNTRY_ID')->
			where('COUNTRY', '!=', '')->
			whereNotNull('COUNTRY')->
			orderBy('COUNTRY_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('COUNTRY_NAME', 'COUNTRY_ID');
	}

	public static function getVertical_by_id()
	{
		return DB::connection('analytics')->
			table('META_VERTICAL')->
			select('VERTICAL_NAME', 'VERTICAL_ID')->
			groupBy('VERTICAL_ID')->
			remember(Format::timeOut())->
			lists('VERTICAL_NAME', 'VERTICAL_ID');
	}


	public static function getAgencyRegion()
	{
		return DB::connection('warroom')->
			table('META_AGENCIES')->
			select('REGION_NAME', 'REGION_ID')->
			join('META_CLIENT_REGIONS', 'META_AGENCIES.REGION', '=', 'META_CLIENT_REGIONS.REGION_ID')->
			where('REGION', '!=', '')->
			whereNotNull('REGION')->
			orderBy('REGION_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('REGION_NAME', 'REGION_ID');
	}

	public static function getRBU()
	{
		return DB::connection('warroom')->
			table('META_AGENCIES')->
			select('REGION_NAME', 'REGION_ID')->
			join('META_CLIENT_REGIONAL_BUSINESS_UNITS', 'META_AGENCIES.REGIONAL_BUSINESS_UNIT', '=', 'META_CLIENT_REGIONAL_BUSINESS_UNITS.REGION_ID')->
			where('REGIONAL_BUSINESS_UNIT', '!=', '')->
			whereNotNull('REGIONAL_BUSINESS_UNIT')->
			orderBy('REGION_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('REGION_NAME', 'REGION_ID');
	}

	public static function getCountryBidder()
	{
		return DB::connection('analytics')->
			table('COUNTRY_BIDDER')->
			select(DB::raw('concat("xx", BIIDER_CODE) as BIIDER_CODE'), 'COUNTRY')->
			orderBy('COUNTRY', 'ASC')->
			remember(Format::timeOut())->
			lists('COUNTRY', 'BIIDER_CODE');
	}

	public static function getContinents()
	{
		return DB::connection('analytics')->
			table('avails.meta_country_extended')->
			select(DB::raw('IFNULL(continent,"Not Yet Classified") as continent'))->
			groupBy('continent')->
			orderBy('continent')->
			remember(Format::timeOut())->
			lists('continent','continent');
	}

	public static function getVendorTypes()
	{
		return DB::connection('analytics')->
			table('META_VENDOR_TYPES')->
			select('VENDOR_TYPE', 'VENDOR_TYPE')->
			orderBy('VENDOR_TYPE', 'ASC')->
			remember(Format::timeOut())->
			lists('VENDOR_TYPE', 'VENDOR_TYPE');
	}

	public static function getAppCategory()
	{
		return DB::connection('analytics')->
			table('META_APP_CATEGORY_LIST')->
			select('CATEGORY_NAME','CATEGORY_NAME')->
			groupBy('CATEGORY_NAME')->
			orderBy('CATEGORY_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('CATEGORY_NAME', 'CATEGORY_NAME');
	}

	public static function getUnknownAppCategory()
	{
		return ['Unknown'=>NULL];
	}

	public static function getAppOS()
	{
		return ['iTunes'=>'iTunes', 'Android'=>'Android', 'Unknown' => 'Unknown'];
	}

	public static function getVendorTypesMeta()
	{
		return DB::connection('analytics')->
			table('META_VENDOR')->
			select('VENDOR_TYPE', 'VENDOR_TYPE')->
			orderBy('VENDOR_TYPE', 'ASC')->
			remember(Format::timeOut())->
			lists('VENDOR_TYPE', 'VENDOR_TYPE');
	}

	public static function getInventoryType()
	{
		return ['Global Deals'=>'Global Deals', 'Non-Global Deals'=>'Non-Global Deals'];
	}

	public static function getDealHealthType()
	{
		return ['Global Deal'=>'Global Deal', 'PMP-E'=>'PMP-E'];
	}

	public static function getDealHealthStatus()
	{
		return ['RUNNING'=>'RUNNING', 'STOPPED'=>'STOPPED'];
	}

	public static function getPageType()
	{
		return ['NON_SSL'=>'NON_SSL', 'SSL'=>'SSL'];
	}

	public static function getWebVsInApp()
	{
		return ['web' =>'web','in-app' =>'in-app'];
	}

	public static function getSupplyType()
	{
		return ['FLPAR'=>'FLPAR', 'PMP-E'=> 'PMP-E', 'PMP-D'=>'PMP-D', 'RTB'=>'RTB'];
	}

	public static function getMainSupplyType()
	{
		return ['PMP-E'=> 'PMP-E', 'PMP-D'=>'PMP-D', 'RTB'=>'RTB', 'Global Deal' => 'Global Deal'];
	}

	public static function getMainSupplyTypeNoPMPD()
	{
		return ['PMP-E'=> 'PMP-E', 'RTB'=>'RTB', 'Global Deal' => 'Global Deal'];
	}

	public static function getCategory()
	{
		return ['Private Supply'=>'Private Supply', 'Privileged Supply'=>'Privileged Supply'];
	}

	public static function getPrivSupplyType()
	{
		return ['Private Supply | PMP-E'=>'Private Supply | PMP-E', 'Private Supply | PMP-D'=>'Private Supply | PMP-D','Privileged Supply | iAds'=>'Privileged Supply | iAds', 'Privileged Supply | Global Deals'=>'Privileged Supply | Global Deals', 'Privileged Supply | Preferred Bidding'=>'Privileged Supply | Preferred Bidding','Privileged Supply | Preferred Integrations'=>'Privileged Supply | Preferred Integrations'];
	}

	public static function getOPENSupplyType()
	{
		return ['PMP-E'=> 'PMP-E', 'PMP-D'=>'PMP-D', 'Priv Exch'=>'Open Marketplace', 'Remnant'=>'Remnant'];
	}

	public static function getInvSupplyType()
	{
		return ['PMP-E'=> 'PMP-E', 'PMP-D'=>'PMP-D', 'Open Auction'=>'Open Auction', 'Global Deal'=>'Global Deal'];
	}

	public static function getInventory()
	{
		return ['1'=>'Display', '3'=> 'Mobile', '2'=>'Video'];
	}

	public static function getBidChannel()
	{
		return ['Display'=>'Display', 'Mobile'=> 'Mobile', 'Video'=>'Video'];
	}

	public static function getSiteInventory()
	{
		return ['1'=>'Display', '2'=>'Video'];
	}

	public static function getGeo()
	{
		return DB::connection('analytics')->
			table('COUNTRY_BUSSINES_UNIT')->
			select('GEO')->
			where("GEO", "!=", '')->
			groupBy('GEO')->
			remember(Format::timeOut())->
			lists('GEO', 'GEO');
	}

	public static function getCountry()
	{
		return DB::connection('analytics')->
			table('META_COUNTRY')->
			select('COUNTRY')->
			where("COUNTRY", "!=", '')->
			groupBy('COUNTRY')->
			remember(Format::timeOut())->
			lists('COUNTRY', 'COUNTRY');
	}

	public static function getOrgCountry()
	{
		$first = DB::connection('analytics')->
			table('META_ORGANIZATION_GEO')->
			select('COUNTRY')->
			where("COUNTRY", "!=", '')->
			groupBy('COUNTRY');

			return DB::connection('analytics')->
			table('META_AGENCY_GEO')->
			select('COUNTRY')->
			where("COUNTRY", "!=", '')->
			groupBy('COUNTRY')->
			union($first)->
			remember(Format::timeOut())->
			lists('COUNTRY', 'COUNTRY');
	}

	public static function getBusinessUnitBrain()
	{
		return DB::connection('analytics')->
		table('GOAL_BRAIN_USAGE')->
		select('REGION')->
		where("REGION", "!=", '')->
		groupBy('REGION')->
		remember(Format::timeOut())->
		lists('REGION', 'REGION');
	}

	public static function getCountryCode()
	{
		return DB::connection('analytics')->
			table('META_COUNTRY')->
			select('COUNTRY')->
			where("COUNTRY", "!=", '')->
			groupBy('COUNTRY')->
			remember(Format::timeOut())->
			lists('COUNTRY', 'COUNTRY_CODE');
	}

	public static function getVendor()
	{
		return DB::connection('analytics')->
			table('VENDOR_GROSS_NET_COSTS')->
			select('VENDOR_NAME')->
			where("VENDOR_NAME", "!=", '')->
			groupBy('VENDOR_NAME')->
			remember(Format::timeOut())->
			lists('VENDOR_NAME', 'VENDOR_NAME');
	}

	public static function getVendorOrgAgn()
	{
		return DB::connection('analytics')->
			table('VENDOR_ORG_AGENCY_GEO')->
			select('VENDOR_NAME')->
			where("VENDOR_NAME", "!=", '')->
			groupBy('VENDOR_NAME')->
			remember(Format::timeOut())->
			lists('VENDOR_NAME', 'VENDOR_NAME');
	}

	public static function getVendorMeta()
	{
		return DB::connection('analytics')->
			table('META_VENDOR_FULL')->
			select(DB::raw('concat("xx", VENDOR_ID) as VENDOR_ID'), 'VENDOR_NAME')->
			groupBy('VENDOR_ID', 'VENDOR_NAME')->
			orderBy('VENDOR_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('VENDOR_NAME', 'VENDOR_ID');
	}

	public static function getVendorType()
	{
		return DB::connection('analytics')->
			table('VENDOR_GROSS_NET_COSTS')->
			select('VENDOR_TYPE')->
			where("VENDOR_TYPE", "!=", '')->
			groupBy('VENDOR_TYPE')->
			remember(Format::timeOut())->
			lists('VENDOR_TYPE', 'VENDOR_TYPE');
	}

	public static function getRegion()
	{
		return DB::connection('analytics')->
			table('COUNTRY_BUSSINES_UNIT')->
			select('REGION')->
			where("REGION", "!=", '')->
			where("REGION", "!=", 'Set at agency')->
			groupBy('REGION')->
			remember(Format::timeOut())->
			lists('REGION', 'REGION');
	}

	public static function getWorldRegionCode()
	{
		return DB::connection('analytics')->
			table('META_COUNTRY_WORLD_REGION')->
			select('WORLD_REGION_CODE')->
			groupBy('WORLD_REGION_CODE')->
			remember(Format::timeOut())->
			lists('WORLD_REGION_CODE', 'WORLD_REGION_CODE');
	}

	public static function getDomainRegion()
	{
		return DB::connection('analytics')->
			table('domain_verticals.META_COUNTRY')->
			select('REGION')->
			where("REGION", "!=", '0')->
			groupBy('REGION')->
			remember(Format::timeOut())->
			lists('REGION', 'REGION');
	}

	public static function getVertical()
	{
		return DB::connection('domain_verticals')->
			table('META_DOMAIN')->
			select('VERTICAL')->
			where("VERTICAL", ">", '')->
			groupBy('VERTICAL')->
			remember(Format::timeOut())->
			lists('VERTICAL', 'VERTICAL');
	}

	public static function getVendorDataTech()
	{
		return DB::connection('analytics')->
			table('META_DATA_TECH_DASHBOARD')->
			select('ID', 'GROUP_NAME')->
			where('DISPLAY', '=', '1')->
			remember(Format::timeOut())->
			lists('GROUP_NAME', 'ID');
	}

	public static function getMonths()
	{
		return [
			'January'	=> 'January',
			'February'		=> 'February',
			'March'	=> 'March',
			'April'		=> 'April',
			'May'		=> 'May',
			'June'	=> 'June',
			'July'		=> 'July',
			'August'		=> 'August',
			'September'	=> 'September',
			'October'	=> 'October',
			'November'		=> 'November',
			'December'		=> 'December'
		];
	}

	public static function getYears()
	{
		return DB::connection('analytics')->
			table('DUAL')->
			select(DB::RAW('SELECT DATE_FORMAT(CURRENT_DATE, \'%Y\')
			FROM DUAL
			UNION
			SELECT DATE_FORMAT(CURRENT_DATE - interval 1 year, \'%Y\')
			FROM DUAL
			UNION
			SELECT DATE_FORMAT(CURRENT_DATE - interval 2 year, \'%Y\')
			FROM DUAL'));
	}

	public static function getChannel($indexNumeric =true)
	{
		if ($indexNumeric) {
			return [
				'1'	=>'Display',
				'2'	=>'Video',
				'10'=> 'Newsfeed (FBX)'
			];
		} else {
			return [
				'Display'	=> 'Display',
				'Video'		=> 'Video',
				'Mobile'	=> 'Mobile',
				'Other'		=> 'Other'
			];
		}
	}

	public static function getChannelMeta()
	{
		return DB::connection('analytics')->
			table('META_CHANNEL_TYPE_IMPRESSION_LOG')->
			select(DB::raw('concat("xx", CHANNEL_ID) as CHANNEL_ID'), 'CHANNEL_NAME')->
			groupBy('CHANNEL_ID', 'CHANNEL_NAME')->
			orderBy(DB::raw('lower(CHANNEL_NAME)'), 'ASC')->
			remember(Format::timeOut())->
			lists('CHANNEL_NAME', 'CHANNEL_ID');
	}

	public static function getMainChannelMeta()
	{
		return DB::connection('analytics')->
			table('META_CHANNEL_TYPE_IMPRESSION_LOG')->
			select(DB::raw('concat("xx", CHANNEL_ID) as CHANNEL_ID'), 'CHANNEL_NAME')->
			whereRaw("CHANNEL_ID in (1,2,4,5,8,9)")->
			groupBy('CHANNEL_ID', 'CHANNEL_NAME')->
			orderBy(DB::raw('lower(CHANNEL_NAME)'), 'ASC')->
			remember(Format::timeOut())->
			lists('CHANNEL_NAME', 'CHANNEL_ID');
	}

	public static function getDataVendor()
	{
		return DB::connection('analytics')->
			table('audience_segments.audience_vendors')->
			select('name')->
			where("name", "!=", '')->
			groupBy('name')->
			remember(Format::timeOut())->
			lists('name', 'name');
	}

	public static function getAggregateDate($filter=false)
	{
		$first_jan		= strtotime('1 January '.date('Y'));
		$w_day			= date('w', $first_jan);
		$less = $w_day >= 1 ? 7 - $w_day: 0;
		$less = $w_day-1;
		$first_day 		= strtotime('-'.$less.' days', $first_jan);

		$this_sunday	= date('N') == 1 ? strtotime('yesterday') : strtotime('Sunday this week');
		$sunday			= strtotime('+6 days', $first_day);

		$date	= [];
		do {
			if ($filter == false) {
				$date[date('Y-m-d', $first_day)] = date('Y-m-d', $first_day).' to '.date('Y-m-d', $sunday);
			} elseif ($filter && date('Y-m-d', $first_day) >= date('2015-05-04')) {
				$date[date('Y-m-d', $first_day).' to '.date('Y-m-d', $sunday)] = date('Y-m-d', $first_day).' to '.date('Y-m-d', $sunday);
			}
			$first_day 	= strtotime('+7 days', $first_day);
			$sunday		= strtotime('+7 days', $sunday);
		} while ($this_sunday >= $sunday);

		return $date;
	}

	public static function usersV3()
	{
		return DB::connection('users_v3')->
			table('users_v3')->
			select(DB::raw('concat("xx", user_id) as user_id'), DB::raw('concat(user_id, \'-\', first_name, \' \', last_name) as user_name'))->
			where('user_type', '<=', '2')->
			groupBy('user_id', 'user_name')->
			orderBy('first_name', 'ASC')->
			orderBy('last_name', 'ASC')->
			remember(Format::timeOut())->
			lists('user_name', 'user_id');
	}

	public static function bucketStatus()
	{
		return [
			'ready for partition recovery' => 'ready for partition recovery',
			'waiting' => 'waiting',
			'partition recovered' => 'partition recovered'
		];
	}

	public static function tables()
	{
		return DB::connection('analytics')->
			table('AD_REPORT_ERROR_LOG')->
			select('TABLE_NAME')->
			groupBy('TABLE_NAME')->
			orderBy('TABLE_NAME', 'ASC')->
			remember(Format::timeOut())->
			lists('TABLE_NAME', 'TABLE_NAME');
	}

	public static function locations()
	{
		return DB::connection('update_process')->
			table('open_daily_import')->
			select('report_location')->
			groupBy('report_location')->
			orderBy('report_location', 'ASC')->
			remember(Format::timeOut())->
			lists('report_location', 'report_location');
	}

	public static function update()
	{
		return DB::connection('update_process')->
			table('open_daily_import')->
			select('status')->
			groupBy('status')->
			orderBy('status', 'ASC')->
			remember(Format::timeOut())->
			lists('status', 'status');
	}

	public static function tablesSum()
	{
		return DB::connection('update_process')->
			table('open_update_sum_tables')->
			select('DB_TABLE')->
			groupBy('DB_TABLE')->
			orderBy('DB_TABLE', 'ASC')->
			remember(Format::timeOut())->
			lists('DB_TABLE', 'DB_TABLE');
	}

	public static function tablesDaily()
	{
		return DB::connection('update_process')->
			table('open_daily_import')->
			select('table_name')->
			groupBy('table_name')->
			orderBy('table_name', 'ASC')->
			remember(Format::timeOut())->
			lists('table_name', 'table_name');
	}

	public static function bucket()
	{
		return DB::connection('update_process')->
			table('data_platform_buckets')->
			select(DB::raw('concat("xx", bucket_id) as bucket_id'), 'bucket_name')->
			groupBy('bucket_id', 'bucket_name')->
			orderBy('bucket_name', 'ASC')->
			remember(Format::timeOut())->
			lists('bucket_name', 'bucket_id');
	}

	public static function activeDealMonthYear()
	{
		return DB::connection('analytics')
			->table('ACTIVE_DEALS_BY_DAY')
			->select('MONTH', 'YEAR')
			->groupBy('MONTH', 'YEAR')
			->orderBy('YEAR', 'ASC')
			->orderBy('MONTH', 'ASC')
			->remember(Format::timeOut())
			->get();
	}

	public static function getAudienceAdoptionOrgs() {
		
		return DB::connection('sergiu_partner_dashboard')
			->table('AUDIENCE_ADOPTION')
			->select(DB::raw('concat("xx", ORG_ID) as org_id'), 'ORG')
			->groupBy('org_id')
			->remember(Format::timeOut())
			->lists('ORG', 'org_id');
			
	}
	
	public static function getAdxBuyerIds() {
		
		return DB::connection('sergiu_partner_dashboard')
			->table('ADX_API')
			->select(DB::raw('concat("xx", BUYER_ID) as b_id'), 'BUYER_ID')
			->groupBy('buyer_id')
			->remember(Format::timeOut())
			->lists('BUYER_ID', 'b_id');
			
	}

}
?>
