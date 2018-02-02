/* global jQuery */
/* global g1PageBuilder */
/* global g1_page_builder_state */
/* global wp */
/* global alert */
/* global tinymce */

(function ($, context) {
    "use strict";

    /**
     * g1Builder class
     */
    var g1Builder = function ($classicEditorWrapper, options) {
        var that = {}; // defines public scope

        /**
         * CONFIG
         */
        var globalOptions = {
            'dropSuggestions': true
        };

        options = $.extend(globalOptions, options);

        /**
         * VARS
         */

        // main wrapper (switcher + components)
        var $pageBuilderWrapper;

        // wrapper for all builder components (controls + elements)
        var $pageBuilderWrapperInner;

        // holds builder controls
        var $pageBuilderToolbar;

        // wrapper for all builder elements
        var $pageBuilderContent;

        // main builder element
        var $pageBuilderMainElement;

        // switch between editors
        var $pageBuilderOnOffSwitch;

        // editor state holder
        var $pageBuilderStateHolder;

        // return to text content without conversion
        var $pageBuilderCancelButton;

        var autosaveEnabled;

        // editor states
        var currentState = null;
        var ACTIVE_STATE = 'active';
        var INACTIVE_STATE = 'inactive';

        var draggingElementLevel;

        var i18n = {
            'loading_info': 'Loading...',
            'builder_cannot_be_loaded': 'You are not using default WP editor. Builder cannot be used',
            'switch_on_builder': 'Change to the Page Builder',
            'switch_off_builder': 'Change to the Default Editor',
            'switch_cancel_builder': 'Cancel',
            'cancel_builder_tooltip': 'If something is wrong with your page layout, please click "Cancel" to return to the original content and check your markup.'
        };

        /**
         * METHODS
         */

            // pseudo constructor
        function init () {
            createPageBuilderComponents();
            bindEvents();
            createOnOffSwitch();
            initEditorState();

            if (isEditorActive()) {
                activateBuilderEditor(true);
            }
        }

        function createPageBuilderComponents () {
            // HIERARCHY of elements:
            // level 0 - main area
            // level 1 - section
            // level 2 - grid element (eg. column)
            // level 3 - content element
            //
            // DRAG capability:
            // each element can be dragged besides element with level 0
            //
            // DROP capability:
            // each element can be dropped onto placeholder

            // builds main components
            $pageBuilderWrapper = $('<div class="g1-pb g1-pb-off g1-pb-not-dragging">');
            $pageBuilderWrapperInner = $('<div class="g1-pb-inner">');
            $pageBuilderToolbar = $('<div class="g1-pb-toolbar">');
            $pageBuilderContent = $('<div class="g1-pb-content">');

            // composes initial state
            $pageBuilderWrapperInner.append($pageBuilderToolbar);
            $pageBuilderWrapper.append($pageBuilderWrapperInner);

            $classicEditorWrapper.before($pageBuilderWrapper);
        }

        function bindEvents () {
            /* jshint unused:false */
            $pageBuilderWrapper.on('g1BuilderElementSizeChanged', function (e, $elementParent) {
                rebuildPlaceholdersForElement($elementParent);
            });

            $pageBuilderWrapper.on('g1BuilderElementRemoved', function (e, $elementParent) {
                rebuildPlaceholdersForElement($elementParent);
            });

            $pageBuilderWrapper.on('g1BuilderStartSwitchingEditor', function (e, $elementParent) {
                showLoadingInfo();
            });

            $pageBuilderWrapper.on('g1BuilderEditorSwitched', function (e, $elementParent) {
                editorSwitched();
            });
            /* jshint unused:true */

            $('#post-preview, #save-post, #publish').on('click', function () {
                convertBuilderElementsToText();
            });

            hookBeforeWpAutosave(convertBuilderElementsToText);
        }

        function hookBeforeWpAutosave (callback) {
            if (typeof wp === 'undefined' || typeof wp.autosave === 'undefined' || typeof wp.autosave.getCompareString !== 'function') {
                return;
            }

            var wpOrigFunc = wp.autosave.getCompareString;

            // hook into original WP function to fire our callback before autosave function
            wp.autosave.getCompareString = function ( post_data ) {
                if (autosaveEnabled) {
                    callback();
                }

                wpOrigFunc(post_data);
            };
        }

        function bindCustomEventsToElement (element) {
            if (options.dropSuggestions) {
                enableDropSuggestions(element);
            }
        }

        var outEventsCount = 0; // global counter

        function enableDropSuggestions (element) {
            var isContentElement = element.getLevel() > 2;
            var $element = element.getHtml();
            var $elementInner = getElementInnerHtml($element);
            var overEventsCount = 0; // per element counter

            if (!isContentElement) {
                $elementInner.on('mouseover', function (e) {
                    // don't do anything is user not dragging any element
                    if ($pageBuilderWrapper.is('.g1-pb-not-dragging')) {
                        return;
                    }

                    var userDraggingSectionElement = draggingElementLevel === 1;
                    var draggingElementIsNotOverMainElement = element.getLevel() !== 0;
                    var droppingForbidden = userDraggingSectionElement && draggingElementIsNotOverMainElement;

                    if (!droppingForbidden) {
                        // event will be handled by this element
                        // parents shouldn't know about it
                        e.stopPropagation();
                        overEventsCount++;

                        // execute after last event occurred
                        setTimeout(function () {
                            overEventsCount--;

                            if (overEventsCount <= 0) {
                                highlightElementPlaceholders($element);
                            }
                        }, 10);
                    }
                });

                $elementInner.on('mouseout', function (e) {
                    if ($pageBuilderWrapper.is('.g1-pb-not-dragging')) {
                        return;
                    }

                    // event will be handled by this element
                    // parents shouldn't know about it
                    e.stopPropagation();
                    outEventsCount++;

                    // execute after last event occurred
                    setTimeout(function () {
                        outEventsCount--;

                        if (outEventsCount <= 0) {
                            removeAllHighlightedPlaceholders();
                        }
                    }, 10);

                });
            }
        }

        function highlightElementPlaceholders ($element) {
            findElementPlaceholders($element).addClass('g1-pb-placeholder-highlighted');
        }

        function removeAllHighlightedPlaceholders () {
            $pageBuilderWrapper.find('.g1-pb-placeholder-highlighted').removeClass('g1-pb-placeholder-highlighted');
        }

        function findElementPlaceholders ($element) {
            var selectors = [
                '> .g1-pb-element-inner > .g1-pb-element-content > .g1-pb-placeholder',
                '> .g1-pb-element-inner > .g1-pb-element-content > .g1-pb-element > .g1-pb-placeholder'
            ];

            var $placeholders = $element.find(selectors.join(','));

            return $placeholders;
        }

        function loadControls () {
            $pageBuilderToolbar.html(context.classes.g1BuilderToolbar().getHtml());
            $pageBuilderToolbar.find('.g1-pb-block').each(function () {
                registerDraggableElement($(this), false);
            });
        }

        function convertTextToBuilderElements (textContent) {
            if ($pageBuilderMainElement) {
                $pageBuilderMainElement.remove();
            }

            var parser = context.classes.g1BuilderParser();
            var htmlContent = parser.shortcodesToHtml(textContent);

            // creates main builder elements,
            // other elements will be created recursively
            var pageBuilderMainElement = createElementInstance('main_element', null, htmlContent, []);
            $pageBuilderMainElement = pageBuilderMainElement.getHtml();

            $pageBuilderContent.append($pageBuilderMainElement);
            $pageBuilderWrapperInner.append($pageBuilderContent);
            $pageBuilderWrapper.trigger('g1BuilderElementsLoaded');
        }

        function convertBuilderElementsToText () {
            if (!isEditorActive()) {
                context.log('Builder is not active so we can\'t convert elements to text.');
                return;
            }

            var text = '';

            $pageBuilderContent.children('.g1-pb-element').each(function () {
                text += getElementTextRepresentation($(this));
            });

            if (text.length > 0) {
                text = removeUnwantedEmptyLines(text);
            }

            var isEditorInVisualMode = $classicEditorWrapper.find('.wp-editor-wrap.tmce-active').length > 0;
            var $htmlEditor = $classicEditorWrapper.find('textarea.wp-editor-area');
            var editorId = $htmlEditor.attr('id');

            // update WP Visual editor
            if (isEditorInVisualMode && typeof window.tinyMCE !== 'undefined') {
                var tinyEditor = window.tinyMCE.get(editorId);

                if (tinyEditor && typeof tinyEditor.setContent !== 'undefined') {
                    tinyEditor.setContent(window.switchEditors.wpautop(text), {format:'raw'});
                }
            }

            // update WP Text editor
            if ( typeof window.switchEditors.pre_wpautop === 'function' ) {
                $classicEditorWrapper.find('#' + editorId).val(window.switchEditors.pre_wpautop(text));
            } else {
                $classicEditorWrapper.find('#' + editorId).val(text);
            }
        }

        function removeUnwantedEmptyLines (text) {
            // remove more that 2 new lines after shortcode tag
            text = text.replace(/(\[\/?[^\]]+\])\n{3,}/ig, '$1\n\n');

            // remove more that 2 new lines before shortcode tag
            text = text.replace(/\n{3,}(\[\/?[^\]]+\])/ig, '\n\n$1');

            // remove new lines before first shortcode tag (if content starts with shortcode)
            text = text.replace(/^\n+(\[[^\]]+\])/ig, '$1');

            // remove new lines after last shortcode tag (if content ends with shortcode)
            text = text.replace(/(\[\/[^\]]+\])\n+$/ig, '$1');

            return text;
        }

        function testDependenciesFailed () {
            return (typeof tinymce === 'undefined' || typeof tinymce.create === 'undefined');
        }

        function createOnOffSwitch () {
            $pageBuilderOnOffSwitch = $('<div class="g1-pb-switch">');
            var $links = $(
                '<a href="#" class="g1-pb-switch-on">'+ i18n.switch_on_builder +'</a>'+
                    '<a href="#" class="g1-pb-switch-off">'+ i18n.switch_off_builder +'</a>'
            );

            $links.on('click', function () {
                /*
                if (testDependenciesFailed()) {
                    if ($(this).is('.g1-pb-switch-off')) {
                        deactivateBuilderEditor(true);
                    } else {
                        alert(i18n.builder_cannot_be_loaded);
                    }

                    return false;
                }
                */

                $pageBuilderWrapper.trigger('g1BuilderStartSwitchingEditor');

                setTimeout(function () {
                    switchEditor();
                }, 100);

                return false;
            });

            $pageBuilderOnOffSwitch.append($links);
            $pageBuilderWrapper.prepend($('<div class="g1-pb-actions">').append($pageBuilderOnOffSwitch));
        }

        function switchEditor () {
            if (isEditorActive()) {
                deactivateBuilderEditor();
            } else {
                activateBuilderEditor();
            }
        }

        function activateBuilderEditor (builderLoadedOnStartup) {
            var isTinyActive = typeof window.tinyMCE !== 'undefined';
            var currentWpEditorMode = $classicEditorWrapper.find('.wp-editor-wrap.html-active').length > 0 ? 'html' : 'tmce';
            var textContent;

            // ----------------------------
            // if WP editor is in HTML mode
            // ----------------------------

            // we need to switch to visual (tmce) mode
            // this way we can make sure that tmce is loaded when we want to use it (eg. for rich text fields)
            if (currentWpEditorMode === 'html') {
                textContent = $classicEditorWrapper.find('#content').val();

                // if builder is activated on page startup,
                // we need to wait until we can switch to visual mode (tmce needs some time to load)
                if (builderLoadedOnStartup) {
                    setTimeout(function() {
                        $('#content-tmce').trigger('click');
                    }, 500);
                } else {
                    if (isTinyActive) {
                        $('#content-tmce').trigger('click');
                    }
                }

                showBuilderEditor(textContent);
            }

            // ------------------------------
            // if WP editor is in VISUAL mode
            // ------------------------------

            // in visual mode, we need to switch to text mode, read raw content and switch back to visual mode
            if (currentWpEditorMode === 'tmce') {
                if (builderLoadedOnStartup) {
                    // fix #2
                    textContent = $classicEditorWrapper.find('#content').val();

                    if (isTinyActive) {
                        textContent = window.switchEditors._wp_Nop(textContent);
                    }

                    showBuilderEditor(textContent);
                } else {
                    $('#content-html').trigger('click');
                    textContent = $classicEditorWrapper.find('#content').val();
                    $('#content-tmce').trigger('click');

                    showBuilderEditor(textContent);
                }
            }
        }

        function showBuilderEditor (editorInputContent) {
            loadControls();
            convertTextToBuilderElements(editorInputContent);

            setEditorState(ACTIVE_STATE);

            $pageBuilderWrapper.trigger('g1BuilderEditorSwitched');
        }

        function deactivateBuilderEditor (withoutConversion) {
            // switch wp editor to HTML mode

            $('#content-html').trigger('click');

            if (!withoutConversion) {
                convertBuilderElementsToText();
                disableCancelAction();
            }

            setEditorState(INACTIVE_STATE);

            $pageBuilderWrapper.trigger('g1BuilderEditorSwitched');
        }

        function showLoadingInfo () {
            $pageBuilderOnOffSwitch.find('a').text(i18n.loading_info);
        }

        function editorSwitched () {
            if (isEditorActive()) {
                $pageBuilderOnOffSwitch.find('.g1-pb-switch-off').text(i18n.switch_off_builder);

                $classicEditorWrapper.hide();
            } else {
                $pageBuilderOnOffSwitch.find('.g1-pb-switch-on').text(i18n.switch_on_builder);
                $classicEditorWrapper.show();

                // update position of sticky WP editor toolbar (added in WP 4.0)
                $(window).trigger('scroll');
                $(window).trigger('resize');
            }

            $pageBuilderWrapper.toggleClass('g1-pb-on g1-pb-off');
        }

        function initEditorState () {
            autosaveEnabled = true;

            // state holder
            $pageBuilderStateHolder = $('<input type="hidden" name="g1_page_builder_state" value="" />');
            $pageBuilderWrapper.prepend($pageBuilderStateHolder);

            // editor should be inactive for new pages
            setEditorState(INACTIVE_STATE);

            // set state based on post meta setting
            if (!isBuilderLoadedFirstTime()) {
                setEditorState(g1_page_builder_state);
            } else {
                enableCancelAction();
            }
        }

        function isBuilderLoadedFirstTime () {
            return typeof g1_page_builder_state === 'undefined';
        }

        function enableCancelAction () {
            autosaveEnabled = false;
            addCancelButton();
        }

        function disableCancelAction () {
            autosaveEnabled = true;
            removeCancelButton();
        }

        function addCancelButton () {
            $pageBuilderCancelButton = $('<div class="g1-pb-cancel"><a class="g1-pb-cancel-button" href="#">'+ i18n.switch_cancel_builder +'</a></div>');

            var $tooltip = $('<div class="g1-pb-tooltip">'+ i18n.cancel_builder_tooltip +'<a class="g1-pb-tooltip-close" href="#">X</a></div>');

            $tooltip.on('click', 'a.g1-pb-tooltip-close', function () {
                $tooltip.remove();
                return false;
            });

            $pageBuilderCancelButton.on('click', 'a.g1-pb-cancel-button', function () {
                deactivateBuilderEditor(true);
                return false;
            });

            $pageBuilderCancelButton.append($tooltip);

            $pageBuilderCancelButton.insertBefore($pageBuilderOnOffSwitch);
        }

        function removeCancelButton () {
            if ($pageBuilderCancelButton) {
                $pageBuilderCancelButton.remove();
            }
        }

        function setEditorState (state) {
            currentState = state;
            $pageBuilderStateHolder.val(state);
        }

        function getEditorState () {
            return currentState;
        }

        function isEditorActive () {
            return getEditorState() === ACTIVE_STATE;
        }

        function rebuildPlaceholdersForElement ($element) {
            var $elementContent = getElementContentHtml($element);

            // we operates only on first level elements
            var $childElements = $elementContent.children('.g1-pb-element');
            // row size calculation helper
            var currentSize = 1;

            // first we need to remove all placeholders
            $elementContent.children('.g1-pb-placeholder').remove();

            // if element has children placeholders will be created around them
            $childElements.each(function () {
                var $elem = $(this);
                var elemObj = $elem.data('g1_page_builder_element');
                var elemSize = parseFloat(elemObj.getSizeNumeric());
                var isLayoutElement = elemObj.getLevel() <= 2;
                var hasColPlaceholder = $elem.find('> .g1-pb-placeholder-col').length > 0;

                currentSize += elemSize;

                // new row?
                if (currentSize > 1) {
                    currentSize = elemSize;

                    var $rowPh = createRowPlaceholder();
                    $elem.before($rowPh);

                    // add column placeholder after the last element in prev row
                    if (canAddEmptyPlaceholder($rowPh)) {
                        $rowPh.before(createColumnPlaceholder('g1-pb-placeholder-empty'));
                    }
                }

                // adds column placeholder before element
                if (isLayoutElement && !hasColPlaceholder) {
                    $elem.prepend(createColumnPlaceholder());
                }

            });

            // we need to add at least one placeholder to make the element droppable
            var $rowPlaceholder = createRowPlaceholder();
            $elementContent.append($rowPlaceholder);

            // add column placeholder after the last element in prev row
            if (canAddEmptyPlaceholder($rowPlaceholder)) {
                $rowPlaceholder.before(createColumnPlaceholder('g1-pb-placeholder-empty'));
            }
        }

        function canAddEmptyPlaceholder ($element) {
            var $prevElement = $element.prev();

            return $prevElement.is('.g1-pb-element') && !$prevElement.hasClass('g1-pb-element-full');
        }

        function dropElementOnArea ($elementToDrop, $dropArea, isNew) {
            var $droppingTo = getParentPbElement($dropArea);

            // one of columns placeholder are put inside element
            var $isDropAreaInsideElement = $dropArea.parent().is('.g1-pb-element');

            // where to drop element?
            if ($isDropAreaInsideElement) {
                var $dropAreaParentElement = getParentPbElement($dropArea);

                $elementToDrop.insertBefore($dropAreaParentElement);

                // in this case we need to change place where element will be dropped
                $droppingTo = getParentPbElement($dropAreaParentElement);
            } else {
                $elementToDrop.insertAfter($dropArea);
            }

            if (isNew) {
                $elementToDrop.addClass('g1-pb-element-added');

                setTimeout(function () {
                    $elementToDrop.removeClass('g1-pb-element-added');
                }, 1500);
            }

            // reload placeholders related to dropping area
            if ($droppingTo) {
                rebuildPlaceholdersForElement($droppingTo);
            }

            return $droppingTo;
        }

        function openToolbarPopupForDroppableArea ($dropArea) {
            var $detachedToolbar = $pageBuilderToolbar.detach();
            var $clonedToolbar = $detachedToolbar.clone();
            $pageBuilderWrapperInner.prepend($clonedToolbar);

            var $blocks = $detachedToolbar.find('.g1-pb-block');
            $blocks.draggable('disable');

            $blocks.on('click', function () {
                var $block = $(this);
                var elementName = $block.attr('data-g1-pb-element-name');

                var $elementToDrop = createElementInstance(elementName, elementName, null, []).getHtml();

                dropElementOnArea($elementToDrop, $dropArea, true);

                $.magnificPopup.close();

                return false;
            });

            var $popup = $(
                '<div class="g1-pb-popup">'+
                    '<div class="g1-pb-popup-header">'+
                    '<div class="g1-pb-popup-title">'+
                    'Insert element' +
                    '</div>'+
                    '</div>'+
                    '<div class="g1-pb-popup-content"></div>'+
                    '</div>'
            );

            $popup.find('.g1-pb-popup-content:first').append($detachedToolbar);

            var $cancelButton = $popup.find('.g1-pb-form-action-cancel');

            $cancelButton.on('click', function () {
                $.magnificPopup.close();

                return false;

            });

            $.magnificPopup.open({
                removalDelay:350,
                items: {
                    src: $popup,
                    type: 'inline'
                },
                closeBtnInside: true,
                closeOnBgClick: false,
                callbacks: {
                    close: function () {
                        $clonedToolbar.replaceWith($detachedToolbar);
                        $blocks.draggable('enable');
                    }
                }
            });
        }

        /**
         * Placeholder is a droppable area. Draggable elements can be dropped only onto this element.
         */
        function createPlaceholder (type, extraCssClass) {
            extraCssClass = extraCssClass ? ' ' + extraCssClass : '';

            var $placeholder = $('<div class="g1-pb-placeholder g1-pb-placeholder-'+ type + extraCssClass +'"><div class="g1-pb-placeholder-inner"></div></div>');

            registerDroppableArea($placeholder);

            $placeholder.on('click', function () {
                openToolbarPopupForDroppableArea($(this));

                return false;
            });

            $placeholder.on('dropover', function () {
                $(this).addClass('g1-pb-placeholder-dropover');
                return false;
            });

            $placeholder.on('dropout', function () {
                $(this).removeClass('g1-pb-placeholder-dropover');
                return false;
            });

            return $placeholder;
        }

        function createRowPlaceholder (extraCssClass) {
            return createPlaceholder('row', extraCssClass);
        }

        function createColumnPlaceholder (extraCssClass) {
            return createPlaceholder('col', extraCssClass);
        }

        function registerDraggableElement ($obj, dragHandle) {
            /*jshint unused:false */
            $obj.draggable({
                appendTo:'.g1-pb',
                revert:'invalid',
                helper: "clone",
                handle: dragHandle,
                scroll: true,
                zIndex: 1000,
                cursorAt: {
                    left:40,
                    top:-10
                },
                start: function( event, ui ) {
                    var $dragElem = $(event.target);
                    $(ui.helper).addClass('g1-pb-element-dragging');

                    draggingElementLevel = $dragElem.data('g1_page_builder_element_level');

                    $pageBuilderWrapper.toggleClass('g1-pb-not-dragging g1-pb-dragging');

                    if (options.dropSuggestions) {
                        highlightElementPlaceholders(getParentPbElement($dragElem));
                    }
                },
                stop: function(event, ui ) {
                    $(ui.helper).removeClass('g1-pb-element-dragging');

                    draggingElementLevel = null;

                    $pageBuilderWrapper.toggleClass('g1-pb-dragging g1-pb-not-dragging');
                    removeAllHighlightedPlaceholders();
                }
            });
            /*jshint unused:true */
        }

        function registerDroppableArea ($obj) {
            /*jshint unused:false */
            $obj.droppable({
                tolerance: 'pointer',
                greedy: true,

                drop: function(event, ui) {
                    var $dropArea = $(this);
                    var $dragElem = ui.draggable;
                    var $draggingFrom = getParentPbElement($dragElem);

                    var isDropAllowed = canBeDropped($dropArea);

                    if (!isDropAllowed) {
                        return;
                    }

                    // element which will be dropped (it's drag element only if we don't took it from toolbar)
                    var $elementToDrop = $dragElem;

                    var dragElemTakenFromToolbar = $dragElem.parents('.g1-pb-toolbar').length > 0;

                    // creates new element based on toolbar control
                    if (dragElemTakenFromToolbar) {
                        var elementNodeName = $dragElem.data('g1_page_builder_element_name');

                        $elementToDrop = createElementInstance(elementNodeName, elementNodeName, null, []).getHtml();
                    }

                    var $droppingTo = dropElementOnArea($elementToDrop, $dropArea, dragElemTakenFromToolbar);

                    // reload placeholders related to area from where element was taken
                    if (!dragElemTakenFromToolbar) {
                        if ($draggingFrom && $draggingFrom.get(0) !== $droppingTo.get(0)) {
                            rebuildPlaceholdersForElement($draggingFrom);
                        }
                    }
                }
            });
            /*jshint unused:true */
        }

        function getParentPbElement ($elem) {
            return $elem.parents('.g1-pb-element').first();
        }

        function canBeDropped ($dropArea) {
            if (options.dropSuggestions) {
                return $dropArea.hasClass('g1-pb-placeholder-highlighted');
            }

            return true;
        }

        /**
         * Creates every draggable/droppable element
         *
         * @param string name           Index from config.elements array eg. main_wrapper, one_half
         * @param string tagName        Shortcode name, html tag name of #text for text nodes
         * @param string htmlContent    Html valid content of the element
         * @param array  attrs          Element attributes
         *
         * @returns g1BuilderElement object
         */
        function createElementInstance (name, tagName, htmlContent, attrs) {
            var elementConfig = getElementConfig(name);
            htmlContent = htmlContent || '';
            attrs = attrs || [];

            var isContentElement = elementConfig.level === 3;
            var isDraggable = elementConfig.level > 0;
            var isDroppable = !isContentElement;
            var content = null; // visual representation of the page builder element

            // element can't hold other elements
            if (isContentElement) {
                content = htmlContent;
            }

            // build element
            elementConfig.eventDispatcher = $pageBuilderWrapper;

            var element = context.classes.g1BuilderElement(name, tagName, content, attrs, elementConfig);
            var $element = element.getHtml();
            var $elementContent = getElementContentHtml($element);

            if (isDroppable) {
                // Each droppable element must have at least one placeholder.
                // In other case no element can be dropped onto it.
                $elementContent.append(createRowPlaceholder());
            }

            if (isDraggable) {
                registerDraggableElement($element, ".g1-pb-element-header");
            }

            bindCustomEventsToElement(element);

            // traverse element content if can contain other elements
            if (!isContentElement && htmlContent) {
                var domNodes = $.parseHTML(htmlContent);

                domNodes = mergeTextNodes(domNodes);

                $(domNodes).each(function () {
                    var node = this;
                    var $node = $(this);

                    if (isNotEmptyNode(node)) {
                        var isShortcode = $node.is('.g1-shortcode');
                        var nodeName = isShortcode ? $node.attr('data-g1-name') : 'html_block';
                        var nodeTagName = isShortcode ? $node.attr('data-g1-name') : null;
                        var nodeHtmlContent = getNodeHtmlContent(node);
                        var nodeAttrs = [];

                        if (isShortcode) {
                            nodeAttrs = getNodeAttributesInCorrectOrder($node);
                        }

                        // create recursively inner elements
                        $elementContent.append(createElementInstance(nodeName, nodeTagName, nodeHtmlContent, nodeAttrs).getHtml());
                        rebuildPlaceholdersForElement($element);
                    }
                });
            }

            return element;
        }

        function mergeTextNodes (domNodes) {
            var nodes = [];
            var nodeIndex = 0;
            var mergedNodes = 0;
            var emptyNodes = 0;
            var nodesLength = domNodes.length;

            if (nodesLength === 0) {
                return domNodes;
            }

            $(domNodes).each(function (domNodeIndex) {
                var domNode = this;

                if (shouldNodeBeMerged(domNode)) {
                    if (typeof nodes[nodeIndex] === 'undefined') {
                        nodes[nodeIndex] = document.createElement('div');
                        nodes[nodeIndex].className = 'g1-temp-dom-object';
                    }

                    nodes[nodeIndex].appendChild(domNode);

                    mergedNodes++;

                    if (!isNotEmptyNode(domNode)) {
                        emptyNodes++;

                        // is last node?
                        if (domNodeIndex === nodesLength - 1) {
                            if (mergedNodes > 0 && mergedNodes === emptyNodes) {
                                nodes.splice(nodeIndex, 1);
                            }
                        }
                    }
                } else {
                    if (mergedNodes > 0 && mergedNodes === emptyNodes) {
                        delete nodes[nodeIndex];
                    }

                    if (typeof nodes[nodeIndex] !== 'undefined') {
                        nodeIndex++;
                    }

                    nodes[nodeIndex] = domNode;
                    nodeIndex++;

                    // reset
                    mergedNodes = 0;
                    emptyNodes = 0;
                }
            });

            return nodes;
        }

        function shouldNodeBeMerged (node) {
            var TEXT_NODE = node.nodeType === 3;
            var COMMENT_NODE = node.nodeType === 8;

            if (TEXT_NODE || COMMENT_NODE) {
                return true;
            }

            if (node.className.indexOf('g1-shortcode') === -1) {
                return true;
            }

            return false;
        }

        function getNodeAttributesInCorrectOrder ($node) {
            var orderedAttrs = {};
            var attrsListString = $node.attr('data-g1-attrs-list');

            if (!attrsListString) {
                return orderedAttrs;
            }

            var attrsList = attrsListString.split(',');

            for (var i = 0; i < attrsList.length; i += 1) {
                var attrName = attrsList[i];

                orderedAttrs[attrName] = $node.attr('data-g1-' + attrName);
            }

            return orderedAttrs;
        }

        function getElementContentHtml ($element) {
            return $element.find('> .g1-pb-element-inner > .g1-pb-element-content');
        }

        function getElementInnerHtml ($element) {
            return $element.find('> .g1-pb-element-inner');
        }

        function getNodeHtmlContent (node) {
            var out = '';

            // is text node?
            if (node.nodeType === 3) {
                out = node.nodeValue;
            } else {
                out = $(node).html();
            }

            return out;
        }

        function isNotEmptyNode (node) {
            // checks if node is text node and new line characters are the only characters
            return !(node.nodeType === 3 && node.nodeValue.replace(/(\r\n|\n|\r)/gm,"") === '');
        }

        function getElementConfig (name) {
            var elementsConfig = context.config.elements;
            var templateConfig;

            if (typeof elementsConfig[name] !== 'undefined') {
                templateConfig = elementsConfig[name];
            } else {
                templateConfig = elementsConfig.wrong_element;
            }

            // returns new config object
            return $.extend({}, templateConfig);
        }

        var processedElementsSize = [];
        var sizeComplement = 0.000009;

        function getElementTextRepresentation ($elem, level) {
            var $elemContent = getElementContentHtml($elem);
            var elem = $elem.data('g1_page_builder_element');

            if (!elem) {
                context.log('Element has no data object');
                context.log($elem);
                return '';
            }

            level = level || 0;
            var text = '';
            var openingTag = elem.getOpeningTag();
            var closingTag = elem.getClosingTag();
            var emptyLine = openingTag.length > 0 ? '\n\n' : '';
            var elementSize = getElementSize(elem);

            // adds "_last" suffix to grid element name that is last in row
            if (elementSize > 0) {
                if (typeof processedElementsSize[level] === 'undefined') {
                    processedElementsSize[level] = 0;
                }

                processedElementsSize[level] += elementSize;

                // columns sizes are defined as fractions (eg. 0.333333 for one_third)
                // so we need to use size complement to know when columns form a full row
                if (processedElementsSize[level] + sizeComplement >= 1) {
                    // row was closed correctly
                    if (processedElementsSize[level] - sizeComplement <= 1) {
                        openingTag = openingTag.replace('[' + elem.getId(), '['+ elem.getId() +'_last');
                        closingTag = closingTag.replace(']', '_last]');

                        // current row is over so we need to reset size
                        processedElementsSize[level] = 0;
                    }
                    // another row started but previous wasn't closed so we need to close it
                    else {
                        text += emptyLine;
                        text += getTextColumnToCloseGrid(processedElementsSize[level] - elementSize);
                        text += emptyLine;

                        // set grid size to current element size (because now it's first and only element in the row)
                        processedElementsSize[level] = elementSize;
                    }
                }
            }
            // non-grid element
            else if (processedElementsSize[level] > 0) {
                // another row started but previous wasn't closed so we need to close it
                if (processedElementsSize[level] + sizeComplement < 1) {
                    text += emptyLine;
                    text += getTextColumnToCloseGrid(processedElementsSize[level]);
                    text += emptyLine;
                }

                // we need to reset size (the last row is over)
                processedElementsSize[level] = 0;
            }

            // composes full text representation
            text += emptyLine;
            text += openingTag;
            text += emptyLine;

            if (elem.hasContent()) {
                text += elem.getTextContent();
            } else {
                $elemContent.children('.g1-pb-element').each(function () {
                    text += getElementTextRepresentation($(this), level + 1);
                });
            }

            text += emptyLine;
            text += closingTag;
            text += emptyLine;

            // temporary workaround

            if (elem.getId() === 'rev_slider') {
                var matches = text.match(/alias="([^"]+)"/);

                if (matches !== null) {
                    text = text.replace('alias="'+ matches[1] +'"', matches[1]);
                }
            }

            // end of temporary workaround

            return text;
        }

        function getElementSize (element) {
            var id = element.getId();
            id = id.replace('_', '-');

            var sizeConfig = context.helpers.getValueByKeyIfDefinedOrNull(context.config.element.sizes, id);

            if (sizeConfig) {
                return sizeConfig.numValue;
            }

            return 0;
        }

        function getTextColumnToCloseGrid (unclosedGridSize, emptyLine) {
            var out = '';
            var matchedSize = null;

            var sizesInReverseOrder = context.config.elementSizesReverseOrder;
            var allSizes = context.config.element.sizes;

            for (var i = 0; i < sizesInReverseOrder.length; i++) {
                var sizeName = sizesInReverseOrder[i];

                if (unclosedGridSize + allSizes[sizeName].numValue <= 1) {
                    matchedSize = sizeName;
                    break;
                }
            }

            if (matchedSize) {
                var elementName = matchedSize.replace('-', '_');

                out += '['+ elementName +'_last]\n\n[/'+ elementName +'_last]';
            }

            return out;
        }

        // execute pseudo constructor
        init();

        return that;
    };

    /**
     * g1Builder jQuery function
     */
    $.fn.g1Builder = function (options) {
        return this.each(function () {
            var $this = $(this);

            var instance = $this.data('g1_builder');

            if (!instance) {
                options = options || {};

                instance = g1Builder($this, options);

                // object instance is bound to DOM element
                // so we can easily access it
                $this.data('g1_builder', instance);
            }

            return this;
        });
    };
})(jQuery, g1PageBuilder);