(function ($, context) {
    "use strict";

    var elementUniqueId = 'table';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'table',
        cssElementClass: 'table',
        toolbarSection: 'content_elements',
        label: 'Table',
        description: 'A styled table.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type':     'text',
            'default':  ''
        };

        var fieldsConfig = {
            'style': {
                'type':     'select',
                'options':  {
                    'simple':   'simple',
                    'solid':    'solid'
                },
                'default':  'simple'
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