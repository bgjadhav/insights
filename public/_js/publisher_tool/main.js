/**
 * Main.js
 * @created 21/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define([ 'Request',
         'Handlebars',
         'Text!Templates/loading.handlebars',
         'Source',
         'Questionaire',
         'Publisher',
         'Question',
         'Search' ],
    function(request,
             handlebars,
             template,
             source,
             questionaire,
             publisher,
             question,
             search){

    var $main = {
        div : {
            publisher_tool: '#publisher_tool'
        },

        tab : {
            publisher_manager : 'publisher_manager',
            questionaire : 'questionaire',
            question_manager : 'question_manager',
            search : 'search'
        },

        /**
         * Events
         */
        events: function () {
            var self = this;
            var div = $(self.div.publisher_tool);

            //Tab Navigation
            div.parent().parent().parent().find('li').on('click', function (e) {
                e.preventDefault();
                self.toggle_tab(this);
            });
        },

        /**
         * Init
         */
        init : function(){
            var self = this;
            self.events();
            self.toggle_tab(self.tab.search); //Load default tab
        },

        /**
         * Loading
         */
        loading : function(){
            var self = this;
            var loading = handlebars.compile(template);
            self.set(source.html(loading()));
        },

        /**
         * Set
         * @param html
         */
        set : function(html){
            var self = this;
            $(self.div.publisher_tool).html(html);
        },

        /**
         * Toggle Tab
         * @param div
         */
        toggle_tab: function (div) {
            var self = this;

            if( !request.running(null) ) {
                request.open('toggle_tab',true);

                $('#' + $(div).attr('id')).parent().find('.active').removeClass();
                $(div).addClass('active');

                //Show the loader whilst we get the right template
                self.loading();

                //Acquire tab
                if ($(div).attr('id') == self.tab.questionaire) {
                    questionaire.init().done(function (html) {
                        self.set(html);
                        request.close('toggle_tab');
                        questionaire.events();
                    });
                }else if ($(div).attr('id') == self.tab.publisher_manager) {
                    publisher.init().done(function (html) {
                        self.set(html);
                        request.close('toggle_tab');
                        publisher.events();
                    });
                }else if ($(div).attr('id') == self.tab.question_manager) {
                    question.init().done(function(html){
                        self.set(html);
                        request.close('toggle_tab');
                        question.events();
                    });
                }else{
                    search.init().done(function (html) {
                        self.set(html);
                        request.close('toggle_tab');
                        search.events();
                    });
                }
            }
        }
    };

    return $main.init();
});
