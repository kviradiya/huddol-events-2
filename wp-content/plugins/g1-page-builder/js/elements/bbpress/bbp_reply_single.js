(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-single-reply';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-reply',
        cssElementClass: 'bbp-single-reply',
        toolbarSection: 'bbpress',
        label: 'Single reply',
        description: 'Display a single reply.'
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
                'hint': 'Reply id'
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