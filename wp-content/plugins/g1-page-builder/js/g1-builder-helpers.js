/* global g1PageBuilder */

/**
 * Some useful helpers
 */
g1PageBuilder.helpers.getValueByKeyIfDefinedOrNull = function (obj, key) {
    "use strict";

    if (!obj || !key) {
        return null;
    }

    return typeof obj[key] !== 'undefined' ? obj[key] : null;
};
