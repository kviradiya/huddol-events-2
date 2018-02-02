(function ($, context) {
    "use strict";

    var elementUniqueId = 'tabs';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'tabs',
        cssElementClass: 'tabs',
        toolbarSection: 'content_elements',
        label: 'Tabs',
        description: 'A content area with multiple panels.',
        htmlRepresentationCallback: renderBlock
    };

    /* jshint unused:true */
    function renderBlock (fieldsValues, contentValue) {
        return '';
    }
    /* jshint unused:false */

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Button element class
     */
    var tabs = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'position': {
                'type': 'select',
                'options': {
                    'top-left':     'top-left',
                    'top-center':   'top-center',
                    'top-right':    'top-right',
                    'bottom-left':  'bottom-left',
                    'bottom-center':'bottom-center',
                    'bottom-right': 'bottom-right',
                    'left-top':     'left-top',
                    'right-top':    'right-top'
                },
                'default': 'top-left'
            },
            'style': {
                'type': 'select',
                'options': {
                    'simple':       'simple',
                    'button':       'button',
                    'transparent':  'transparent'
                },
                'default': 'simple'
            },
            'type': {
                'type': 'select',
                'options': {
                    'click':        'change tab on click',
                    'hover':        'change tab on hover'
                },
                'default': 'click'
            }
        };

        var currentTabIndex = 0;
        var origWpActiveEditorId;
        var tinyVersion;

        // custom tabs template
        that.buildForm = function () {
            var html = '<ul class="g1-pb-form-rows">';

            html += '<li>' + that.renderSelectField('position') + '</li>';
            html += '<li>' + that.renderSelectField('style') + '</li>';
            html += '<li>' + that.renderSelectField('type') + '</li>';
            html += '<li>' + renderTabs() + '</li>';
            html += '<li class="g1-pb-form-row-field-content">' + that.renderTextField(that.CONTENT_FIELD_NAME, that.getContentValue(), contentConfig) + '</li>';

            html += '</ul>';

            return html;
        };

        that.bindEvents = function ($form) {
            // apply sortable interface
            if (typeof $.fn.sortable === 'function') {
                $form.find('.g1-pb-tabs-nav-menu').sortable({
                    items: "li:not(.g1-pb-tab-empty)"
                });
            }

            // actions: add, select and remove tab
            $form.find('.g1-pb-tabs-nav-menu').on('click', function (e) {
                var $target = $(e.target);
                var $tab = $target.parents('li');
                var tabIndex = getIndex($tab);

                if ($target.is('.g1-pb-tab-action-remove')) {
                    if (!confirm('Are you sure you want to delete this tab?')) {
                        return false;
                    }

                    var isSelected = $tab.is('.g1-pb-tab-selected');

                    removeTab($form, tabIndex);

                    if (isSelected) {
                        selectTab($form, getFirstTabIndex());
                    }

                    return false;
                }

                if ($target.is('.g1-pb-tab-action-add')) {
                    addNewTab($form, currentTabIndex);
                }

                if ($target.is('.g1-pb-tab-title')) {
                    selectTab($form, tabIndex);
                }

                return false;
            });

            // update tab title
            $form.find('.g1-pb-tabs-viewport-items').on('keyup', function (e) {
                var $target = $(e.target);

                if ($target.is('input[name="title"]')) {
                    var $viewport = $target.parents('li');
                    var $navItem = $form.find('.g1-pb-tabs-nav-menu > li[data-g1-index='+ getIndex($viewport) +']');

                    $navItem.find('.g1-pb-tab-title').html($target.val());

                }
            });

            // initial state

            // store current wp active editor
            origWpActiveEditorId = wpActiveEditor;

            selectTab($form, getFirstTabIndex());

            // hide content
            $form.find('.g1-pb-form-row-field-content').hide();
        };

        function getFirstTabIndex () {
            var $firstTab = that.getForm().find('.g1-pb-tabs-nav-menu > li:first');

            if ($firstTab.length > 0) {
                return getIndex($firstTab);
            }

            return null;
        }

        that.beforeNotify = function (type) {
            if (type === 'update') {
                switchAllLoadedEditorsToTextMode();
                copyContentFromRichEditorToTextarea();

                // update element content field.
                // we need to handle it manually because we use it only as a wrapper for content, not as real element content
                that.getContent().setValue(convertTabsToHtml());
            }
        };

        function switchAllLoadedEditorsToTextMode () {
            var $form = that.getForm();

            $form.find('.g1-pb-tabs-viewport-items .wp-switch-editor.switch-html').trigger('click');
        }

        function copyContentFromRichEditorToTextarea () {
            var $form = that.getForm();

            $form.find('.g1-pb-tabs-viewport-items li').each(function () {
                var $richEditor = $(this).find('textarea.wp-editor-area');

                if ($richEditor.length > 0) {
                    var $simpleEditor = $(this).find('textarea[name=content]');

                    $simpleEditor.val($richEditor.val());
                }
            });
        }

        that.isFormSet = function () {
            // this way form won't be cached
            return false;
        };

        function getIndex ($tab) {
            return parseInt($tab.attr('data-g1-index'), 10);
        }

        function selectTab ($form, index) {
            var $navs = $form.find('.g1-pb-tabs-nav-menu > li');
            var $viewports = $form.find('.g1-pb-tabs-viewport-items > li');

            // quit if no tabs
            if ($viewports.length === 0) {
                return;
            }

            // remove selection from all
            $navs.removeClass('g1-pb-tab-selected');
            $viewports.removeClass('g1-pb-tab-selected');

            // select tab
            $navs.filter('[data-g1-index='+ index +']').addClass('g1-pb-tab-selected');
            var $viewport = $viewports.filter('[data-g1-index='+ index +']');

            $viewport.addClass('g1-pb-tab-selected');
            loadTinyMCEEditor($viewport, 'g1-pb-content-rich' + index);
        }

        function addNewTab ($form, index) {
            var newTab = {
                title: 'New tab',
                content: 'Tab content goes here...'
            };

            var navItem = renderNavItem(newTab);
            var viewportItem = renderViewportItem(newTab);

            // add before last tab (empty tab)
            $form.find('li.g1-pb-tab-empty').before(navItem);

            $form.find('.g1-pb-tabs-viewport-items').append(viewportItem);

            selectTab($form, index);
        }

        function removeTab ($form, index) {
            var $nav = $form.find('.g1-pb-tabs-nav-menu > li[data-g1-index='+ index +']');
            var $viewport = $form.find('.g1-pb-tabs-viewport-items > li[data-g1-index='+ index +']');

            // switch to text mode
            $viewport.find('.wp-switch-editor.switch-html').trigger('click');

            $nav.remove();
            $viewport.remove();
        }

        function renderTabs () {
            var tabs = parseHtmlToTabsElements(that.getContentValue());
            var tabsCount = tabs.length;
            var html = '';
            var navHtml = '';
            var viewportHtml = '';

            navHtml += '<ul class="g1-pb-tabs-nav-menu">';
            viewportHtml += '<ul class="g1-pb-tabs-viewport-items">';

            for (var i = 0; i < tabsCount; i += 1) {
                var tab = tabs[i];

                navHtml += renderNavItem(tab);
                viewportHtml += renderViewportItem(tab);
            }

            // "add new" tab
            navHtml +=  '<li class="g1-pb-tab-empty">' +
                            '<a href="#" class="g1-pb-tab-action-add">New</a>' +
                        '</li>';

            navHtml += '</ul>';
            viewportHtml += '</ul>';

            html += '<div class="g1-pb-tabs">';
                html += '<div class="g1-pb-tabs-nav">';
                    html += navHtml;
                html += '</div>';

                html += '<div class="g1-pb-tabs-viewport">';
                    html += viewportHtml;
                html += '</div>';

            html += '</div>';

            return html;
        }

        function renderNavItem (tab) {
            var html = '';

            html += '<li data-g1-index="'+ currentTabIndex +'">';
                html += '<a href="#" class="g1-pb-tab-title">'+ tab.title +'</a>';
                html += '<a href="#" class="g1-pb-tab-action-remove">Remove</a>';
            html += '</li>';

            return html;
        }

        function renderViewportItem (tab) {
            var html = '';

            var titleHtml = that.renderInputField(
                'title',
                tab.title,
                {
                    label: 'Tab title',
                    id: '',
                    cssClass: 'g1-pb-tabs-nav-viewport-title'
                }
            );

            var contentHtml = context.classes.htmlFields.text(
                'content',

                tab.content,
                {
                    label: 'Tab content',
                    id: '',
                    cssClass: 'g1-pb-tabs-nav-viewport-content'
                }
            );

            html += '<li data-g1-index="'+ currentTabIndex +'">';
                html += titleHtml;
                html += contentHtml;
            html += '</li>';

            currentTabIndex++;

            return html;
        }

        that.beforeDestroy = function () {
            that.getForm().find('.switch-html').trigger('click');

            wpActiveEditor = origWpActiveEditorId;
        };

        function loadTinyMCEEditor ($viewport, editorId) {
            // override it with our new loaded editor id
            wpActiveEditor = editorId;

            if ($viewport.hasClass('g1-pb-tab-loaded')) {
                return;
            }

            $viewport.addClass('g1-pb-tab-loading');

            var $textarea = $viewport.find('.g1-pb-tabs-nav-viewport-content textarea');
            var $editor;

            var getValue = function () {
                if ($editor) {
                    return $editor.find('#' + editorId).val();
                } else {
                    return $textarea.val();
                }
            };

            // ajax call
            var xhr = $.ajax({
                'type': 'POST',
                'url':  ajaxurl,
                'data': {
                    action: 'g1_pb_load_wp_editor',
                    ajax_data: {
                        'content': $textarea.val(),
                        'element_id': editorId
                    }
                }
            });

            // success response
            xhr.done(function(response) {
                if (response === '0' && response === '-1') {
                    return;
                }

                $editor = $(response);

                $editor.insertBefore($textarea);

                $textarea.hide();

                // add toolbar buttons for text editor
                quicktags({ 'id': editorId });

                // TinyMCE initializes toolbar button on document.ready
                // we loaded wp editor later so we need to run this function once again
                QTags._buttonsInit();

                // mode switcher
                var $switch = $editor.find('.wp-switch-editor');

                // prevents default action
                $switch.removeAttr('onclick');

                // set up editor for chosen mode
                $switch.on('click', function () {
                    if ($(this).is('.switch-tmce')) {
                        switchToTinyEditor($editor, editorId, getValue());
                    } else {
                        switchToHtmlEditor($editor, editorId);
                    }

                    return false;
                });

                // set up current selected editor when it's loaded for the first time
                if ($editor.is('.tmce-active')) {
                    switchToTinyEditor($editor, editorId, getValue());
                } else {
                    switchToHtmlEditor($editor, editorId);
                }

                $viewport.removeClass('g1-pb-tab-loading');
                $viewport.addClass('g1-pb-tab-loaded');
            });
        }

        function switchToTinyEditor ($editor, editorId, value) {
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
                window.switchEditors.wpautop(value),
                {
                    format:'raw'
                }
            );
        }

        function switchToHtmlEditor ($editor, editorId) {
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

        function convertTabsToHtml () {
            var html = '';
            var $form = that.getForm();
            var $navs = $form.find('.g1-pb-tabs-nav-menu > li:not(.g1-pb-tab-empty)');
            var $viewports = $form.find('.g1-pb-tabs-viewport-items > li');

            $navs.each(function () {
                var $navItem = $(this);
                var index = getIndex($navItem);

                $navItem = $navItem.find('.g1-pb-tab-title');
                var $content = $viewports.filter('[data-g1-index='+ index +']').find('textarea[name=content]');

                html += '<div class="g1-shortcode" data-g1-name="tab_title" data-g1-attrs-list="">'+ $navItem.html() +'</div>';
                html += '<div class="g1-shortcode" data-g1-name="tab_content" data-g1-attrs-list="">'+ $content.val() +'</div>';
            });

            return html;
        }

        function parseHtmlToTabsElements (html) {
            var tabs = [];
            var tabIndex = 0;

            $(html).each(function () {
                var $this = $(this);

                if ($this.is('.g1-shortcode')) {
                    if (typeof tabs[tabIndex] === 'undefined') {
                        tabs[tabIndex] = {};
                    }

                    var shortcodeName = $this.attr('data-g1-name');
                    var shortcodeContent = $this.html();

                    if (shortcodeName === 'tab_title') {
                        tabs[tabIndex].title = shortcodeContent;
                    }

                    if (shortcodeName === 'tab_content') {
                        tabs[tabIndex].content = shortcodeContent;
                        tabIndex++;
                    }
                }
            });

            // add new tab at start
            if (tabs.length === 0) {
                tabs[0] = {
                    title: 'New tab',
                    content: 'Tab content goes here...'
                };
            }

            return tabs;
        }

        that.convertToText = function (content) {
            var out = '';

            $(content).each(function () {
                var $this = $(this);

                if ($this.is('.g1-shortcode')) {
                    var shortcodeName = $this.attr('data-g1-name');

                    out += '\n\n['+ shortcodeName +']\n\n' + $this.html() + '\n\n[/'+ shortcodeName +']\n\n';
                }
            });

            return out;
        };

        function init () {
            that.init(fieldsValues, fieldsConfig, contentValue, contentConfig);
            tinyVersion = window.tinyMCE.majorVersion;
        }

        // pseudo constructor
        init();

        return that; // gives access to public methods
    };

    context.registerElement(elementUniqueId, tabs);
})(jQuery, g1PageBuilder);