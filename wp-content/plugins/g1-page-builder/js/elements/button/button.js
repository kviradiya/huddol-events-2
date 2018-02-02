(function ($, context) {
    "use strict";

    var elementUniqueId = 'button';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'button',
        cssElementClass: 'button',
        toolbarSection: 'content_elements',
        label: 'Button',
        description: 'A button element with optional icon.',
        htmlRepresentationCallback: 'button'
    };

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
            link: {
                'type': 'input'
            },
            'linking': {
                'type': 'select',
                'options': {
                    'default':      'open in the same window',
                    'new_window':   'open in a new window',
                    'lightbox':     'open in a lightbox'
                },
                'default': 'new-window'
            },
            lightbox_group: {
                'type': 'input'
            },
            'align': {
                type: 'select',
                    'options': {
                    'left':     'left',
                        'center':   'center',
                        'right':    'right'
                },
                'default': 'left'
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
            'type': {
                type: 'select',
                    'options': {
                    'standard': 'standard',
                        'wide':     'wide'

                },
                'default': 'standard'
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
            'text': null,
            'text_color': {
                type: 'colorpicker'
            },
            'bg_color': null,
            'label': null
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