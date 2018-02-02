/* global jQuery */
/* global g1PageBuilder */
/* jshint unused:false */

(function ($, context) {
    "use strict";

    var elementUniqueId = 'audio_player';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'audio',
        cssElementClass: 'audio',
        toolbarSection: 'content_elements',
        label: 'Audio',
        description: 'An audio player.',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var out = '';

        return out;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'title': {
                'type': 'input',
                'hint': 'The title of the audio file'
            },
            'mp3': {
                'type': 'input',
                'hint': 'The source of the mp3 file'
            }
        };

        // pseudo constructor
        function init () {
            that.init(fieldsValues, fieldsConfig, contentValue, contentConfig);
        }

        init();

        // public scope

        return that; // gives access to public methods
    };

    context.registerElement(elementUniqueId, pbElement);
})(jQuery, g1PageBuilder);