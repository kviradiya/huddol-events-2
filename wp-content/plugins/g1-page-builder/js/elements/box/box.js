(function ($, context) {
    "use strict";

    var elementUniqueId = 'box';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'box',
        cssElementClass: 'box',
        toolbarSection: 'content_elements',
        label: 'Box',
        description: 'A visual box with optional icon.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'richtext',
            'default': ''
        };

        var fieldsConfig = {
            'icon': {
                type: 'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default': ''
            },
            'style': {
                type: 'select',
                'options': {
                    'simple':   'simple',
                    'solid':    'solid'
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