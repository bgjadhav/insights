<?php
class RoadmapController extends Controller
{
	protected $product;
	protected $query;

	public function index()
	{
		View::addExtension('handlebars', 'php');

		return View::make('jira.product.roadmap.index')
			->with('project', 'roadmap')
			->with('subscribed', $this->statusSubscribe())
			->with('subproject', ParameterExtrasRoadmap::subProject())
			->with('filters',  [
				'roadmap' => ParameterRoadmap::all(),
				'candidate' => ParameterCandidate::all()
			])
			->with('extras', ParameterExtrasRoadmap::all()
		);
	}

	public function show()
	{
		$order = Input::get('order');

		$this->projectOption();

		$extra = 'ParameterExtrasRoadmap';

		$tid = $extra::tid();

		(new RoadmapTrack)->sendTrack('table', $this->productNameToSaveTrack());

		try {
			if ($tid > 0) {
				return Response::json($this->one($tid));

			} elseif ($extra::order($order)) {
				return Response::json($this->filtered($order, $extra));

			} else {
				return Response::json([]);
			}
		} catch(Exception $e) {
			return Response::json([]);
			die();
		}
	}

	protected function one($tid)
	{
		$this->query->validated()
			->validID($tid)
			->year('all')
			->remember(1);

		return $this->query->get();
	}

	protected function filtered($order, $extra)
	{
		$parameter = $this->parameterValidate();

		$orderI = $extra::idOrder();

		if ($orderI == 'geo') {
			$orderI = 'geo_all';
		}

		$this->query->validated()
			
			->firstComponent($parameter::validate('component'))

			->label($parameter::validate('label'))

			->status($parameter::validate('status'))

			->geo($parameter::validate('geo'))

			->year($parameter::validate('year'))

			->targetMM($parameter::validate('quarter'))

			->released($extra::hideReleased())

			->remember(1)

			->orderTickets(
				$extra::loadFiltered(),
				$orderI,
				$order
			);

		//Search
		if ($search = SearchJIRA::generateRegex(
				[
					'epic_name',
					'labels'
				],
				$this->productNameToSaveTrack(),
				$extra::search()
			)) {
				$this->query->whereRaw($search);
		}

		return $this->query->get();
	}

	protected function productNameToSaveTrack()
	{
		$relates = [
			'Product Roadmap' => 'roadmap',
			'Product-Roadmap Candidate' => 'candidate'
		];

		return isset($relates[$this->product]) ? $relates[$this->product] : 'unknown';
	}

	protected function projectOption()
	{
		if (Input::get('roadmap') == 'roadmap') {
			$this->product = 'Product Roadmap';
			$this->query = ProdRoadmap::roadmap();

		} else {
			$this->product = 'Product-Roadmap Candidate';
			$this->query = ProdRoadmap::candidate();
		}
	}

	protected function parameterValidate()
	{
		if ($this->product == 'Product Roadmap') {
			return 'ParameterRoadmap';
		} else {
			return 'ParameterCandidate';
		}
	}

	protected function statusSubscribe()
	{
		$data = ProdSubscription::select('status')
			->userId(User::MMId())
			->get()
			->toArray();

		return isset($data[0]['status']) ? $data[0]['status'] : 'none';
	}

}
