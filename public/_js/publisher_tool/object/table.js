/**
 * Table.js
 * @created 22/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(function(){
    return {

        div : {
            notification : '#table-notification',
            header : '.header.row'
        },

        /**
         * Toggle Notification
         * @param div
         * @param string
         * @param ajax_icon
         */
        toggle_notification : function( div, string, ajax_icon ){
            var self = this;

            $(self.div.notification).remove();

            if( string != null ) {
                var icon = '';
                if (ajax_icon) {
                    icon = '<img src="_img/ajax-loader.gif" style="width: 15px; height: 15px;"/>';
                }

                $(div).find(self.div.header).after('<tr id="table-notification" class="row">' +
                                                        '<td colspan="2" class="cell ten notification">' + icon + ' ' + string + '</td>' +
                                                    '</tr>');

                $(self.div.notification + ' .notification').css('display', 'table-cell');
            }
        }
    };
});