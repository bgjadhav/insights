<?php
class RequestController extends Controller
{

	protected $tid;

	protected $order;

	protected $extra;

	protected $parameter;

	public function index()
	{
		View::addExtension('handlebars', 'php');

		return View::make('jira.product.requests.index')
			->with('project', 'requests')

			->with('filters', ParameterRequests::all())

			->with('extras', ParameterExtrasRequests::all())
		;
	}

	public function show()
	{
		$this->essential();

		$this->track();

		$extraClass = new $this->extra;

		try {
			if ($this->tid > 0) {

				return Response::json($this->one($this->tid));

			} elseif ($extraClass::order($this->order)) {

				return Response::json($this->filtered());

			} else {
				return Response::json([]);
			}
		} catch(Exception $e) {

			ddd(print_R($e->getTrace()));
			die();
			return Response::json([]);
			die();
		}
	}

	protected function one()
	{
		$query = ProdRequest::validated()

			->select('issue_id', 'created', 'key', 'summary', 'status', 'labels', 'first_component', 'candidate_consid', 'reporter', 'assignee', 'description', 'watchers')

			->validID($this->tid)

			->remember(1);

		return $query->get();
	}

	protected function filtered()
	{

		$extraClass = new $this->extra;

		$parameterClass = new $this->parameter;

		$resolution = $this->resolution($parameterClass);

		$query = ProdRequest::validated()

			->select('issue_id', 'created', 'key', 'summary', 'status', 'labels', 'first_component', 'candidate_consid', 'reporter', 'assignee', 'description', 'watchers')

			->firstComponent($parameterClass::validate('component'))

			->label($parameterClass::validate('label'))

			->status($parameterClass::validate('status'))

			->candidateConsideration($resolution)

			->reporter($parameterClass::validate('reporter'))

			->remember(1)

			->orderTickets(
				$extraClass::loadFiltered(),

				$extraClass::idOrder(),

				$this->order
			);

		//Search
		if ($search = SearchJIRA::generateRegex(
				[
					'summary',
					'labels'
				],
				'requests',
				$extraClass::search()
			)) {
				$query->whereRaw($search);
		}

		return $query->get();
	}

	protected function resolution($parameterClass)
	{
		$resolution = $parameterClass::validate('resolution');

		if ($resolution == 'none') {

			$resolution = '';

		}

		return $resolution;
	}

	protected function track()
	{
		(new RoadmapTrack)->sendTrack('table', 'requests');
	}

	protected function essential()
	{
		$this->tid = ParameterExtrasRequests::tid();

		$this->order = Input::get('order');

		$this->extra = 'ParameterExtrasRequests';

		$this->parameter = 'ParameterRequests';

	}
}
