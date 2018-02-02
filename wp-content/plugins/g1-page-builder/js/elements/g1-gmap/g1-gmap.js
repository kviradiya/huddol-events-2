(function ($, context) {
    "use strict";

    var elementUniqueId = 'g1_gmap';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'g1gmap',
        cssElementClass: 'g1gmap',
        toolbarSection: 'content_elements',
        label: 'G1 GMap'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    function fetchMapList ($obj) {
        // default response, we assume that something will go wrong
        var list = {
            'error': 'An error has occurred while trying to fetch map list'
        };

        var xhr = $.ajax({
            'async': false,  // don't make here async ajax call
            'type': 'POST',
            'url':  ajaxurl,
            'data': {
                action: 'g1_pb_load_map_list'
            },
            dataType: 'json'
        });

        xhr.done(function(response) {
            if (response !== '0' && response !== '-1') {
                switch (response.type) {
                    case 'error':
                        list.error = response.message;
                        break;

                    case 'success':
                        list = response.list;
                        break;
                }
            }
        });

        return list;
    }

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'map_id': {
                'type':     'select',
                'optionsCallback': fetchMapList
            }
        };

        /**
         * METHODS
         */

        that.isAsync = function () {
            return true;
        };

        that.useCommonFields = function () {
            return false;
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