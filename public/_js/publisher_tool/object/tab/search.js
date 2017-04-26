/**
 * Search.js
 * @created 22/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(['Alert',
        'Chosen',
        'Request',
        'Table',
        'Handlebars',
        'Source',
        'Fooltip',
        'Text!Templates/search/form.handlebars',
        'Text!Templates/search/keyword.handlebars',
        'Text!Templates/search/results.handlebars' ],
    function( alert,
              chosen,
              request,
              table,
              handlebars,
              source,
              fooltip,
              form,
              keyword,
              results ) {
        return {

            ajax : {
                search : 'publisher_database/search',
                tags : 'publisher_database/tags'
            },

            div : {
                exclude_keywords : '.exclude-keywords',
                exclude_question_tags : '#exclude-question-tags',
                include_keywords : '.include-keywords',
                include_question_tags : '#include-question-tags',
                search : '#search.display-table',
                search_results : '#search-results.display-table'
            },

            searching : null,

            template : {},

            /**
             * Events
             */
            events : function(){
                var self = this;

                //Fooltip - events
                fooltip.events();

                //add keyword
                $(self.div.search).on('click', '.add_keyword', function(e){
                    e.preventDefault();
                    $(this).parent().parent().append('<li>' +
                        '<input type="text" name="keyword[]"/>' +
                            '<span><a href="" class="remove_keyword">Remove</a></span>' +
                        '</li>');
                });

                //remove keyword
                $(self.div.search).on('click', '.remove_keyword', function(e){
                    e.preventDefault();
                    $(this).parent().parent().remove();
                    self.search();
                });

                //search
                $(self.div.search).on('change', '[name="keyword[]"]', function(e) {
                    e.preventDefault();
                    self.search();
                });

                //include tag
                $(self.div.search).on('change', '#include-question-tags', function(e) {
                    e.preventDefault();
                    self.search();
                });

                //exclude tag
                $(self.div.search).on('change', '#exclude-question-tags', function(e) {
                    e.preventDefault();
                    self.search();
                });

                //results
                $(self.div.search_results).on('click', '.publisher', function(){
                    var publisher_id = $(this).parent().attr('data-search-publisher-id');
                    var answers = $('[data-answer-publisher-id="'+publisher_id+'"]').find('.cell');

                    if( answers.is(':hidden') ){
                        answers.css('display', 'table-cell');
                        $(this).find('.icon').removeClass('closed');
                        $(this).find('.icon').addClass('open');
                    }else{
                        answers.css('display', 'none');
                        $(this).find('.icon').removeClass('open');
                        $(this).find('.icon').addClass('closed');
                    }
                });
            },

            /**
             * Generate Page
             */
            generate_search : function(){
                var self = this;
                var state = $.Deferred();

                request.open(self.ajax.tags,
                    $.getJSON(self.ajax.tags, function (data) {
                        var html = source.html(self.template.form({options: data}));
                        state.resolve(html);
                        $(self.div.include_question_tags).chosen({allow_single_deselect: true});
                        $(self.div.exclude_question_tags).chosen({allow_single_deselect: true});
                        request.close(self.ajax.tags);
                    })
                );

                return $.when(state).done().promise();
            },

            /**
             * Init
             */
            init : function(){
                var self = this;

                //Templates
                self.template['form'] = handlebars.compile(form);
                self.template['results'] = handlebars.compile(results);

                //Swag Helper
                Swag.registerHelpers(handlebars);

                //Initialize fooltips
                fooltip.init('#publisher_tool');

                return self.generate_search();

            },

            /**
             * Search
             */
            search : function (){
                var self = this;

                var include_question_tags = $(self.div.include_question_tags).val();
                var include_keywords = [];
                $(self.div.include_keywords + ' [name="keyword[]"]').each(function (key, obj) {
                    include_keywords.push($(obj).val());
                });

                var exclude_question_tags = $(self.div.exclude_question_tags).val();
                var exclude_keywords = [];
                $(self.div.exclude_keywords + ' [name="keyword[]"]').each(function (key, obj) {
                    exclude_keywords.push($(obj).val());
                });

                //Remove old results
                $(self.div.search_results + ' tbody').empty();

                table.toggle_notification(self.div.search_results,'Searching...', true);
                request.open(self.ajax.search, $.getJSON(self.ajax.search, {
                        include_tags: ( include_question_tags ) ? include_question_tags : [],
                        include_keywords: ( include_keywords ) ? include_keywords : [],
                        exclude_tags: ( exclude_question_tags ) ? exclude_question_tags : [],
                        exclude_keywords: ( exclude_keywords ) ? exclude_keywords : []
                    }, function (data) {
                        //Add new results
                        $(self.div.search_results + ' tbody').append( source.html(
                                self.template.results({results: data})
                            )
                        );

                        //If no results show warning
                        if ($.isEmptyObject(data)) {
                            table.toggle_notification(self.div.search_results,'No results found', false);
                        } else {
                            table.toggle_notification(self.div.search_results,null, false);
                        }

                        request.close(self.ajax.search);
                    })
                )
            }
        }
    }
);