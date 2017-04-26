<?php
class MarketInsightsController extends Controller
{

	public function index()
	{
		return View::make('jira.marketinsights.index');
	}

	public function competitiveintel()
	{
		try {
			$query = MarketInsights::select(
					'issues.issue_id',
					'key',
					'summary',
					'region',
					'issues.label',
					'priority',
					'status',
					'last_updated',
					'date_created',
					'comment_count',
					'description'
				)->with(array('labels' => function($query) {
					$query->where('label', '!=', '');
				}))->with(array('components' => function($query){
					$query->where('component', '!=', '');
				}))
				->where('status', '=', 'Approved')
				->where('validate', '=' , 1)
				->groupBy('issues.issue_id')
				->orderBy('last_updated', 'DESC');

			if (($component = Input::get('idComponent')) != 'All') {
				$query->whereHas('components', function($q) use ($component) {
					$q->where('component', 'LIKE', '%'.$component.'%');
				});
			}

			if (($label = Input::get('idLabel')) != 'All') {
				$query->whereHas('labels', function($q) use ($label) {
					$q->where('label', 'LIKE', '%'.$label.'%');
				});
			}

			if (($regions = Input::get('regions')) != 'All') {
				$query->where('region', 'LIKE', '%'.$regions.'%');
			}

			//Search
			if ($search = SearchJIRA::generateRegex(
					['description'],
					'CompetitiveIntel',
					Input::get('search')
				)) {
					$query->whereRaw($search);
			}

			return Response::json($query->get());

		} catch(Exception $e) {
			return Response::json([]);
			die();
		}
	}

	public function getMetaComponent()
	{
		return MetaMarketInsightsComponent::where('component', '!=', '_Not Applicable')->groupBy('component')->remember(60*24)->lists('component');
	}

	public function getMetaLabel()
	{
		return MetaMarketInsightsLabel::where('label', '!=', '')->groupBy('label')->remember(60*24)->lists('label');
	}

	public function getMetaRegions()
	{
		return MarketInsights::select('region')->groupBy('region')->remember(60*24)->lists('region');
	}
}
