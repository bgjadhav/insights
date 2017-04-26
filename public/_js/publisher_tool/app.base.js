/**
 * App.js
 * @url http://requirejs.org/
 * @created 21/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
$( function() {

    // Configure loading modules
    requirejs.config({
        baseUrl : '_js/publisher_tool',

        paths: {
            Alert : 'object/alert',
            Chosen : '../jira/chosen.jquery.min',
            Fooltip : 'object/fooltip',
            Handlebars : '../handlebars',
            Publisher : 'object/tab/publisher',
            Question : 'object/tab/question',
            Questionaire : 'object/tab/questionaire',
            Request : 'object/request',
            Search : 'object/tab/search',
            SimpleSlider : 'package/slider/simple-slider.min',
            Source : 'object/source',
            Table : 'object/table',
            Templates : 'template',
            Text : 'package/require/text'
        },

        shim: {
            Handlebars : {
                exports : 'Handlebars'
            }
        }
    });

    // Main app file.
    requirejs(['main']);
});