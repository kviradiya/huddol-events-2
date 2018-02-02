/* global console */

/**
 * G1 Page Builder global context
 *
 * Here we only define necessary namespaces
 */
var g1PageBuilder = {
    'classes': {
        'elements':     {},
        'fields':       {},
        'htmlFields':   {}
    },
    'config':   {},
    'helpers':  {},
    'callbacks': {}
};

/**
 * G1 Page Builder object
 */
(function (context) {
    "use strict";

    /**
     * VARS
     */
    var debugMode = false;

    /**
     * METHODS
     */
    function log (content) {
        if (debugMode && typeof console !== 'undefined') {
            console.log(content);
        }
    }

    function setDebugMode (state) {
        debugMode = state;
    }

    // element
    function registerElement (elementUniqueId, elementClass) {
        if (elementExists(elementUniqueId)) {
            context.log('Element "'+ elementUniqueId +'" already exists!');
            return;
        }

        context.classes.elements[elementUniqueId] = elementClass;
    }

    function elementExists (elementId) {
        return typeof context.classes.elements[elementId] !== 'undefined';
    }

    // block
    function registerBlock (blockUniqueId, blockConfig) {
        if (blockExists(blockUniqueId)) {
            context.log('Block "'+ blockUniqueId +'" already exists!');
            return;
        }

        context.config.elements[blockUniqueId] = blockConfig;
    }

    function blockExists (blockId) {
        return typeof context.config.elements[blockId] !== 'undefined';
    }

    function getListOfRegisteredElements () {
        var list = [];
        var name;

        for (name in context.classes.elements) {
            if (context.classes.elements.hasOwnProperty(name)) {
                list.push(name);
            }
        }

        return list;
    }

    // public scope
    context.log = log;
    context.setDebugMode = setDebugMode;
    context.registerElement = registerElement;
    context.elementExists = elementExists;
    context.registerBlock = registerBlock;
    context.blockExists = blockExists;
    context.getListOfRegisteredElements = getListOfRegisteredElements;
})(g1PageBuilder);

