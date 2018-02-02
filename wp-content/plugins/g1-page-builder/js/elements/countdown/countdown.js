(function ($, context) {
    "use strict";

    var elementUniqueId = 'countdown';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'countdown',
        cssElementClass: 'countdown',
        toolbarSection: 'content_elements',
        label: 'Countdown',
        description: 'A countdown clock to count the time left to a date of your choice.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type':     'text',
            'default':  '',
            'hint':     'Expiry text'
        };

        var fieldsConfig = {
            'until': {
                'type': 'input',
                'hint': 'For example: 1 June 2013'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
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