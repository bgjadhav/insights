<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RoadmapWeeklyMailingCommand extends Command {

	protected $name	= 'email:send:changed:PRODUCT';
	protected $description = 'CLI that send Roadmap and Candidates weekly updates.';
	protected $issues_id = [];
	protected $test = [];

	public function fire()
	{
		try {

			self::send();

		} catch (Exception $e) {

			$this->error($e->getMessage());

		}
	}

	private static function send()
	{
		$data = self::content();

		Mail::send(

			self::view(),

			$data,

			function ($message)

			use ($data) {

				//$message->to('koruequiroz@mediamath.com', 'Kathia Orue Quiroz');

				$message = self::apendSubscriberInBCC($message);

				//$message->to('kcasteel@mediamath.com', 'Katie Casteel');

				$message->to('product-operations@mediamath.com', 'Product Operations');

				$message->from('insights_noreply@mediamath.com', 'Insights')

					->subject('Product Roadmap and Candidates Update - '.$data['description']);
			}
		);
	}

	private static function apendSubscriberInBCC($message)
	{
		$subscribers = self::subscribers();

		foreach ($subscribers as $subscriber) {
			$message->bcc($subscriber->user_email, $subscriber->user_full_name);
		}

		return $message;
	}

	private static function view()
	{
		return 'jira.product.roadmap.email.weekly';
	}

	private static function content()
	{
		$timestamp = strtotime('last monday');

		$config = self::config();

		return [
			'data' => self::data(

				$config,

				self::date($timestamp),

				self::endDate($timestamp)

			),

			'description' => self::description($timestamp),

			'labels' => self::labels($config)
		];
	}

	private static function subscribers()
	{
		return ProdSubscription::select('user_email', 'user_full_name')
			->where('user_email', '<>', '\'\'')
			->get();
	}

	private static function date($timestamp)
	{
		//return '2016-07-20'; //to test
		return date('Y-m-d', $timestamp);
	}

	private static function endDate($timestamp)
	{
		return date('Y-m-d 06:00:00', strtotime('+7 day', $timestamp));
	}

	private static function labels($config)
	{
		return array_column($config, 'label', 'id');
	}

	private static function description($timestamp)
	{
		return 'Week of '
			.self::startWeek($timestamp)
			.' - '
			.self::endWeek($timestamp);
	}

	private static function startWeek($timestamp)
	{
		$month = date('F', $timestamp);

		$from = $month.date(' j', $timestamp);

		if ($month == 'December') {
			$from .= date(', Y', $timestamp);
		}

		return $from;
	}

	private static function endWeek($timestamp)
	{
		return date('F j, Y', strtotime('+6 day', $timestamp));
	}

	private static function config()
	{
		return [
			'new' => [
				'class' => 'ProdRoadmapNewProject',
				'clean' => 'newTicket',
				'label' => 'MOVED TO',
				'id' => 'new'
			],

			'project' => [
				'class' => 'ProdRoadmapChangedProject',
				'clean' => 'movedTicket',
				'label' => 'MOVED TO',
				'id' => 'project'
			],

			'status' => [
				'class' => 'ProdRoadmapChangedStatus',
				'clean' => 'withoutPrevious',
				'label' => 'CURRENT PHASE',
				'id' => 'status'
			],

			'region' => [
				'class' => 'ProdRoadmapChangedRegion',
				'clean' => 'withPrevious',
				'label' => 'REGION',
				'id' => 'region'
			],

			'closed_beta' => [
				'class' => 'ProdRoadmapChangedTargetClosedBeta',
				'clean' => 'withPrevious',
				'label' => 'TARGET CLOSED BETA',
				'id' => 'closed_beta'
			],

			'open_beta' => [
				'class' => 'ProdRoadmapChangedTargetOpenBeta',
				'clean' => 'withPrevious',
				'label' => 'TARGET OPEN BETA',
				'id' => 'open_beta'
			],

			'target' => [
				'class' => 'ProdRoadmapChangedTargetGA',
				'clean' => 'withPrevious',
				'label' => 'TARGET GA',
				'id' => 'target'
			]
		];
	}

	private static function data($config, $date, $endDate)
	{
		//return RoadmapWeeklyMailingScenarios::get(); // to scenarios

		//return []; // no data

		$dataByChange = RoadmapAndCandidateChanges::filter(
			[
				'config' => $config,

				'date' => $date,

				'end_date' => $endDate

			]
		);

		$data = self::joinItemsById($dataByChange);

		$data = self::order($data);

		return $data;
	}

	private static function joinItemsById($dataByChange)
	{
		$data = [];

		foreach($dataByChange as $id => $items) {

			$data = array_replace_recursive($items, $data);

		}

		return $data;
	}


	private static function order($data = [])
	{
		$base = self::baseOrder();

		$clean = [];

		foreach ($base as $project) {

			foreach ($project as $item) {

				$id = $item['issue_id'];

				if (isset($data[$id])) {

					$clean[$id] = $data[$id];

				}
			}

		}

		return $clean;
	}

	private static function baseOrder()
	{
		return [
			'roadmap' => self::baseOrderRoadmap(),

			'candidates' => self::baseOrderCandidate()
		];
	}

	private static function baseOrderRoadmap()
	{
		return ProdRoadmap::roadmap()
			->select('issue_id')
			->validated()
			->firstComponent('all')
			->label('all')
			->status('all')
			->geo('all')
			->year('all')
			->targetMM('all')
			->released('all')
			->orderBy('first_component', 'ASC')
			->orderBy('major', 'DESC')
			->orderBy('epic_name', 'ASC')
			->get()
			->toArray();
	}

	private static function baseOrderCandidate()
	{
		return ProdRoadmap::candidate()
			->select('issue_id')
			->validated()
			->firstComponent('all')
			->label('all')
			->status('all')
			->geo('all')
			->year('all')
			->targetMM('all')
			->released('all')
			->orderBy('first_component', 'ASC')
			->orderBy('epic_name', 'ASC')
			->get()
			->toArray();
	}

	protected function getArguments()
	{
		return [];
	}

	protected function getOptions()
	{
		return [];
	}

	public static function helperRegion($ticket)
	{
		if (isset($ticket['historial']['region'])) {

			$ticket = self::prepareRegionTicket($ticket);
		}

		return $ticket;
	}

	private static function original($ticket)
	{
		$original[0] = explode(', ', $ticket['historial']['region']['changes'][0]);

		$original[1] = explode(', ', $ticket['historial']['region']['changes'][1]);

		$original[0][] = $original[1][] = 'None';

		return $original;
	}

	private static function regions($original)
	{
		$region_current = array_diff($original[0], $original[1]);

		$region_previous = array_diff($original[1], $original[0]);

		$regions = [];

		foreach ($region_current as $geo) {
			$regions[] = $geo . self::concated('added');
		}

		if (self::isValidAndNoGlobalRegion($regions)) {
			foreach ($region_previous as $geo) {
				$regions[] = $geo . self::concated('removed');
			}
		}

		return $regions;
	}

	private static function prepareRegionTicket($ticket)
	{
		$original = self::original($ticket);

		$regions = self::regions($original);

		unset($ticket['historial']['region']['changes']);

		if (!empty($regions)) {
			$ticket['historial']['region']['changes'][0] = implode(', ', $regions);
		}

		if (empty($ticket['historial']['region']['changes'])) {
			unset($ticket['historial']['region']);
		}

		return $ticket;
	}

	private static function concated($text)
	{
		return ' <i margin="0" border="0" style="padding:0px;'
			.' margin:0px; border: 0px; font-size: 12px; color:#4d4d4d;">('
			.$text
			.')';
	}

	private static function isValidAndNoGlobalRegion($regions)
	{
		if (!isset($regions[0]) || $regions[0] != 'GLOBAL'.self::concated('added')) {
			return true;

		} else {
			return false;
		}

	}


	public static function backgroundColorToCategoryInHexa($component)
	{
		return '#fff';

		if (self::categoryIsAudiencia($component)) {
			return '#fde8de';

		} elseif (self::categoryIsIntelligence($component)) {
			return '#e2f4e5';

		} elseif (self::categoryIsMedia($component)) {
			return '#d9f3fc';

		} elseif (self::categoryIsPerformanceSolutions($component)) {
			return '#e8e0ee';

		} elseif (self::categoryIsIPS($component)) {
			return '#ffffd9';

		} else {
			return '#fff';
		}
	}

	private static function categoryIsAudiencia($component)
	{
		return in_array(
			$component,
			[
				'Audience',
				'Audience Platform',
				'ConnectedID',
				'Helix'
			]
		);
	}

	private static function categoryIsIntelligence($component)
	{
		return in_array(
			$component,
			[
				'Intelligence',
				'Attribution',
				'Optimization'
			]
		);
	}

	private static function categoryIsMedia($component)
	{
		return in_array(
			$component,
			[
				'Media',
				'Omni-Channel (General)',
				'Omni-Channel (Display/Native)',
				'Omni-Channel (Mobile)',
				'Omni-Channel (Video)',
				'Omni-Channel (Social)',
				'Omni-Channel (Audio)',
				'Omni-Channel (TV)',
				'Creatives',
				'Marketplaces',
				'Lightbox'

			]
		);
	}

	private static function categoryIsPerformanceSolutions($component)
	{
		return in_array(
			$component,
			[
				'Performance Solutions',
				'Performance Package',
				'Universal Audience Match'

			]
		);
	}

	private static function categoryIsIPS($component)
	{
		return in_array(
			$component,
			[
				'IPS',
				'App Store',
				'Data Platform',
				'Financial Platform',
				'Infrastructure',
				'Platform Tools'

			]
		);
	}

}
