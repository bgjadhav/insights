<?php

class PublisherTool extends Eloquent
{
    public $timestamps = false;

	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER';

	public static function getUser( $userId ){
		return DB::reconnect('users_v3')->table('users_v3')->where('user_id', '=', $userId)->first();
	}

	public static function getUsers( array $userIds ){
		return DB::reconnect('users_v3')->table('users_v3')->whereIn('user_id', $userIds)->get();
	}

	public static function partners(){
		return DB::reconnect('users_v3')->table('partners AS p')->select( DB::raw( 'p.*' ) )
				->leftJoin('partner_type_matches AS ptm', 'p.id', '=', 'ptm.partner_id')
				->leftJoin('partner_types AS pt', 'pt.id', '=', 'ptm.type_id')

				->where( 'p.edit_accept', '=', 1 )
				->where( 'p.pending', '=', '0' )
				->where( 'p.status', '=', 1 )

				->where( function( $query ){
					$query->where( 'pt.id', '=', 15 )
						  ->orWhere( 'pt.id', '=', 16 );
				})

				->orderBy( 'p.name' )
				->groupBy( 'p.name' )
				->get();
	}
}

class PublisherToolQuestions extends Eloquent
{
	public $timestamps = false;

	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_QUESTIONS';

	public function groups() {
		return $this->hasOne('PublisherToolAnswersGroups', 'ANSWER_GROUP_ID', 'ANSWER_GROUP_ID');
	}

	public function options() {
		return $this->hasMany('PublisherToolAnswersOptions', 'ANSWER_GROUP_ID', 'ANSWER_GROUP_ID')->where( 'DEPRECIATED', '=', '0' );
	}

	public function increment($column, $amount = 1, $extra = array()){
		return \Illuminate\Database\Eloquent\Builder::increment($column, $amount, $extra);
	}

	public function decrement($column, $amount = 1, $extra = array()){
		return \Illuminate\Database\Eloquent\Builder::decrement($column, $amount, $extra);
	}
}

class PublisherToolAnswers extends Eloquent
{
	public $timestamps  = false;

	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_ANSWERS';

    /**
     * Publisher Answers
     * @param bool $publisher_id
     * @return mixed
     */
    public static function publisher_answers( $publisher_id = false ){
        $query = DB::reconnect('publisher_tool')
                    ->table( DB::raw( 'PUBLISHER_ANSWERS AS pa' ) )
                    ->leftJoin( 'PUBLISHER_QUESTIONS AS pq', 'pq.question_id', '=', 'pa.question_id' )
                    ->leftJoin( 'PUBLISHER_ANSWER_OPTIONS AS pao', 'pao.answer_id', '=', 'pa.answer_id' )
                    ->leftJoin( 'PUBLISHER AS p', 'p.id', '=', 'pa.PUBLISHER_ID' );

        if( $publisher_id ){
            $query->where( 'PUBLISHER_ID', '=', $publisher_id );
        }

        return $query->groupBy( 'pa.PUBLISHER_ID' )
                     ->groupBy( 'pa.QUESTION_ID' )
                     ->get();
    }
}

class PublisherToolAnswersOptions extends Eloquent
{
	public $timestamps = false;

	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_ANSWER_OPTIONS';
}

class PublisherToolAnswersGroups extends Eloquent
{
	public $timestamps = false;

	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_ANSWER_GROUPS';

	public function options() {
		return $this->hasMany('PublisherToolAnswersOptions', 'ANSWER_GROUP_ID', 'ANSWER_GROUP_ID');
	}
}

class PublisherToolTags extends Eloquent
{
	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_TAGS';

	/**
	 * Search
	 * @param array $includeTags
	 * @param array $includeKeywords
	 * @param array $excludeTags
	 * @param array $excludeKeywords
	 * @return mixed
	 */
	public static function search( array $includeTags, array $includeKeywords, array $excludeTags, array $excludeKeywords ){
		$innerSelect = DB::reconnect( 'publisher_tool' )->table( 'PUBLISHER_TAGS AS t' )
													     ->select( DB::raw( 'q.*, t.*' ) )
													     ->leftJoin( 'PUBLISHER_TAG_QUESTIONS AS sq', 't.TAG_ID', '=', 'sq.TAG_ID' )
													     ->leftJoin( 'PUBLISHER_QUESTIONS AS q', 'q.QUESTION_ID', '=', 'sq.QUESTION_ID' )
														 ->whereIn( 't.TAG_ID', $includeTags );

		$select = DB::reconnect( 'publisher_tool' )->table( 'PUBLISHER_ANSWERS AS a' )
												    ->leftJoin( DB::raw( '( ' . $innerSelect->toSql() . ' ) AS tt' ), 'a.QUESTION_ID', '=', 'tt.QUESTION_ID' )
												    ->mergeBindings( $innerSelect )
												    ->leftJoin( 'PUBLISHER_ANSWER_OPTIONS AS ao', 'ao.ANSWER_OPTION_ID', '=', 'a.ANSWER_OPTION_ID' )
												    ->leftJoin( 'PUBLISHER AS p', 'a.PUBLISHER_ID', '=', 'p.id' )
												    ->whereNotNull( 'tt.TAG' )
													->where( 'ao.DEPRECIATED', '=', '0' );

		//Use this for comparison later
		$before_exclude_filter_select = clone $select;
		$before_exclude_filter = $before_exclude_filter_select->select( DB::raw( 'PUBLISHER_ID, COUNT(*) AS answers' ) )->groupBy( 'PUBLISHER_ID' );

		$innerSelectWithExclude = DB::reconnect( 'publisher_tool' )->table( 'PUBLISHER_TAGS AS t' )
							->select( DB::raw( 'q.*, t.*' ) )
							->leftJoin( 'PUBLISHER_TAG_QUESTIONS AS sq', 't.TAG_ID', '=', 'sq.TAG_ID' )
							->leftJoin( 'PUBLISHER_QUESTIONS AS q', 'q.QUESTION_ID', '=', 'sq.QUESTION_ID' )
							->whereIn( 't.TAG_ID', $includeTags )
							->whereNotIn( 'q.QUESTION_ID', function($query) use($excludeTags) {
								$query->from('PUBLISHER_TAGS AS t')
										->select(DB::raw('q.QUESTION_ID'))
										->leftJoin('PUBLISHER_TAG_QUESTIONS AS sq', 't.TAG_ID', '=', 'sq.TAG_ID')
										->leftJoin('PUBLISHER_QUESTIONS AS q', 'q.QUESTION_ID', '=', 'sq.QUESTION_ID')
										->whereIn('t.TAG_ID', $excludeTags);
							});

		$select = DB::reconnect( 'publisher_tool' )->table( 'PUBLISHER_ANSWERS AS a' )
													->leftJoin( DB::raw( '( ' . $innerSelectWithExclude->toSql() . ' ) AS tt' ), 'a.QUESTION_ID', '=', 'tt.QUESTION_ID' )
													->mergeBindings( $innerSelectWithExclude )
													->leftJoin( 'PUBLISHER_ANSWER_OPTIONS AS ao', 'ao.ANSWER_OPTION_ID', '=', 'a.ANSWER_OPTION_ID' )
													->leftJoin( 'PUBLISHER AS p', 'a.PUBLISHER_ID', '=', 'p.id' )
													->whereNotNull( 'tt.TAG' )
													->where( 'ao.DEPRECIATED', '=', '0' );

		//Exclude Keywords
		if( !empty( $excludeKeywords ) ) {
			$select->where(function ($query) use ($excludeKeywords) {
				foreach ($excludeKeywords as $keyword) {
					$query->where(function ($query) use ($keyword) {
						return $query->where( DB::raw( 'LOWER( ANSWER_STRING )' ), 'NOT LIKE', '%' . strtolower( $keyword ) . '%')
								->where( DB::raw( 'LOWER( ANSWER )' ), 'NOT LIKE', '%' . strtolower( $keyword ) . '%');
					});
				}
				return $query;
			});
		}

		//Use this for comparison later
		$with_exclude_filter_select = clone $select;
		$with_exclude_filter = $with_exclude_filter_select->select( DB::raw( 'PUBLISHER_ID, COUNT(*) AS answers' ) )->groupBy( 'PUBLISHER_ID' );

		//Include Keywords
		if( !empty( $includeKeywords ) ) {
			$select->where(function ($query) use ($includeKeywords) {
				foreach ($includeKeywords as $keyword) {
					$query->where(function ($query) use ($keyword) {
						return $query->where( DB::raw( 'LOWER( ANSWER_STRING )' ), 'LIKE', '%' . strtolower( $keyword ) . '%')
								->orwhere( DB::raw( 'LOWER( ANSWER )' ), 'LIKE', '%' . strtolower( $keyword ) . '%');
					});
				}
				return $query;
			});
		}

		$select->select( DB::raw( 'PUBLISHER_ID, PUBLISHER, tt.QUESTION_ID, tt.QUESTION_TITLE, ANSWER_ID, TAG_ID, ANSWER, ANSWER_STRING ' ) )->groupBy( 'PUBLISHER_ID' )->groupBy('ANSWER_ID')->groupBy( 'TAG_ID' )->get();

		//Compare after/with filter to determine if we need to exclude a publisher from the results
		$outerSelect = DB::reconnect( 'publisher_tool' )->table( DB::raw( '( ' . $select->toSql() . ' ) AS t' ) )
														 ->mergeBindings( $select )
														 ->leftJoin( DB::raw( '( ' . $before_exclude_filter->toSql() . ' ) AS tt' ), 'tt.PUBLISHER_ID', '=', 't.PUBLISHER_ID' )
													 	 ->mergeBindings( $before_exclude_filter )
														 ->leftJoin( DB::raw( '( ' . $with_exclude_filter->toSql() . ' ) AS ttt' ), 'ttt.PUBLISHER_ID', '=', 't.PUBLISHER_ID' )
														 ->mergeBindings( $with_exclude_filter )
														 ->whereRaw( '( tt.answers = ttt.answers )');

		return $outerSelect->groupBy('t.PUBLISHER_ID')->groupBy('ANSWER_ID')->groupBy( 'TAG_ID' )->get();
	}

}

class PublisherToolTagQuestions extends Eloquent
{
	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_TAG_QUESTIONS';
}

class PublisherToolNote extends Eloquent
{
	public $timestamps  = false;

	protected $connection = 'publisher_tool';
	protected $table = 'PUBLISHER_ANSWER_NOTES';
}
