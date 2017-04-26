<?php
class MainController extends Controller
{
    // new line comment 
	protected $newSince;
	protected $widthItem;

	public function __construct()
	{
		$this->newSince = strtotime('-7 days');
		$this->widthItem = ['2' => 'double', '3' => 'triple', true => ''];
	}

	public function index()
	{
		View::addExtension('handlebars', 'php');
		return View::make('main.main',
			['widgets' => $this->customOrder()]
		);
	}

	protected function getItems()
	{
		return (new Widget())->getDataEnviromentRoles(
			$this->getTypeByEnviroment(),
			Roles::checkRoles(new WidgetRole)
		);
	}

	public function getTypeByEnviroment()
	{
		return App::environment() == 'dev' ? [0,1] : [1];
	}

	private function getOrderByUser()
	{
		try {
			$order = new WidgetsCustomOrder;
			return $order->getDataUser(Session::get('user_id'));
		} catch(Exception $e) {
			return Response::json(
				['success' => false,
				'error' => $e->getMessage()
				]
			);
		}
	}

	private function customOrder()
	{
		try {
			return $this->changeOrder();
		} catch(Exception $e) {
			return Response::json(
				['success' => false,
				'error' => $e->getMessage()
				]
			);
		}
	}

	public function getCustomOrderDecode()
	{
		$custom = $this->getOrderByUser();
		return isset($custom->order) ? json_decode($custom->order) : [];
	}

	public function changeOrder()
	{
		$custom = $this->getCustomOrderDecode();
		$all = $this->getItemsOriginCopy();
		$count = 0;

		array_walk($custom, function ($id) use (&$all, &$count) {
			$key = isset($all['origin'][$id]) ? $all['origin'][$id] : -1;
			if ($key != -1 && isset($all['copy'][$key])) {
				$all['items'][$count] = $this->updateItemValues($all['copy'][$key]);
				unset($all['copy'][$key]);
				$count++;
			}
		});

		return $this->getItemsOrdered($all, $count);
	}

	public function getItemsOriginCopy()
	{
		$all = [
			'items' => $this->getItems(),
			'origin' => []
		];
		$all['copy'] = clone $all['items'];

		foreach ($all['items'] as $key => $widget) {
			$all['origin'][$widget->id] = $key;
			$all['items'][$key] = null;
		}
		return $all;
	}

	public function getItemsOrdered($all, $count)
	{
		foreach ($all['copy'] as $key => $widget) {
			$all['items'][$count] = $this->updateItemValues($widget);
			$count++;
		}
		return $all['items'];
	}

	public function updateItemValues($widget)
	{
		$widget->width = $this->widthItem[$widget->width];
		$widget->height = $widget->height == '2' ? 'tall' : '';
		$widget->{'new'} = $this->newSince <= strtotime($widget->dateadded) ? 1 : 0;

		$categories = [];
		foreach ($widget->categories as $cat) {
			$categories[] = $cat->category;
		}
		$widget->categories = implode(',', $categories);
		return $widget;
	}

	public function updateWidgetOrderbyUser()
	{
		$user = WidgetsCustomOrder::FirstOrCreate(['user_id' => SESSION::get('user_id')]);
		$user->order = json_encode(Input::get('order'));
		$user->save();
	}
 
	public function singleWidget()
	{
		Session::put('wiki_code', Input::get('code'));
		View::addExtension('handlebars', 'php');
		$ids = explode(',', Input::get('id'));
		$widgets = [];
		foreach($ids as $widget) {
			array_push($widgets, (new Widget())->getWidget($widget));
		}
		return View::make('widgets.single',
			['widgets' => $widgets]
		);
	}
}
