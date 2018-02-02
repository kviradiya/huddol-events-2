(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-search-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-search-form',
        cssElementClass: 'bbp-search-form',
        toolbarSection: 'bbpress',
        label: 'Search form',
        description: 'Display the search form template.'
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