/**
 * Alert.js
 * @created 22/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(['Handlebars',
        'Source',
        'Text!Templates/alert.handlebars'],
        function( handlebars,
                  source,
                  alert ){
    return {

        div : {
            container : '#alert-container',
            alert : '.alert',
            alert_container : '#alert-container',
            alert_message : '.alert-message',
            publisher_tool : '#publisher_tool'
        },

        template : {
            alert : handlebars.compile( alert )
        },

        /**
         * Alert
         * @param type
         * @param message
         */
        set : function( type, message ){
            var self = this;

            //Check if an alert contain has been set if not add one
            if( $(self.div.alert_container).length == 0 ){
                $(self.div.publisher_tool).append('<div id="alert-container"></div>');
            }

            //Type
            if( type == 'success' ){
                type = 'alert-success';
            }else if( type == 'error' ){
                type = 'alert-danger';
            }

            //Template
            $(self.div.alert_container).append( source.html( self.template.alert({
                type : type,
                message : message
            })));

            //Remove message after interval of 6 seconds
            $(self.div.alert).slideDown(500, function(){
                var alert = $(this);
                setTimeout(function(){
                    alert.slideUp(function(){
                        alert.remove();
                    });
                }, 6000);
            });
        }
    };
});