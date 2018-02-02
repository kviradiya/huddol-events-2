(function ($, context) {
    "use strict";

    var elementUniqueId = 'placeholder';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'placeholder',
        cssElementClass: 'placeholder',
        toolbarSection: 'content_elements',
        label: 'Placeholder',
        description: 'An image placeholder. Useful for prototyping.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element


        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'width': {
                'type':     'input',
                'hint':     'The width in pixels'
            },
            'height': {
                'type':     'input',
                'hint':     'The height in pixels'
            },
            'size': {
                type:       'select',
                'options':  {
                    'small':    'small',
                    'medium':   'medium',
                    'big':      'big'
                },
                'default':  'small'
            },
            'icon': {
                'type':     'select',
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