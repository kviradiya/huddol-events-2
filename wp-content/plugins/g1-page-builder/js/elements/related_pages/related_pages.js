(function ($, context) {
    "use strict";

    var elementUniqueId = 'related_pages';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'related-pages',
        cssElementClass: 'related-pages',
        toolbarSection: 'collections_elements',
        label: 'Related Pages',
        description: 'A collection of related pages.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'max': {
                'type':     'input',
                'hint':     'Maximum entries to display'
            },
            'entry_id': {
                'type':     'input',
                'hint':     'Entry ID - leave empty to use the current entry ID'
            },
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':       'one_fourth',
                    'one_third':        'one_third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,author,summary,button-1'
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