(function ($, context) {
    "use strict";

    var elementUniqueId = 'html_block';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'html',
        cssElementClass: 'html',
        toolbarSection: 'content_elements',
        label: 'HTML',
        description: 'Here you can put any HTML content',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        contentValue = contentValue.replace(/^\n+|\n+$/g , "");
        contentValue = contentValue.replace(/\n/g , "<br />");

        return contentValue;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'richtext'
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