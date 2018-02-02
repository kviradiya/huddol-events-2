(function ($, context) {
    "use strict";

    var elementUniqueId = 'quote';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'quote',
        cssElementClass: 'quote',
        toolbarSection: 'content_elements',
        label: 'Quote',
        description: 'A quotation or a testimonial.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'author_name': {
                'type': 'input'
            },
            'author_description': {
                'type': 'input'
            },
            'author_image': {
                'type': 'input'
            },
            'size': {
                type: 'select',
                    'options': {
                        'small':  'small',
                        'medium': 'medium',
                        'big':    'big'
                },
                'default': 'small'
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