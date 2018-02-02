(function ($, context) {
    "use strict";

    var elementUniqueId = 'numbers';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'numbers',
        cssElementClass: 'numbers',
        toolbarSection: 'content_elements',
        label: 'Numbers',
        description: 'An animated number counter with optional icon.',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var stop = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'stop');
        var out = '';

        if ( stop ) {
            out = '<div class="g1-pb-numbers-value">' + parseInt( stop, 10 ) + '</div>';
        }

        return out;
    }

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
            'start': {
                'type':     'input',
                'default':  '0',
                'hint':     'Initial value'
            },
            'stop': {
                'type':     'input',
                'default':  '100',
                'hint':     'Final value'
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
            'prefix': {
                'type':     'input',
                'hint':     'Put some value before'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
            },
            'suffix': {
                'type':     'input',
                'hint':     'Put some value after'
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