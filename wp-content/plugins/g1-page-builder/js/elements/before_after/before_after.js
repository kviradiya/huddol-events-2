(function ($, context) {
    "use strict";

    var elementUniqueId = 'before_after';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'before-after',
        cssElementClass: 'before-after',
        toolbarSection: 'content_elements',
        label: 'Before & After',
        description: 'A banner to visualize differencies between two images.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */

        var contentConfig = null;

        var fieldsConfig = {
            'type': {
                'type': 'select',
                'options': {
                    'smooth':   'smooth',
                    'flip':     'flip',
                    'hover':    'hover'
                },
                'default': 'smooth'
            },
            'before_src': {
                'type': 'input'
            },
            'after_src': {
                'type': 'input'
            },
            'width': {
                'type': 'input',
                'hint': 'In pixels'
            },
            'height': {
                'type': 'input',
                'hint': 'In pixels'
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