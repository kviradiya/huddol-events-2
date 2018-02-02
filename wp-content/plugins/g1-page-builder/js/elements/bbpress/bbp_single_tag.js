(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-single-tag';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-tag',
        cssElementClass: 'bbp-single-tag',
        toolbarSection: 'bbpress',
        label: 'Single tag',
        description: 'Display a list of all topics associated with a specific tag.'
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
                'hint': 'Tag id.'
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