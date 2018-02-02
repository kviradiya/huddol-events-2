(function ($, context) {
    "use strict";

    var elementUniqueId = 'section';

    var pbBlock = {
        level: 1,
        withControls: true,
        disableResizeControls: true,
        cssBlockClass: 'section',
        cssElementClass: 'section',
        toolbarSection: 'layout_elements',
        label: 'Section',
        description: 'A full width section with a custom background'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'background_color': {
                'type': 'colorpicker'
            },
            'background_image': {
                'type': 'input'
            },
            'background_repeat': {
                'type': 'select',
                'options': {
                    'no-repeat':    'no-repeat',
                    'repeat':       'repeat',
                    'repeat-x':     'repeat-x',
                    'repeat-y':     'repeat-y'
                },
                'default': 'repeat'
            },
            'background_position': {
                'type': 'select',
                'options': {
                    'left top':     'left top',
                    'center top':   'center top',
                    'right top':    'right top',
                    'left bottom':  'left bottom',
                    'center bottom':'center bottom',
                    'right bottom': 'right bottom'
                },
                'default': 'center top'
            },
            'background_attachment': {
                'type': 'select',
                'options': {
                    'static':       'static',
                    'fixed':        'fixed'
                },
                'default':  'static'
            },
            'background_scroll': {
                'type': 'select',
                'options': {
                    'none':         'none',
                    'standard':     'standard'
                },
                'default':  'none'
            },
            'border_size': {
                'type': 'input',
                'hint': 'In pixels'
            },
            'padding_bottom': {
                'type': 'input',
                'hint': 'In pixels'
            },
            'padding_top': {
                'type': 'input',
                'hint': 'In pixels'
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