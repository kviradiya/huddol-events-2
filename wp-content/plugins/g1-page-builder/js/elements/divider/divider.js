(function ($, context) {
    "use strict";

    var elementUniqueId = 'divider';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'divider',
        cssElementClass: 'divider',
        toolbarSection: 'content_elements',
        label: 'Divider',
        description: 'A horizontal divider with optional icon.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'style': {
                'type': 'select',
                'options': {
                    'none':             'none',
                    'simple':           'simple'
                },
                'default': 'none'
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