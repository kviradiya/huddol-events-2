(function ($, context) {
    "use strict";

    var elementUniqueId = 'space';

    var pbBlock = {
        level: 3,
        withControls: true,
        attrs: {
            'value': {
                'default': '20px'
            }
        },
        cssBlockClass: 'space',
        cssElementClass: 'space',
        toolbarSection: 'content_elements',
        label: 'Space',
        description: 'A blank, vertical separator.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        var contentConfig = null;

        var fieldsConfig = {
            value: {
                'type': 'input',
                'hint': 'The height in pixels (can be negative).'
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