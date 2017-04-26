/**
 * Question.js
 * @created 25/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(['Alert',
        'Chosen',
        'Request',
        'Handlebars',
        'Source',
        'Fooltip',
        'SimpleSlider',
        'Text!Templates/questionaire/question/question.handlebars',
        'Text!Templates/questionaire/question/preview/table.handlebars',
        'Text!Templates/questionaire/question/preview/spinner.handlebars',
        'Text!Templates/questionaire/question/form.handlebars',
        'Text!Templates/questionaire/question/answer/option.handlebars',
        'Text!Templates/questionaire/question/input/option.handlebars' ],
    function( alert,
              chosen,
              request,
              handlebars,
              source,
              fooltip,
              simple_slider,
              question,
              preview,
              spinner,
              form,
              answer_option,
              option ) {
        return {

            //Ajax
            ajax : {
                add_edit_question : 'publisher_database/add_edit_question',
                answer_groups : 'publisher_database/answer/groups',
                answer_options : 'publisher_database/answer/options',
                questions : 'publisher_database/questions',
                question_tags : 'publisher_database/question_tags',
                question_depreciate : 'publisher_database/question_depreciate'
            },

            //Div
            div : {
                add : '#add',
                add_group : '.add_group',
                add_answer_option : '.add-answer-option',
                add_edit_answer_group : '#add-edit-answer-group',
                add_edit_actions : '#add-edit-actions',
                add_edit_question : '#add-edit-question',
                alert : '#alert-message',
                cancel : '#cancel',
                edit : '#edit',
                edit_group : '.edit_group',
                input : {
                    answer_required : '[name="ANSWER-REQUIRED"]',
                    answer_type : '[name="ANSWER-TYPE"]',
                    answer_group : '[name="ANSWER-GROUP"]',
                    answer_group_name : '[name="ANSWER-GROUP-NAME"]',
                    answer_group_option : '[name="ANSWER-OPTION[]"]',
                    question : '[name="QUESTION"]',
                    question_number : '[name="QUESTION-NUMBER"]',
                    question_sub_number : '[name="QUESTION-SUB-NUMBER"]',
                    question_restore : '[name="QUESTION-RESTORE"]',
                    question_required : '[name="QUESTION-REQUIRED"]',
                    question_tags : '[name="QUESTION-TAGS"]',
                    slider : {
                        question_number : '[name="SLIDER-QUESTION-NUMBER"]',
                        question_sub_number : '[name="SLIDER-QUESTION-SUB-NUMBER"]'
                    }
                },
                output : '.output',
                option : '.option',
                option_add : '.add-answer-option',
                preivew : '.preview',
                question : '.question',
                question_selected : '.question select option:selected',
                question_manager_nav : '#question-manager-nav',
                question_number : '#question-number',
                remove_question : '#remove-question',
                remove_answer_option : '.remove-answer-option',
                reset_question_number : '#reset-question-number',
                reset_question_sub_number : '#reset-question-sub-number',
                restore : '#restore',
                restore_question : '#restore-question',
                save : '#save'
            },

            //Question
            question_manage : [],
            question_manage_depreciated : [],
            question_manage_answer_groups : [],
            question_manage_answer_options : [],
            question_manage_tags : [],

            //Slider
            slider : {},

            //Template
            template : {},

            /**
             * Add Edit Question
             */
            add_edit_question : function(){
                var self = this;
                var question_id = $(self.div.question_selected).val();
                var question_number = $(self.div.input.question_number).val();
                var question_sub_number = $(self.div.input.question_sub_number).val();
                var question_title = $(self.div.add_edit_question).find(self.div.input.question).val();

                //If we are restoring a question then override question_id with question restore id
                if( $(self.div.input.question_restore).parent().is(':visible') ){
                    question_id = $(self.div.input.question_restore).val();
                }

                var question_tags = [];
                var tags = $(self.div.add_edit_question).find(self.div.input.question_tags).val();
                var tags_split = tags.split(',');
                $.each(tags_split, function(index, value) {
                    if( value != "" ) {
                        question_tags.push( $.trim(value) );
                    }
                });

                var question_required = $(self.div.add_edit_question).find(self.div.input.question_required).val();
                var question_required_answer = $(self.div.add_edit_question).find(self.div.input.answer_required).val();
                var question_answer_type = $(self.div.add_edit_question).find(self.div.input.answer_type).val();
                var question_answer_group = $(self.div.add_edit_question).find(self.div.input.answer_group).val();

                var answer_group_id = $(self.div.add_edit_question).find(self.div.input.answer_group).val();
                var answer_group = $(self.div.add_edit_question).find(self.div.input.answer_group_name).val();

                var answer_options = {};
                var answer_options_new = [];
                $(self.div.add_edit_question).find(self.div.input.answer_group_option).each(function(index, option){
                    if( $(option).data('id') != "" ) {
                        answer_options[$(option).data('id')] = $(option).val(); //Old options
                    }else{
                        answer_options_new.push( $(option).val() ); //New options
                    }
                });

                //Toggle Spinner
                self.toggle_spinner(true);

                //Submit request
                request.open(self.ajax.add_edit_question,
                    $.post(self.ajax.add_edit_question, {
                        question_id : question_id,
                        question_number : question_number,
                        question_sub_number : question_sub_number,
                        question : question_title,
                        question_tags : question_tags,
                        question_required : question_required,
                        question_required_answer : question_required_answer,
                        question_answer_type : question_answer_type,
                        question_answer_group : question_answer_group,
                        answer_group_id : answer_group_id,
                        answer_group : answer_group,
                        answer_group_options : answer_options,
                        answer_group_options_new : answer_options_new
                    },function(response) {
                        if (response['status'] == true) {
                            $.each( response['data'], function(key,response) {
                                var question_updated = false;
                                var answer_group_updated = false;
                                var answer_group_option_updated = false;
                                var tags_updated = false;

                                //QUESTION
                                if( key == "QUESTION" && response != null ){
                                    $.each(self.question_manage, function (index,row) {
                                        if( typeof row != 'undefined' ) {
                                            if (row['QUESTION_ID'] == response['QUESTION_ID']) {
                                                self.question_manage[index] = response;
                                                question_updated = true;
                                            }
                                        }
                                    });

                                    if(!question_updated) {
                                        self.question_manage.push( response );
                                    }

                                    $.each(self.question_manage_depreciated, function (index,row) {
                                        if( typeof row != 'undefined' ) {
                                            if (row['QUESTION_ID'] == response['QUESTION_ID']) {
                                                delete self.question_manage_depreciated[index];
                                            }
                                        }
                                    });
                                }

                                //ANSWER_GROUP
                                if( key == "ANSWER_GROUP" && response != null ){
                                    $.each(self.question_manage_answer_groups, function (index, row) {
                                        if( typeof row != 'undefined' ) {
                                            if (row['ANSWER_GROUP_ID'] == response['ANSWER_GROUP_ID']) {
                                                self.question_manage_answer_groups[index] = response;
                                                answer_group_updated = true;
                                            }
                                        }
                                    });

                                    if (!answer_group_updated) {
                                        self.question_manage_answer_groups.push( response );
                                    }
                                }

                                //ANSWER GROUP OPTIONS
                                if( key == "ANSWER_GROUP_OPTIONS" && response != null ){
                                    $.each(response, function (key,row) {
                                        answer_group_option_updated = false;
                                        $.each(self.question_manage_answer_options, function (index, value) {
                                            if( typeof value != 'undefined' ) {
                                                if (value['ANSWER_OPTION_ID'] == row['ANSWER_OPTION_ID']) {
                                                    self.question_manage_answer_options[index] = row;
                                                    answer_group_option_updated = true;
                                                }
                                            }
                                        });

                                        if (!answer_group_option_updated) {
                                            self.question_manage_answer_options.push( row );
                                        }
                                    });
                                }

                                //TAGS
                                if( key == "TAGS" && response != null ){
                                    $.each(response, function (key,row) {
                                        tags_updated = false;
                                        $.each(self.question_manage_tags, function (index, value) {
                                            if( typeof value != 'undefined' ) {
                                                if (value['QUESTION_ID'] == row['QUESTION_ID']) {
                                                    if (value['TAG_ID'] == row['TAG_ID']) {
                                                        self.question_manage_tags[index] = row;
                                                        tags_updated = true;
                                                    }
                                                }
                                            }
                                        });

                                        if (!tags_updated) {
                                            self.question_manage_tags.push( row );
                                        }
                                    });
                                }
                            });

                            //Trigger Refresh
                            self.refresh_question_restore("");
                            self.refresh_question_selected(response['data']['QUESTION']['QUESTION_ID']);
                            self.refresh_question_required(response['data']['QUESTION']['REQUIRES_QUESTION_ID']);
                            self.refresh_answer_group(response['data']['QUESTION']['ANSWER_GROUP_ID']);
                            self.refresh_answer_group_options(response['data']['QUESTION']['ANSWER_GROUP_ID'],"");

                            //Set buttons to edit as we have something to edit now
                            $(self.div.add_edit_actions).find(self.div.save).text('Edit');
                            self.toggle_button_active($(self.div.question_manager_nav).find('#edit'));

                            alert.set('success', response['success']);
                        }else{
                            alert.set('error', response['error']);
                        }

                        //Toggle Spinner
                        self.toggle_spinner(false);

                        request.close(self.ajax.add_edit_question);
                    })
                );
            },

            /**
             * Depreciate Question
             * @param question_id
             */
            depreciate_question : function( question_id ){
                var self = this;

                request.open(self.ajax.question_depreciate,
                    $.getJSON(self.ajax.question_depreciate,{
                        question_id : question_id
                    },function( data ){
                        if( data['status'] == 'success' ) {

                            $.each(self.question_manage,function(index,value){
                                if( typeof value != 'undefined' ) {
                                    if (value['QUESTION_ID'] == question_id) {
                                        self.question_manage[index]['DEPRECIATED'] = 1;
                                        self.question_manage_depreciated.push( self.question_manage[index] );
                                        delete self.question_manage[index];
                                    }
                                }
                            });

                            self.refresh_question_restore("");
                            self.refresh_question_required("");
                            self.refresh_question_selected("");
                            self.refresh_answer_group("");
                            self.toggle_form(false);

                            alert.set('success', data['message']);
                            request.close(self.ajax.question_depreciate);
                        }
                    })
                );
            },

            /**
             * Events
             */
            events : function(){
                var self = this;

                //Fooltip - events
                fooltip.events();

                //Question Nav - add
                $(self.div.question_manager_nav).on('click', self.div.add, function(e) {
                    e.preventDefault();
                    self.toggle_form_restore_question(false);
                    self.toggle_form(true);
                    self.toggle_button_active(this);
                    self.reset_form(true);
                    self.refresh_question_selected("");
                    self.refresh_preview("");
                    self.toggle_question_restore(false);
                    self.toggle_remove_question(false);

                    //Set save button to add
                    $(self.div.add_edit_actions).find(self.div.save).text('Add');
                });

                //Question - restore
                $(self.div.question_manager_nav).on('click', self.div.restore, function(e) {
                    e.preventDefault();
                    self.toggle_form(true);
                    self.toggle_button_active(this);
                    self.reset_form(true);
                    self.refresh_question_selected("");
                    self.toggle_question_restore(true);
                    self.toggle_remove_question(false);

                    //Only show restore drop down until we have selected a question
                    self.toggle_form(false);
                    self.toggle_form_restore_question(true);

                    //Set save button to edit
                    $(self.div.add_edit_actions).find(self.div.save).text('Restore');
                });

                //Question Nav - edit
                $(self.div.question_manager_nav).on('click', self.div.edit, function(e) {
                    e.preventDefault();
                    //Does not actually need to do anything as edit is done from the select
                });

                //Question Nav - select
                $(self.div.question_manager_nav).on('change', 'select', function(e) {
                    e.preventDefault();
                    var selected = $(self.div.question).find('select option:selected');

                    self.toggle_form_restore_question(false);
                    self.toggle_form(true);
                    self.toggle_button_active($(self.div.question_manager_nav).find(self.div.edit));
                    self.reset_form(false);
                    self.toggle_question_restore(false);
                    self.toggle_remove_question(true);
                    self.populate_add_edit_question(selected.val(),false);
                    self.refresh_preview( $(self.div.question_selected).val() );

                    //Set save button to edit
                    $(self.div.add_edit_actions).find(self.div.save).text('Edit');
                });

                //Question Nav - restore select
                $(self.div.restore_question).on('change', self.div.input.question_restore, function(e) {
                    e.preventDefault();
                    if($(this).val()) {
                        //Show rest of the form now
                        self.toggle_form(true);

                        //Populate with data
                        self.populate_add_edit_question($(this).val(), true);
                    }else{
                        //Hide form if no question selected
                        self.toggle_form(false);
                        self.toggle_form_restore_question(true);
                    }
                });

                //Question - question required
                $(self.div.add_edit_question).on('change', self.div.input.question_required, function(e){
                    e.preventDefault();
                    var new_question = true;
                    var question_id = $(self.div.publisher_select).val();
                    var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );

                    if( $(this).val() != "" ) {
                        $.each(questions, function(index,question){
                            if( typeof question != "undefined") {
                                if (question['QUESTION_ID'] == question_id) {
                                    if (question['REQUIRED_QUESTION_ID'] == $(this).val()) {
                                        new_question = false;
                                    }
                                }
                            }
                        });

                        self.question_number_handler(true,$(self.div.input.question_required).val(),null,null,new_question);
                        self.toggle_answer_required($(this).parent().parent(),true);
                    }else{
                        if( question_id != ""){
                            new_question = false;
                        }

                        self.toggle_answer_required($(this).parent().parent(),false);
                        self.question_number_handler(true,null,null,null,new_question);
                    }
                });

                //Question - answer type
                $(self.div.add_edit_question).on('change', self.div.input.answer_type, function(e){
                    e.preventDefault();
                    if( $(this).val() == "SINGLE" || $(this).val() == "MANY" ) {
                        self.toggle_answer_group($(this).parent().parent(),true);
                    }else{
                        self.toggle_answer_group($(this).parent().parent(),false);
                    }
                });

                //Question - answer type
                $(self.div.add_edit_question).on('change', self.div.input.answer_group, function(e){
                    e.preventDefault();
                    self.toggle_answer_group_edit($(self.div.add_edit_answer_group).find(self.div.edit_group),true);
                    self.refresh_preview($(self.div.question_selected).val());
                });

                //Question - add answer group
                $(self.div.add_edit_question).on('click', self.div.add_group, function(e){
                    e.preventDefault();
                    self.toggle_answer_group_add(this,true);
                });

                //Question - add answer option
                $(self.div.add_edit_question).on('click', self.div.add_answer_option, function(e){
                    e.preventDefault();
                    $(self.div.option_add).parent().parent().before(source.html(self.template.answer_option({
                                ANSWER_OPTION_ID: null,
                                ANSWER: null
                            })
                        )
                    );
                });

                //Question - cancel
                $(self.div.add_edit_question).on('click', self.div.cancel, function(e) {
                    e.preventDefault();
                    self.toggle_form(false);
                    self.reset_form(true);
                    self.refresh_question_selected("");
                    self.toggle_button_active($(self.div.question_manager_nav).find('#edit'));
                });

                //Question - edit answer group
                $(self.div.add_edit_question).on('click', self.div.edit_group, function(e){
                    e.preventDefault();
                    self.toggle_answer_group_edit(this,true);
                });

                //Question - remove
                $(self.div.add_edit_question).on('click', self.div.remove_question, function(e){
                    e.preventDefault();
                    self.depreciate_question( $(self.div.question_selected).val() );
                });

                //Question - remove answer
                $(self.div.add_edit_question).on('click', self.div.remove_answer_option, function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                    self.refresh_preview( $(self.div.question_selected).val() );
                });

                //Question - question number
                $(self.div.add_edit_question).on('change', self.div.input.question_number, function(e) {
                    e.preventDefault();
                    var type = $(self.div.question_selected).val() != "" ? false : true;
                    self.question_number_handler(true,null,$(this).val(),null, type);
                });

                //Question - sub question number
                $(self.div.add_edit_question).on('change', self.div.input.question_sub_number, function(e) {
                    e.preventDefault();
                    var type = $(self.div.question_selected).val() != "" ? false : true;
                    self.question_number_handler(true,$(self.div.input.question_required).val(),null,$(this).val(),type);
                });

                //Question - question number reset
                $(self.div.add_edit_question).on('click', self.div.reset_question_number, function(e) {
                    e.preventDefault();

                    //Get selected question
                    var question_id = false;
                    if( $(self.div.restore_question).is(':hidden') ) {
                        question_id = $(self.div.question_selected).val();
                    }else{
                        question_id = $(self.div.restore_question).find(self.div.input.question_restore).val();
                    }

                    //Reset question number
                    if( question_id ) {
                        var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );
                        $.each( questions, function(index,question){
                            if( typeof question != 'undefined' ) {
                                if (question["QUESTION_ID"] == question_id) {
                                    self.question_number_handler(true, null, question["QUESTION_NUMBER"], null, false);
                                }
                            }
                        });
                    }else{
                        self.question_number_handler(true,null,null,null,true);
                    }
                });

                //Question - sub question number reset
                $(self.div.add_edit_question).on('click', self.div.reset_question_sub_number, function(e) {
                    e.preventDefault();

                    //Get selected question
                    var question_id = false;
                    if( $(self.div.restore_question).is(':hidden') ) {
                        question_id = $(self.div.question_selected).val();
                    }else{
                        question_id = $(self.div.restore_question).find(self.div.input.question_restore).val();
                    }

                    //Get question reduired id
                    var question_required_id = $(self.div.input.question_required).val();

                    //Reset question number
                    if( question_id ) {
                        $.each( self.question_manage, function(index,question){
                            if( typeof question != 'undefined' ) {
                                if (question["QUESTION_ID"] == question_id) {
                                    self.question_number_handler(true, question_required_id, null, question['QUESTION_SUB_NUMBER'], false);
                                }
                            }
                        });
                    }else{
                        self.question_number_handler(true,question_required_id,null,null,true);
                    }
                });

                //Question - save
                $(self.div.add_edit_question).on('click', self.div.save, function(e) {
                    e.preventDefault();
                    self.add_edit_question();
                });

                //Preview
                $(self.div.add_edit_question).on('change', 'input', function(){
                    self.refresh_preview( $(self.div.question_selected).val() );
                });

                //Preview
                $(self.div.add_edit_question).on('change', 'select', function(){
                    self.refresh_preview( $(self.div.question_selected).val() );
                });

            },

            /**
             * Question Manager
             */
            generate_question_manager : function() {
                var self = this;
                var html = null;
                var state = $.Deferred();

                self.question_manage = [];
                self.question_manage_depreciated = [];
                request.open(self.ajax.questions,
                    $.getJSON(self.ajax.questions, function (data){
                        $.each( data, function(key,row){
                            if(row['DEPRECIATED'] == 1) {
                                self.question_manage_depreciated[key] = row;
                            }else{
                                self.question_manage[key] = row;
                            }
                        });
                    request.close(self.ajax.questions);
                    request.open(self.ajax.answer_groups,
                        $.getJSON(self.ajax.answer_groups, function (data) {
                        self.question_manage_answer_groups = data;
                        request.close(self.ajax.answer_groups);
                        request.open(self.ajax.question_tags,
                        $.getJSON(self.ajax.question_tags, function (data) {
                            self.question_manage_tags = data;
                            request.close(self.ajax.question_tags);
                            request.open(self.ajax.answer_options,
                            $.getJSON(self.ajax.answer_options, function (data) {
                                self.question_manage_answer_options = data;
                                html = source.html(self.template.question({
                                        questions: self.question_manage
                                    })
                                );

                                //Include depreciated questions as parent questions for editing/restore purposes
                                var parent_questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );
                                html += source.html(self.template.form({
                                        parent_questions: parent_questions,
                                        depreciated: self.question_manage_depreciated,
                                        answer_groups: self.question_manage_answer_groups,
                                        answer_options: self.question_manage_answer_options
                                    })
                                );

                                state.resolve(html);

                                //Hide Remove Question
                                self.toggle_remove_question(false);

                                //Reset Sliders
                                self.slider = {};

                                //Question Number's
                                self.question_number_handler(true,null,null,null,true);

                                //Hide Question Restore
                                self.toggle_question_restore(false);

                                //Chosen JS
                                $(self.div.question).find('select').chosen({allow_single_deselect: true});
                                $(self.div.input.question_required).chosen({allow_single_deselect: true});
                                $(self.div.input.question_restore).chosen({allow_single_deselect: true});
                                $(self.div.input.answer_required).chosen({allow_single_deselect: true});
                                $(self.div.input.answer_type).chosen({allow_single_deselect: true});
                                $(self.div.input.answer_group).chosen({allow_single_deselect: true});

                                $(self.div.input.answer_required).parent().hide();
                                $(self.div.answer_group).parent().parent().parent().hide();
                                $(self.div.add_edit_answer_group + ' table').hide();

                                //Trigger refresh for sorting purposes
                                self.refresh_question_selected("");
                                self.refresh_question_required("");
                                self.refresh_question_restore("");
                                self.refresh_answer_group("");
                                self.refresh_preview("");

                                request.close(self.ajax.answer_options);
                            }));
                        }));
                    }));
                }));

                return $.when(state).done().promise();
            },

            /**
             * Init
             */
            init : function(){
                var self = this;

                //Templates
                self.template['question'] = handlebars.compile(question);
                self.template['preview'] = handlebars.compile(preview);
                self.template['spinner'] = handlebars.compile(spinner);
                self.template['form'] = handlebars.compile(form);
                self.template['answer_option'] = handlebars.compile(answer_option);
                self.template['option'] = handlebars.compile(option);

                //Swag Helper
                Swag.registerHelpers(handlebars);

                //Initialize fooltips
                fooltip.init('#publisher_tool');

                return self.generate_question_manager();
            },

            /**
             * Populate add edit question
             * @param question_id
             * @param depreciated
             */
            populate_add_edit_question : function( question_id, depreciated ){
                var self = this;
                var type = depreciated ? self.question_manage_depreciated : self.question_manage;
                $.each( type,function(index, question){
                    if( typeof question != 'undefined' ) {
                        if (question['QUESTION_ID'] == question_id) {
                            //QUESTION
                            $(self.div.add_edit_question).find(self.div.input.question).val(question['QUESTION_TITLE']);

                            //QUESTION NUMBER's
                            self.question_number_handler(true, question['REQUIRES_QUESTION_ID'], question['QUESTION_NUMBER'], question['QUESTION_SUB_NUMBER'], false);

                            //TAGS
                            var tags = '';
                            $.each(self.question_manage_tags, function (key, tag) {
                                if (typeof tag != 'undefined') {
                                    if (tag['QUESTION_ID'] == question_id) {
                                        tags += ( tags == '' ) ? tag['TAG'] : ', ' + tag['TAG'];
                                    }
                                }
                            });

                            $(self.div.add_edit_question).find(self.div.input.question_tags).val(tags);

                            //REQUIRES QUESTION/ANSWER
                            if (question['REQUIRES_QUESTION_ID'] != null && question['REQUIRES_QUESTION_ID'] != 0) {
                                $(self.div.add_edit_question).find(self.div.input.question_required).val(question['REQUIRES_QUESTION_ID']);
                                self.toggle_answer_required($(self.div.add_edit_question).find('ul'), true);
                                $(self.div.add_edit_question).find(self.div.input.answer_required).val(question['REQUIRES_ANSWER_ID']);
                            } else {
                                $(self.div.add_edit_question).find(self.div.input.question_required).val('');
                                self.toggle_answer_required($(self.div.add_edit_question).find('ul'), false);
                            }

                            //ANSWER TYPE
                            $(self.div.add_edit_question).find(self.div.input.answer_type).val(question['ANSWER_TYPE']);

                            //ANSWER GROUP
                            $(self.div.add_edit_question).find(self.div.input.answer_group).val(question['ANSWER_GROUP_ID']);

                            if (question['ANSWER_GROUP_ID'] != null && question['ANSWER_GROUP_ID'] != 0) {
                                self.toggle_answer_group($(self.div.add_edit_question).find('ul'), true);
                                self.toggle_answer_group_edit($(self.div.add_edit_answer_group).find(self.div.edit_group), true);
                            } else {
                                self.toggle_answer_group($(self.div.add_edit_question).find('ul'), false);
                                self.toggle_answer_group_edit($(self.div.add_edit_answer_group).find(self.div.edit_group), false);
                            }

                            //Buttons
                            $(self.div.add_edit_question + ' ' + self.div.add_edit_answer_group + ' .blue').removeClass('blue');
                            $(self.div.add_edit_question + ' ' + self.div.add_edit_answer_group + ' ' + self.div.edit_group).addClass('blue');

                            //Chosen
                            $(self.div.add_edit_question).find(self.div.input.question_required).trigger("chosen:updated");
                            $(self.div.add_edit_question).find(self.div.input.answer_required).trigger("chosen:updated");
                            $(self.div.add_edit_question).find(self.div.input.answer_type).trigger("chosen:updated");
                            $(self.div.add_edit_question).find(self.div.input.answer_group).trigger("chosen:updated");
                        }
                    }
                });
            },

            /**
             * Question Number Handler
             * @param show
             * @param parent_question_id
             * @param number
             * @param sub_number
             * @param new_question
             */
            question_number_handler : function( show, parent_question_id, number, sub_number, new_question ){
                var self = this;
                var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );

                if( parent_question_id ) {
                    if( sub_number ) {
                        self.question_sub_number(show,parent_question_id,sub_number,false);
                    }else if( !new_question ){
                        $.each( questions,function(index, question){
                            if( typeof question != 'undefined' ) {
                                if (question['QUESTION_ID'] == parseInt( $(self.div.input.question_required).val() ) ) {
                                    self.question_sub_number(show, parent_question_id, question['QUESTION_SUB_NUMBER'], false);
                                }
                            }
                        });
                    }else{
                        self.question_sub_number(show,parent_question_id,null,true);
                    }
                }else {
                    if( number ) {
                        self.question_number(show, number, false);
                    }else if( !new_question ){
                        $.each( questions,function(index, question){
                            if( typeof question != 'undefined' ) {
                                if (question['QUESTION_ID'] == $(self.div.question_selected).val()) {
                                    self.question_number(show, question['QUESTION_NUMBER'], false);
                                }
                            }
                        });
                    }else {
                        self.question_number(show,number,true);
                    }
                }

                self.refresh_preview( $(self.div.question_selected).val() );
            },

            /**
             * Question Number
             * @param show
             * @param value
             * @param new_question
             */
            question_number : function( show, value, new_question ){
                var self = this;

                //Get max question number
                var max_question_number = 1;
                var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );

                $.each(questions, function(index,obj){
                    if( typeof obj != 'undefined' ) {
                        if ( parseInt(obj["QUESTION_NUMBER"]) > max_question_number) {
                            max_question_number = parseInt(obj["QUESTION_NUMBER"]);
                        }
                    }
                });

                //Plus 1 for new question position
                if( new_question ){
                    max_question_number = max_question_number + 1;
                    value = max_question_number;
                }

                //Animate
                if(show && $(self.div.input.slider.question_number).parent().parent().parent().is(':hidden')) {
                    $(self.div.input.slider.question_sub_number).parent().parent().parent().slideUp();
                    $(self.div.input.question_number).parent().parent().parent().slideDown();
                }

                //Set sub question
                $(self.div.input.question_sub_number).val("");

                //Set starting value
                $(self.div.input.slider.question_number).parent().parent().find(self.div.output).val(value);

                //Slider
                if(!self.slider.question_number) {
                    $(self.div.input.slider.question_number).simpleSlider({
                        range: [1, max_question_number],
                        step: 1,
                        snap: true
                    }).bind("slider:ready slider:changed", function (event, data) {
                        $(this).parent().parent().find(self.div.output).val(data.value.toFixed(0));
                        self.refresh_preview( $(self.div.question_selected).val() );
                    });

                    self.slider.question_number = $(self.div.input.slider.question_number).data("slider-object");
                    self.slider.question_number.setValue(value);
                }else{
                    self.slider.question_number.settings.range = [1,max_question_number];
                    self.slider.question_number.setValue(value);
                }
            },

            /**
             * Question Sub Number
             * @param show
             * @param parent_question_id
             * @param value
             * @param new_question
             */
            question_sub_number : function( show, parent_question_id, value, new_question ){
                var self = this;

                //Get max question number
                var parent_number = null;
                var max_question_sub_number = null;
                var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );

                $.each(questions, function (index, value) {
                    if( typeof value != 'undefined' ) {
                        if ( parseInt(value["QUESTION_ID"]) == parent_question_id) {
                            parent_number = parseInt(value["QUESTION_NUMBER"]);
                        }

                        if (parseInt(value["REQUIRES_QUESTION_ID"]) == parent_question_id) {
                            if (parseInt(value["QUESTION_SUB_NUMBER"]) > max_question_sub_number) {
                                max_question_sub_number = parseInt(value["QUESTION_SUB_NUMBER"]);
                            }
                        }
                    }
                });

                //Plus 1 for new question position
                if (new_question) {
                    max_question_sub_number = max_question_sub_number + 1;
                    value = max_question_sub_number;
                }

                //Set parent question
                $(self.div.input.question_number).val(parent_number);

                //Animate
                if(show && $(self.div.input.slider.question_sub_number).parent().parent().parent().is(':hidden') ){
                    $(self.div.input.question_number).parent().parent().parent().slideUp();
                    $(self.div.input.slider.question_sub_number).parent().parent().parent().slideDown();
                }

                //Set starting value
                $(self.div.input.slider.question_sub_number).parent().parent().find(self.div.output).val(value);

                //Slider
                if (!self.slider.question_sub_number) {
                    $(self.div.input.slider.question_sub_number).simpleSlider({
                        range: [0, max_question_sub_number],
                        step: 1,
                        snap: true
                    }).bind("slider:ready slider:changed", function (event, data) {
                        $(this).parent().parent().find(self.div.output).val(data.value.toFixed(0));
                        self.refresh_preview( $(self.div.question_selected).val() );
                    });

                    self.slider.question_sub_number = $(self.div.input.slider.question_sub_number).data("slider-object");
                    self.slider.question_sub_number.setValue(value);
                } else {
                    self.slider.question_sub_number.settings.range = [0, max_question_sub_number];
                    self.slider.question_sub_number.setValue(value);
                }
            },

            /**
             * Refresh Question Selected
             * @param val
             */
            refresh_question_selected : function( val ){
                var self = this;
                $(self.div.question_manager_nav).find(self.div.question + ' select').empty().append( source.html(self.template.option({
                        VALUE: null,
                        TEXT: null
                    })
                ));

                $.each(self.question_manage.sort(self.sort_by_question_numbers),function(index,value){
                    if( typeof value != 'undefined' ) {
                        $(self.div.question + ' select').append(source.html(self.template.option({
                                VALUE: value['QUESTION_ID'],
                                TEXT: value['QUESTION_TITLE']
                            })
                        ));
                    }
                });

                $(self.div.question).find('select').val( val );
                $(self.div.question).find('select').trigger('chosen:updated');
            },

            /**
             * Refresh Question Required
             * @param val
             */
            refresh_question_required : function( val ){
                var self = this;
                $(self.div.input.question_required).empty().append(source.html(self.template.option({
                        VALUE: null,
                        TEXT: null
                    })
                ));

                var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );
                $.each(questions.sort(self.sort_by_question_numbers),function(index,value){
                    if( typeof value != 'undefined' ) {
                        $(self.div.input.question_required).append(source.html(self.template.option({
                                VALUE: value['QUESTION_ID'],
                                TEXT: ( value['DEPRECIATED'] == 1 ) ? value['QUESTION_TITLE'] + ' [DEPRECIATED]' : value['QUESTION_TITLE']
                            })
                        ));
                    }
                });

                $(self.div.input.question_required).val( val );
                $(self.div.input.question_required).trigger('chosen:updated');
            },

            /**
             * Refresh Question Restore
             * @param val
             */
            refresh_question_restore : function( val ){
                var self = this;
                $(self.div.input.question_restore).empty().append(source.html(self.template.option({
                        VALUE: null,
                        TEXT: null
                    })
                ));

                $.each(self.question_manage_depreciated.sort(self.sort_by_question_numbers),function(index,value){
                    if( typeof value != 'undefined' ) {
                        $(self.div.input.question_restore).append(source.html(self.template.option({
                                VALUE: value['QUESTION_ID'],
                                TEXT: value['QUESTION_TITLE']
                            })
                        ));
                    }
                });

                $(self.div.input.question_restore).val( val );
                $(self.div.input.question_restore).trigger('chosen:updated');
            },

            /**
             * Refresh Answer Group
             * @param val
             */
            refresh_answer_group : function( val ){
                var self = this;
                $(self.div.question_manage_answer_groups).empty().append(source.html(self.template.option({
                        VALUE: null,
                        TEXT: null
                    })
                ));

                $.each(self.question_manage_answer_groups, function (index, row) {
                    if( typeof row != 'undefined' ) {
                        $(self.div.input.answer_group).append(source.html(self.template.option({
                                VALUE: row['ANSWER_GROUP_ID'],
                                TEXT: row['ANSWER_GROUP_NAME']
                            })
                        ));
                    }
                });

                $(self.div.input.answer_group).val( val );
                $(self.div.input.answer_group).trigger('chosen:updated');
            },

            /**
             * Refresh Answer Group Options
             * @param answer_group_id
             * @param val
             */
            refresh_answer_group_options : function( answer_group_id, val ){
                var self = this;
                $(self.div.question_manage_answer_options).empty().append(source.html(self.template.option({
                        VALUE: null,
                        TEXT: null
                    })
                ));

                $.each(self.question_manage_answer_options, function(index,row){
                    if( typeof row != 'undefined' ) {
                        if (answer_group_id == row['ANSWER_GROUP_ID']) {
                            $(self.div.input.answer_group_option).append(source.html(self.template.option({
                                    VALUE: row['ANSWER_OPTION_ID'],
                                    TEXT: row['ANSWER']
                                })
                            ));
                        }
                    }
                });

                $(self.div.input.question_manage_answer_options).val( val );
                $(self.div.input.question_manage_answer_options).trigger('chosen:updated');
            },

            /**
             * Refresh Preview
             * @param question_id
             */
            refresh_preview : function(question_id){
                var self = this;
                var active_question = false;
                var question_number_offset = {};
                var question_sub_number_offset = {};

                //Get the latest question option answers
                var questions = [];
                $.each(self.question_manage, function(index,question){
                    if( typeof question != 'undefined' ) {
                        var options = [];
                        $.each(self.question_manage_answer_options, function (index,option) {
                            if (typeof option != 'undefined') {
                                if (question['ANSWER_GROUP_ID'] == option['ANSWER_GROUP_ID']) {
                                    options.push(option);
                                }
                            }
                        });

                        //If question active get latest details from form otherwise populate with data from array
                        if( question['QUESTION_ID'] == question_id ){
                            active_question = true;

                            //Override options with options on form
                            options = [];
                            $(self.div.input.answer_group_option).each( function(index,obj){
                                if( !$(self.div.input.answer_group).parent().is(':hidden') ) {
                                    options.push({
                                        ANSWER: $(obj).val(),
                                        ANSWER_GROUP_ID: $(self.div.input.answer_group).val(),
                                        ANSWER_OPTION_ID: $(obj).data('id'),
                                        DEPRECIATED: 0
                                    });
                                }
                            });

                            //Check for question number change?
                            if( $(self.div.input.question_sub_number).val() == "" &&
                                    $(self.div.input.question_number).val() != question['QUESTION_NUMBER'] ) {
                                question_number_offset = {
                                    question_id : question['QUESTION_ID'],
                                    old : question['QUESTION_NUMBER'],
                                    new : parseInt($(self.div.input.question_number).val())
                                };
                            }

                            //Check for question sub number change?
                            if( $(self.div.input.question_sub_number).val() != "" &&
                                $(self.div.input.question_sub_number).val() != question['QUESTION_SUB_NUMBER'] ) {
                                question_sub_number_offset = {
                                    question_id : question['QUESTION_ID'],
                                    old : question['QUESTION_SUB_NUMBER'],
                                    new : parseInt($(self.div.input.question_sub_number).val())
                                }
                            }

                            questions.push({
                                ACTIVE: true,
                                ANSWER_GROUP_ID: $(self.div.input.answer_group).val(),
                                ANSWER_TYPE: $(self.div.input.answer_type).val(),
                                QUESTION_ID: $(self.div.question_selected).val(),
                                QUESTION_NUMBER: parseInt($(self.div.input.question_number).val()),
                                QUESTION_SUB_NUMBER: parseInt($(self.div.input.question_sub_number).val()),
                                QUESTION_TITLE: $(self.div.input.question).val(),
                                REQUIRES_ANSWER_ID: $(self.div.input.question_required_answer).val(),
                                REQUIRES_QUESTION_ID: $(self.div.input.question_required).val(),
                                DEPRECIATED: 0,
                                options: options
                            });
                        }else {
                            questions.push({
                                ACTIVE: false,
                                ANSWER_GROUP_ID: question['ANSWER_GROUP_ID'],
                                ANSWER_TYPE: question['ANSWER_TYPE'],
                                QUESTION_ID: question['QUESTION_ID'],
                                QUESTION_NUMBER: question['QUESTION_NUMBER'],
                                QUESTION_SUB_NUMBER: question['QUESTION_SUB_NUMBER'],
                                QUESTION_TITLE: question['QUESTION_TITLE'],
                                REQUIRES_ANSWER_ID: question['REQUIRES_ANSWER_ID'],
                                REQUIRES_QUESTION_ID: question['REQUIRES_QUESTION_ID'],
                                DEPRECIATED: question['DEPRECIATED'],
                                options: options
                            });
                        }
                    }
                });

                //Are we adding a new question?
                if( !active_question && !$(self.div.add_edit_question).is(':hidden') ) {
                    active_question = true;

                    //Override options with options on form
                    var options = [];
                    $(self.div.input.answer_group_option).each( function(index,obj){
                        if( !$(self.div.input.answer_group).parent().is(':hidden') ) {
                            options.push({
                                ANSWER: $(obj).val(),
                                ANSWER_GROUP_ID: $(self.div.input.answer_group).val(),
                                ANSWER_OPTION_ID: $(obj).data('id'),
                                DEPRECIATED: 0
                            });
                        }
                    });

                    //Check for question number change?
                    if( !$(self.div.input.question_number).is(':hidden') ) {
                        question_number_offset = {
                            question_id : null,
                            old : null,
                            new : parseInt($(self.div.input.question_number).val())
                        };
                    }

                    //Check for question sub number change?
                    if( !$(self.div.input.question_sub_number).is(':hidden') ) {
                        question_sub_number_offset = {
                            question_id : null,
                            old : null,
                            new : parseInt($(self.div.input.question_sub_number).val())
                        }
                    }

                    questions.push({
                        ACTIVE: true,
                        ANSWER_GROUP_ID: $(self.div.input.answer_group).val(),
                        ANSWER_TYPE: $(self.div.input.answer_type).val(),
                        QUESTION_ID: $(self.div.question_selected).val(),
                        QUESTION_NUMBER: parseInt($(self.div.input.question_number).val()),
                        QUESTION_SUB_NUMBER: parseInt($(self.div.input.question_sub_number).val()),
                        QUESTION_TITLE: $(self.div.input.question).val(),
                        REQUIRES_ANSWER_ID: $(self.div.input.question_required_answer).val(),
                        REQUIRES_QUESTION_ID: $(self.div.input.question_required).val(),
                        DEPRECIATED: 0,
                        options: options
                    });
                }

                //Question number offset?
                $.each(questions, function(index,question) {
                    if (!$.isEmptyObject(question_number_offset)) {
                        if ( question_number_offset.question_id != null &&
                                (question['REQUIRES_QUESTION_ID'] == question_number_offset.question_id) ) {
                            question['QUESTION_NUMBER'] = question_number_offset.new;
                        }else if( !question['ACTIVE'] ) {
                            if( question_number_offset.old != null ) {
                                if (question['QUESTION_NUMBER'] > 0 ){
                                    if(question['QUESTION_NUMBER'] > question_number_offset.old ){
                                        questions[index]['QUESTION_NUMBER'] = question['QUESTION_NUMBER'] - 1;
                                    }
                                }
                            }

                            if (question['QUESTION_NUMBER'] >= question_number_offset.new) {
                                questions[index]['QUESTION_NUMBER'] = questions[index]['QUESTION_NUMBER'] + 1;
                            }
                        }
                    }
                });

                //Question sub number offset?
                $.each(questions, function(index,question) {
                    if (!$.isEmptyObject(question_sub_number_offset)) {
                        if( !question['ACTIVE'] ) {
                            if( question_sub_number_offset.old != null ) {
                                if (question['QUESTION_SUB_NUMBER'] > 0 ){
                                    if(question['QUESTION_SUB_NUMBER'] > question_sub_number_offset.old ){
                                        questions[index]['QUESTION_SUB_NUMBER'] = question['QUESTION_SUB_NUMBER'] - 1;
                                    }
                                }
                            }

                            if (question['QUESTION_SUB_NUMBER'] >= question_sub_number_offset.new) {
                                questions[index]['QUESTION_SUB_NUMBER'] = question['QUESTION_SUB_NUMBER'] + 1;
                            }
                        }
                    }
                });

                //Populate preview with questions
                $(self.div.add_edit_question).find(self.div.preivew).empty().append( source.html(
                    self.template.preview({
                        questions : questions.sort(self.sort_by_question_numbers)
                    })
                ));

                //Scroll to active question
                var position_parent = $(self.div.add_edit_question).find(self.div.preivew).offset();
                var position_child = $(self.div.add_edit_question).find(self.div.preivew + ' .active').offset();
                var position_current = $(self.div.add_edit_question).find(self.div.preivew).scrollTop();

                if( typeof position_parent != 'undefined' && typeof position_child != 'undefined' ) {
                    var position = ( ( position_child.top + position_current ) - position_parent.top );
                    $(self.div.add_edit_question).find(self.div.preivew).stop(true, true).animate({scrollTop: position}, 1000);
                }
            },

            /**
             * Reset Answer Options
             */
            reset_answer_options : function(){
                var self = this;
                $(self.div.input.answer_group_option).each(function (index, obj) {
                    $(obj).parent().parent().remove();
                });
            },

            /**
             * Reset Form
             */
            reset_form : function(new_question){
                var self = this;
                $.each(self.div.input, function(index, input){
                    $(input).val("");
                });

                $(self.div.input.question_required).trigger('chosen:updated');
                $(self.div.input.answer_required).trigger('chosen:updated');
                $(self.div.input.answer_type).trigger('chosen:updated');
                $(self.div.input.answer_group).trigger('chosen:updated');

                $(self.div.input.answer_required).parent().hide();
                $(self.div.add_edit_answer_group).hide();

                //If new question show question number and hide sub question number
                if(new_question) {
                    self.question_number_handler(true,null,null,null,new_question);
                }else{
                    var show = $(self.div.input.slider.question_sub_number).parent().parent().parent().is(':hidden');
                    self.question_number_handler(show,null,null,null,new_question);
                }
            },

            sort_by_question_numbers : function(a, b){
                //Sort by depreciated
                var depreciated_a = parseInt( a['DEPRECIATED'] );
                var depreciated_b = parseInt( b['DEPRECIATED'] );

                if(depreciated_a < depreciated_b){
                    return -1;
                }else if(depreciated_a > depreciated_b){
                    return 1;
                }else if(depreciated_a == depreciated_b){

                    //Sort by number
                    var number_a = parseInt( a['QUESTION_NUMBER'] );
                    var number_b = parseInt( b['QUESTION_NUMBER'] );

                    if(number_a < number_b){
                        return -1;
                    }else if(number_a > number_b){
                        return 1;
                    }else if(number_a == number_b) {

                        //Sort by sub number
                        var sub_number_a = a['QUESTION_SUB_NUMBER'] == null ? 0 : parseInt( a['QUESTION_SUB_NUMBER'] );
                        var sub_number_b = b['QUESTION_SUB_NUMBER'] == null ? 0 : parseInt( b['QUESTION_SUB_NUMBER'] );

                        if (sub_number_a < sub_number_b) {
                            return -1;
                        } else if (sub_number_a > sub_number_b) {
                            return 1;
                        }else if (sub_number_a == sub_number_b) {

                            //Sort by parent question_id
                            var parent_question_a = a['REQUIRES_QUESTION_ID'] == null ? 0 : parseInt( a['REQUIRES_QUESTION_ID'] );
                            var parent_question_b = b['REQUIRES_QUESTION_ID'] == null ? 0 : parseInt( b['REQUIRES_QUESTION_ID'] );

                            if (parent_question_a < parent_question_b) {
                                return -1;
                            } else if (parent_question_a > parent_question_b) {
                                return 1;
                            }
                        }
                    }
                }
                return 0;
            },

            /**
             * Toggle Answer Group
             * @param div
             * @param show
             */
            toggle_answer_group : function(div, show){
                var self = this;
                if( show ) {
                    $(div).find(self.div.add_edit_answer_group).slideDown();
                    $(div).find('table').hide();
                }else{
                    $(div).find(self.div.add_edit_answer_group).slideUp();
                }
            },

            /**
             * Toggle Answer Group Add
             * @param div
             * @param show
             */
            toggle_answer_group_add : function(div, show){
                var self = this;
                if( show ) {
                    //Hide answer group edit
                    self.toggle_answer_group_edit($(self.div.add_edit_answer_group).find(self.div.edit_group),false);

                    //Highlight add button
                    self.toggle_button_active(div);

                    //Add blank answer option
                    $(self.div.option_add).parent().parent().before(source.html(self.template.answer_option({
                                ANSWER_OPTION_ID: null,
                                ANSWER: null
                            })
                        )
                    );

                    $(div).parent().parent().parent().find('table').fadeIn();
                }else{
                     //Reset answer options
                    self.reset_answer_options();
                }
            },

            /**
             * Toggle Answer Group Edit\
             * @param div
             * @param show
             */
            toggle_answer_group_edit : function(div, show){
                var self = this;
                if( show ) {
                    //Hide answer group add
                    self.toggle_answer_group_add($(self.div.add_edit_answer_group).find(self.div.add_group),false);

                    //Highlight edit button
                    self.toggle_button_active(div);

                    //Answer group name
                    $(self.div.add_edit_answer_group).find(self.div.input.answer_group_name).val($(self.div.input.answer_group + ' :selected').text());

                    //New answer options
                    $.each(self.question_manage_answer_options, function (index, obj) {
                        if( typeof obj != 'undefined' ) {
                            if (obj['ANSWER_GROUP_ID'] == $(self.div.input.answer_group).val()) {
                                $(self.div.option_add).parent().parent().before(source.html(self.template.answer_option({
                                        ANSWER_OPTION_ID: obj['ANSWER_OPTION_ID'],
                                        ANSWER: obj['ANSWER']
                                    })
                                    )
                                );
                            }
                        }
                    });

                    $(div).parent().parent().parent().find('table').fadeIn();
                }else{
                    //Reset answer group select
                    $(self.div.add_edit_answer_group).find(self.div.input.answer_group).val("");
                    $(self.div.add_edit_answer_group).find(self.div.input.answer_group).trigger('chosen:updated');

                    //Reset answer group name
                    $(self.div.add_edit_answer_group).find(self.div.input.answer_group_name).val("");

                    //Reset answer options
                    self.reset_answer_options();
                }
            },

            /**
             * Toggle Answer Required
             * @param div
             * @param show
             */
            toggle_answer_required : function(div, show){
                var self = this;
                if( show ) {
                    //Populate options for required answer
                    $(self.div.input.answer_required).empty().append('<option value=""></option>');

                    //Include depreciated questions here for restore/editting purposes
                    var questions = $.merge( $.merge( [], self.question_manage ), self.question_manage_depreciated );
                    $.each( questions,function(key, requires_question) {
                        if( typeof requires_question != 'undefined' ) {
                            if ($(self.div.input.question_required).val() == requires_question['QUESTION_ID']) {
                                $.each(self.question_manage_answer_options, function (key, requires_answer_option) {
                                    if (requires_question['ANSWER_GROUP_ID'] == requires_answer_option['ANSWER_GROUP_ID']) {
                                        $(self.div.input.answer_required).append("<option value='" + requires_answer_option['ANSWER_OPTION_ID'] + "'>" + requires_answer_option['ANSWER'] + "</option>");
                                    }
                                });
                            }
                        }
                    });

                    $(div).find(self.div.input.answer_required).hide();
                    $(div).find(self.div.input.answer_required).trigger("chosen:updated");
                    $(div).find(self.div.input.answer_required).parent().slideDown(function(){
                        self.refresh_preview($(self.div.question_selected).val());
                    });
                }else{
                    $(div).find(self.div.input.answer_required).parent().slideUp(function(){
                        self.refresh_preview($(self.div.question_selected).val());
                    });
                }
            },

            /**
             * Toggle Button Active
             * @param div
             */
            toggle_button_active : function(div){
                $(div).parent().parent().find('.blue').removeClass('blue');
                $(div).addClass('blue');
            },

            /**
             * Toggle Form
             */
            toggle_form : function(show){
                var self = this;
                if( show ){
                    $(self.div.add_edit_question).show();
                }else{
                    $(self.div.add_edit_question).hide();
                }
            },

            /**
             * Toggle Form Restore Question
             * @param show
             */
            toggle_form_restore_question : function(show){
                var self = this;
                if(show){
                    $(self.div.restore_question).show();
                }else{
                    $(self.div.restore_question).hide();
                }
            },

            /**
             * Toggle Question Restore
             * @param show
             */
            toggle_question_restore : function(show){
                var self = this;
                if( show ){
                    $(self.div.input.question_restore).parent().show();
                }else{
                    $(self.div.input.question_restore).parent().hide();
                }
            },

            /**
             * Remove Question
             * @param show
             */
            toggle_remove_question : function(show){
                var self = this;
                if (show) {
                    if($(self.div.remove_question).is(':hidden')) {
                        $(self.div.add_edit_question).find(self.div.input.question).parent().animate({width: '85%'}, function () {
                            $(self.div.add_edit_question).find(self.div.remove_question).parent().show();
                        });
                    }
                }else{
                    $(self.div.add_edit_question).find(self.div.remove_question).parent().hide();
                    $(self.div.add_edit_question).find(self.div.input.question).parent().css('width','100%');
                }
            },

            /**
             * Toggle Spinner
             */
            toggle_spinner : function(show){
                var self = this;
                if(show){
                    $(self.div.preivew).find('table').addClass('blur');
                    $(self.div.preivew).find('table').before(source.html(self.template.spinner()));
                }else{
                    $(self.div.preivew).find('table').removeClass('blur');
                    $(self.div.preivew).find('.spinner').remove();
                }
            }
        }
    }
);