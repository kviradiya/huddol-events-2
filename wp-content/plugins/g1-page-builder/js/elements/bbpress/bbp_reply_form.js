(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-reply-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-reply-form',
        cssElementClass: 'bbp-reply-form',
        toolbarSection: 'bbpress',
        label: 'Reply form',
        description: 'Display the "New Reply" form.'
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