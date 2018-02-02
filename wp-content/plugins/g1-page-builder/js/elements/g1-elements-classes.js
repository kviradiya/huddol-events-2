/* global jQuery */
/* global g1PageBuilder */

(function ($, context) {
    "use strict";

    var baseElement = function () {
        var that = {};

        /**
         * VARS
         */

        var fields;
        var fieldsConfig;
        var fieldsValues;

        var content;
        var contentValue;
        var contentConfig;

        // edit form
        var $form;
        var fieldsObjects; // form fields

        var DEFAULT_FIELD_TYPE = 'input';
        var CONTENT_FIELD_NAME = '_content';

        // pseudo constructor
        function init (values, fieldsConf, content, contentConf) {
            fieldsValues = values;
            fieldsConfig = fieldsConf || {};
            contentValue = content || '';
            contentConfig = contentConf || null;

            if (that.useCommonFields()) {
                // each element has the same common fields (id, class)
                fieldsConfig = $.extend(getCommonFieldsConfig(), fieldsConfig);
            }
        }

        /**
         * METHODS
         */
        that.isAsync = function () {
            return false;
        };

        that.useCommonFields = function () {
            return true;
        };

        that.convertToText = function (content) {
            return content;
        };

        function getCommonFieldsConfig () {
            return {
                'id': {
                    'description': 'The id attribute specifies an id for an HTML element. It must be unique within the HTML document. (Mainly for additional styling/scripting purposes)'
                },
                'class': {
                    'description': 'The class attribute specifies a class name for an HTML element. (Mainly for additional styling/scripting purposes)'
                }
            };
        }

        that.setForm = function ($html) {
            $form = $html;
        };

        that.getForm = function () {
            return $form;
        };

        that.isFormSet = function () {
            return typeof $form !== 'undefined';
        };

        that.getFieldsValues = function () {
            return getFields();
        };

        that.getContentValue = function () {
            return contentValue;
        };

        that.setContentValue = function (value) {
            contentValue = value;
        };

        that.hasContent = function () {
            return contentConfig !== null;
        };

        that.getContent = function () {
            return content;
        };

        function getFormHtml () {
            if (!that.isFormSet()) {
                var formHtml = that.buildForm();

                $form = $('<form class="g1-pb-form">').append(formHtml);

                bindHtmlFieldsToObjectFields();
                that.bindEvents($form);
            }

            return $form;
        }

        // treat this method as hook

        /* jshint unused:false */
        that.bindEvents = function ($form) {};
        /* jshint unused:true */

        function bindHtmlFieldsToObjectFields () {
            fieldsObjects = [];

            for (var fieldName in fieldsConfig) {
                if (fieldsConfig.hasOwnProperty(fieldName)) {
                    var $html = $form.find('#g1_pb_form_row_field_' + fieldName);

                    if ($html.length > 0) {
                        createField(fieldName, $html);
                    }
                }
            }

            if (that.hasContent()) {
                var $contentHtml = $form.find('#g1_pb_form_row_field_' + CONTENT_FIELD_NAME);
                var contentType = contentConfig && typeof contentConfig.type !== 'undefined' ? contentConfig.type : DEFAULT_FIELD_TYPE;

                var fieldHandler =  context.helpers.getFieldHandler(contentType);

                if (!fieldHandler) {
                    context.log('Form field handler for type "'+ contentType +'" doesn\'t exist!');
                    return;
                }

                content = fieldHandler(CONTENT_FIELD_NAME, $contentHtml);
            }
        }

        function loadFields () {
            fields = {};

            if (!fieldsConfig || !fieldsValues) {
                return;
            }

            // fieldsConfig - all available element attributes (from config)
            // fieldsValues - values for fields that were set for this element (from markup)

            for (var fieldName in fieldsConfig) {
                if (fieldsConfig.hasOwnProperty(fieldName)) {
                    fields[fieldName] = getInitialFieldValue(fieldName);
                }
            }
        }

        function getInitialFieldValue (fieldName) {
            var value = '';

            // lets assume that attribute was set for element
            var fieldValue = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, fieldName);

            // if yes, return its value
            if (fieldValue) {
                value = fieldValue;
                // if no, we can try to set default value (can be set in config but it's not required)
            } else {
                var fieldConfig = fieldsConfig[fieldName];
                var defaultValue = context.helpers.getValueByKeyIfDefinedOrNull(fieldConfig, 'default');

                if (defaultValue) {
                    value = defaultValue;
                }
            }

            return value;
        }

        function buildForm () {
            var itemsHtml = '';

            for (var fieldName in fieldsConfig) {
                if (fieldsConfig.hasOwnProperty(fieldName)) {
                    var config = fieldsConfig[fieldName];

                    var fieldType = config && typeof config.type !== 'undefined' ? config.type : DEFAULT_FIELD_TYPE;

                    var html = renderField(fieldType, fieldName, null, config);

                    if (html) {
                        itemsHtml += '<li>' + html + '</li>';
                    }
                }
            }

            if (that.hasContent()) {
                var contentType = contentConfig && typeof contentConfig.type !== 'undefined' ? contentConfig.type : DEFAULT_FIELD_TYPE;

                itemsHtml += '<li>' + renderField(contentType, CONTENT_FIELD_NAME, contentValue, contentConfig) + '</li>';
            }

            if (itemsHtml.length === 0) {
                itemsHtml = '<li>' + 'This element has no fields and content defined.' + '</li>';
            }

            // compose
            var out = '<ul class="g1-pb-form-rows">';
            out += itemsHtml;
            out += '</ul>';

            return out;
        }

        function createField (name, $html) {
            if (typeof fieldsConfig[name] === 'undefined') {
                context.log('Field "'+ name +'" is not defied as element field!');
                return null;
            }

            var config = fieldsConfig[name];
            var fieldType = config !== null && typeof config.type !== 'undefined' ? config.type : DEFAULT_FIELD_TYPE;
            var fieldHandler =  context.helpers.getFieldHandler(fieldType);

            if (!fieldHandler) {
                context.log('Form field handler for type "'+ fieldType +'" doesn\'t exist!');
                return $();
            }

            var fieldObject = fieldHandler(name, $html);

            fieldsObjects.push(fieldObject);

            return fieldObject;
        }

        function sendNotificationToFields (type) {
            for (var i = 0; i < fieldsObjects.length; i += 1) {
                var field = fieldsObjects[i];

                field.notify(type);
            }

            if (that.hasContent()) {
                that.getContent().notify(type);
            }
        }

        function update () {
            for (var i = 0; i < fieldsObjects.length; i += 1) {
                var field = fieldsObjects[i];

                setFieldValue(field.getName(), field.getValue());
            }

            if (that.hasContent()) {
                that.setContentValue(content.getValue());
            }
        }

        function revertFormFieldsValues() {
            for (var i = 0; i < fieldsObjects.length; i += 1) {
                var field = fieldsObjects[i];
                var oldValue = getFieldValue(field.getName());

                field.setValue(oldValue);
            }

            if (that.hasContent()) {
                content.setValue(that.getContentValue());
            }
        }

        that.beforeNotify = function (type) {};

        that.beforeDestroy = function () {};

        function notify (type) {
            // element hook (custom elements eg. tabs can use this hook)
            that.beforeNotify(type);

            // all element fields are notified before real action
            sendNotificationToFields(type);

            switch (type) {
                case 'update':
                    update();
                    break;

                case 'revert_changes':
                    revertFormFieldsValues();
                    break;

                case 'before_removing_from_DOM':
                    that.beforeDestroy();
                    break;
            }
        }

        function setFieldValue (name, value) {
            var fields = getFields();

            if (typeof fields[name] !== 'undefined') {
                fields[name] = value;
            }
        }

        function getFieldValue (name) {
            var fields = getFields();

            if (typeof fields[name] !== 'undefined') {
                return fields[name];
            }

            return '';
        }

        function getFields () {
            // lazy loading
            if (typeof fields === 'undefined') {
                loadFields();
            }

            return fields;
        }

        function renderField (type, name, fieldValue, config) {
            if (typeof config === 'undefined') {
                config = typeof fieldsConfig[name] !== 'undefined' ? fieldsConfig[name] : null;
            }

            var value = fieldValue ? fieldValue : getInitialFieldValue(name);
            var htmlFieldHandler = context.helpers.getHtmlFieldHandler(type);

            if (htmlFieldHandler === null) {
                context.log('Html field handler for type "'+ type +'" not exists!');
                return '';
            }

            var html = htmlFieldHandler(name, value, config);

            return html;
        }

        function renderInputField (name, value, config) {
            return renderField('input', name, value, config);
        }

        function renderSelectField (name, value, config) {
            return renderField('select', name, value, config);
        }

        function renderTextField (name, value, config) {
            return renderField('text', name, value, config);
        }

        function renderRichTextField (name, value, config) {
            return renderField('richtext', name, value, config);
        }

        function renderColorPickerField (name, value, config) {
            return renderField('colorpicker', name, value, config);
        }

        function getBuilderElementRepresentation () {
            return that.getContentValue();
        }

        // public scope
        that.CONTENT_FIELD_NAME = CONTENT_FIELD_NAME;

        that.init = init;
        that.notify = notify;
        that.getFormHtml = getFormHtml;
        that.getFields = getFields;
        that.buildForm = buildForm;
        that.getBuilderElementRepresentation = getBuilderElementRepresentation;

        // html helpers
        that.renderInputField = renderInputField;
        that.renderSelectField = renderSelectField;
        that.renderTextField = renderTextField;
        that.renderRichTextField = renderRichTextField;
        that.renderColorPickerField = renderColorPickerField;

        return that;
    };

    /**
     * binds to global context
     */

    // elements
    context.classes.elements.baseElement = baseElement;

})(jQuery, g1PageBuilder);