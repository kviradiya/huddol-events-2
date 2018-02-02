(function ($, context) {
    "use strict";

    var elementUniqueId = 'twitter';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'twitter',
        cssElementClass: 'twitter',
        toolbarSection: 'content_elements',
        label: 'Twitter',
        description: 'A list of recent tweets.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'username': {
                'type':     'input'
            },
            'max': {
                'type':     'input',
                'hint':     'Maximum items to display'
            },
            'type': {
                'type': 'select',
                'options': {
                    'simple':       'simple',
                    'carousel':     'carousel'
                },
                'default': 'simple'
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