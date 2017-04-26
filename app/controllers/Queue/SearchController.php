<?php
class SearchController
{
	public function fire()
	{
	}

	public static function storagedSearchInPage($page, $search)
	{
		if (App::environment('production')) {

			Queue::push('SearchController@storeWords',
			[

				'detail'=> self::details($page),

				'words'	=> $search,
			]);
		}
	}


	public static function details($page='unknown')
	{
		$page = trim($page);

		return [

			'date' => date('Y-m-d'),

			'report' => $page != '' ? $page : 'unknown',

			'user' => [
				'uid' => Session::get('user_id'),
				'name' => SessionService::currentDisplayNameOrEmail()
			]

		];
	}

	public function storeWords($job, $data)
	{
		if ($job->attempts() < 2) {

			$words = self::dividedPhaseByWord($data['words']);

			array_walk($words, function ($word) use ($data) {

				self::storeOneWord($data, $word);

			});

		}

		$job->delete();
	}

	public static function storeOneWord($data, $word)
	{

		$word = trim($word);

		if (strlen($word) > 1) {

			$full_data = array_merge(
				$data,
				['word' => $word]
			);

			self::InsertNewOrUpdateIfExist($full_data);

			self::fireEvent($full_data);

		}
	}

	public static function dividedPhaseByWord($words)
	{
		$words = explode('|', $words);
		$words = array_unique($words);

		return $words;
	}

	public static function InsertNewOrUpdateIfExist($data)
	{
		$search = Search::firstOrNew([
			'MM_DATE' =>  $data['detail']['date'],

			'WORD' => $data['word'],

			'REPORT' => $data['detail']['report'],

			'USER_' => $data['detail']['user']['uid']
		]);

		if (!$search->id) {

			$search->COUNT_ = 1;

		} else {
			$search->COUNT_ = $search->COUNT_ + 1;
		}

		$search->NAME_OR_EMAIL = $data['detail']['user']['name'];

		$search->save();
	}

	protected static function fireEvent($data)
	{
		Event::fire(
			'search.counter',

			[
			'word' => $data['word'],
			'report' => $data['detail']['report'],
			'date' => $data['detail']['date']
		]);
	}
}
?>
