(function ($, context) {
    "use strict";

    var elementUniqueId = 'progress_bar';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'progress-bar',
        cssElementClass: 'progress-bar',
        toolbarSection: 'content_elements',
        label: 'Progress Bar',
        description: 'A progress (or a skill) visualization in a form of a horizontal bar.',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var value = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'value');
        var out = '';

        if (value) {
            out = '<div class="g1-pb-progress-bar-value" style="width:' + parseInt( value, 10 ) + '%;"></div>';
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
            'type': 'input',
            'default': ''
        };

        var fieldsConfig = {
            'value': {
                'type': 'input',
                'hint': '0-100 range'
            },
            'direction': {
                'type': 'select',
                'options': {
                    'left':     'left',
                    'right':    'right'
                },
                'default': 'right'
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
                'default': 'solid'
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