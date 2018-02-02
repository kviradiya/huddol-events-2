(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-stats';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-stats',
        cssElementClass: 'bbp-stats',
        toolbarSection: 'bbpress',
        label: 'Stats',
        description: 'Display the forum statistics.'
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