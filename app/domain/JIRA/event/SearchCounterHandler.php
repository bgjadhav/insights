<?php
class SearchCounterHandler
{
	public function handle($word, $report, $date)
	{
		$search = SearchTerm::firstOrNew([

			'mm_date' => $date,

			'word' => $word,

			'report' => $report
		]);

		if (!$search->id) {

			$search->total = 1;

		} else {
			$search->total = $search->total + 1;
		}

		$search->save();

		return false;
	}

}
