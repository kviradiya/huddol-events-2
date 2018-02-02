(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-topic-index';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-topic-index',
        cssElementClass: 'bbp-topic-index',
        toolbarSection: 'bbpress',
        label: 'Topic index',
        description: 'Display the most recent topics across all forums.'
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