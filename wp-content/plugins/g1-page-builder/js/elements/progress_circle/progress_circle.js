(function ($, context) {
    "use strict";

    var elementUniqueId = 'progress_circle';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'progress-circle',
        cssElementClass: 'progress-circle',
        toolbarSection: 'content_elements',
        label: 'Progress Circle',
        description: 'A progress (or a skill) visualization in a form of an animated circle.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'value': {
                'type': 'input',
                'hint': '0-100 range'
            },
            'style': {
                type: 'select',
                    'options': {
                        'simple':   'simple',
                        'solid':    'solid'
                },
                'default': 'simple'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
            },
            'text_color': {
                type: 'colorpicker'
            },
            'bg_color': {
                type: 'colorpicker'
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