(function ($, context) {
    "use strict";

    var elementUniqueId = 'duplicator';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'duplicator',
        cssElementClass: 'duplicator',
        toolbarSection: 'content_elements',
        label: 'Duplicator',
        description: 'An animated icon graph.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'icon': {
                type:       'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default':  ''
            },
            'start': {
                'type':     'input',
                'default':  0,
                'hint': '   Elements at index lower than "start" are visible all time'
            },
            'stop': {
                'type':     'input',
                'default':  15,
                'hint':     'Stop animation at this index'
            },
            'max': {
                'type':     'input',
                'default':  20,
                'hint': 'Max. amount of elements to show'
            },
            'direction': {
                'type': 'select',
                'options': {
                    'left':     'left',
                    'right':    'right'
                },
                'default': 'right'
            },
            'style': {
                type: 'select',
                'options': {
                    'simple':   'simple',
                    'solid':    'solid'
                },
                'default': 'simple'
            },
            'color': {
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