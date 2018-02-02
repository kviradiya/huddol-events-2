(function ($, context) {
    "use strict";

    var elementUniqueId = 'g1_bbp_forums';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'g1-bbp-forums',
        cssElementClass: 'g1-bbp-forums',
        toolbarSection: 'bbpress',
        label: 'Forum collection',
        description: 'Display forums as a collection.'
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
            return true;
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