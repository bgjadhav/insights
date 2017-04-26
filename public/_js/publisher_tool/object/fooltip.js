/**
 * Fooltip.js
 * @created 25/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(['Handlebars',
        'Source',
        'Text!Templates/fooltip.handlebars' ],
    function( handlebars,
              source,
              fooltip ){
        return{

            //Alignments
            alignments : [ 'left', 'right', 'bottom' ],

            //Div
            div : {
                fooltip : '#fooltips',
                fooltip_data : '[data-fooltip]',
                nav_main : '#nav-main',
                side_bar : '#sidebar'
            },

            //Template
            template : {},

            /**
             * Append
             */
            append : function(){
                var self = this;

                //Get fooltip template
                var html = source.html(self.template.fooltip());

                //Append fooltip to body
                $('body').append(html);

                //Hide fooltip
                $(self.div.fooltip).hide();
            },

            /**
             * Events
             */
            events : function(){
                var self = this;

                //hide
                $(self.div.init).on('mouseleave', self.div.fooltip_data, function(){
                    $(self.div.fooltip).stop( true, true ).fadeOut();
                    $(this).removeClass('active_fooltip');
                });

                //show
                $(self.div.init).on('mouseover', self.div.fooltip_data, function(){
                    if( $(window).width() > 500 ) {
                        var element = $(this);
                        if (!$(this).hasClass('active_fooltip') ) {

                            //First try the alignment position we have specified for this fooltip
                            self.position(element,$(this).data('align'));

                            //If the fooltip can't be displayed then try each position to see which one we can use
                            if(!self.on_screen()) {
                                $.each(self.alignments, function (index, value) {
                                    self.position(element, value);
                                    if (self.on_screen()) {
                                        $(self.div.fooltip).stop(true, true).fadeIn();
                                        $(this).addClass('active_fooltip');
                                        return null;
                                    }
                                });
                            }else{
                                $(self.div.fooltip).stop(true, true).fadeIn();
                                $(this).addClass('active_fooltip');
                            }
                        }
                    }
                });
            },

            /**
             * On Screen
             * @returns {boolean}
             */
            on_screen : function(){
                var self = this;
                var position = $(self.div.fooltip).show().css('visibility','hidden').position();
                $(self.div.fooltip).hide().css('visibility','visible');

                if(position.left < 0){
                    return false;
                }else if(position.top < 0){
                    return false;
                }else if(position.left + $(self.div.fooltip).width() >= $('body').width() ){
                    return false;
                }else if(position.top + $(self.div.fooltip).height() >= $('body').height() ){
                    return false;
                }
                return true;
            },

            /**
             * Position
             * @param element
             * @param align
             */
            position : function(element,align){
                var self = this;
                var position = element.position();
                var width = element.outerWidth();
                var height = element.outerHeight();

                //Ignore nav
                var ignore_nav_top = 0;
                var ignore_nav_side = 0;
                if ($(self.div.nav_main).css('position') == "relative") {
                    ignore_nav_top = $(self.div.nav_main).outerHeight() + 60;
                }else{
                    ignore_nav_side = $(self.div.nav_main).width();
                }

                //Set text
                $(self.div.fooltip).find('p').text(element.data('fooltip'));

                //Get new height width of fooltip
                $(self.div.fooltip).show().css('visibility','hidden');
                var fooltip_height = $(self.div.fooltip).find('p').outerHeight();
                var fooltip_width = $(self.div.fooltip).find('p').outerWidth();
                $(self.div.fooltip).hide().css('visibility','visible');

                if (align == 'left') {
                     $(self.div.fooltip).removeClass('bottom');
                     $(self.div.fooltip).removeClass('right');
                     $(self.div.fooltip).addClass('left');
                     $(self.div.fooltip).css({
                        top: ignore_nav_top + ( position['top'] - ( (fooltip_height / 2) - ( height / 2 ) ) ),
                        left: position['left'] - ( fooltip_width - ignore_nav_side ),
                        position: 'absolute'
                    });
                }else if (align == 'right') {
                     $(self.div.fooltip).removeClass('bottom');
                     $(self.div.fooltip).removeClass('left');
                     $(self.div.fooltip).addClass('right');
                     $(self.div.fooltip).css({
                         top: ignore_nav_top + ( position['top'] - ( (fooltip_height / 2) - ( height / 2 ) ) ),
                         left: position['left'] + ( width + ignore_nav_side ),
                         position: 'absolute'
                     });
                }else {
                    $(self.div.fooltip).removeClass('right');
                    $(self.div.fooltip).removeClass('left');
                    $(self.div.fooltip).addClass('bottom');
                    $(self.div.fooltip).css({
                        top: ignore_nav_top + ( position['top'] - fooltip_height ),
                        left: position['left'] + ( ( width / 2 ) - ignore_nav_side ),
                        position: 'absolute'
                    });
                }
            },

            /**
             * Init
             */
            init : function( div ){
                var self = this;
                self.div['init'] = div;
                self.template['fooltip'] = handlebars.compile(fooltip);
                self.append();
            }
        }
    }
);