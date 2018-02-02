/* global jQuery */
/* global g1PageBuilder */
/* global Storage */
/* global confirm */

(function ($, context) {
    "use strict";

    /**
     * g1BuilderParser class
     */
    var g1BuilderParser = function () {
        var that = {}; // defines public scope

        /**
         * VARS
         */
        var patterns;
        var elementList;

        /**
         * METHODS
         */

        that.shortcodesToHtml = function (text) {
            text = normalizeShortcodeTags(text);

            var html = replaceShortcodeTagsWithHtmlTags(text);

            return html;
        };

        function replaceShortcodeTagsWithHtmlTags (text) {
            // wrap all shortcodes into html to parse them as xml
            text = '<div class="g1-temp-html-wrapper">' + wrapShortcodeTagsIntoHtmlHolders(text) + '</div>';

            text = convertFirstLevelShortcodesToHtml(text);

            // unwrap
            text = $(text).html();

            return text;
        }

        function wrapShortcodeTagsIntoHtmlHolders (out) {
            out = out.replace(/(\[\/[^\]]+\])/ig, '$1</div>');
            out = out.replace(/(\[[^\/]{1}[^\]]+\])/ig, '<div class="g1-temp-shortcode-wrapper">$1');

            return out;
        }

        function unwrapShortcodeTagsFromHtmlHolders ($set) {
            var removeWrappers = function () {
                $set.find('.g1-temp-shortcode-wrapper').each(function () {
                    $(this).replaceWith($(this).html());
                });
            };

            // runs as many times as deep is DOM tree
            while ($set.find('.g1-temp-shortcode-wrapper').length > 0) {
                removeWrappers();
            }

            return $set;
        }

        function convertFirstLevelShortcodesToHtml (text, dontTraverseDeeper) {
            dontTraverseDeeper = dontTraverseDeeper || false;

            var $out = $(text);
            var $set = $out.find('> .g1-temp-shortcode-wrapper');

            // if there is no children shortcodes
            if ($set.length === 0 || dontTraverseDeeper) {
                unwrapShortcodeTagsFromHtmlHolders($out);

                // end exit
                if (typeof $out[0] !== 'undefined' && typeof $out[0].outerHTML !== 'undefined') {
                    return $out[0].outerHTML;
                } else {
                    return text;
                }
            }

            // we have some children
            $set.each(function () {
                var registeredElementList = getElementList();
                var $shortcode = $(this);
                var html = $(this).html();

                // temporary workaround

                if (html.indexOf('[box') === 0 || html.indexOf('[carousel_item') === 0 || html.indexOf('[tab_title') === 0 || html.indexOf('[tab_content') === 0) {
                    dontTraverseDeeper = true;
                }

                if (html.indexOf('[rev_slider') === 0) {
                    var matches = html.match(/\[rev_slider\s+([^\]]+)\]/);

                    if (matches !== null) {
                        html = html.replace(matches[1], 'alias="'+ matches[1] +'"');
                    }
                }

                // end of temporary workaround

                var shortcodeNameMatches = html.match(/^\[([^\s\]]+)/);

                if (shortcodeNameMatches !== null) {
                    var shortcodeName = shortcodeNameMatches[1];

                    var omitChecking =
                            shortcodeName.indexOf('_last') !== -1 ||            // last column in row
                            shortcodeName.indexOf('carousel_item') !== -1 ||    // internal carousel item
                            shortcodeName.indexOf('tab_title') !== -1 ||        // internal tabs item
                            shortcodeName.indexOf('tab_content') !== -1;        // internal tabs item

                    if (!omitChecking) {
                        // checks if shortcode is registered as builder element
                        // if not, we will treat it as normal html block
                        if ($.inArray(shortcodeName, registeredElementList) === -1) {
                            unwrapShortcodeTagsFromHtmlHolders($shortcode);
                            $shortcode.replaceWith($shortcode.html());
                            return;
                        }
                    }
                }
                // end of temporary workaround

                var match;
                var shortcode;
                var openingPattern = /^\[[^\/]{1}[^\]]+\]/g;
                var closingPattern = /\[\/[^\]]+\]$/g;

                // convert opening shortcode
                match=openingPattern.exec(html);

                if (match) {
                    shortcode = match[0];

                    html = html.substr(openingPattern.lastIndex);
                    html = convertShortcodeToHtml(shortcode) + html;
                }

                // convert closing shortcode
                match=closingPattern.exec(html);

                if (match) {
                    shortcode = match[0];

                    html = html.substr(0, match.index);
                    html += convertShortcodeToHtml(shortcode);
                }

                // run recursive conversion for current shortcode html content
                $shortcode.replaceWith(convertFirstLevelShortcodesToHtml(html, dontTraverseDeeper));

                // revert to init state
                dontTraverseDeeper= false;
            });

            unwrapShortcodeTagsFromHtmlHolders($out);

            return $out[0].outerHTML;
        }

        function getElementList () {
            if (typeof elementList === 'undefined') {
                elementList = context.getListOfRegisteredElements();
            }

            return elementList;
        }

        function registerPatterns () {
            patterns = {
                'shortcode_tag': /\[[^\]]+\]/g
            };
        }

        function normalizeShortcodeTags (content) {
            var match;
            var patt = patterns.shortcode_tag;
            var tags = [];

            while ((match=patt.exec(content))) {
                tags.push({
                    content: match[0],
                    startAt: match.index,
                    endAt: patt.lastIndex
                });
            }

            var tagsCount = tags.length;

            if (tagsCount === 0) {
                return content;
            }

            var offset = 0;

            for (var i = 0; i < tagsCount; i += 1) {
                var tagObj = tags[i];
                var tag = tagObj.content;

                // omit closing tag?
                if (tag.indexOf('[/') === 0) {
                    continue;
                }

                var tagName = tag.match(/\[\/?([^\s\]]+)/)[1];
                var closeTag = '[/' + tagName + ']';

                if (!isTagClosed(tagName, tags, i + 1, tagsCount)) {
                    content = content.substr(0, tagObj.startAt + offset) + tag + closeTag + content.substr(tagObj.endAt + offset);

                    offset += closeTag.length;
                }
            }

            return content;
        }

        function convertShortcodeToHtml (shortcode) {
            if (shortcode.indexOf('[/') === 0) {
                return '</div>';
            }

            var html = '<div class="g1-shortcode"';
            var attributesOrderedList = [];

            // finds shortcode name
            var nameMatch = shortcode.match(/^\[([^\s\]]+)/);

            if (nameMatch === null) {
                return shortcode;
            }

            var shortcodeName = nameMatch[1];

            // removes "_last" name part
            shortcodeName = shortcodeName.replace('_last', '');

            // remove brackets
            shortcode = shortcode.replace(/\[|\]/g, '');

            // finds shortcode attributes
            var attrs = shortcode.match(/[\w-]+="[^"]*"|[\w-]+='[^']*'|[\w-]+=[^\s]*/ig);

            html += ' data-g1-name="'+ shortcodeName +'"';

            if (attrs) {
                var attrsCount = attrs.length;

                for (var i = 0; i < attrsCount; i += 1) {
                    attributesOrderedList.push(attrs[i].split('=')[0]);

                    html += ' data-g1-' + attrs[i];
                }
            }

            html += ' data-g1-attrs-list="'+ attributesOrderedList.join(',') +'"';

            html += '>';

            return html;
        }

        function isTagClosed (tagName, tags, startSearchAtIndex, tagsCount) {

            for (var i = startSearchAtIndex; i < tagsCount; i += 1) {
                var tagObj = tags[i];
                var tag = tagObj.content;

                // same opening tag
                if (tag.indexOf('[' + tagName + ' ') === 0) {
                    return false;
                }

                // closing tag found
                if (tag.indexOf('[/' + tagName + ']') === 0) {
                    return true;
                }
            }

            return false;
        }

        function init () {
            registerPatterns();
        }

        // pseudo constructor
        init();

        return that; // gives access to public methods
    };

    /**
     * g1BuilderToolbar class
     */
    var g1BuilderToolbar = function () {
        var that = {}; // defines public scope

        /**
         * VARS
         */
        var $html;
        var $tabsNavigation;
        var $tabs;
        var sections;

        /**
         * METHODS
         */
        function buildToolbar () {
            var html =
                '<div class="g1-pb-blocks">'+
                    '<div class="g1-pb-tabs">'+
                        '<div class="g1-pb-tabs-nav"></div>'+
                        '<div class="g1-pb-tabs-viewport"></div>'+
                    '</div>'+
                '</div>';

            $html = $(html);
            $tabsNavigation = $('<ul>').addClass('g1-pb-tabs-nav-menu');
            $tabs = $('<ul>').addClass('g1-pb-tabs-viewport-items');

            createSections();
            createSectionsElements();

            $html.find('.g1-pb-tabs-nav').append($tabsNavigation);
            $html.find('.g1-pb-tabs-viewport').append($tabs);
        }

        function createSections () {
            var sectionsConfig = context.config.toolbar.sections;

            for (var sectionId in sectionsConfig) {
                if (sectionsConfig.hasOwnProperty(sectionId)) {
                    createSection(sectionId, sectionsConfig[sectionId]);
                }
            }

            var tabIndexToSelect = 0;

            if (typeof Storage !== 'undefined') {
                var index = sessionStorage.getItem('g1_pb_tabs_nav_item_selected');

                if (index !== null) {
                    tabIndexToSelect = index;
                }
            }

            $tabsNavigation.find('> li:eq('+ tabIndexToSelect +') a').trigger('click');
        }

        function createSection (id, config) {
            var $sectionNavItem = $('<li><a class="g1-pb-tab-title" href="#">' + config.label + '</a></li>');
            var $sectionContent = $('<li>');

            $sectionNavItem.find('a').click(function (e) {
                e.stopImmediatePropagation();

                $tabsNavigation.find('> li').removeClass('g1-pb-tab-selected');
                $sectionNavItem.addClass('g1-pb-tab-selected');
                $tabs.find('> li').removeClass('g1-pb-tab-selected');
                $sectionContent.addClass('g1-pb-tab-selected');

                if (typeof Storage !== 'undefined') {
                    sessionStorage.setItem('g1_pb_tabs_nav_item_selected', $sectionNavItem.index());
                }

                return false;
            });

            // register section
            sections[id] = {
                navItem: $sectionNavItem,
                content: $sectionContent,
                elements: []
            };

            // append to parent element
            $tabsNavigation.append($sectionNavItem);
            $tabs.append($sectionContent);
        }

        function createSectionsElements () {
            var elementsConfig = context.config.elements;

            for (var elementId in elementsConfig) {
                if (elementsConfig.hasOwnProperty(elementId)) {
                    addElementToSection(elementId, elementsConfig[elementId]);
                }
            }

            // add section elements to DOM
            for (var i in sections) {
                if (sections.hasOwnProperty(i)) {
                    var section = sections[i];

                    var $elementList = $('<ul class="g1-pb-blocks-list">');

                    for (var k in section.elements) {
                        if (section.elements.hasOwnProperty(k)) {
                            $elementList.append($('<li>').append(section.elements[k]));
                        }
                    }

                    section.content.append($elementList);
                }
            }
        }

        function addElementToSection (id, config) {
            if (typeof config.toolbarSection === 'undefined') {
                return;
            }

            var sectionId = config.toolbarSection;

            if (typeof sections[sectionId] === 'undefined') {
                context.log('Element "'+ id +'" cannot be added to section "'+ sectionId +'". Section not defined!');
                return;
            }

            var $element = buildMarkupForSectionElement(config);

            $element.data('g1_page_builder_element_name', id);
            $element.data('g1_page_builder_element_level', config.level);
            $element.attr('data-g1-pb-element-name', id);

            sections[sectionId].elements.push($element);
        }

        function buildMarkupForSectionElement (config) {
            var cssClass = typeof config.cssBlockClass !== 'undefined' ? config.cssBlockClass : '';
            var label = typeof config.label !== 'undefined' ? config.label : '';
            var description = typeof config.description !== 'undefined' ? config.description : '';

            var $element = $('<div class="g1-pb-block g1-pb-block-type-'+ cssClass +'">');
            var $icon = $('<div class="g1-pb-block-icon">');
            var $title = $();
            var $description = $();

            if (label) {
                $title = $('<div class="g1-pb-block-title">'+ label +'</div>');
            }

            if (description) {
                $description = $('<div class="g1-pb-block-description">'+ description +'</div>');
            }

            // compose
            $element.append($('<a href="#">').append($icon, $title), $description);

            return $element;
        }

        function getHtml () {
            return $html;
        }

        function init () {
            sections = {};

            buildToolbar();
        }

        // pseudo constructor
        init();

        // public scope
        that.getHtml = getHtml;

        return that; // gives access to public methods
    };

    /**
     * g1BuilderElement class
     */
    var g1BuilderElement = function (elementName, tagName, content, attrs, options) {
        var that = {}; // defines public scope

        /**
         * CONFIG
         */
        var globalOptions = {
            withControls: true,
            disableResizeControls: false,
            disableEditControl: false,
            disableRemoveControl: false
        };

        options = $.extend(globalOptions, options);

        /**
         * VARS
         */
        var config;
        var sizes;
        var element; // represents modal box element

        // builder element
        var $element;
        var $elementInner;
        var $elementHeader;
        var $elementTitle;
        var $elementContent;

        // builder element controls
        var $increaseSizeElement;
        var $decreaseSizeElement;
        var $removeElement;
        var $editElement;

        var DEFAULT_SIZE = 'full';

        // pseudo constructor
        function init () {
            registerSizes();
            createElement();
        }

        /**
         * METHODS
         */
        function triggerEvent (type, params) {
            if (typeof options.eventDispatcher === 'undefined') {
                return;
            }

            params = params || [];

            options.eventDispatcher.trigger(type, params);
        }

        function getElementId () {
            return elementName;
        }

        function registerSizes () {
            sizes = context.config.element.sizes;
        }

        function getElementConfig () {
            if (!config) {
                config = context.config.elements[elementName];

                if (!config) {
                    config = context.config.elements['wrong_element'];
                }
            }

            return config;
        }

        function getSizeConfigByName (sizeName) {
            if (typeof sizes[sizeName] !== 'undefined') {
                return sizes[sizeName];
            }

            return null;
        }

        function getCurrentSizeConfig () {
            var sizeName = getSize();

            if (!sizeName) {
                return null;
            }

            if (typeof sizes[sizeName] !== 'undefined') {
                return sizes[sizeName];
            }

            context.log('Incorrect size "'+ sizeName +'" passed. Config not defined for this size.');

            return null;
        }

        function getSize () {
            return typeof options.size !== 'undefined' ? options.size : DEFAULT_SIZE;
        }

        function getLevel () {
            return options.level;
        }

        function getTitle () {
            return options.label;
        }

        /**
         * Calculates numeric size of element (helpful for calculating row size)
         */
        function getSizeNumeric () {
            var config = getCurrentSizeConfig();

            if (config) {
                return config.numValue;
            }

            context.log('Size config not found. Default size used instead.');

            return 0.25;
        }

        function getSizeLabel () {
            var config = getCurrentSizeConfig();

            if (config) {
                return config.label;
            }

            context.log('Size config not found. Empty label used instead.');

            return '';
        }

        function remove () {
            if (!confirm('Are you sure you want to delete this element?')) {
                return false;
            }

            var $elementParent = getParentPbElement();

            $element.remove();
            triggerEvent('g1BuilderElementRemoved', [$elementParent]);

            return false;
        }

        function increaseSize () {
            var newSize = getNextSize();

            if (getSize() === newSize) {
                return;
            }

            var $elementParent = getParentPbElement();

            setSize(newSize);
            triggerEvent('g1BuilderElementSizeChanged', [$elementParent]);
        }

        function decreaseSize () {
            var newSize = getPrevSize();

            if (getSize() === newSize) {
                return;
            }

            var $elementParent = getParentPbElement();

            setSize(newSize);
            triggerEvent('g1BuilderElementSizeChanged', [$elementParent]);
        }

        function getParentPbElement () {
            return $element.parents('.g1-pb-element').first();
        }

        function edit () {
            var element = getElement();

            if (!element) {
                return;
            }

            var $popup = $(
                '<div class="g1-pb-popup">'+
                    '<div class="g1-pb-popup-header">'+
                        '<div class="g1-pb-popup-title">'+
                            getTitle() +
                        '</div>'+
                    '</div>'+
                    '<div class="g1-pb-popup-content"></div>'+
                    '<div class="g1-pb-popup-footer">'+
                        '<div class="g1-pb-form-actions">'+
                            '<ul>'+
                                '<li><a href="#" class="button button-large button-primary g1-pb-form-action-save">Save</a></li>'+
                                '<li><a href="#" class="button button-large g1-pb-form-action-cancel">Cancel</a></li>'+
                            '</ul>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );

            if (element.isAsync()) {
                var $content = $popup.find('.g1-pb-popup-content:first');
                $content.html('<span>Loading...</span>');

                setTimeout(function () {
                    $content.html(element.getFormHtml());
                    element.notify('added_to_DOM');
                }, 100);
            } else {
                $popup.find('.g1-pb-popup-content:first').append(element.getFormHtml());
            }

            var $saveButton = $popup.find('.g1-pb-form-action-save');
            var $cancelButton = $popup.find('.g1-pb-form-action-cancel');

            $saveButton.on('click', function () {
                // send notification to popup form
                element.notify('update');

                // content can be updated only for none layout elements
                if (!isLayoutElement()) {
                    if (element.hasContent()) {
                        setContent(element.getContentValue());
                    }

                    setHtmlContent(getElementHtmlRepresentation(element));
                }

                $.magnificPopup.close();

                return false;
            });

            $cancelButton.on('click', function () {
                element.notify('revert_changes');

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
                    open: function () {
                        if (!element.isAsync()) {
                            element.notify('added_to_DOM');
                        }
                    },
                    beforeClose: function () {
                        element.notify('before_removing_from_DOM');
                    }
                }
            });

            var $closeButton = $popup.find('.mfp-close');

            $closeButton.on('click', function() {
                element.notify('revert_changes');

                $.magnificPopup.close();

                return false;
            });
        }

        function getElementHtmlRepresentation (element) {
            var callback = context.helpers.getValueByKeyIfDefinedOrNull(getElementConfig(), 'htmlRepresentationCallback');

            // custom representation (callback passed as func ref)
            if (typeof callback === 'function') {
                if (isElementLoaded()) {
                    return callback(element.getFieldsValues(), element.getContentValue());
                } else {
                    return callback(attrs, getContent());
                }
            }

            // custom representation (callback passed as string)
            if (typeof callback === 'string') {
                var callbackFunc = context.helpers.getValueByKeyIfDefinedOrNull(context.callbacks, callback);

                if (callbackFunc && typeof callbackFunc === 'function') {
                    if (isElementLoaded()) {
                        return callbackFunc(element.getFieldsValues(), element.getContentValue());
                    } else {
                        return callbackFunc(attrs, getContent());
                    }
                }
            }

            // default representation (no callback defined)
            if (isElementLoaded()) {
                return element.getContentValue();
            } else {
                return getContent();
            }
        }

        function isElementLoaded () {
            return typeof element !== 'undefined';
        }

        function getElement () {
            if (!isElementLoaded()) {
                var elementClass = context.helpers.getValueByKeyIfDefinedOrNull(context.classes.elements, getElementId());

                if (!elementClass) {
                    elementClass = context.helpers.getValueByKeyIfDefinedOrNull(context.classes.elements, 'wrong_element');
                }

                element = elementClass(attrs, content);
            }

            return element;
        }

        function getPrevSize () {
            var sizeConfig = getSizeConfigByName(getSize());

            if (sizeConfig && sizeConfig.prev) {
                return sizeConfig.prev;
            }

            return getSize();
        }

        function getNextSize () {
            var sizeConfig = getSizeConfigByName(getSize());

            if (sizeConfig && sizeConfig.next) {
                return sizeConfig.next;
            }

            return getSize();
        }

        function buildControlsPanel () {
            var $controlsPanel = $('<ul class="g1-pb-element-controls">');

            if (!options.disableEditControl) {
                $editElement = $('<a href="#" class="g1-pb-edit">edit</a>');

                $editElement.on('click', function(){
                    edit();
                    return false;
                });

                $controlsPanel.append(
                    $('<li></li>').append($editElement)
                );
            }

            if (!options.disableRemoveControl) {
                $removeElement = $('<a href="#" class="g1-pb-close">X</a>');

                $removeElement.on('click', function(){
                    remove();
                    return false;
                });

                $controlsPanel.append(
                    $('<li></li>').append($removeElement)
                );
            }

            return $controlsPanel;
        }

        function buildResizePanel () {
            var $resizePanel = $('<ul class="g1-pb-element-resize">');

            $increaseSizeElement = $('<a href="#" class="g1-pb-inc-size">+</a>');
            $decreaseSizeElement = $('<a href="#" class="g1-pb-dec-size">-</a>');

            // add behaviour
            $increaseSizeElement.on('click', function (e) {
                e.preventDefault();

                increaseSize();
            });
            $decreaseSizeElement.on('click', function (e) {
                e.preventDefault();

                decreaseSize();
            });

            // compose
            $resizePanel.append(
                $('<li></li>').append($decreaseSizeElement),
                $('<li></li>').append($increaseSizeElement)
            );

            return $resizePanel;
        }

        function createElement () {
            $element = buildMarkupForElement();

            if (hasContent()) {
                setHtmlContent(getElementHtmlRepresentation());
            }

            $elementTitle.text(getTitle());

            if (options.withControls) {
                if (isLayoutElement() && !options.disableResizeControls) {
                    $elementHeader.append(buildResizePanel());
                }

                $elementHeader.append(buildControlsPanel());
            }

            // memory storage
            $element.data('g1_page_builder_element', that);
            $element.data('g1_page_builder_element_level', getLevel());
        }

        function setHtmlContent (value) {
            $elementContent.html(value);
        }

        function buildMarkupForElement () {
            var classes = [
                'g1-pb-element',
                'g1-pb-element-level-'+ getLevel(),
                'g1-pb-element-'+ getSize()
            ];

            var config = getElementConfig();

            if (typeof config.cssElementClass !== 'undefined') {
                classes.push('g1-pb-element-' + config.cssElementClass);
            }

            $element = $('<div class="'+ classes.join(' ') +'">');
            $elementInner = $('<div class="g1-pb-element-inner"></div>');
            $elementHeader = $('<div class="g1-pb-element-header"></div>');
            $elementTitle = $('<div class="g1-pb-element-title"></div>');
            $elementContent = $('<div class="g1-pb-element-content"></div>');

            // compose
            $elementHeader.append($elementTitle);
            $elementInner.append($elementHeader, $elementContent);
            $element.append($elementInner);

            return $element;
        }

        function getHtml () {
            return $element;
        }

        function setSize (size) {
            $element.removeClass('g1-pb-element-' + getSize());

            options.size = size;

            $element.addClass('g1-pb-element-' + getSize());
            $elementTitle.text(getSizeLabel());

            // by changing size we, in face, change element type
            elementName = size.replace('-', '_');
            tagName = elementName;
            options.label = getSizeLabel();
        }

        function hasContent () {
            return content !== null;
        }

        that.getTextContent = function () {
            var element = getElement();

            if (element) {
                return element.convertToText(getContent());
            }

            return getContent();
        };

        function getContent () {
            return content;
        }

        function setContent (value) {
            content = value;
        }

        function isTextElement () {
            return elementName === 'text_node';
        }

        function isLayoutElement () {
            return getLevel() <= 2;
        }

        function getAttributesString() {
            var attrName, out = '';
            var element = getElement();

            if (element === null) {
                return '';
            }

            var attributes = element.getFields();

            for (attrName in attributes) {
                if (attributes.hasOwnProperty(attrName)) {
                    var attrValue = attributes[attrName];

                    if (attrValue) {
                        out += ' ' + attrName + '="' + attrValue + '"';
                    }
                }
            }

            return out;
        }

        function getOpeningTag () {
            if (getElementId() === 'html_block') {
                return '';
            }

            if (isTextElement()) {
                if (tagName !== '#text') {
                    return '<' + tagName.toLowerCase() + getAttributesString() + '>';
                }
            } else if (tagName !== null) {
                return '[' + tagName + getAttributesString() + ']';
            }

            return '';
        }

        function getClosingTag () {
            if (getElementId() === 'html_block') {
                return '';
            }

            if (isTextElement()) {
                if (tagName !== '#text') {
                    return '</' + tagName.toLowerCase() + '>';
                }
            } else if (tagName !== null) {
                return '[/' + tagName + ']';
            }

            return '';
        }

        init();

        // public scope
        that.getHtml = getHtml;
        that.getLevel = getLevel;
        that.getSize = getSize;
        that.setSize = setSize;
        that.getSizeNumeric = getSizeNumeric;
        that.hasContent = hasContent;
        that.getContent = getContent;
        that.getOpeningTag = getOpeningTag;
        that.getClosingTag = getClosingTag;
        that.isLayoutElement = isLayoutElement;
        that.getId = getElementId;

        return that; // gives access to public methods
    };

    // binds to global context
    context.classes.g1BuilderToolbar = g1BuilderToolbar;
    context.classes.g1BuilderElement = g1BuilderElement;
    context.classes.g1BuilderParser = g1BuilderParser;
})(jQuery, g1PageBuilder);