(function ($, context) {
    "use strict";

    var elementUniqueId = 'wrong_element';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'wrong-element',
        cssElementClass: 'wrong-element',
        label: 'Unknown element'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            type: 'richtext'
        };

        var fieldsConfig = null;

        /**
         * METHODS
         */

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