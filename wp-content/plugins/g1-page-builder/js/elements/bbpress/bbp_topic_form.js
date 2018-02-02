(function ($, context) {
    "use strict";

    var elementUniqueId = 'bbp-topic-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-topic-form',
        cssElementClass: 'bbp-topic-form',
        toolbarSection: 'bbpress',
        label: 'Topic form',
        description: 'Display the "New Topic" form.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'forum_id': {
                'type': 'input',
                'hint': 'If you leave this field empty, you can choose from a drop down menu the forum that this topic is to be associated with.'
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