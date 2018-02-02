(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-lost-pass';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-lost-pass',
        cssElementClass: 'bbp-lost-pass',
        toolbarSection: 'bbpress',
        label: 'Lost password',
        description: 'Display the lost password screen.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

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