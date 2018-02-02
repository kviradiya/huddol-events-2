(function ($, context) {
    "use strict";

    var elementUniqueId = 'gmap';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'gmap',
        cssElementClass: 'gmap',
        toolbarSection: 'content_elements',
        label: 'GMap',
        description: 'A Google Map embed.'
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
            'width': {
                'type':     'input',
                'default':  '100%',
                'hint':     'Map width in pixels or % (leave it empty to use 100% width)'
            },
            'height': {
                'type':     'input',
                'default':  '500px',
                'hint':     'Map height in pixels'
            },
            'type': {
                'type': 'select',
                'options': {
                    'simple':   'simple',
                    'rich':     'right'
                },
                'default': 'rich',
                'hint': 'Map interface. Simple has no controls'
            },
            'color': {
                'type': 'colorpicker'
            },
            'invert_lightness': {
                type: 'select',
                    'options': {
                        '0':   'none',
                        '1':   'standard'
                },
                'default': '0'
            },
            'map_type': {
                type: 'select',
                'options': {
                    'small':  'small',
                    'medium': 'medium',
                    'big':    'big'
                },
                'default': 'small'
            },
            'latitude': {
                'type':     'input',
                'hint':     'To center the map on a specific point (eg. 40.714353 for New York, USA)'
            },
            'longitude': {
                'type':     'input',
                'hint':     'To center the map on a specific point (eg. -74.005973 for New York, USA)'
            },
            'zoom': {
                'type':     'input',
                'default':  '15',
                'hint':     'The initial resolution at which to display the map (default: 15)'
            },
            'marker': {
                type: 'select',
                'options': {
                    'none':         'none',
                    'standard':     'standard',
                    'open-bubble':  'open-bubble'
                },
                'default': 'standard',
                'hint': 'Decide if you want to show marker on the map'
            },
            'marker_icon': {
                'type':     'input',
                'hint':     'optional, if empty default marker will be used'
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