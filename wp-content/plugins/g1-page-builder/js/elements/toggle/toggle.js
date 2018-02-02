(function ($, context) {
    "use strict";

    var elementUniqueId = 'toggle';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'toggle',
        cssElementClass: 'toggle',
        toolbarSection: 'content_elements',
        label: 'Toggle',
        description: 'A content box with a toggle',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var title = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'title');
        var out = '';

        if (title) {
            out = '<div class="g1-pb-toggle-title">' + title + '</div>';
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
            'type': 'richtext',
            'default': ''
        };

        var fieldsConfig = {
            'title': {
                'type': 'input'
            },
            'state': {
                'type': 'select',
                'options': {
                    'on':   'on',
                    'off':  'off'
                },
                'default': 'off'
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