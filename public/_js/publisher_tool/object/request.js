/**
 * Request.js
 * @created 22/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(function(){

    $request = {

        div : {
            main: '#main'
        },

        requests : {},

        /**
         * Abort
         * @key
         */
        abort : function( key ){
            var self = this;

            if( key in self.requests ) {
                if(typeof self.requests[key] == 'object' ) {
                    //Abort
                    if( $.isFunction( self.requests[key]['abort'] ) ) {
                        self.requests[key].abort();
                        console.log('Aborting: ' + key );
                    }
                }
                self.requests[key] = false;
            }
        },

        /**
         * Open
         * @param key
         * @param state
         */
        open : function( key, state ){
            var self = this;

            //Abort previous request
            self.abort(key);

            //Add new request
            self.requests[key] = state;

            //Pointer Event
            //self.pointer_events(true);

        },

        /**
         * Close
         * @param key
         */
        close : function( key ){
            var self = this;

            self.requests[key] = false;

            //Check requests running
            var requests = 0;
            $.each(self.requests, function (index, row) {
                if (row) {
                    requests += 1;
                }
            });

            //If there are no more requests running then disable pointer events
            if (requests == 0) {
                self.pointer_events(false);
            }
        },

        /**
         * Pointer Events
         * @param state
         */
        pointer_events : function( state ){
            var self = this;
            if( state ) {
               // $(self.div.main).css('pointer-events', 'none');
            }else {
               // $(self.div.main).css('pointer-events', 'inherit');
            }
        },

        /**
         * Running
         * @param key
         * @returns {*}
         */
        running : function( key ){
            var self = this;
            var requests = false;
            if( key == null ){
                $.each( self.requests, function( index, row ){
                    if( row ) {
                        requests = row;
                    }
                });
            }else {
                requests = ( key in self.requests ) ? self.requests[key] : false;
            }

            return requests;
        }
    };

    return $request;
});