(function ($, context) {
    "use strict";

    var elementUniqueId = 'precontent';

    var pbBlock = {
        level: 1,
        withControls: true,
        disableResizeControls: true,
        disableEditControl: true,
        cssBlockClass: 'precontent',
        cssElementClass: 'precontent',
        toolbarSection: 'layout_elements',
        label: 'Precontent',
        description: 'A special theme area'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = null;

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