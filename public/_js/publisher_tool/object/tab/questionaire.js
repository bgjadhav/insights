/**
 * Questionaire.js
 * @created 20/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define([ 'Alert',
         'Chosen',
         'Request',
         'Table',
         'Handlebars',
         'Source',
         'Fooltip',
         'Publisher',
         'Text!Templates/publisher/publisher.handlebars',
         'Text!Templates/questionaire/questionaire.handlebars',
         'Text!Templates/questionaire/note/note.handlebars',
         'Text!Templates/questionaire/note/count.handlebars'],
    function(alert,
             chosen,
             request,
             table,
             handlebars,
             source,
             fooltip,
             publisher,
             publisher_select,
             questionaire,
             note,
             note_count){
    return {

        //Ajax
        ajax : {
            add_edit_answers : 'publisher_database/add_edit_answers',
            add_edit_note : 'publisher_database/add_edit_note',
            answers : 'publisher_database/answers',
            publisher : 'publisher_database/publishers',
            questions : 'publisher_database/questions',
            remove_note : 'publisher_database/remove_note'
        },

        //Div
        div : {
            actions : '.actions',
            active : '.active',
            add_answers : '#add-answers',
            add_note : '.add_note',
            add_note_by_id : '#add-note',
            answer : '.answer',
            attr : {
                answer_option_id : 'data-answer-option-id',
                count_id : 'data-count-id',
                edit_note_id : 'data-edit-note-id',
                id : 'data-id',
                note_id : 'data-note-id',
                note_question_id : 'data-note-question-id',
                publisher_id : 'publisher-id',
                question_title : 'data-question_title',
                readonly : 'readonly',
                type : 'type'
            },
            cell : '.cell',
            decimal : '.decimal',
            edit : '.edit',
            filter : '.filter',
            option : '.option',
            input : {
                button : 'input[name^=button]',
                decimal : 'input[name^=decimal]',
                question_id : '[name="question_id"]',
                string : 'input[name^=string]'
            },
            name : '.name',
            note : '.note',
            note_count : '.note_count',
            publisher : '.publisher',
            publisher_select : '.publisher select',
            publisher_selected : '.publisher select option:selected',
            publisher_logo : '.publisher .logo',
            publisher_name : '.publisher .name',
            publisher_nav : '#publisher-nav',
            questionaire : '#questionaire.display-table',
            question : '.question',
            question_select : 'input:not(.select)',
            remove : '.remove',
            save : '#save',
            save_note : '#save-note',
            save_note_close : '#save-note-close',
            select : '.select',
            select_all : '#select-all',
            string : '.string',
            text : '#text',
            toggle : '.toggle',
            unanswered : '.unanswered'
        },

        //Template
        template : {},

        //Question
        question : {},

        /**
         * Add Edit Note
         * @param question_id
         * @param publisher_id
         * @param note_id
         * @param note_text
         */
        add_edit_note : function( question_id, publisher_id, note_id, note_text ){
            var self = this;

            if(!request.running(self.ajax.add_edit_note)) {
                table.toggle_notification(self.div.questionaire,'Saving your changes',true);

                request.open(self.ajax.add_edit_note,
                    $.post(self.ajax.add_edit_note, {
                        note_id: note_id,
                        question_id: question_id,
                        publisher_id: publisher_id,
                        note: note_text
                    }, function (data) {
                        if (data['status'] == true) {
                            var note_id = data['note_id'];
                            var note = $('['+self.div.attr.note_id+'="' + note_id + '"]');
                            var question = $('['+self.div.attr.id+'="' + question_id + '"]');

                            //Do we already have a note which we need to update?
                            if (note.length > 0) {
                                note.find('#text').text(note_text);
                                alert.set('success', 'Your note has been updated');
                            } else {
                                //Remove old count
                                $('['+self.div.attr.count_id+'="' + question_id + '"]').remove();
                                var notes = $('['+self.div.attr.note_question_id+'="' + question_id + '"]');

                                //Hide all old notes
                                notes.find('.cell').css('display', 'none');

                                //Note count
                                var html = source.html( self.template.note_count({
                                        QUESTION_ID: question_id,
                                        NOTES: ( notes.length + 1 ),
                                        TYPE: ( ( notes.length + 1 ) > 1 ) ? "notes" : "note"
                                    })
                                );

                                //Display notes for this question
                                html += source.html( self.template.note({
                                        QUESTION_ID: question_id,
                                        ANSWER_NOTE_ID: note_id,
                                        NOTE: note_text,
                                        USER: data['user'],
                                        DATE: data['date']
                                    })
                                );

                                question.next('.add_note').after(html);

                                alert.set('success', 'Your note has been added');
                            }

                            //Reset
                            question.next(self.div.add_note).find('textarea').val('');
                            question.next(self.div.add_note).attr(self.div.attr.edit_note_id, '');
                        } else {
                            alert.set('error', data['error']);
                        }
                        table.toggle_notification(self.div.questionaire,null,false);
                        request.close(self.ajax.add_edit_note);
                    })
                );
            }
        },

        /**
         * Disable Question
         * @param id
         */
        disable_question : function(id) {
            var self = this;
            $(this.div.questionaire).find('['+self.div.attr.id+'="'+id+'"]').addClass('readonly').find(self.div.question_select).attr(self.div.attr.readonly,'readonly');
            $(this.div.questionaire).find('['+self.div.attr.id+'="'+id+'"]').next(self.div.add_note).find(self.div.cell).css('display','none');
            $(this.div.questionaire).find('['+self.div.attr.note_question_id+'="'+id+'"]').find(self.div.cell).css('display','none');
            $(this.div.questionaire).find('['+self.div.attr.note_question_id+'="'+id+'"]').prev(self.div.note_count).find(self.div.cell).css('display','none');
        },

        /**
         * Easter Egg :)
         */
        easter_egg : function(){
            var self = this;
            var animation = true;
            var answers = { button1:1, button2:5, button4:6, button5:5, button6:4, button31:5, button7:4, button8:5 };

            $(self.div.option + self.div.active ).removeClass( 'animation' );

            $.each( answers, function( index, value ){
                var div = $( 'input[name="' + index + '"][value="' + value + '"]');
                if( !div.parent().hasClass( 'active' ) ){
                    animation = false;
                }
            });

            if( animation ){
                $(self.div.option + self.div.active ).addClass( 'animation' );
                console.log( 'disco disco!' );
            }
        },

        /**
         * Enable Question
         * @param id
         */
        enable_question : function(id) {
            var self = this;
            $(this.div.questionaire).find('['+self.div.attr.id+'="'+id+'"]').removeClass('readonly').find(self.div.question_select).removeAttr(self.div.attr.readonly);
            $(this.div.questionaire).find('['+self.div.attr.note_question_id+'="'+id+'"]').prev(self.div.note_count).find(self.div.cell).css('display','table-cell');
            $(this.div.questionaire).find('['+self.div.attr.note_question_id+'="'+id+'"]').prev(self.div.note_count).find(self.div.cell + ' #hide').hide();
            $(this.div.questionaire).find('['+self.div.attr.note_question_id+'="'+id+'"]').prev(self.div.note_count).find(self.div.cell + ' #show').show();
        },

        /**
         * Events
         */
        events : function (){
            var self = this;

            //Fooltip - events
            fooltip.events();

            //add answers for selected questions
            $(self.div.publisher_nav).on('click', self.div.actions + ' ' + self.div.add_answers, function(e){
                e.preventDefault();
                $(self.div.question).each( function( index, obj ){
                    if( $(obj).find(self.div.select).is(':checked') ) {
                        self.toggle_unanswered( $(obj).find(self.div.unanswered),true );
                    }
                });
            });

            //add note
            $(self.div.questionaire).on('click', self.div.add_note + ' ' + self.div.save_note_close, function(e){
                e.preventDefault();
                $(this).parent().parent().find(self.div.cell).css('display','none');
            });

            //add note for selected questions
            $(self.div.publisher_nav).on('click', self.div.actions + ' ' + self.div.add_note_by_id, function(e){
                e.preventDefault();

                $(self.div.question).each( function( index, obj ){
                    if( $(obj).find(self.div.select).is(':checked') ) {
                        $(obj).next(self.div.add_note).find(self.div.cell).css('display','table-cell');
                    }
                });
            });

            //edit note
            $(self.div.questionaire).on('click', self.div.note + ' ' + self.div.edit, function(){
                var question_id = $(this).parent().parent().parent().parent().attr(self.div.attr.note_question_id);
                var question = $(self.div.question + '['+self.div.attr.id+'="'+question_id+'"]');
                var add_note = question.next(self.div.add_note);
                var note_id = $(this).parent().parent().parent().parent().attr(self.div.attr.note_id);

                add_note.find('.cell').css('display','table-cell');
                add_note.find('textarea').val($(this).parent().parent().parent().find(self.div.text).text());
                add_note.attr(self.div.attr.edit_note_id,note_id);
            });

            //filter questions
            $(self.div.publisher_nav).on('keyup', self.div.filter + ' input', function(){
                self.filter_questions( $(this).val() );
            });

            //select a option
            $(self.div.questionaire).on('click', 'li.option', function() {
                self.toggle_input( $(this) );

                //Don't update the form if the filter is enabled
                if( $('.filter input').val() == "" ) {
                    self.update_form();
                    self.easter_egg(); // :)
                }
            });

            //save
            $(self.div.publisher_nav).on('click', self.div.save, function(e) {
                e.preventDefault();
                if(!request.running(null)) {
                    self.submit_answers();
                }
            });

            //save add note
            $(self.div.questionaire).on('click', self.div.add_note + ' ' + self.div.save_note, function(e){
                e.preventDefault();
                if(!request.running(null)) {
                    var question_id = $(this).parent().find(self.div.input.question_id).val();
                    var note = $(this).parent().find('textarea').val();
                    var note_id = $(this).parent().parent().attr(self.div.attr.edit_note_id);
                    var publisher_id = $(self.div.publisher_selected).data('publisher-id');
                    self.add_edit_note(question_id, publisher_id, note_id, note);
                }
            });

            //select all questions
            $(self.div.publisher_nav).on('click', self.div.actions + ' ' + self.div.select_all, function(e){
                e.preventDefault();

                var show = false;
                if( $(this).text() == "select all" ){
                    $(this).text( 'deselect all' );
                    show = true;
                }else{
                    $(this).text( 'select all' );
                }

                $(self.div.question).each( function( index, obj ) {
                    if( $(obj).is(':visible') ) {
                        self.toggle_select($(obj).find(self.div.select), show);
                    }
                });
            });

            //select publisher
            $(self.div.publisher).on('change', 'select', function(){
                var selected = $(self.div.publisher_selected);

                $(this).parent().find('li.selected').removeClass('selected');
                $(this).addClass('selected');

                self.populate_form(selected.data('publisher-id')).done(function (data) {
                    if (data !== null) {
                        $.each(data['data'], function (index, value) {
                            var question = $('['+self.div.attr.id+'="' + value['QUESTION_ID'] + '"]');
                            question.find(self.div.unanswered).hide();
                            question.find(self.div.answer).show();
                            question.removeClass('readonly');
                        });
                    }
                });

            });

            //show answer options for unanswered question
            $(self.div.questionaire).on('click', self.div.unanswered, function(e){
                e.preventDefault();
                self.toggle_unanswered( $(this), true );
            });

            //show saved notes
            $(self.div.questionaire).on('click', self.div.note_count, function(){
                if( $(this).find('#show').is(':hidden') ){
                    $('['+self.div.attr.note_question_id+'="'+$(this).attr(self.div.attr.count_id)+'"] ' + self.div.cell).css( 'display', 'none');
                    $(this).find('#show').show();
                    $(this).find('#hide').hide();
                }else{
                    $('['+self.div.attr.note_question_id+'="'+$(this).attr(self.div.attr.count_id)+'"] ' + self.div.cell).css( 'display', 'table-cell');
                    $(this).find('#show').hide();
                    $(this).find('#hide').show();
                }
            });

            //remove note
            $(self.div.questionaire).on('click', self.div.note + ' ' + self.div.remove, function(e) {
                e.preventDefault();
                self.remove_note($(this).parent().parent().parent().parent().data('note-id'));
            });

            //toggle question
            $(self.div.questionaire).on('click', self.div.question + ' ' + self.div.toggle, function(){
                var show = false;
                if( !$(this).find(self.div.select).is(':checked') ) {
                    show = true;
                }

                self.toggle_select( $(this).find(self.div.select), show );
            });

        },

        /**
         * Filter Questions
         * @param filter
         */
        filter_questions : function( filter ){
            var self = this;
            if( $(self.div.publisher_select).val() != "" ) {
                table.toggle_notification(self.div.questionaire,null,false);

                if (filter != "") {
                    //Disable questions
                    $(self.div.question).each(function( index, obj ){
                        self.disable_question($(obj).attr(self.div.attr.id));
                    });

                    //Show questions we have filtered
                    $('['+self.div.attr.question_title+'*="' + filter.toLowerCase() + '"]').each(function( index, obj ){
                        self.enable_question($(obj).attr(self.div.attr.id))
                    });

                    //No questions found warning
                    if( !$(self.div.question).is(':visible') ) {
                        table.toggle_notification(self.div.questionaire,'No questions found',false);
                    }
                } else {
                    self.update_form();
                }
            }
        },

        /**
         * Generate Questionaire
         */
        generate_questionaire : function() {
            var self = this;
            var html = null;
            var state = $.Deferred();

            publisher.getPublishers().done(function (data) {
                html = source.html(self.template.publisher({
                        save: true,
                        edit: false,
                        add: false,
                        actions: true,
                        options: data
                    })
                );

                request.open(self.ajax.questions,
                    $.getJSON(self.ajax.questions, function (data) {
                        //Add additional field which we can use to filter question on later
                        $.each(data, function (index, value) {
                            data[index]['FILTER_QUESTION_TITLE'] = value['QUESTION_TITLE'].toLowerCase();
                        });

                        $.each( data, function(key,row){
                            if(row['DEPRECIATED'] == 0) {
                                self.question[key] = row;
                            }
                        });

                        html += source.html(self.template.questionaire(self.question));

                        state.resolve(html);

                        //Chosen drop down
                        $(self.div.publisher).find('select').chosen({allow_single_deselect: true});

                        //Hide questions till we have selected a publisher
                        $(self.div.question).addClass('readonly');

                        table.toggle_notification(self.div.questionaire,'Select a publisher',false);
                        request.close(self.ajax.questions);
                    })
                )
            });

            return $.when(state).done().promise();
        },

        /**
         * Init
         */
        init : function(){
            var self = this;

            //Templates
            self.template['publisher'] = handlebars.compile(publisher_select);
            self.template['questionaire'] = handlebars.compile(questionaire);
            self.template['note'] = handlebars.compile(note);
            self.template['note_count'] = handlebars.compile(note_count);

            //Swag Helper
            Swag.registerHelpers(handlebars);

            //Initialize fooltips
            fooltip.init('#publisher_tool');

            return self.generate_questionaire();
        },

        /**
         * Populate Form
         * @internal populate this form with store values
         * @param publisher_id
         * @returns {*}
         */
        populate_form : function( publisher_id ) {
            var self = this;
            var status = $.Deferred();

            //If we have an id then we must have data that we need to populate
            table.toggle_notification(self.div.questionaire, 'Loading answers', true);

            request.open( self.ajax.answers,
                $.post(self.ajax.answers, {
                    publisher_id: publisher_id
                }, function (data) {

                    //Reset form
                    self.reset_form();

                    //Set logo
                    publisher.toggle_image();

                    //Add answers for questions
                    $.each(data['data'], function (index, row) {

                        //Display question answer
                        if (row['ANSWER_OPTION_ID'] != null) {
                            self.toggle_input($('['+self.div.attr.id+'="' + row['QUESTION_ID'] + '"] ['+self.div.attr.answer_option_id+'="' + row['ANSWER_OPTION_ID'] + '"]'))
                        }else if (row['ANSWER_STRING'] != null) {
                            $('[data-id="' + row['QUESTION_ID'] + '"] ' + self.div.question_select).val(row['ANSWER_STRING']);
                            self.toggle_input($('['+self.div.attr.id+'="' + row['QUESTION_ID'] + '"]'));
                        }

                        //Count number of notes we have
                        if (row['NOTES'].length > 0) {
                            //Note count
                            var html = source.html( self.template.note_count({
                                    QUESTION_ID: row['QUESTION_ID'],
                                    NOTES: row["NOTES"].length,
                                    TYPE: ( row['NOTES'].length > 1 ) ? "notes" : "note"
                                })
                            );

                            //Display notes for this question
                            $.each(row['NOTES'], function (key, note) {
                                html += source.html( self.template.note({
                                        QUESTION_ID: row['QUESTION_ID'],
                                        ANSWER_NOTE_ID: note['ANSWER_NOTE_ID'],
                                        NOTE: note["NOTE"],
                                        USER: note['USER'],
                                        DATE: note['DATE']
                                    })
                                );
                            });

                            //Append notes
                            $('['+self.div.attr.id+'="' + row['QUESTION_ID'] + '"]').next(self.div.add_note).after(html);
                        }
                    });


                    table.toggle_notification(self.div.questionaire, null, false);
                    request.close(self.ajax.answers);

                    self.update_form();
                    status.resolve(data);
                })
            );

            return $.when( status ).done().promise();
        },

        /**
         * Remove Note
         * @param note_id
         */
        remove_note : function( note_id ){
            var self = this;
            table.toggle_notification(self.div.questionaire,'Saving your changes',true);

            $.getJSON(self.ajax.remove_note, {
                note_id : note_id
            },function(data) {
                if (data['status'] == true) {

                    //Get question id for this note
                    var question_id = $('['+self.div.attr.note_id+'="'+data['note_id']+'"]').attr(self.div.attr.note_question_id);

                    //Remove note from div
                    $('['+self.div.attr.note_id+'="'+data['note_id']+'"]').remove();

                    //Remove old note count
                    $('['+self.div.attr.count_id+'="'+question_id+'"]').remove();

                    //New note count
                    var notes = $('['+self.div.attr.note_question_id+'="' + question_id + '"]');
                    if( notes.length > 0 ) {
                        var html = source.html(self.template.note_count({
                                QUESTION_ID: question_id,
                                NOTES: notes.length,
                                TYPE: ( notes.length > 1 ) ? "notes" : "note"
                            })
                        );

                        //Hide notes
                        $('['+self.div.attr.note_question_id+'="' + question_id + '"] .cell').hide();

                        //Add new note count
                        $('.question[data-id='+question_id+']').next('.add_note').after(html);
                    }

                    table.toggle_notification(self.div.questionaire,null,false);
                    alert.set( 'success', 'Note removed' );
                }else{
                    table.toggle_notification(self.div.questionaire,null,false);
                    alert.set( 'error', data['error'] );
                }
            });
        },

        /**
         * Reset Form
         */
        reset_form : function() {
            var self = this;
            $.each(this.question, function(key, value) {
                var question = $('['+self.div.attr.id+'="'+value.QUESTION_ID+'"]');
                question.find('li ' + self.div.question_select).prop('checked', false);
                question.find('li').removeClass( 'active' );
                question.find(self.div.string).val( '');
                question.find(self.div.decimal).val( '');
                question.find(self.div.note).val( '' ).hide();
                question.find(self.div.unanswered).show();
                question.find(self.div.answer).hide();

                $(self.div.add_note).find(self.div.cell).css('display','none');
                $(self.div.add_note).find('textarea').val('');
                $(self.div.note_count).remove();
                $(self.div.note).remove();
            });
            this.update_form();
        },

        /**
         * Submit Answer
         */
        submit_answers : function() {
            var self = this;
            var publisher = $(self.div.publisher_selected);

            //Validation
            var validation = null;
            $.each($(self.div.questionaire + ' tbody').find(self.div.input.decimal), function (index, value) {
                if (!$(this).parent().parent().parent().parent().hasClass('readonly')) {
                    if ($(this).val().indexOf(".") == -1) {
                        var name = $(this).parent().parent().find(self.div.name).text();
                        var question = name.substring(0, name.length - 9);
                        validation = 'Error saving question: ' + question + ' Expected format 0.00';
                    }
                }
            });

            //Save answers
            if (publisher.text()) {
                if (validation == null) {
                    table.toggle_notification(self.div.questionaire,'Saving your changes',true);
                    request.open(self.ajax.add_edit_answers,
                        $.post(self.ajax.add_edit_answers, {
                            publisher_id: publisher.data('publisher-id'),
                            publisher_name: publisher.text(),
                            partner_id: publisher.data('partner-id'),
                            button: $(self.div.questionaire + ' tbody').find(self.div.input.button).serialize(),
                            string: $(self.div.questionaire + ' tbody').find(self.div.input.string).serialize(),
                            decimal: $(self.div.questionaire + ' tbody').find(self.div.input.decimal).serialize()
                        }, function (data) {
                            if (data['status'] == 'success') {
                                publisher.attr(self.div.attr.publisher_id, data['id']);
                                alert.set('success', 'Your changes have been saved');
                                table.toggle_notification(self.div.questionaire,null,false);
                                request.close(self.ajax.add_edit_answers);
                            } else {
                                alert.set('error', data['data']);
                                table.toggle_notification(self.div.questionaire,null,false);
                                request.close(self.ajax.add_edit_answers);
                            }
                        })
                    )
                } else {
                    alert.set('error', validation);
                    request.close(self.ajax.add_edit_answers);
                }
            }else {
                alert.set('error', 'Please select a publishers');
                request.close(self.ajax.add_edit_answers);
            }
        },

        /**
         * Toggle Unanswered
         * @param div
         * @param show
         */
        toggle_unanswered : function( div, show ){
            var self = this;
            var question_id = div.parent().parent().data('id');

            if( show ) {
                div.hide();
                div.parent().parent().find(self.div.answer).show();
                self.enable_question( div.parent().parent().data('id') );
                self.toggle_select($('['+self.div.attr.id+'="' + question_id + '"]').find(self.div.select), true);
            }else{
                div.show();
                div.parent().parent().find(self.div.answer).hide();
                self.disable_question( div.parent().parent().data('id') );
                self.toggle_select($('['+self.div.attr.id+'="' + question_id + '"]').find(self.div.select), false);
            }
        },

        /**
         * Toggle Input
         * @param div
         */
        toggle_input : function( div ) {
            var self = this;
            div.toggleClass('active');
            var input = div.find(self.div.question_select);
            if( input.attr(self.div.attr.type) == 'radio' ) {
                div.addClass('active').siblings().removeClass('active');
                input.prop('checked', true);
            } else {
                input.prop('checked', !input.prop('checked'));
            }
        },

        /**
         * Toggle Select
         * @param div
         * @param show
         */
        toggle_select : function( div, show ){
            var self = this;
            var cell = div.parent().parent().parent().parent().parent().find( self.div.cell+':last-child' );

            if( show ){
                div.prop( "checked", true );
                cell.removeClass('lock');
            }else{
                div.prop( "checked", false );
                cell.addClass('lock');
            }
        },

        /**
         * Update Form
         */
        update_form : function() {
            var self = this;

            $(self.div.questionaire).find(self.div.question).removeClass('readonly').find(self.div.question_select).prop('disabled',false);

            // show hide based on previous answers
            $.each(this.question, function(key, value) {
                if(value.REQUIRES_QUESTION_ID) {
                    if(value.REQUIRES_ANSWER_ID) {
                        var question = $(self.div.question + '['+self.div.attr.id+'="' + value.REQUIRES_QUESTION_ID + '"] ['+self.div.attr.answer_option_id+'="' + value.REQUIRES_ANSWER_ID + '"').find(self.div.question_select).addClass('active').prop('checked');

                        if( question ) {
                            self.enable_question(value.QUESTION_ID);
                        } else {
                            self.disable_question(value.QUESTION_ID);
                        }
                    }
                }
            });
        }

    };
});