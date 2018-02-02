(function ($, context) {
    "use strict";

    var elementUniqueId = 'list';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'list',
        cssElementClass: 'list',
        toolbarSection: 'content_elements',
        label: 'List',
        description: 'A styled list.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'description': 'for example: <br />' +
                '&lt;ul&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 1&lt;/li&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 2&lt;/li&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 3&lt;/li&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 4&lt;/li&gt;<br />' +
                '&lt;/ul&gt;'
        };

        var fieldsConfig = {
            'type': {
                'type': 'select',
                'options': {
                    'icon':             'icon',
                    'empty':            'empty',
                    'upper-alpha':      'upper-alpha',
                    'lower-alpha':      'lower-alpha',
                    'upper-roman':      'upper-roman',
                    'lower-roman':      'lower-roman',
                    'circle':           'circle',
                    'decimal':          'decimal',
                    'disc':             'disc',
                    'square':           'square'
                },
                'default': 'icon'
            },
            'style': {
                'type': 'select',
                'options': {
                    'none':             'none',
                    'simple':           'simple'
                },
                'default': 'none'
            },
            'icon': {
                type: 'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default': ''
            },
            'icon_color': {
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