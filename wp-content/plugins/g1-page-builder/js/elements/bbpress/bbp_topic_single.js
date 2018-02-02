(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-single-topic';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-topic',
        cssElementClass: 'bbp-single-topic',
        toolbarSection: 'bbpress',
        label: 'Single topic',
        description: 'Display a single forums topics.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        that.useCommonFields = function () {
            return false;
        };

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'Topic id'
            }
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