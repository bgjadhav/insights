<?php
/**
 * Class PublisherDatabaseController
 * @author Fraser Reid <freid@mediamath.com>
 * @date 18/01/2016
 */
class PublisherDatabaseController extends Controller
{

    /**
     * Add/Edit Answers
     * @return mixed
     */
    public function add_edit_answers()
    {
        $response = [ 'status' => 'error', 'data' => 'Permission denied, your account does not have access to add or edit questions.' ];
        if(User::hasRole(['PublisherSolution'])) {

            $publisher_name = Input::get('publisher_name', null);
            $publisher_id = Input::get('publisher_id', null);
            $partner_id = Input::get('partner_id', null );

            $answers_button_string = Input::get( 'button', '' );
            $answer_text_string = Input::get( 'string', '' );
            $answer_decimal_string = Input::get( 'decimal', '' );

            //Format answers string
            $format_answers_string = function( $string ){
                $return = [];
                $a = explode( '&', $string );
                foreach( $a as $row ){
                    $b = explode( '=', $row );
                    if( isset( $row[0] ) && isset( $row[1] ) ){
                        $return[$b[0]][] = urldecode ( $b[1] );
                    }
                }
                return $return;
            };

            if( !is_null( $publisher_name ) ){
                if( !is_null( $partner_id ) ){
                    //Are we editing?
                    $edit = PublisherTool::select()->where( 'id', '=', $publisher_id )->first();

                    if( count( $edit ) > 0 ){

                        //If name has change update it
                        if( $edit->PUBLISHER != $publisher_name ){
                            PublisherTool::where( 'id', '=', $publisher_id  )->update( [ 'PUBLISHER' => $publisher_name ] );
                        }

                        $id = $edit->id; //Existing publisher id
                    }else {
                        $id = PublisherTool::insertGetId(['PUBLISHER' => $publisher_name, 'PARTNER_ID' => (int)$partner_id ]); //Add publisher and get new id
                    }

                    //Update/Insert new answers
                    if( $id ) {

                        //Check for answer updates?
                        $result = PublisherToolAnswers::where( 'PUBLISHER_ID', $id )->get();

                        $answer_ids = [];
                        foreach( $result as $row ){
                            $answer_ids[$row['QUESTION_ID']] = $row['ANSWER_ID'];
                        }

                        $insert = [];
                        $update = [];

                        //Answers from radio/checkbox (button)
                        foreach( $format_answers_string( $answers_button_string ) as $key => $answer) {
                            foreach( $answer as $row ){
                                $question_id = str_replace('button', '', $key);
                                if( !empty( $row ) ) {
                                    $array = array(
                                        "PUBLISHER_ID"     => $id,
                                        "QUESTION_ID"      => $question_id,
                                        "ANSWER_OPTION_ID" => $row,
                                        "ANSWER_STRING"    => null,
                                    );

                                    //Insert or update?
                                    if( isset( $answer_ids[$question_id] ) &&
                                            !empty( $answer_ids[$question_id] ) ){
                                        $update[$answer_ids[$question_id]] = $array;
                                    }else{
                                        $insert[] = $array;
                                    }
                                }
                            }
                        }

                        //Answers from text (string)
                        foreach( $format_answers_string( $answer_text_string ) as $key => $answer) {
                            foreach( $answer as $row ){
                                $question_id = str_replace('string', '', $key);
                                if( !empty( $row ) ) {
                                    $array = array(
                                        "PUBLISHER_ID"     => $id,
                                        "QUESTION_ID"      => $question_id,
                                        "ANSWER_OPTION_ID" => null,
                                        "ANSWER_STRING"    => $row
                                    );
                                    //Insert or update?
                                    if( isset( $answer_ids[$question_id] ) &&
                                        !empty( $answer_ids[$question_id] ) ){
                                        $update[$answer_ids[$question_id]] = $array;
                                    }else{
                                        $insert[] = $array;
                                    }
                                }
                            }
                        }

                        //Answers from number (decimal)
                        foreach( $format_answers_string( $answer_decimal_string ) as $key => $answer) {
                            foreach( $answer as $row ){
                                $question_id = str_replace('decimal', '', $key);
                                if( !empty( $row ) ) {
                                    $array = array(
                                        "PUBLISHER_ID" => $id,
                                        "QUESTION_ID" => $question_id,
                                        "ANSWER_OPTION_ID" => null,
                                        "ANSWER_STRING" => $row
                                    );
                                    //Insert or update?
                                    if (isset($answer_ids[$question_id]) &&
                                        !empty($answer_ids[$question_id])
                                    ) {
                                        $update[$answer_ids[$question_id]] = $array;
                                    } else {
                                        $insert[] = $array;
                                    }
                                }
                            }
                        }

                        //Insert Answers
                        if( !empty( $insert ) ) {
                            PublisherToolAnswers::insert($insert);
                        }

                        //Update Answers
                        if( !empty( $update ) ) {
                            foreach ($update as $key => $row) {
                                PublisherToolAnswers::where('ANSWER_ID', $key)->update($row);
                            }
                        }

                        $response = [ 'status' => 'success', 'id' => $id ];
                    }
                }else {
                    $response = [ 'status' => 'error', 'data' => 'Please supply a partner_id' ];
                }
            }else {
                $response = [ 'status' => 'error', 'data' => 'Please supply a publisher' ];
            }
        }
        return Response::json( $response );
    }

    /**
     * Add Edit Publisher
     * @return mixed
     */
    public function add_edit_publisher()
    {
        $response = [ 'status' => false, 'error' => 'Permission denied, your account does not have access to add or edit publishers.' ];
        if(User::hasRole(['PublisherSolution'])) {
            $publisher_id = Input::get('publisher_id',null);
            $partner_id = Input::get('partner_id',null);
            $name = Input::get('name',null);
            $sync = Input::get('sync',1);

            if( !empty( $name ) ) {
                if ($publisher_id) {
                    PublisherTool::where('id', '=', $publisher_id)->update(['PUBLISHER' => $name, 'PARTNER_ID' => ($partner_id == 0) ? null : $partner_id, 'SYNC_WITH_OPEN' => $sync]);
                } else {
                    $publisher_id = PublisherTool::insertGetId(['PUBLISHER' => $name, 'PARTNER_ID' => ($partner_id == 0) ? null : $partner_id, 'SYNC_WITH_OPEN' => $sync]);
                }

                $response = ['status' => true, 'publisher_id' => $publisher_id, 'partner_id' => $partner_id, 'name' => $name, 'sync' => $sync];
            }else{
                $response = [ 'status' => false, 'error' => 'A publisher name can not be left blank' ];
            }
        }
        return Response::json( $response );
    }

    /**
     * Add Edit Note
     * @return mixed
     */
    public function add_edit_note()
    {
        $publisher_id = Input::get( 'publisher_id', null );
        $question_id = Input::get( 'question_id', null );
        $note_id = Input::get( 'note_id', null );
        $note = Input::get( 'note', null );

        if(empty($note)){
            return Response::json( [ 'status' => false, 'error' => 'A note can not be empty' ] );
        }

        $answer = PublisherToolAnswers::where( 'PUBLISHER_ID', '=', $publisher_id )->where( 'QUESTION_ID', '=', $question_id )->first();

        //If no answer found then we need to create a blank answer
        if( empty( $answer ) ){
            $answerId = PublisherToolAnswers::insertGetId( [ 'PUBLISHER_ID' => $publisher_id, 'QUESTION_ID'  => $question_id ] );
        }else {
            $answerId = $answer->ANSWER_ID;
        }

        //Update/Insert Note
        if( $note_id ) {
            //Can we edit this note?
            $old_note = PublisherToolNote::where( 'ANSWER_NOTE_ID', '=', $note_id )->first();

            if( $old_note->USER_ID == SESSION::get('user_id') ) {
                PublisherToolNote::where('ANSWER_NOTE_ID', '=', $note_id)->update(['ANSWER_ID' => $answerId, 'USER_ID' => SESSION::get('user_id'), 'NOTE' => $note, 'UPDATED_ON' => date('Y-m-d H:i:s')]);
            }else{
                return Response::json( [ 'status' => false, 'error' => 'Permission denied, your account does not have access to edit this note' ] );
            }
        }else{
            $note_id = PublisherToolNote::insertGetId(['ANSWER_ID' => $answerId, 'USER_ID' => SESSION::get('user_id'), 'NOTE' => $note, 'UPDATED_ON' => date('Y-m-d H:i:s') ]);
        }

        //Get user's name
        $result = PublisherTool::getUser( SESSION::get('user_id') );
        $user = ucfirst( $result->first_name ) . ' ' . ucfirst( $result->last_name );

        return Response::json( [ 'status' => true, 'note_id' => $note_id, 'user'=> $user, 'date' => date('m/d/Y H:i') ] );
    }

    /**
     * Add Edit Question
     * @return mixed
     */
    public function add_edit_question()
    {
        if(User::hasRole(['PublisherSolution'])) {
            $question_id = Input::get('question_id', null);
            $question = Input::get('question', null);
            $question_number = Input::get('question_number', null);
            $question_sub_number = Input::get('question_sub_number', null);
            $question_tags = Input::get('question_tags', '');
            $question_required = Input::get('question_required', null);
            $question_required_answer = Input::get('question_required_answer', null);
            $question_answer_type = Input::get('question_answer_type', null);
            $question_answer_group = Input::get('question_answer_group', null);

            $answer_group_id = Input::get('answer_group_id', null);
            $answer_group = Input::get('answer_group', null);
            $answer_group_options = Input::get('answer_group_options', []);
            $answer_group_options_new = Input::get('answer_group_options_new', []);

            //Validation - question
            if( empty( $question ) ) {
                return Response::json([ 'status' => false, 'error' => 'Please enter a question' ]);
            }

            //Validation - parent question answer
            if( !empty( $question_required ) && empty( $question_required_answer ) ) {
                return Response::json([ 'status' => false, 'error' => 'An answer for the parent question is required' ]);
            }

            //Validation - answer type
            if( !in_array( $question_answer_type, [ 'MANY', 'SINGLE', 'STRING', 'DECIMAL' ] ) ){
                return Response::json([ 'status' => false, 'error' => 'Invalid answer type' ]);
            }

            //Validation - answer group name
            if( in_array( $question_answer_type, [ 'MANY', 'SINGLE' ] ) ){
                if (empty($answer_group)) {
                    return Response::json(['status' => false, 'error' => 'Answer group name can not be empty']);
                }

                //Validation - Must have one option
                $answer_group_options_count = ( count( $answer_group_options ) + count( $answer_group_options_new ) );
                if( $answer_group_options_count < 2 ){
                    return Response::json([ 'status' => false, 'error' => 'An answer group must have at least two option' ]);
                }

                //Validation - option
                foreach( $answer_group_options as $key=>$option ){
                    if( empty( $option ) ){
                        return Response::json([ 'status' => false, 'error' => 'An answer group option can not be empty' ]);
                    }
                }

                //Validation - new option
                foreach( $answer_group_options_new as $key=>$option ){
                    if( empty( $option ) ){
                        return Response::json([ 'status' => false, 'error' => 'An answer group option can not be empty' ]);
                    }
                }
            }

            //Update/Insert Answer Group
            if ($answer_group_id) {
                PublisherToolAnswersGroups::where('ANSWER_GROUP_ID', '=', $answer_group_id)->update([
                    'ANSWER_GROUP_NAME' => $answer_group
                ]);

                //Old options
                $old_options = PublisherToolAnswersOptions::where('ANSWER_GROUP_ID', '=', $answer_group_id)->get();

                //Get old options compare with new options if no longer exists then this option must be depreciated
                $option_ids = [];
                foreach ($answer_group_options as $key => $row) {
                    $option_ids[] = $key;
                }

                //Has option been depreciated?
                foreach ($old_options as $row) {
                    if (!in_array($row->ANSWER_OPTION_ID, $option_ids)) {
                        $answer_group_options[$row->ANSWER_OPTION_ID]['DEPRECIATED'] = 1;
                    }
                }

                //Update options
                foreach ($answer_group_options as $key => $option) {
                    if (is_array($option) && isset($option['DEPRECIATED'])) {
                        PublisherToolAnswersOptions::where('ANSWER_OPTION_ID', '=', $key)->update($option);
                    } else {
                        PublisherToolAnswersOptions::where('ANSWER_OPTION_ID', '=', $key)->update(['ANSWER' => $option]);
                    }
                }
            }else if ($question_answer_type == "MANY" || $question_answer_type == "SINGLE") {
                $question_answer_group = PublisherToolAnswersGroups::insertGetId(['ANSWER_GROUP_NAME' => $answer_group]);
            }

            //Insert any new answer group options
            foreach ($answer_group_options_new as $option) {
                $id = PublisherToolAnswersOptions::insertGetId([
                    'ANSWER_GROUP_ID' => $question_answer_group,
                    'ANSWER'          => $option,
                    'DEPRECIATED'     => 0
                ]);

                //Store inserted option for api response
                $answer_group_options[$id] = [
                    'ANSWER_OPTION_ID' => $id,
                    'ANSWER_GROUP_ID'  => $question_answer_group,
                    'ANSWER'           => $option,
                    'DEPRECIATED'      => 0
                ];
            }

            //Update/Insert question
            //Let the crazy numbering begin :)
            if ( $question_id ) {
                $old_question = PublisherToolQuestions::where('QUESTION_ID', '=', $question_id)->first();

                //Update question number positioning ready for this questions new question number
                if ( empty( $question_required ) && ( $old_question->QUESTION_NUMBER != $question_number ) ) {
                    PublisherToolQuestions::where('QUESTION_NUMBER', '>', $old_question->QUESTION_NUMBER)
                        ->where('QUESTION_NUMBER','>','0')
                        ->decrement('QUESTION_NUMBER');
                    PublisherToolQuestions::where('QUESTION_NUMBER', '>=', $question_number)->increment('QUESTION_NUMBER');
                }

                //Update new question sub number positioning ready for new question sub number
                if ( $old_question->QUESTION_SUB_NUMBER != $question_sub_number ) {
                    //Decrement only if we have not switched parent question's
                    if( $old_question->REQUIRES_QUESTION_ID == $question_required ) {
                        PublisherToolQuestions::where('REQUIRES_QUESTION_ID', '=', $question_required)
                            ->where('QUESTION_SUB_NUMBER', '>', $old_question->QUESTION_SUB_NUMBER)
                            ->where('QUESTION_SUB_NUMBER','>','0')
                            ->decrement('QUESTION_SUB_NUMBER');
                    }

                    PublisherToolQuestions::where('REQUIRES_QUESTION_ID', '=', $question_required)
                        ->where('QUESTION_SUB_NUMBER', '>=', $question_sub_number)
                        ->increment('QUESTION_SUB_NUMBER');
                }

                //Update old question sub number positioning if this question is no long attached to the same parent question
                if( !empty( $old_question->REQUIRES_QUESTION_ID ) &&
                        $old_question->REQUIRES_QUESTION_ID != $question_required ){

                    if( !empty( $old_question->QUESTION_SUB_NUMBER ) ){
                        PublisherToolQuestions::where('REQUIRES_QUESTION_ID', '=', $old_question->REQUIRES_QUESTION_ID)
                            ->where('QUESTION_SUB_NUMBER','>',$old_question->QUESTION_SUB_NUMBER)
                            ->where('QUESTION_SUB_NUMBER','>','0')
                            ->decrement('QUESTION_SUB_NUMBER');
                    }

                    //Get parent question's number positioning
                    $parent_question = PublisherToolQuestions::where('QUESTION_ID','=',$question_required)->first();

                    if( !empty( $parent_question ) ) {
                        $question_number = $parent_question->QUESTION_NUMBER;
                        $question_sub_number = $parent_question->QUESTION_SUB_NUMBER;
                    }
                }

                //Update sub question positioning to match this parent questions new position
                if ( $old_question->QUESTION_NUMBER != $question_number ) {
                    $question_ids = PublisherToolQuestions::select('QUESTION_ID')->from('PUBLISHER_QUESTIONS')
                        ->whereIn('REQUIRES_QUESTION_ID', function ($query) use ($question_id) {
                            $query->select('QUESTION_ID')->from('PUBLISHER_QUESTIONS')->where('REQUIRES_QUESTION_ID', '=', $question_id);
                        })->orWhereIn('QUESTION_ID', function ($query) use ($question_id) {
                            $query->select('QUESTION_ID')->from('PUBLISHER_QUESTIONS')->where('REQUIRES_QUESTION_ID', '=', $question_id);
                        });

                    if (!empty($question_ids)) {
                        //Question positioning
                        PublisherToolQuestions::whereIn('QUESTION_ID', function ($query) use ($question_ids) {
                            $query->select('QUESTION_ID')->from(DB::raw('( ' . $question_ids->toSql() . ' ) AS t'))->mergeBindings($question_ids->getQuery());
                        })->update(['QUESTION_NUMBER' => $question_number]);
                    }
                }

                //Update sub sub question positioning to match this parent questions new position
                if ( $old_question->QUESTION_SUB_NUMBER != $question_sub_number ) {
                    $question_ids = PublisherToolQuestions::select('QUESTION_ID')->from('PUBLISHER_QUESTIONS')
                        ->whereIn('REQUIRES_QUESTION_ID', function ($query) use ($question_id) {
                            $query->select('QUESTION_ID')->from('PUBLISHER_QUESTIONS')->where('REQUIRES_QUESTION_ID', '=', $question_id);
                        })->orWhereIn('QUESTION_ID', function ($query) use ($question_id) {
                            $query->select('QUESTION_ID')->from('PUBLISHER_QUESTIONS')->where('REQUIRES_QUESTION_ID', '=', $question_id);
                        });

                    if (!empty($question_ids)) {
                        //Sub question positioning
                        PublisherToolQuestions::whereIn('QUESTION_ID', function ($query) use ($question_ids) {
                            $query->select('QUESTION_ID')->from(DB::raw('( ' . $question_ids->toSql() . ' ) AS t'))->mergeBindings($question_ids->getQuery());
                        })->update(['QUESTION_SUB_NUMBER' => $question_sub_number]);
                    }
                }

                //Update question
                PublisherToolQuestions::where('QUESTION_ID', '=', $question_id)->update([
                    'QUESTION_NUMBER'      => $question_number,
                    'QUESTION_SUB_NUMBER'  => !empty($question_sub_number) ? $question_sub_number : null,
                    'REQUIRES_QUESTION_ID' => !empty($question_required) ? $question_required : null,
                    'REQUIRES_ANSWER_ID'   => !empty($question_required_answer) ? $question_required_answer : null,
                    'QUESTION_TITLE'       => $question,
                    'ANSWER_TYPE'          => $question_answer_type,
                    'ANSWER_GROUP_ID'      => !empty($question_answer_group) && ($question_answer_type == "MANY" || $question_answer_type == "SINGLE") ? $question_answer_group : null,
                    'DEPRECIATED'          => 0
                ]);

                $message = 'Question updated';
            } else {
                //If we have a question sub number then don't increment as we need to keep the parent question's number
                if( !empty( $question_sub_number ) ) {
                    //Update question sub number positioning ready for question sub number change
                    PublisherToolQuestions::where('QUESTION_NUMBER', '=', $question_number)
                        ->where('QUESTION_SUB_NUMBER', '>=', $question_sub_number)
                        ->increment('QUESTION_NUMBER');
                }else{
                    //Update question number positioning ready for new question number
                    PublisherToolQuestions::where('QUESTION_NUMBER', '>=', $question_number)->increment('QUESTION_NUMBER');
                }

                //Insert question
                $question_id = PublisherToolQuestions::insertGetId([
                    'QUESTION_NUMBER'      => $question_number,
                    'QUESTION_SUB_NUMBER'  => !empty($question_sub_number) ? $question_sub_number : null,
                    'REQUIRES_QUESTION_ID' => !empty($question_required) ? $question_required : null,
                    'REQUIRES_ANSWER_ID'   => !empty($question_required_answer) ? $question_required_answer : null,
                    'QUESTION_TITLE'       => $question,
                    'ANSWER_TYPE'          => $question_answer_type,
                    'ANSWER_GROUP_ID'      => !empty($question_answer_group) && ($question_answer_type == "MANY" || $question_answer_type == "SINGLE") ? $question_answer_group : null,
                    'DEPRECIATED'          => 0
                ]);
                $message = 'Question added';
            }

            //Question tags
            $new_tags = [];
            if (!empty($question_tags)) {
                $old_tags = PublisherToolTags::whereIn('TAG', $question_tags)->get();

                //Remove tags which already exist
                foreach ($question_tags as $key => $tag) {
                    foreach ($old_tags as $index => $row) {
                        if (strtolower($tag) == $row->TAG) {
                            unset($question_tags[$key]);
                        }
                    }
                }

                //Insert new tags
                foreach ($question_tags as $key => $tag) {
                    $id = PublisherToolTags::insertGetId(['TAG' => strtolower($tag)]);
                    $new_tags[$id] = $tag;
                }

                //Merge old tags into new tags
                foreach ($old_tags as $key => $tag) {
                    $new_tags[$tag->TAG_ID] = $tag->TAG;
                }

                //Add default all tag
                $new_tags[1] = 'all';

                //Delete existing tags associated to this question
                if (!empty($question_id)) {
                    PublisherToolTagQuestions::where('QUESTION_ID', '=', $question_id)->delete();
                }

                //Insert new tags associated to this question
                foreach ($new_tags as $key => $tag) {
                    if( !empty( $question_id ) &&
                            !empty( $tag ) ) {
                        PublisherToolTagQuestions::insert(['QUESTION_ID' => $question_id, 'TAG_ID' => $key]);
                    }
                }
            }

            //Response
            $response = [];
            $response['QUESTION'] = null;
            if( $question_id ) {
                $response['QUESTION'] = [
                    'ANSWER_GROUP_ID'      => (int)$question_answer_group,
                    'ANSWER_TYPE'          => $question_answer_type,
                    'QUESTION_ID'          => (int)$question_id,
                    'QUESTION_TITLE'       => $question,
                    'QUESTION_NUMBER'      => (int)$question_number,
                    'QUESTION_SUB_NUMBER'  => (int)$question_sub_number,
                    'REQUIRES_ANSWER_ID'   => (int)$question_required_answer,
                    'REQUIRES_QUESTION_ID' => (int)$question_required,
                    'DEPRECIATED'          => 0
                ];
            }

            $response['ANSWER_GROUP'] = null;
            if( $question_answer_group ) {
                $response['ANSWER_GROUP'] = [
                    'ANSWER_GROUP_ID'   => (int)$question_answer_group,
                    'ANSWER_GROUP_NAME' => $answer_group
                ];
            }

            $response['ANSWER_GROUP_OPTIONS'] = null;
            foreach( $answer_group_options as $key => $row ){
                $response['ANSWER_GROUP_OPTIONS'][] = [
                    'ANSWER_OPTION_ID' => (int)$key,
                    'ANSWER_GROUP_ID'  => (int)$question_answer_group,
                    'ANSWER'           => isset( $row['ANSWER'] ) ? $row['ANSWER'] : $row,
                    'DEPRECIATED'      => 0
                ];
            }

            $response['TAGS'] = null;
            foreach( $new_tags as $key => $tag ){
                $response['TAGS'][] = [
                    'QUESTION_ID' => (int)$question_id,
                    'TAG'         => $tag,
                    'TAG_ID'      => $key
                ];
            }

            return Response::json([ 'status' => true, 'success' => $message, 'data' => $response]);
        }
        return Response::json([ 'status' => false, 'error' => 'Permission denied, your account does not have access to add or edit questions.']);
    }

    /**
     * Answers
     * @return mixed
     */
    public function answers()
    {
        $publisher_id = Input::get( 'publisher_id', null );

        //Get answers
        $answers = PublisherToolAnswers::select()->leftJoin('PUBLISHER_ANSWER_OPTIONS','PUBLISHER_ANSWER_OPTIONS.ANSWER_OPTION_ID','=','PUBLISHER_ANSWERS.ANSWER_OPTION_ID')
                                                 ->where( 'PUBLISHER_ID', '=', $publisher_id )
                                                 ->where(function($query){
                                                     $query->where('PUBLISHER_ANSWER_OPTIONS.DEPRECIATED', '=', '0')
                                                           ->orWhereNull('PUBLISHER_ANSWERS.ANSWER_OPTION_ID');
                                                 })
                                                 ->get();

        //Get answer_ids
        $answer_ids = [];
        foreach( $answers as $answer ){
            $answer_ids[] = $answer->ANSWER_ID;
        }

        //Get notes for answer_ids
        $result = PublisherToolNote::whereIn( 'ANSWER_ID', $answer_ids )->get();

        //Get name of user for answer_ids as this is stored in open_v3 DB we can't use joins :(
        $user_ids = [];
        foreach( $result as $row ){
            if( !in_array( $row->USER_ID, $user_ids ) ) {
                $user_ids[] = $row->USER_ID;
            }
        }

        //Match users name with notes
        $users = PublisherTool::getUsers( $user_ids );
        foreach( $users as $user){
            foreach( $result as &$row ){
                if( $row->USER_ID == $user->user_id ){
                    $row->USER = $user->first_name . ' ' . $user->last_name;
                }
            }
        }

        //Format notes array
        $notes = [];
        foreach( $result as $row ){
            $row->DATE = date( 'm/d/Y H:i', strtotime( $row->UPDATED_ON ) );
            $notes[$row->ANSWER_ID][] = $row;
        }

        //Match associated note with answer
        foreach( $answers as &$answer ){
            if( isset( $notes[$answer->ANSWER_ID] ) ){
                $answer->NOTES = $notes[$answer->ANSWER_ID];
            }else{
                $answer->NOTES = [];
            }
        }

        $response = [ 'status' => 'error', 'data' => [] ];
        if( !empty( $answers ) ){
            $response = [ 'status' => 'success', 'data' => $answers ];
        }

        return Response::json( $response );
    }

    /**
     * Answer Groups
     * @return mixed
     */
    public function answer_groups(){
        return PublisherToolAnswersGroups::get();
    }

    /**
     * Answer Options
     * @return mixed
     */
    public function answer_options()
    {
        return PublisherToolAnswersOptions::where('DEPRECIATED','=','0')->get();
    }

    /**
     * Depreciate Question
     */
    public function depreciate_question()
    {
        $question_id = Input::get('question_id',null);
        PublisherToolQuestions::where('QUESTION_ID','=',$question_id)->update(['DEPRECIATED'=>1]);
        return Response::json( [ 'status' => 'success', 'message' => 'Question removed' ] );
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        return View::make('knowledge.publishertool.index' );
    }

    /**
     * Get Publishers
     * @return mixed
     */
    public function publishers()
    {
        $publishers = [];

        //Get existing publishers
        $result = PublisherTool::select()->get();
        foreach( $result as $row ){
            $publishers[] = [ 'publisher_id' => $row->id,
                              'partner_id'   => $row->PARTNER_ID,
                              'name'         => $row->PUBLISHER,
                              'sync'         => $row->SYNC_WITH_OPEN,
                              'logo'         => '' ];
        }

        //Get partners
        $partners = PublisherTool::partners();

        foreach ( $partners as $row ) {
            $sync = 1;
            $publisher_id = null;
            $publisher_old_name = '';
            foreach( $publishers as $key=>$publisher ){
                if( $row->id == $publisher['partner_id'] ){

                    //Do we want to sync this publisher with the latest information from the OPEN portal for things like PUBLISHER_NAME?
                    $sync = $publisher['sync'];

                    //Get the publisher_id for adding this partner to the publishers list later
                    $publisher_id = $publisher['publisher_id'];

                    //Old publisher name
                    $publisher_old_name = $publisher['name'];

                    //Unset the old publisher so that we do not duplicate the same partnerId
                    unset($publishers[$key]);

                    break;
                }
            }

            //Add partner to publishers list
            $publishers[] = [ 'publisher_id' => $publisher_id,
                              'partner_id'   => $row->id,
                              'name'         => ( $sync ) ? $row->name : $publisher_old_name,
                              'sync'         => $sync,
                              'logo'         => $row->logo ];

        }

        //Sort by name
        usort( $publishers, function( $a, $b ){
            return strcmp($a["name"], $b["name"]);
        });

        return Response::json( $publishers );
    }

    /**
     * Questions
     * @return mixed
     */
    public function questions()
    {
        $questions = PublisherToolQuestions::select()->with('options')
                  ->orderBy('QUESTION_NUMBER','ASC')
                  ->orderBy('QUESTION_SUB_NUMBER','ASC')
                  ->orderBy('REQUIRES_QUESTION_ID','ASC')
                  ->get();

        return Response::json($questions);
    }

    /**
     * Questions tags
     */
    public function question_tags()
    {
        $tags = PublisherToolTagQuestions::leftJoin('PUBLISHER_TAGS', 'PUBLISHER_TAGS.TAG_ID','=','PUBLISHER_TAG_QUESTIONS.TAG_ID')->groupBy('QUESTION_ID')->groupBy('PUBLISHER_TAGS.TAG_ID')->get();
        return Response::json($tags);
    }

    /**
     * Remove Note
     */
    public function remove_note()
    {
        $note_id = Input::get( 'note_id', null );
        $user = SESSION::get('user_id');

        if( $note_id ) {
            $note = PublisherToolNote::where('ANSWER_NOTE_ID', '=', $note_id)->first();

            if ($note->USER_ID == $user) {
                PublisherToolNote::where('ANSWER_NOTE_ID', '=', $note_id)->delete();
                return Response::json(['status' => true, 'note_id' => $note_id ]);
            }
            return Response::json(['status' => false, 'error' => 'Permission denied, your account does not have access to delete this note']);
        }
        return Response::json(['status' => false, 'error'=> 'Please select a note to delete' ]);
    }

    /**
     * Search
     * @return mixed
     */
    public function search()
    {
        $includeTags = Input::get('include_tags', []);
        $includeKeywords = Input::get('include_keywords', []);
        $excludeTags = Input::get('exclude_tags', []);
        $excludeKeywords = Input::get('exclude_keywords', []);

        $result = PublisherToolTags::search( $includeTags, $includeKeywords, $excludeTags, $excludeKeywords );

        //Format results
        $response = [];
        foreach( $result as $row ){
            if ( !isset( $response[$row->PUBLISHER_ID]['TAG_ID'] ) ||
                    !in_array( $row->TAG_ID, $response[$row->PUBLISHER_ID]['TAG_ID'] ) ) {
                $response[$row->PUBLISHER_ID]['TAG_ID'][] = $row->TAG_ID;
            }

            $response[$row->PUBLISHER_ID]['PUBLISHER'] = $row->PUBLISHER;

            $response[$row->PUBLISHER_ID]['ANSWERS'][$row->QUESTION_ID]['PUBLISHER'] = $row->PUBLISHER_ID;
            $response[$row->PUBLISHER_ID]['ANSWERS'][$row->QUESTION_ID]['QUESTION'] = $row->QUESTION_TITLE;

            if (!empty($row->ANSWER)) {
                if ( !isset( $response[$row->PUBLISHER_ID]['ANSWERS'][$row->QUESTION_ID]['ANSWER'] ) ||
                        !in_array( $row->ANSWER, $response[$row->PUBLISHER_ID]['ANSWERS'][$row->QUESTION_ID]['ANSWER'] ) ) {
                    $response[$row->PUBLISHER_ID]['ANSWERS'][$row->QUESTION_ID]['ANSWER'][] = $row->ANSWER;
                }
            }

            if (!empty($row->ANSWER_STRING)) {
                $response[$row->PUBLISHER_ID]['ANSWERS'][$row->QUESTION_ID]['ANSWER'][] = $row->ANSWER_STRING;
            }
        }

        //Number of tag matches for this publisher
        $tag_match = [];
        foreach( $response as $key => $row ){
            foreach( $row['TAG_ID'] as $tag ) {
                if ( !isset( $tag_match[$key] ) || !in_array( $tag, $tag_match[$key] ) ) {
                    $tag_match[$key][] = $tag;
                }
            }
        }

        //Remove publisher where tag_match does not meet the minimum required
        foreach( $response as $key => $row ){
            if( isset( $tag_match[$key] ) ){
                if( count( $tag_match[$key] ) != ( count( $includeTags ) ) ){
                    //unset( $response[$key] );
                }
            }
        }
        return Response::json( $response );
    }

    /**
     * Tags
     */
    public function tags()
    {
        $tags = PublisherToolTags::select()->orderBy('TAG')->get();
        return Response::json($tags);
    }
}
