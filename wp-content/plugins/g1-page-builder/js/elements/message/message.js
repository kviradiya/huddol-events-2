(function ($, context) {
    "use strict";

    var elementUniqueId = 'message';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'message',
        cssElementClass: 'message',
        toolbarSection: 'content_elements',
        label: 'Message',
        description: 'A message box.',
        htmlRepresentationCallback: renderBlock
    };

    /* jshint unused:true */
    /* @todo Improvement: better handling of default values */
    /* @todo Improvement: */
    function renderBlock (fieldsValues, contentValue) {
        var out = '';

        contentValue = contentValue.replace(/^\n+|\n+$/g, '');

        if ( !contentValue ) {
            contentValue = '&hellip; here goes some text &hellip;';
        }

        var type = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'type');
        type = type || 'success';

        out += '<div class="g1-pb-preview-message">';
            out += '<div class="g1-pb-preview-message-type">(' + type + ')</div>';
            out += '<div class="g1-pb-preview-message-content">' + contentValue + '</div>';
        out += '</div>';

        return out;
    }
    /* jshint unused:false */

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var message = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'type': {
                'type': 'select',
                'options': {
                    'success':      'success',
                    'info':         'info',
                    'warning':      'warning',
                    'error':        'error'
                },
                'default': 'success'
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

    context.registerElement(elementUniqueId, message);
})(jQuery, g1PageBuilder);