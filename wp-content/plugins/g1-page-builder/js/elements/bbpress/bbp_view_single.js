(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-single-view';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-view',
        cssElementClass: 'bbp-single-view',
        toolbarSection: 'bbpress',
        label: 'Single view',
        description: 'Display topics associated with a specific view.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'View name eg. "no-replies" or "popular".'
            }
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