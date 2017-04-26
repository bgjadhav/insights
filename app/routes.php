<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// @todo this can be always in the route
// here login in filter, delete double comparasion incompatibles and up the user roles
Route::get('/', 'HomeController@index');
Route::post('/ajax_login', 'HomeController@login');

Route::get('/widget', 'MainController@singleWidget');

Route::group(array('before' => 'open_login'), function()
{
	// Main page
	Route::get('/main', 'MainController@index');

	Route::get('/logout', 'HomeController@logout');
	Route::post('/set_dialog', 'HomeController@set_dialog');


	// JIRA

	// MarketPlace
	Route::get('/market_insights', 'MarketInsightsController@index');
	Route::get('/Seller', 'MarketSellerController@index');
	Route::get('/market_insights/data', 'MarketInsightsController@competitiveintel');
	Route::get('/publisher_search/data', 'MarketSellerController@search');
	Route::get('/publisher_autofill/data', 'MarketSellerController@autofill');
	Route::get('/meta_mi_component', 'MarketInsightsController@getMetaComponent');
	Route::get('/meta_mi_label', 'MarketInsightsController@getMetaLabel');
	Route::get('/meta_mi_region', 'MarketInsightsController@getMetaRegions');

	// Product Dashboard
	Route::post('/product/roadmap/download', 'RoadmapDownloadController@download');
	Route::post('/product/roadmap/askReady/{extension}', 'RoadmapDownloadController@askReady');
	Route::post('/product/roadmap/downloadFile/{extension}', 'RoadmapDownloadController@file');

	Route::post('/product/roadmap/subscription', 'RoadmapSubscriptionController@subscription');

	Route::post('/jira/add/watcher', 'JiraWatcherController@add');

	Route::get('/product/roadmap/data', 'RoadmapController@show');
	Route::get('/product/roadmap', 'RoadmapController@index');

	Route::get('/product/requests/data', 'RequestController@show');
	Route::get('/product/requests', 'RequestController@index');

	// PROPS Track
	Route::post('/analytics/{representation}/{report}/send-track-props', 'PROPSTrackController@sendTrack');
	Route::post('/analytics/{representation}/DBOpenActivity_PRDREQ/send-track', 'PRDREQTrackController@track');
	Route::post('/analytics/{representation}/DBResolutionAnalytics/send-track', 'DBResolutionTrackController@track');

	// User stats
	Route::get('/analytics/jira/performance-metrics/r/0/{type}', 'PerformanceJIRAController@index');
	Route::get('/analytics/jira/performance-metrics/{whatever?}', 'PerformanceJIRAController@category');
	//Route::get('/roadmap/stats', 'RoadmapStatsController@index');

	// Roadmap Usage
	Route::get('/analytics/jira/roadmap-stats/r/0/{type}', 'RoadmapStatsController@index');
	Route::get('/analytics/jira/roadmap-stats/{whatever?}', 'RoadmapStatsController@category');

	// Publisher Database
	Route::get('/publisher_database', 'PublisherDatabaseController@index');
	Route::get('/publisher_database/questions', 'PublisherDatabaseController@questions');
	Route::get('/publisher_database/question_depreciate', 'PublisherDatabaseController@depreciate_question');
	Route::get('/publisher_database/question_tags', 'PublisherDatabaseController@question_tags');
	Route::get('/publisher_database/tags', 'PublisherDatabaseController@tags');
	Route::get('/publisher_database/publishers', 'PublisherDatabaseController@publishers');
	Route::get('/publisher_database/search', 'PublisherDatabaseController@search');
	Route::get('/publisher_database/note', 'PublisherDatabaseController@note');
	Route::get('/publisher_database/remove_note', 'PublisherDatabaseController@remove_note');
	Route::get('/publisher_database/answer/groups', 'PublisherDatabaseController@answer_groups');
	Route::get('/publisher_database/answer/options', 'PublisherDatabaseController@answer_options');
	Route::post('/publisher_database/add_edit_answers', 'PublisherDatabaseController@add_edit_answers');
	Route::post('/publisher_database/add_edit_note', 'PublisherDatabaseController@add_edit_note');
	Route::post('/publisher_database/add_edit_publisher', 'PublisherDatabaseController@add_edit_publisher');
	Route::post('/publisher_database/add_edit_question', 'PublisherDatabaseController@add_edit_question');
	Route::post('/publisher_database/answers', 'PublisherDatabaseController@answers');

	// Organisation
	Route::post('/analytics/update-organisations/upload', 'OrganisationController@update_organisations_post');
	Route::post('/analytics/getPodOrg', 'OrganisationController@getPodOrg');
	Route::get('/analytics/update-organisations', 'OrganisationController@update_organisations');

	// search
	Route::get('/analytics/search', 'SearchReportsController@search');

	Route::get('analytics/cso/data/GlobalAccountsDashboard/download', function()
	{
		GlobalAccountsDashboard::export();
	});

	// Reports
	Route::get('/analytics/{representation}/data/{reportName}', 'ReportsController@datajson');
	Route::post('/analytics/{representation}/wrapper/{reportName}', 'ReportsController@wrapperjson');
	Route::post('/analytics/{representation}/data/{reportName}', 'ReportsController@datajson');

	Route::post('/analytics/close-pid', 'NavigationController@closedWindow');
	Route::post('/analytics/{representation}/{report}/send-track', 'TrackController@sendTrack');
	Route::get('/favourites/reports', ['as' => 'favourites.reports', 'uses' => 'FavouritesController@reports']);
	Route::post('/favourites/reports/addReport', ['as' => 'favourites.reports.addReport', 'uses' => 'FavouritesController@addReport']);
	Route::post('/favourites/reports/removeReport', ['as' => 'favourites.reports.removeReport', 'uses' => 'FavouritesController@removeReport']);

	// Reports Home
	Route::get('/analytics/{main}/r/{reportid?}/{reportname?}','NavigationController@reportFirstLevel');
	Route::get('/analytics/{main}/{category?}/r/{reportid?}/{reportname?}','NavigationController@reportCategory');
	Route::get('/analytics/{main}/{category?}/{subcategory?}/r/{reportid?}/{reportname?}','NavigationController@reportSubCategory');

	// Report Category
	Route::get('/analytics/{main}/{category?}/{subcategory?}','NavigationController@navigation');

	// Report Download
	Route::post('/analytics/{category}/data/{reportName}/download', 'DownloadController@start');
	Route::post('/analytics/askReady', 'DownloadController@askReady');
	Route::post('/analytics/downloadFile', 'DownloadController@file');

	//Widgets Admin
	Route::post('/widgets/update_order', 'MainController@updateWidgetOrderbyUser');
	Route::get('/widgets/open-revenue-target/edit', 'WidgetsControllerEdit@open_revenue_target');
	Route::post('/widgets/open-revenue-target/edit', 'WidgetsControllerEdit@open_revenue_target_edit');

	//Others
	Route::get('/emails', 'EmailController@emails');
	Route::get('/sendemail', 'EmailController@sendemail');
});

// Widgets routes
Route::group(array(), function() {
	if(Session::get('wiki_code') == 'VMNDGFDxaN' || UserService::validLogin() !== false) {
		Route::get('/tile-{tile}', 'WidgetsController@tile');
		Route::get('/widgets/website-uniques-page/{start_date?}/{end_date?}', 'WidgetsController@website_uniques_page');
		Route::get('/widgets/certified-buyer-spend', 'WidgetsController@certified_buyer_spend');
		Route::get('/widgets/certified-{type}', 'WidgetsController@certified');
		Route::get('/widgets/billed-spend', 'WidgetsController@billed_spend');
		Route::get('/widgets/media-partner', 'WidgetsController@media_partner');
		Route::get('/widgets/revenue', 'WidgetsController@revenue');
		Route::get('/widgets/open-revenue-target', 'WidgetsController@open_revenue_target');
		Route::get('/widgets/open-revenue-{months?}', 'WidgetsController@open_revenue');
		Route::get('/widgets/partner-types', 'WidgetsController@partner_types');
		Route::get('/widgets/christmas-party-countdown', 'WidgetsController@christmas_party_countdown');
		Route::get('/widgets/kpi-{type?}', 'WidgetsController@kpi');
		Route::get('/widgets/message-count', 'WidgetsController@message_count');
		Route::get('/widgets/open-marketplace', 'WidgetsController@open_marketplace');
		Route::get('/widgets/open-exchanges', 'WidgetsController@open_exchanges');
		Route::get('/widgets/privileged-supply', 'WidgetsController@privileged_supply');
		Route::get('/widgets/global-deals', 'WidgetsController@global_deals');
		Route::get('/widgets/open-visits', 'WidgetsController@open_visits');
		Route::get('/widgets/employee-visits', 'WidgetsController@employee_visits');
		Route::get('/widgets/ag-spend', 'WidgetsController@ag_spend');
		Route::get('/widgets/private-marketplace', 'WidgetsController@private_marketplace');
		Route::get('/widgets/deal-discovery-advertisers', 'WidgetsController@deal_discovery_advertisers');
		Route::get('/widgets/raptor-attack', 'WidgetsController@raptor_attack');
		Route::get('/widgets/partner-offices', 'WidgetsController@partner_offices');
		Route::get('/widgets/global-deals-overview', 'WidgetsController@global_deals_overview');
		Route::get('/widgets/top-organisations', 'WidgetsController@top_organisations');
		Route::get('/widgets/web-vs-inapp', 'WidgetsController@web_vs_inapp');
		Route::get('/widgets/message-topics', 'WidgetsController@message_topics');
		Route::get('/widgets/region-exchange-sov', 'WidgetsController@region_exchange_sov');
		Route::get('/widgets/vendor-sov', 'WidgetsController@vendor_sov');
		Route::get('/widgets/creative-bulk-uploader-spend', 'WidgetsController@creative_bulk_uploader_spend');
		Route::get('/widgets/publisher-query-tool', 'WidgetsController@publisher_query_tool');
		Route::get('/widgets/incremental-reach-tool', 'WidgetsController@incremental_reach_tool');
		Route::get('/widgets/organisations', 'WidgetsController@organisations');
		Route::get('/widgets/exchanges', 'WidgetsController@exchanges');
		Route::get('/widgets/countries', 'WidgetsController@countries');
		Route::get('/widgets/revenue-gross-profit', 'WidgetsController@revenue_gross_profit');
		Route::get('/widgets/revenue-3rd-party', 'WidgetsController@revenue_3rd_party');
		Route::get('/widgets/direct-revenue-profit-margin', 'WidgetsController@direct_revenue_profit_margin');
		Route::get('/widgets/preferred-indirect-revenue', 'WidgetsController@preferred_indirect_revenue');
		Route::get('/widgets/indirect-fees', 'WidgetsController@indirect_fees');
		Route::get('/widgets/vendor-data-tech', 'WidgetsController@vendor_data_tech');
		Route::get('/widgets/unique-deals-by-supply-type', 'WidgetsController@UniqueDealsBySupplyType');
		Route::get('/widgets/segment-usage', 'WidgetsController@segment_usage');
		Route::get('/widgets/segment-spend', 'WidgetsController@segment_spend');
		Route::get('/widgets/new-segments', 'WidgetsController@new_segments');
		Route::get('/widgets/top-organisations-segments', 'WidgetsController@top_organisations_segments');
		Route::get('/widgets/top-advertisers-segments', 'WidgetsController@top_advertisers_segments');
		Route::get('/widgets/top-segments', 'WidgetsController@top_segments');
		Route::post('/widgets/publisher-query-tool-save', 'WidgetsController@publisher_query_tool_save');
		Route::post('/widgets/incremental-reach-tool-save', 'WidgetsController@incremental_reach_tool_save');
		Route::get('/widgets/qubole-data-status', 'WidgetsController@qubole_data_status');
		Route::get('/widgets/decisioning-and-opto-usage', 'WidgetsController@decisioning_and_opto_usage');
		Route::get('/widgets/channels/chart/brain', 'WidgetsController@channel_chart_widgets_brain');
		Route::get('/widgets/channels/chart/adaptive_segments', 'WidgetsController@channel_chart_widgets_adaptive');
		Route::get('/widgets/channels/main/{type}', 'WidgetsController@channel_main_widgets');
		Route::get('/widgets/channels/main/export/{type}', 'WidgetsController@channel_main_widgets_export');
		Route::get('/widgets/channels/small/{type}', 'WidgetsController@channel_small_widgets');
		Route::get('/widgets/channels/small/export/{type}', 'WidgetsController@channel_small_widgets_export');
		Route::get('/widgets/channels/chart/{type}', 'WidgetsController@channel_chart_widgets');
		Route::get('/widgets/stats/organisation', 'WidgetsController@top_organisations_media_cost');
		Route::get('/widgets/stats/vertical', 'WidgetsController@top_verticals_spend');
		Route::get('/widgets/stats/vertical-pie', 'WidgetsController@top_verticals_spend_pie');
		Route::get('/widgets/stats/urls-composition', 'WidgetsController@top_urls_media_cost');
		Route::get('/widgets/stats/size-comparison', 'WidgetsController@size_comparison');
	}
});

App::missing(function($exception)
{
	if (Request::is('_img/orgs/*')) {
		$response = Response::make(File::get('_img/orgs/default.png'));
		$response->header('Content-Type', 'image/png');
		return $response;
	} else {
		return '404';
	}
});
