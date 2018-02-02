(function ($, context) {
    "use strict";

    var elementUniqueId = 'contact_form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'contact-form',
        cssElementClass: 'contact-form',
        toolbarSection: 'content_elements',
        label: 'Contact Form',
        description: 'A simple contact form with just basic fields.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'email': {
                'type':     'input',
                'hint':     'The recipient\'s email'
            },
            'name': {
                'type':     'input',
                'hint':     'The recipient\'s name'
            },
            'subject': {
                'type':     'input',
                'hint':     'The subject of the email'
            },
            'success': {
                'type':     'input',
                'hint':     'The text to display after sending an email successfully'
            },
            'failure': {
                'type':     'input',
                'hint':     'The text to display, if the contact  form has errors'
            },
            'submit_method': {
                'type':     'select',
                'options':  {
                    'standard':     'standard',
                    'ajax':         'AJAX'
                },
                'default':  'standard'
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