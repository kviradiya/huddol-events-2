(function ($, context) {
    "use strict";

    var elementUniqueId = 'mailchimp_newsletter';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'mailchimp',
        cssElementClass: 'mailchimp',
        toolbarSection: 'content_elements',
        label: 'Mailchimp Newsletter',
        description: 'A newsletter subscribe/signup form.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    function fetchMailingList () {
        // default response, we assume that something will go wrong
        var list = {
            'error': 'An error has occurred while trying to fetch mailing list'
        };

        var xhr = $.ajax({
            'async': false,  // don't make here async ajax call
            'type': 'POST',
            'url':  ajaxurl,
            'data': {
                action: 'g1_pb_load_mailing_list'
            },
            dataType: 'json'
        });

        xhr.done(function(response) {
            if (response !== '0' && response !== '-1') {
                switch (response.type) {
                    case 'error':
                        list.error = response.message;
                        break;

                    case 'success':
                        list = response.list;
                        break;
                }
            }
        });

        return list;
    }

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'label': 'Text before',
            'default': '',
            'hint': 'Text to show above subscription form'
        };

        var fieldsConfig = {
            'mailing_list': {
                'type':     'select',
                'optionsCallback': fetchMailingList
            }
        };

        // pseudo constructor
        function init () {
            that.init(fieldsValues, fieldsConfig, contentValue, contentConfig);
        }

        that.isAsync = function () {
            return true;
        };

        init();

        // public scope

        return that; // gives access to public methods
    };

    context.registerElement(elementUniqueId, pbElement);
})(jQuery, g1PageBuilder);