(function ($, context) {
    "use strict";

    var elementUniqueId = 'divider_top';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'divider-top',
        cssElementClass: 'divider-top',
        toolbarSection: 'content_elements',
        label: 'Divider Top',
        description: 'A horizontal divider with a "back to top" link.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
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