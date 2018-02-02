/* global jQuery */
/* global g1PageBuilder */
/* global ajaxurl */
/* global quicktags */
/* global QTags */
/* global wpActiveEditor */
/* jshint unused:true */


/* global jQuery */
/* global g1PageBuilder */
/* jshint unused:false */

(function ($, context) {
    "use strict";

    var elementUniqueId = 'audio_player';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'audio',
        cssElementClass: 'audio',
        toolbarSection: 'content_elements',
        label: 'Audio',
        description: 'An audio player.',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var out = '';

        return out;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'title': {
                'type': 'input',
                'hint': 'The title of the audio file'
            },
            'mp3': {
                'type': 'input',
                'hint': 'The source of the mp3 file'
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

    var elementUniqueId = 'bbp-forum-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-forum-form',
        cssElementClass: 'bbp-forum-form',
        toolbarSection: 'bbpress',
        label: 'Forum form',
        description: 'Display the "New Forum" form.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-forum-index';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-forum-index',
        cssElementClass: 'bbp-forum-index',
        toolbarSection: 'bbpress',
        label: 'Forum index',
        description: 'Display entire forum index.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-single-forum';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-forum',
        cssElementClass: 'bbp-single-forum',
        toolbarSection: 'bbpress',
        label: 'Single forum',
        description: 'Display a single forums topics.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        that.useCommonFields = function () {
            return false;
        };

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'Forum id'
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

    var elementUniqueId = 'bbp-login';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-login',
        cssElementClass: 'bbp-login',
        toolbarSection: 'bbpress',
        label: 'Login',
        description: 'Display the login screen.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-lost-pass';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-lost-pass',
        cssElementClass: 'bbp-lost-pass',
        toolbarSection: 'bbpress',
        label: 'Lost password',
        description: 'Display the lost password screen.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-register';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-register',
        cssElementClass: 'bbp-register',
        toolbarSection: 'bbpress',
        label: 'Register',
        description: 'Display the register screen.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-reply-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-reply-form',
        cssElementClass: 'bbp-reply-form',
        toolbarSection: 'bbpress',
        label: 'Reply form',
        description: 'Display the "New Reply" form.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-single-reply';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-reply',
        cssElementClass: 'bbp-single-reply',
        toolbarSection: 'bbpress',
        label: 'Single reply',
        description: 'Display a single reply.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'Reply id'
            }
        };

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-search';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-search',
        cssElementClass: 'bbp-search',
        toolbarSection: 'bbpress',
        label: 'Search input',
        description: 'Display the search input form.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-search-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-search-form',
        cssElementClass: 'bbp-search-form',
        toolbarSection: 'bbpress',
        label: 'Search form',
        description: 'Display the search form template.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-single-tag';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-tag',
        cssElementClass: 'bbp-single-tag',
        toolbarSection: 'bbpress',
        label: 'Single tag',
        description: 'Display a list of all topics associated with a specific tag.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'Tag id.'
            }
        };

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-stats';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-stats',
        cssElementClass: 'bbp-stats',
        toolbarSection: 'bbpress',
        label: 'Stats',
        description: 'Display the forum statistics.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-topic-form';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-topic-form',
        cssElementClass: 'bbp-topic-form',
        toolbarSection: 'bbpress',
        label: 'Topic form',
        description: 'Display the "New Topic" form.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'forum_id': {
                'type': 'input',
                'hint': 'If you leave this field empty, you can choose from a drop down menu the forum that this topic is to be associated with.'
            }
        };

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-topic-index';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-topic-index',
        cssElementClass: 'bbp-topic-index',
        toolbarSection: 'bbpress',
        label: 'Topic index',
        description: 'Display the most recent topics across all forums.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-single-topic';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-topic',
        cssElementClass: 'bbp-single-topic',
        toolbarSection: 'bbpress',
        label: 'Single topic',
        description: 'Display a single forums topics.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        that.useCommonFields = function () {
            return false;
        };

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'Topic id'
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

    var elementUniqueId = 'bbp-topic-tags';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-topic-tags',
        cssElementClass: 'bbp-topic-tags',
        toolbarSection: 'bbpress',
        label: 'Topic tags',
        description: 'Display a tag cloud of all topic tags.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'bbp-single-view';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'bbp-single-view',
        cssElementClass: 'bbp-single-view',
        toolbarSection: 'bbpress',
        label: 'Single view',
        description: 'Display topics associated with a specific view.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'id': {
                'type': 'input',
                'hint': 'View name eg. "no-replies" or "popular".'
            }
        };

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'g1_bbp_forums';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'g1-bbp-forums',
        cssElementClass: 'g1-bbp-forums',
        toolbarSection: 'bbpress',
        label: 'Forum collection',
        description: 'Display forums as a collection.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {};

        that.useCommonFields = function () {
            return true;
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

    var elementUniqueId = 'before_after';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'before-after',
        cssElementClass: 'before-after',
        toolbarSection: 'content_elements',
        label: 'Before & After',
        description: 'A banner to visualize differencies between two images.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */

        var contentConfig = null;

        var fieldsConfig = {
            'type': {
                'type': 'select',
                'options': {
                    'smooth':   'smooth',
                    'flip':     'flip',
                    'hover':    'hover'
                },
                'default': 'smooth'
            },
            'before_src': {
                'type': 'input'
            },
            'after_src': {
                'type': 'input'
            },
            'width': {
                'type': 'input',
                'hint': 'In pixels'
            },
            'height': {
                'type': 'input',
                'hint': 'In pixels'
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

    var elementUniqueId = 'box';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'box',
        cssElementClass: 'box',
        toolbarSection: 'content_elements',
        label: 'Box',
        description: 'A visual box with optional icon.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'richtext',
            'default': ''
        };

        var fieldsConfig = {
            'icon': {
                type: 'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default': ''
            },
            'style': {
                type: 'select',
                'options': {
                    'simple':   'simple',
                    'solid':    'solid'
                },
                'default': 'simple'
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

    var elementUniqueId = 'carousel';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'carousel',
        cssElementClass: 'carousel',
        toolbarSection: 'content_elements',
        label: 'Carousel',
        description: 'A simple HTML slider',
        htmlRepresentationCallback: renderBlock
    };

    /* jshint unused:true */
    function renderBlock (fieldsValues, contentValue) {
        var out = '';

        out += '<div class="g1-pb-preview-carousel">';
        out += '</div>';

        return out;
    }
    /* jshint unused:false */

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Button element class
     */
    var carousel = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'coin_nav': {
                'type': 'select',
                'options': {
                    'none':     'hide',
                    'standard': 'show'
                },
                'default': 'none'
            },
            'direction_nav': {
                'type': 'select',
                'options': {
                    'none':     'hide',
                    'standard': 'show'
                },
                'default': 'none'
            },
            'autoplay': {
                'type': 'select',
                'options': {
                    'none':     'no',
                    'standard': 'yes'
                },
                'default': 'standard'
            },
            'timeout': {
                'type': 'input',
                'hint': 'The time carousel will pause between transitions (in miliseconds)'
            }
        };

        var currentTabIndex = 0;
        var origWpActiveEditorId;
        var tinyVersion;

        // custom tabs template
        that.buildForm = function () {
            var html = '<ul class="g1-pb-form-rows">';

            html += '<li>' + that.renderSelectField('coin_nav') + '</li>';
            html += '<li>' + that.renderSelectField('direction_nav') + '</li>';
            html += '<li>' + that.renderSelectField('autoplay') + '</li>';
            html += '<li>' + that.renderInputField('timeout') + '</li>';
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
                title: 'New item',
                content: 'Carousel content goes here...'
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
                    label: 'Item title',
                    'hint': 'Only for builder purposes. This title won\'t be displayed on front page.',
                    id: '',
                    cssClass: 'g1-pb-tabs-nav-viewport-title'
                }
            );

            var contentHtml = context.classes.htmlFields.text(
                'content',

                tab.content,
                {
                    label: 'Item content',
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

                var $content = $viewports.filter('[data-g1-index='+ index +']').find('textarea[name=content]');

                html += '<div class="g1-shortcode" data-g1-name="carousel_item" data-g1-attrs-list="">'+ $content.val() +'</div>';
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

                    if (shortcodeName === 'carousel_item') {
                        tabs[tabIndex].title = 'Item ' + (tabIndex + 1);
                        tabs[tabIndex].content = shortcodeContent;
                        tabIndex++;
                    }
                }
            });

            // add new tab at start
            if (tabs.length === 0) {
                tabs[0] = {
                    title: 'Item 1',
                    content: 'Carousel content goes here...'
                };
            }

            return tabs;
        }

        that.convertToText = function (content) {
            var out = '';

            $(content).each(function () {
                var $this = $(this);

                if ($this.is('.g1-shortcode')) {
                    out += '\n\n[carousel_item]\n\n' + $this.html() + '\n\n[/carousel_item]\n\n';
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

    context.registerElement(elementUniqueId, carousel);
})(jQuery, g1PageBuilder);

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

(function ($, context) {
    "use strict";

    var elementUniqueId = 'countdown';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'countdown',
        cssElementClass: 'countdown',
        toolbarSection: 'content_elements',
        label: 'Countdown',
        description: 'A countdown clock to count the time left to a date of your choice.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type':     'text',
            'default':  '',
            'hint':     'Expiry text'
        };

        var fieldsConfig = {
            'until': {
                'type': 'input',
                'hint': 'For example: 1 June 2013'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
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

    var elementUniqueId = 'custom_pages';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'custom-pages',
        cssElementClass: 'custom-pages',
        toolbarSection: 'collections_elements',
        label: 'Custom Pages',
        description: 'A collection of custom pages.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':       'one_fourth',
                    'one_third':        'one_third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,author,summary,button-1'
            },
            'entry_ids': {
                'type':     'input',
                'hint':     'Comma separated list of entry IDs'
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

    var elementUniqueId = 'custom_posts';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'custom-posts',
        cssElementClass: 'custom-posts',
        toolbarSection: 'collections_elements',
        label: 'Custom Posts',
        description: 'A collection of custom posts.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
            },
            'entry_ids': {
                'type':     'input',
                'hint':     'Comma separated list of entry IDs'
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

    var elementUniqueId = 'custom_works';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'custom-works',
        cssElementClass: 'custom-works',
        toolbarSection: 'collections_elements',
        label: 'Custom Works',
        description: 'A collection of custom works.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
            },
            'entry_ids': {
                'type':     'input',
                'hint':     'Comma separated list of entry IDs'
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

    var elementUniqueId = 'divider';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'divider',
        cssElementClass: 'divider',
        toolbarSection: 'content_elements',
        label: 'Divider',
        description: 'A horizontal divider with optional icon.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'style': {
                'type': 'select',
                'options': {
                    'none':             'none',
                    'simple':           'simple'
                },
                'default': 'none'
            },
            'icon': {
                type: 'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default': ''
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

    var elementUniqueId = 'divider_top';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'divider-top',
        cssElementClass: 'divider-top',
        toolbarSection: 'content_elements',
        label: 'Divider Top',
        description: 'A horizontal divider with a "back to top" link.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
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

    var elementUniqueId = 'duplicator';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'duplicator',
        cssElementClass: 'duplicator',
        toolbarSection: 'content_elements',
        label: 'Duplicator',
        description: 'An animated icon graph.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'icon': {
                type:       'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default':  ''
            },
            'start': {
                'type':     'input',
                'default':  0,
                'hint': '   Elements at index lower than "start" are visible all time'
            },
            'stop': {
                'type':     'input',
                'default':  15,
                'hint':     'Stop animation at this index'
            },
            'max': {
                'type':     'input',
                'default':  20,
                'hint': 'Max. amount of elements to show'
            },
            'direction': {
                'type': 'select',
                'options': {
                    'left':     'left',
                    'right':    'right'
                },
                'default': 'right'
            },
            'style': {
                type: 'select',
                'options': {
                    'simple':   'simple',
                    'solid':    'solid'
                },
                'default': 'simple'
            },
            'color': {
                type: 'colorpicker'
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

    var elementUniqueId = 'g1_gmap';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'g1gmap',
        cssElementClass: 'g1gmap',
        toolbarSection: 'content_elements',
        label: 'G1 GMap'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    function fetchMapList ($obj) {
        // default response, we assume that something will go wrong
        var list = {
            'error': 'An error has occurred while trying to fetch map list'
        };

        var xhr = $.ajax({
            'async': false,  // don't make here async ajax call
            'type': 'POST',
            'url':  ajaxurl,
            'data': {
                action: 'g1_pb_load_map_list'
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
        var contentConfig = null;

        var fieldsConfig = {
            'map_id': {
                'type':     'select',
                'optionsCallback': fetchMapList
            }
        };

        /**
         * METHODS
         */

        that.isAsync = function () {
            return true;
        };

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'gmap';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'gmap',
        cssElementClass: 'gmap',
        toolbarSection: 'content_elements',
        label: 'GMap',
        description: 'A Google Map embed.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'width': {
                'type':     'input',
                'default':  '100%',
                'hint':     'Map width in pixels or % (leave it empty to use 100% width)'
            },
            'height': {
                'type':     'input',
                'default':  '500px',
                'hint':     'Map height in pixels'
            },
            'type': {
                'type': 'select',
                'options': {
                    'simple':   'simple',
                    'rich':     'right'
                },
                'default': 'rich',
                'hint': 'Map interface. Simple has no controls'
            },
            'color': {
                'type': 'colorpicker'
            },
            'invert_lightness': {
                type: 'select',
                    'options': {
                        '0':   'none',
                        '1':   'standard'
                },
                'default': '0'
            },
            'map_type': {
                type: 'select',
                'options': {
                    'small':  'small',
                    'medium': 'medium',
                    'big':    'big'
                },
                'default': 'small'
            },
            'latitude': {
                'type':     'input',
                'hint':     'To center the map on a specific point (eg. 40.714353 for New York, USA)'
            },
            'longitude': {
                'type':     'input',
                'hint':     'To center the map on a specific point (eg. -74.005973 for New York, USA)'
            },
            'zoom': {
                'type':     'input',
                'default':  '15',
                'hint':     'The initial resolution at which to display the map (default: 15)'
            },
            'marker': {
                type: 'select',
                'options': {
                    'none':         'none',
                    'standard':     'standard',
                    'open-bubble':  'open-bubble'
                },
                'default': 'standard',
                'hint': 'Decide if you want to show marker on the map'
            },
            'marker_icon': {
                'type':     'input',
                'hint':     'optional, if empty default marker will be used'
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

    var elementUniqueId = 'html_block';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'html',
        cssElementClass: 'html',
        toolbarSection: 'content_elements',
        label: 'HTML',
        description: 'Here you can put any HTML content',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        contentValue = contentValue.replace(/^\n+|\n+$/g , "");
        contentValue = contentValue.replace(/\n/g , "<br />");

        return contentValue;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'richtext'
        };

        var fieldsConfig = null;

        /**
         * METHODS
         */

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'list';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'list',
        cssElementClass: 'list',
        toolbarSection: 'content_elements',
        label: 'List',
        description: 'A styled list.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'description': 'for example: <br />' +
                '&lt;ul&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 1&lt;/li&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 2&lt;/li&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 3&lt;/li&gt;<br />' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;Line 4&lt;/li&gt;<br />' +
                '&lt;/ul&gt;'
        };

        var fieldsConfig = {
            'type': {
                'type': 'select',
                'options': {
                    'icon':             'icon',
                    'empty':            'empty',
                    'upper-alpha':      'upper-alpha',
                    'lower-alpha':      'lower-alpha',
                    'upper-roman':      'upper-roman',
                    'lower-roman':      'lower-roman',
                    'circle':           'circle',
                    'decimal':          'decimal',
                    'disc':             'disc',
                    'square':           'square'
                },
                'default': 'icon'
            },
            'style': {
                'type': 'select',
                'options': {
                    'none':             'none',
                    'simple':           'simple'
                },
                'default': 'none'
            },
            'icon': {
                type: 'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default': ''
            },
            'icon_color': {
                type: 'colorpicker'
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

(function ($, context) {
    "use strict";

    var elementUniqueId = 'message';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'message',
        cssElementClass: 'message',
        toolbarSection: 'content_elements',
        label: 'Message',
        description: 'A message box.',
        htmlRepresentationCallback: renderBlock
    };

    /* jshint unused:true */
    /* @todo Improvement: better handling of default values */
    /* @todo Improvement: */
    function renderBlock (fieldsValues, contentValue) {
        var out = '';

        contentValue = contentValue.replace(/^\n+|\n+$/g, '');

        if ( !contentValue ) {
            contentValue = '&hellip; here goes some text &hellip;';
        }

        var type = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'type');
        type = type || 'success';

        out += '<div class="g1-pb-preview-message">';
            out += '<div class="g1-pb-preview-message-type">(' + type + ')</div>';
            out += '<div class="g1-pb-preview-message-content">' + contentValue + '</div>';
        out += '</div>';

        return out;
    }
    /* jshint unused:false */

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var message = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'type': {
                'type': 'select',
                'options': {
                    'success':      'success',
                    'info':         'info',
                    'warning':      'warning',
                    'error':        'error'
                },
                'default': 'success'
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

    context.registerElement(elementUniqueId, message);
})(jQuery, g1PageBuilder);

(function ($, context) {
    "use strict";

    var elementUniqueId = 'numbers';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'numbers',
        cssElementClass: 'numbers',
        toolbarSection: 'content_elements',
        label: 'Numbers',
        description: 'An animated number counter with optional icon.',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var stop = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'stop');
        var out = '';

        if ( stop ) {
            out = '<div class="g1-pb-numbers-value">' + parseInt( stop, 10 ) + '</div>';
        }

        return out;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'start': {
                'type':     'input',
                'default':  '0',
                'hint':     'Initial value'
            },
            'stop': {
                'type':     'input',
                'default':  '100',
                'hint':     'Final value'
            },
            'size': {
                type: 'select',
                'options': {
                    'small':  'small',
                    'medium': 'medium',
                    'big':    'big'
                },
                'default': 'small'
            },
            'prefix': {
                'type':     'input',
                'hint':     'Put some value before'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
            },
            'suffix': {
                'type':     'input',
                'hint':     'Put some value after'
            },
            'text_color': {
                type: 'colorpicker'
            },
            'bg_color': {
                type: 'colorpicker'
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

    var elementUniqueId = 'placeholder';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'placeholder',
        cssElementClass: 'placeholder',
        toolbarSection: 'content_elements',
        label: 'Placeholder',
        description: 'An image placeholder. Useful for prototyping.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element


        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'width': {
                'type':     'input',
                'hint':     'The width in pixels'
            },
            'height': {
                'type':     'input',
                'hint':     'The height in pixels'
            },
            'size': {
                type:       'select',
                'options':  {
                    'small':    'small',
                    'medium':   'medium',
                    'big':      'big'
                },
                'default':  'small'
            },
            'icon': {
                'type':     'select',
                'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                'default': ''
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

    var elementUniqueId = 'popular_posts';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'popular-posts',
        cssElementClass: 'popular-posts',
        toolbarSection: 'collections_elements',
        label: 'Popular Posts',
        description: 'A collection of popular posts.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
            },
            'max': {
                'type':     'input',
                'hint':     'Maximum items to display'
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

    var elementUniqueId = 'popular_works';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'popular-works',
        cssElementClass: 'popular-works',
        toolbarSection: 'collections_elements',
        label: 'Popular Works',
        description: 'A collection of popular works.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
            },
            'max': {
                'type':     'input',
                'hint':     'Maximum items to display'
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

    var elementUniqueId = 'precontent';

    var pbBlock = {
        level: 1,
        withControls: true,
        disableResizeControls: true,
        disableEditControl: true,
        cssBlockClass: 'precontent',
        cssElementClass: 'precontent',
        toolbarSection: 'layout_elements',
        label: 'Precontent',
        description: 'A special theme area'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = null;

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

    var elementUniqueId = 'progress_bar';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'progress-bar',
        cssElementClass: 'progress-bar',
        toolbarSection: 'content_elements',
        label: 'Progress Bar',
        description: 'A progress (or a skill) visualization in a form of a horizontal bar.',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var value = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'value');
        var out = '';

        if (value) {
            out = '<div class="g1-pb-progress-bar-value" style="width:' + parseInt( value, 10 ) + '%;"></div>';
        }

        return out;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'input',
            'default': ''
        };

        var fieldsConfig = {
            'value': {
                'type': 'input',
                'hint': '0-100 range'
            },
            'direction': {
                'type': 'select',
                'options': {
                    'left':     'left',
                    'right':    'right'
                },
                'default': 'right'
            },
            'size': {
                type: 'select',
                    'options': {
                        'small':  'small',
                        'medium': 'medium',
                        'big':    'big'
                },
                'default': 'small'
            },
            'style': {
                type: 'select',
                    'options': {
                        'simple':   'simple',
                        'solid':    'solid'
                },
                'default': 'solid'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
            },
            'text_color': {
                type: 'colorpicker'
            },
            'bg_color': {
                type: 'colorpicker'
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

    var elementUniqueId = 'progress_circle';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'progress-circle',
        cssElementClass: 'progress-circle',
        toolbarSection: 'content_elements',
        label: 'Progress Circle',
        description: 'A progress (or a skill) visualization in a form of an animated circle.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'value': {
                'type': 'input',
                'hint': '0-100 range'
            },
            'style': {
                type: 'select',
                    'options': {
                        'simple':   'simple',
                        'solid':    'solid'
                },
                'default': 'simple'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
            },
            'text_color': {
                type: 'colorpicker'
            },
            'bg_color': {
                type: 'colorpicker'
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

    var elementUniqueId = 'quote';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'quote',
        cssElementClass: 'quote',
        toolbarSection: 'content_elements',
        label: 'Quote',
        description: 'A quotation or a testimonial.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'text',
            'default': ''
        };

        var fieldsConfig = {
            'author_name': {
                'type': 'input'
            },
            'author_description': {
                'type': 'input'
            },
            'author_image': {
                'type': 'input'
            },
            'size': {
                type: 'select',
                    'options': {
                        'small':  'small',
                        'medium': 'medium',
                        'big':    'big'
                },
                'default': 'small'
            },
            'style': {
                type: 'select',
                    'options': {
                        'simple':   'simple',
                        'solid':    'solid'
                },
                'default': 'simple'
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

    var elementUniqueId = 'recent_posts';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'recent-posts',
        cssElementClass: 'recent-posts',
        toolbarSection: 'collections_elements',
        label: 'Recent Posts',
        description: 'A collection of recent posts.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
            },
            'max': {
                'type':     'input',
                'hint':     'Maximum entries to display'
            },
            'category_ids': {
                'type':     'input',
                'hint':     'Comma separated list of category IDs (optional)'
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

    var elementUniqueId = 'recent_works';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'recent-works',
        cssElementClass: 'recent-works',
        toolbarSection: 'collections_elements',
        label: 'Recent Works',
        description: 'A collection of recent works.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
            },
            'max': {
                'type':     'input',
                'hint':     'Maximum entries to display'
            },
            'category_ids': {
                'type':     'input',
                'hint':     'Comma separated list of category IDs (optional)'
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

    var elementUniqueId = 'related_pages';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'related-pages',
        cssElementClass: 'related-pages',
        toolbarSection: 'collections_elements',
        label: 'Related Pages',
        description: 'A collection of related pages.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'max': {
                'type':     'input',
                'hint':     'Maximum entries to display'
            },
            'entry_id': {
                'type':     'input',
                'hint':     'Entry ID - leave empty to use the current entry ID'
            },
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':       'one_fourth',
                    'one_third':        'one_third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,author,summary,button-1'
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

    var elementUniqueId = 'related_posts';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'related-posts',
        cssElementClass: 'related-posts',
        toolbarSection: 'collections_elements',
        label: 'Related Posts',
        description: 'A collection of related posts.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'max': {
                'type':     'input',
                'hint':     'Maximum items to display'
            },
            'entry_id': {
                'type':     'input',
                'hint':     'Entry ID - leave empty to use the current entry ID'
            },
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
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

    var elementUniqueId = 'related_works';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'related-works',
        cssElementClass: 'related-works',
        toolbarSection: 'collections_elements',
        label: 'Related Works',
        description: 'A collection of related works.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'max': {
                'type':     'input',
                'hint':     'Maximum items to display'
            },
            'entry_id': {
                'type':     'input',
                'hint':     'Entry ID - leave empty to use the current entry ID'
            },
            'template': {
                'type':     'select',
                'options':  {
                    'one_fourth':           'one fourth',
                    'one_fourth_gallery':   'one fourth gallery',
                    'one_fourth_masonry':   'one fourth masonry',
                    'one_third':            'one third',
                    'one_third_gallery':    'one third gallery',
                    'one_third_masonry':    'one third masonry',
                    'one_half':             'one half',
                    'one_half_gallery':     'one half gallery',
                    'two_third':            'two third'
                },
                'default':  'one_fourth'
            },
            'effect': {
                'type':     'select',
                'options':  {
                    'none':         'none',
                    'grayscale':    'grayscale'
                },
                'default':  'none'
            },
            'hide': {
                'type':     'input',
                'hint':     'Comma separated list of elements. Full list: title,featured-media,date,author,comments-link,summary,categories,tags,button-1'
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

    var elementUniqueId = 'rev_slider';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'revslider',
        cssElementClass: 'revslider',
        toolbarSection: 'content_elements',
        label: 'Revolution Slider',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var alias = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'alias');

        return alias ? alias : '';
    }

    context.registerBlock(elementUniqueId, pbBlock);

    function fetchRevsliderAliases ($obj) {
        // default response, we assume that something will go wrong
        var list = {
            'error': 'An error has occurred while trying to fetch slider list'
        };

        var xhr = $.ajax({
            'async': false,  // don't make here async ajax call
            'type': 'POST',
            'url':  ajaxurl,
            'data': {
                action: 'g1_pb_load_revslider_list'
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
        var contentConfig = null;

        var fieldsConfig = {
            'alias': {
                'type':     'select',
                'optionsCallback': fetchRevsliderAliases
            }
        };

        /**
         * METHODS
         */

        that.isAsync = function () {
            return true;
        };

        that.useCommonFields = function () {
            return false;
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

    var elementUniqueId = 'section';

    var pbBlock = {
        level: 1,
        withControls: true,
        disableResizeControls: true,
        cssBlockClass: 'section',
        cssElementClass: 'section',
        toolbarSection: 'layout_elements',
        label: 'Section',
        description: 'A full width section with a custom background'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'background_color': {
                'type': 'colorpicker'
            },
            'background_image': {
                'type': 'input'
            },
            'background_repeat': {
                'type': 'select',
                'options': {
                    'no-repeat':    'no-repeat',
                    'repeat':       'repeat',
                    'repeat-x':     'repeat-x',
                    'repeat-y':     'repeat-y'
                },
                'default': 'repeat'
            },
            'background_position': {
                'type': 'select',
                'options': {
                    'left top':     'left top',
                    'center top':   'center top',
                    'right top':    'right top',
                    'left bottom':  'left bottom',
                    'center bottom':'center bottom',
                    'right bottom': 'right bottom'
                },
                'default': 'center top'
            },
            'background_attachment': {
                'type': 'select',
                'options': {
                    'static':       'static',
                    'fixed':        'fixed'
                },
                'default':  'static'
            },
            'background_scroll': {
                'type': 'select',
                'options': {
                    'none':         'none',
                    'standard':     'standard'
                },
                'default':  'none'
            },
            'border_size': {
                'type': 'input',
                'hint': 'In pixels'
            },
            'padding_bottom': {
                'type': 'input',
                'hint': 'In pixels'
            },
            'padding_top': {
                'type': 'input',
                'hint': 'In pixels'
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

    var elementUniqueId = 'space';

    var pbBlock = {
        level: 3,
        withControls: true,
        attrs: {
            'value': {
                'default': '20px'
            }
        },
        cssBlockClass: 'space',
        cssElementClass: 'space',
        toolbarSection: 'content_elements',
        label: 'Space',
        description: 'A blank, vertical separator.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    /**
     * Class
     */
    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        var contentConfig = null;

        var fieldsConfig = {
            value: {
                'type': 'input',
                'hint': 'The height in pixels (can be negative).'
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

    var elementUniqueId = 'table';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'table',
        cssElementClass: 'table',
        toolbarSection: 'content_elements',
        label: 'Table',
        description: 'A styled table.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type':     'text',
            'default':  ''
        };

        var fieldsConfig = {
            'style': {
                'type':     'select',
                'options':  {
                    'simple':   'simple',
                    'solid':    'solid'
                },
                'default':  'simple'
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

(function ($, context) {
    "use strict";

    var elementUniqueId = 'toggle';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'toggle',
        cssElementClass: 'toggle',
        toolbarSection: 'content_elements',
        label: 'Toggle',
        description: 'A content box with a toggle',
        htmlRepresentationCallback: renderBlock
    };

    function renderBlock (fieldsValues, contentValue) {
        var title = context.helpers.getValueByKeyIfDefinedOrNull(fieldsValues, 'title');
        var out = '';

        if (title) {
            out = '<div class="g1-pb-toggle-title">' + title + '</div>';
        }

        return out;
    }

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            'type': 'richtext',
            'default': ''
        };

        var fieldsConfig = {
            'title': {
                'type': 'input'
            },
            'state': {
                'type': 'select',
                'options': {
                    'on':   'on',
                    'off':  'off'
                },
                'default': 'off'
            },
            'style': {
                type: 'select',
                'options': {
                    'simple':   'simple',
                    'solid':    'solid'
                },
                'default': 'simple'
            },
            'icon': {
                type: 'select',
                    'optionsCallback': g1PageBuilder.helpers.getIconsChoices,
                    'default': ''
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

    var elementUniqueId = 'twitter';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'twitter',
        cssElementClass: 'twitter',
        toolbarSection: 'content_elements',
        label: 'Twitter',
        description: 'A list of recent tweets.'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = null;

        var fieldsConfig = {
            'username': {
                'type':     'input'
            },
            'max': {
                'type':     'input',
                'hint':     'Maximum items to display'
            },
            'type': {
                'type': 'select',
                'options': {
                    'simple':       'simple',
                    'carousel':     'carousel'
                },
                'default': 'simple'
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

    var elementUniqueId = 'wrong_element';

    var pbBlock = {
        level: 3,
        withControls: true,
        cssBlockClass: 'wrong-element',
        cssElementClass: 'wrong-element',
        label: 'Unknown element'
    };

    context.registerBlock(elementUniqueId, pbBlock);

    var pbElement = function (fieldsValues, contentValue) {
        var that = context.classes.elements.baseElement(); // inherit from base element

        /**
         * VARS
         */
        var contentConfig = {
            type: 'richtext'
        };

        var fieldsConfig = null;

        /**
         * METHODS
         */

        that.useCommonFields = function () {
            return false;
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

