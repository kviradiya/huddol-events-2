/* global jQuery */
/* global g1PageBuilder */
/* global ajaxurl */
/* global quicktags */
/* global QTags */

(function ($, context) {
    "use strict";

    /**
     * Base form field handler
     *
     * @param name
     * @param value
     * @param config
     */

    /* jshint unused:false */
    var baseField = function (name, $html) {
        var that = {}; // defines public scope

        /**
         * VARS
         */

        /**
         * METHODS
         */

        that.getName = function () {
            return name;
        };

        that.notify = function (type) {
            switch (type) {
                case 'added_to_DOM':
                    that.loaded();
                    break;

                case 'before_removing_from_DOM':
                    that.beforeDestroy();
                    break;

                case 'update':
                    that.beforeUpdate();
                    break;
            }
        };

        that.loaded = function () {};

        that.beforeDestroy = function () {};

        that.beforeUpdate = function () {};

        that.init = function () {};

        // abstract methods
        that.getValue = function () {};
        that.setValue = function () {};

        return that;
    };
    /* jshint unused:true */

    /**
     * Input field
     *
     */

    var inputField = function (name, $html) {
        var that = baseField(name, $html); // functional inheritance

        var $input;

        that.getValue = function () {
            return $input.val();
        };

        that.setValue = function (value) {
            $input.val(value);
        };

        // pseudo constructor
        function init () {
            that.init();

            $input = $html;
        }

        init();

        return that;
    };

    var inputHtmlField = function (name, value, config) {
        value = value || '';

        var field = '<input type="text" name="'+ name +'" value="'+ value +'" />';

        return createFieldHtml(name, value, config, field);
    };

    /**
     * Text field
     *
     */

    var textField = function (name, $html) {
        var that = baseField(name, $html); // functional inheritance

        var $textarea;

        that.getValue = function () {
            return $textarea.val();
        };

        that.setValue = function (value) {
            $textarea.val(value);
        };

        // pseudo constructor
        function init () {
            that.init();

            $textarea = $html;
        }

        init();

        return that;
    };

    var textHtmlField = function (name, value, config) {
        value = value || '';

        var field = '<textarea name="'+ name +'" >'+ value +'</textarea>';

        return createFieldHtml(name, value, config, field);
    };

    /**
     * Rich Text field
     *
     */

    var richTextField = function (name, $html) {
        var that = baseField(name, $html); // functional inheritance

        var $textarea;
        var $editor;
        var editorId;
        var origWpActiveEditorId;
        var tinyVersion;

        that.getValue = function () {
            if ($editor) {
                return $editor.find('#' + editorId).val();
            } else {
                return $textarea.val();
            }
        };

        that.setValue = function (value) {
            if ($editor) {
                $editor.find('#' + editorId).val(value);
            } else {
                $textarea.val(value);
            }
        };

        that.loaded = function () {
            if (typeof window.tinyMCE !== 'undefined') {
                loadTinyMCEEditor();
            }
        };

        that.beforeUpdate = function () {
            if ($editor) {
                $editor.find('.switch-html').trigger('click');
            }
        };

        that.beforeDestroy = function () {
            if ($editor) {
                $editor.find('.switch-html').trigger('click');
            }

            // restore original wp editor id
            wpActiveEditor = origWpActiveEditorId;
        };

        function loadTinyMCEEditor () {
            editorId = $textarea.attr('id') + '_tmce';

            // ajax call
            var xhr = $.ajax({
                'type': 'POST',
                'url':  ajaxurl,
                'data': {
                    action: 'g1_pb_load_wp_editor',
                    ajax_data: {
                        'content': that.getValue(),
                        'element_id': editorId
                    }
                }
            });

            // success response
            xhr.done(function(response) {
                if (response === '0' && response === '-1') {
                    return;
                }

                $textarea.show();

                if ($editor !== null) {
                    $editor.remove();
                }

                $editor = $(response);

                $editor.insertBefore($textarea);

                $textarea.hide();

                // add toolbar buttons for text editor
                quicktags({ 'id': editorId });

                // TinyMCE initializes toolbar button on document.ready
                // we loaded wp editor later so we need to run this function once again
                QTags._buttonsInit();

                // store original id
                origWpActiveEditorId = wpActiveEditor;

                // override it with our new loaded editor id
                wpActiveEditor = editorId;

                // mode switcher
                var $switch = $editor.find('.wp-switch-editor');

                // prevents default action
                $switch.removeAttr('onclick');

                // set up editor for chosen mode
                $switch.on('click', function () {
                    if ($(this).is('.switch-tmce')) {
                        switchToTinyEditor();
                    } else {
                        switchToHtmlEditor();
                    }

                    return false;
                });

                // set up current selected editor when it's loaded for the first time
                if ($editor.is('.tmce-active')) {
                    switchToTinyEditor();
                } else {
                    switchToHtmlEditor();
                }

            });
        }

        function switchToTinyEditor () {
            $editor.removeClass('html-active');
            $editor.addClass('tmce-active');

            if (tinyVersion >= 4) {
                window.tinyMCE.execCommand("mceAddEditor", true, editorId);
            } else {
                window.tinyMCE.execCommand("mceAddControl", true, editorId);
            }

            window.tinyMCE
                .get(editorId)
                .setContent(
                window.switchEditors.wpautop(that.getValue()),
                {
                    format:'raw'
                }
            );
        }

        function switchToHtmlEditor () {
            $editor.removeClass('tmce-active');
            $editor.addClass('html-active');

            if (tinyVersion >= 4) {
                window.tinyMCE.execCommand("mceRemoveEditor", true, editorId);
            } else {
                window.tinyMCE.execCommand("mceRemoveControl", true, editorId);
            }

            // The TinyMCE instance doesn't exist, run the content through 'pre_wpautop()' and show the textarea
            if ( typeof window.switchEditors.pre_wpautop === 'function' ) {
                setTimeout(function () {
                    var $editorTextarea = $editor.find('#' + editorId);

                    $editorTextarea.val(window.switchEditors.pre_wpautop($editorTextarea.val()));
                }, 10);
            }
        }

        // pseudo constructor
        function init () {
            that.init();

            $textarea = $html;
            $editor = null;
            tinyVersion = window.tinyMCE.majorVersion;
        }

        init();

        return that;
    };

    var richTextHtmlField = function (name, value, config) {
        value = value || '';

        var field = '<textarea name="'+ name +'" >'+ value +'</textarea>';

        return createFieldHtml(name, value, config, field);
    };

    /**
     * Select field
     *
     */
    var selectField = function (name, $html) {
        var that = baseField(name, $html); // functional inheritance

        var $select;

        that.getValue = function () {
            return $('option:selected', $select).val();
        };

        that.setValue = function (value) {
            $('option:selected', $select).removeAttr('selected');
            $('option[value="'+ value +'"]', $select).attr('selected', 'selected');
        };

        // pseudo constructor
        function init () {
            that.init();

            $select = $html;
        }

        init();

        return that;
    };

    var selectHtmlField = function (name, value, config) {
        if (typeof config.options === 'undefined' && typeof config.optionsCallback === 'undefined') {
            context.log('Field "'+ name +'" has no options defined!');
            return;
        }

        var options = config.options ? config.options : config.optionsCallback();

        var field = '<select name="'+ name +'">';

            if (typeof options !== 'undefined') {
                for (var optionId in options) {
                    if (options.hasOwnProperty(optionId)) {
                        var selected = optionId === value ? ' selected="selected"' : '';

                        field += '<option'+selected+' value="'+ optionId +'">'+ options[optionId] +'</option>';
                    }
                }
            }

        field += '</select>';

        return createFieldHtml(name, value, config, field);
    };

    /**
     * Colorpicker field
     *
     */

    var colorpickerField = function (name, $html) {
        var that = baseField(name, $html); // functional inheritance

        var $input;

        that.getValue = function () {
            return $input.val();
        };

        that.setValue = function (value) {
            $input.val(value);

            var $picker = $input.parents('.wp-picker-container');

            if ($picker.length > 0) {
                $input.detach();
                $input.removeClass('wp-color-picker');
                $input.insertBefore($picker);
                $picker.remove();
            }
        };

        that.loaded = function () {
            loadColorPicker();
        };

        function loadColorPicker () {
            if (typeof $.fn.wpColorPicker === 'function') {
                $input.wpColorPicker();
            } else {
                context.log('wpColorPicker is not defined!');
            }
        }

        // pseudo constructor
        function init () {
            that.init();

            $input = $html;
        }

        init();

        return that;
    };

    var colorpickerHtmlField = function (name, value, config) {
        value = value || '';

        var field = '<input type="text" name="'+ name +'" value="'+ value +'" />';

        return createFieldHtml(name, value, config, field);
    };

    /**
     * Helpers
     */

    /* jshint unused:false */
    var createFieldHtml = function (name, value, config, field) {
        var html = '';
        var id = getHtmlFieldIdFromConfig(config, name);
        var label = getHtmlFieldLabelFromConfig(config, name);
        var hint = context.helpers.getValueByKeyIfDefinedOrNull(config, 'hint');
        var description = context.helpers.getValueByKeyIfDefinedOrNull(config, 'description');
        var type = context.helpers.getValueByKeyIfDefinedOrNull(config, 'type');
        var cssClass = context.helpers.getValueByKeyIfDefinedOrNull(config, 'cssClass');

        type = type || 'input';
        cssClass = cssClass ? ' ' + cssClass : '';

        html += '<div class="g1-pb-form-row g1-pb-form-row-'+ type + cssClass +'">';

            // label
            html += '<div class="g1-pb-form-row-label">';
                html += '<label for="'+ id +'">'+ label +'</label>';
                html += createDescriptionHtml(description);
            html += '</div>';

            // field
            html += '<div class="g1-pb-form-row-field">';
                html += $(field).attr('id', id).get(0).outerHTML;
                html += createHintHtml(hint);
            html += '</div>';

        html += '</div>';

        return html;
    };
    /* jshint unused:true */

    var createHintHtml = function (hint) {
        var html = '';

        if (hint) {
            html += '<div class="g1-pb-form-row-hint">';
            html += hint;
            html += '</div>';
        }

        return html;
    };

    var createDescriptionHtml = function (description) {
        var html = '';

        if (description) {
            html += '<div classs="g1-pb-form-row-description">';
            html += '<a href="#" class="g1-pb-form-row-description-toggle"></a>';
            html += '<div class="g1-pb-form-row-description-content">';
            html += description;
            html += '</div>';
            html += '</div>';
        }

        return html;
    };

    var getHtmlFieldLabelFromConfig = function (config, defaultName) {
        var label;

        if (config && typeof config.label !== 'undefined') {
            label = config.label;
        } else {
            label = $.trim(defaultName.replace('_', ' '));
            label = label[0].toUpperCase() + label.substr(1);
        }

        return label;
    };

    var getHtmlFieldIdFromConfig = function (config, defaultId) {
        if (config && typeof config.id !== 'undefined') {
            return config.id;
        } else {
            return 'g1_pb_form_row_field_' + defaultId;
        }
    };

    var getFieldHandler = function (type) {
        return typeof context.classes.fields[type] ? context.classes.fields[type] : null;
    };

    var getHtmlFieldHandler = function (type) {
        return typeof context.classes.htmlFields[type] ? context.classes.htmlFields[type] : null;
    };

    /**
     * binds to global context
     */

    // fields
    context.classes.fields.baseField = baseField;
    context.classes.fields.input = inputField;
    context.classes.fields.select = selectField;
    context.classes.fields.text = textField;
    context.classes.fields.richtext = richTextField;
    context.classes.fields.colorpicker = colorpickerField;

    // html fields
    context.classes.htmlFields.input = inputHtmlField;
    context.classes.htmlFields.select = selectHtmlField;
    context.classes.htmlFields.text = textHtmlField;
    context.classes.htmlFields.richtext = richTextHtmlField;
    context.classes.htmlFields.colorpicker = colorpickerHtmlField;

    // helpers
    context.helpers.getFieldHandler = getFieldHandler;
    context.helpers.getHtmlFieldHandler = getHtmlFieldHandler;

})(jQuery, g1PageBuilder);