(function ($, context) {
    "use strict";

    var elementUniqueId = 'one_sixth';

    var pbBlock = {
        level: 2,
        size: 'one-sixth',
        withControls: true,
        cssBlockClass: 'one-sixth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '1/6',
        description: 'one_sixth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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




(function ($, context) {
    "use strict";

    var elementUniqueId = 'one_fifth';

    var pbBlock = {
        level: 2,
        size: 'one-fifth',
        withControls: true,
        cssBlockClass: 'one-fifth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '1/5',
        description: 'one_fifth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'one_fourth';

    var pbBlock = {
        level: 2,
        size: 'one-fourth',
        withControls: true,
        cssBlockClass: 'one-fourth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '1/4',
        description: 'one_fourth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'one_third';

    var pbBlock = {
        level: 2,
        size: 'one-third',
        withControls: true,
        cssBlockClass: 'one-third-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '1/3',
        description: 'one_third column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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


(function ($, context) {
    "use strict";

    var elementUniqueId = 'two_fifth';

    var pbBlock = {
        level: 2,
        size: 'two-fifth',
        withControls: true,
        cssBlockClass: 'two-fifth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '2/5',
        description: 'two_fifth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'one_half';

    var pbBlock = {
        level: 2,
        size: 'one-half',
        withControls: true,
        cssBlockClass: 'one-half-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '1/2',
        description: 'one_half column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'three_fifth';

    var pbBlock = {
        level: 2,
        size: 'three-fifth',
        withControls: true,
        cssBlockClass: 'three-fifth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '3/5',
        description: 'three_fifth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'two_third';

    var pbBlock = {
        level: 2,
        size: 'two-third',
        withControls: true,
        cssBlockClass: 'two-third-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '2/3',
        description: 'two_third column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'three_fourth';

    var pbBlock = {
        level: 2,
        size: 'three-fourth',
        withControls: true,
        cssBlockClass: 'three-fourth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '3/4',
        description: 'three_fourth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'four_fifth';

    var pbBlock = {
        level: 2,
        size: 'four-fifth',
        withControls: true,
        cssBlockClass: 'four-fifth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '4/5',
        description: 'four_fifth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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



(function ($, context) {
    "use strict";

    var elementUniqueId = 'five_sixth';

    var pbBlock = {
        level: 2,
        size: 'five-sixth',
        withControls: true,
        cssBlockClass: 'five-sixth-col',
        cssElementClass: 'col',
        toolbarSection: 'layout_elements',
        label: '5/6',
        description: 'five_sixth column'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'valign': {
                'type': 'select',
                'options': {
                    'top':      'top',
                    'middle':   'middle',
                    'bottom':   'bottom'
                },
                'default': 'top'
            },
            'animation': {
                'type': 'select',
                'options': {
                    'none':      'none',
                    'fade_in':   'fade in'
                },
                'default': 'none'
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






