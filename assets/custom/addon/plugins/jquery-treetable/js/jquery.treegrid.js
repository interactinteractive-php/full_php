/*
 * jQuery tabletree Plugin 0.3.0
 * https://github.com/maxazan/jquery-tabletree
 *
 * Copyright 2013, Pomazan Max
 * Licensed under the MIT licenses.
 */
(function($) {

    var methods = {
        /**
         * Initialize tree
         *
         * @param {Object} options
         * @returns {Object[]}
         */
        initTree: function(options) {
            var settings = $.extend({}, this.tabletree.defaults, options);
            return this.each(function() {
                var $this = $(this);
                $this.tabletree('setTreeContainer', $(this));
                $this.tabletree('setSettings', settings);
                settings.getRootNodes.apply(this, [$(this)]).tabletree('initNode', settings);
                $this.tabletree('getRootNodes').tabletree('render');
            });
        },
        /**
         * Initialize node
         *
         * @param {Object} settings
         * @returns {Object[]}
         */
        initNode: function(settings) {
            return this.each(function() {
                var $this = $(this);
                $this.tabletree('setTreeContainer', settings.gettabletreeContainer.apply(this));
                $this.tabletree('getChildNodes').tabletree('initNode', settings);
                $this.tabletree('initExpander').tabletree('initIndent').tabletree('initEvents').tabletree('initState').tabletree('initChangeEvent').tabletree("initSettingsEvents");
            });
        },
        initChangeEvent: function() {
            var $this = $(this);
            //Save state on change
            $this.on("change", function() {
                var $this = $(this);
                $this.tabletree('render');
                if ($this.tabletree('getSetting', 'saveState')) {
                    $this.tabletree('saveState');
                }
            });
            return $this;
        },
        /**
         * Initialize node events
         *
         * @returns {Node}
         */
        initEvents: function() {
            var $this = $(this);
            //Default behavior on collapse
            $this.on("collapse", function() {
                var $this = $(this);
                $this.removeClass('tabletree-expanded');
                $this.addClass('tabletree-collapsed');
            });
            //Default behavior on expand
            $this.on("expand", function() {
                var $this = $(this);
                $this.removeClass('tabletree-collapsed');
                $this.addClass('tabletree-expanded');
            });

            return $this;
        },
        /**
         * Initialize events from settings
         *
         * @returns {Node}
         */
        initSettingsEvents: function() {
            var $this = $(this);
            //Save state on change
            $this.on("change", function() {
                var $this = $(this);
                if (typeof($this.tabletree('getSetting', 'onChange')) === "function") {
                    $this.tabletree('getSetting', 'onChange').apply($this);
                }
            });
            //Default behavior on collapse
            $this.on("collapse", function() {
                var $this = $(this);
                if (typeof($this.tabletree('getSetting', 'onCollapse')) === "function") {
                    $this.tabletree('getSetting', 'onCollapse').apply($this);
                }
            });
            //Default behavior on expand
            $this.on("expand", function() {
                var $this = $(this);
                if (typeof($this.tabletree('getSetting', 'onExpand')) === "function") {
                    $this.tabletree('getSetting', 'onExpand').apply($this);
                }

            });

            return $this;
        },
        /**
         * Initialize expander for node
         *
         * @returns {Node}
         */
        initExpander: function() {
            var $this = $(this);
            var cell = $this.find('td').get($this.tabletree('getSetting', 'treeColumn'));
            var tpl = $this.tabletree('getSetting', 'expanderTemplate');
            var expander = $this.tabletree('getSetting', 'getExpander').apply(this);
            if (expander) {
                expander.remove();
            }
            $(tpl).prependTo(cell).click(function() {
                $($(this).closest('tr')).tabletree('toggle');
            });
            return $this;
        },
        /**
         * Initialize indent for node
         *
         * @returns {Node}
         */
        initIndent: function() {
            var $this = $(this);
            $this.find('.tabletree-indent').remove();
            var tpl = $this.tabletree('getSetting', 'indentTemplate');
            var expander = $this.find('.tabletree-expander');
            var depth = $this.tabletree('getDepth');
            for (var i = 0; i < depth; i++) {
                $(tpl).insertBefore(expander);
            }
            return $this;
        },
        /**
         * Initialise state of node
         *
         * @returns {Node}
         */
        initState: function() {
            var $this = $(this);
            if ($this.tabletree('getSetting', 'saveState') && !$this.tabletree('isFirstInit')) {
                $this.tabletree('restoreState');
            } else {
                if ($this.tabletree('getSetting', 'initialState') === "expanded") {
                    $this.tabletree('expand');
                } else {
                    $this.tabletree('collapse');
                }
            }
            return $this;
        },
        /**
         * Return true if this tree was never been initialised
         *
         * @returns {Boolean}
         */
        isFirstInit: function() {
            var tree = $(this).tabletree('getTreeContainer');
            if (tree.data('first_init') === undefined) {
                tree.data('first_init', $.cookie(tree.tabletree('getSetting', 'saveStateName')) === undefined);
            }
            return tree.data('first_init');
        },
        /**
         * Save state of current node
         *
         * @returns {Node}
         */
        saveState: function() {
            var $this = $(this);
            if ($this.tabletree('getSetting', 'saveStateMethod') === 'cookie') {

                var stateArrayString = $.cookie($this.tabletree('getSetting', 'saveStateName')) || '';
                var stateArray = (stateArrayString === '' ? [] : stateArrayString.split(','));
                var nodeId = $this.tabletree('getNodeId');

                if ($this.tabletree('isExpanded')) {
                    if ($.inArray(nodeId, stateArray) === -1) {
                        stateArray.push(nodeId);
                    }
                } else if ($this.tabletree('isCollapsed')) {
                    if ($.inArray(nodeId, stateArray) !== -1) {
                        stateArray.splice($.inArray(nodeId, stateArray), 1);
                    }
                }
                $.cookie($this.tabletree('getSetting', 'saveStateName'), stateArray.join(','));
            }
            return $this;
        },
        /**
         * Restore state of current node.
         *
         * @returns {Node}
         */
        restoreState: function() {
            var $this = $(this);
            if ($this.tabletree('getSetting', 'saveStateMethod') === 'cookie') {
                var stateArray = $.cookie($this.tabletree('getSetting', 'saveStateName')).split(',');
                if ($.inArray($this.tabletree('getNodeId'), stateArray) !== -1) {
                    $this.tabletree('expand');
                } else {
                    $this.tabletree('collapse');
                }

            }
            return $this;
        },
        /**
         * Method return setting by name
         *
         * @param {type} name
         * @returns {unresolved}
         */
        getSetting: function(name) {
            if (!$(this).tabletree('getTreeContainer')) {
                return null;
            }
            return $(this).tabletree('getTreeContainer').data('settings')[name];
        },
        /**
         * Add new settings
         *
         * @param {Object} settings
         */
        setSettings: function(settings) {
            $(this).tabletree('getTreeContainer').data('settings', settings);
        },
        /**
         * Return tree container
         *
         * @returns {HtmlElement}
         */
        getTreeContainer: function() {
            return $(this).data('tabletree');
        },
        /**
         * Set tree container
         *
         * @param {HtmlE;ement} container
         */
        setTreeContainer: function(container) {
            return $(this).data('tabletree', container);
        },
        /**
         * Method return all root nodes of tree.
         *
         * Start init all child nodes from it.
         *
         * @returns {Array}
         */
        getRootNodes: function() {
            return $(this).tabletree('getSetting', 'getRootNodes').apply(this, [$(this).tabletree('getTreeContainer')]);
        },
        /**
         * Method return all nodes of tree.
         *
         * @returns {Array}
         */
        getAllNodes: function() {
            return $(this).tabletree('getSetting', 'getAllNodes').apply(this, [$(this).tabletree('getTreeContainer')]);
        },
        /**
         * Mthod return true if element is Node
         *
         * @returns {String}
         */
        isNode: function() {
            return $(this).tabletree('getNodeId') !== null;
        },
        /**
         * Mthod return id of node
         *
         * @returns {String}
         */
        getNodeId: function() {
            if ($(this).tabletree('getSetting', 'getNodeId') === null) {
                return null;
            } else {
                return $(this).tabletree('getSetting', 'getNodeId').apply(this);
            }
        },
        /**
         * Method return parent id of node or null if root node
         *
         * @returns {String}
         */
        getParentNodeId: function() {
            return $(this).tabletree('getSetting', 'getParentNodeId').apply(this);
        },
        /**
         * Method return parent node or null if root node
         *
         * @returns {Object[]}
         */
        getParentNode: function() {
            if ($(this).tabletree('getParentNodeId') === null) {
                return null;
            } else {
                return $(this).tabletree('getSetting', 'getNodeById').apply(this, [$(this).tabletree('getParentNodeId'), $(this).tabletree('getTreeContainer')]);
            }
        },
        /**
         * Method return array of child nodes or null if node is leaf
         *
         * @returns {Object[]}
         */
        getChildNodes: function() {
            return $(this).tabletree('getSetting', 'getChildNodes').apply(this, [$(this).tabletree('getNodeId'), $(this).tabletree('getTreeContainer')]);
        },
        /**
         * Method return depth of tree.
         *
         * This method is needs for calculate indent
         *
         * @returns {Number}
         */
        getDepth: function() {
            if ($(this).tabletree('getParentNode') === null) {
                return 0;
            }
            return $(this).tabletree('getParentNode').tabletree('getDepth') + 1;
        },
        /**
         * Method return true if node is root
         *
         * @returns {Boolean}
         */
        isRoot: function() {
            return $(this).tabletree('getDepth') === 0;
        },
        /**
         * Method return true if node has no child nodes
         *
         * @returns {Boolean}
         */
        isLeaf: function() {
            return $(this).tabletree('getChildNodes').length === 0;
        },
        /**
         * Method return true if node last in branch
         *
         * @returns {Boolean}
         */
        isLast: function() {
            if ($(this).tabletree('isNode')) {
                var parentNode = $(this).tabletree('getParentNode');
                if (parentNode === null) {
                    if ($(this).tabletree('getNodeId') === $(this).tabletree('getRootNodes').last().tabletree('getNodeId')) {
                        return true;
                    }
                } else {
                    if ($(this).tabletree('getNodeId') === parentNode.tabletree('getChildNodes').last().tabletree('getNodeId')) {
                        return true;
                    }
                }
            }
            return false;
        },
        /**
         * Method return true if node first in branch
         *
         * @returns {Boolean}
         */
        isFirst: function() {
            if ($(this).tabletree('isNode')) {
                var parentNode = $(this).tabletree('getParentNode');
                if (parentNode === null) {
                    if ($(this).tabletree('getNodeId') === $(this).tabletree('getRootNodes').first().tabletree('getNodeId')) {
                        return true;
                    }
                } else {
                    if ($(this).tabletree('getNodeId') === parentNode.tabletree('getChildNodes').first().tabletree('getNodeId')) {
                        return true;
                    }
                }
            }
            return false;
        },
        /**
         * Return true if node expanded
         *
         * @returns {Boolean}
         */
        isExpanded: function() {
            return $(this).hasClass('tabletree-expanded');
        },
        /**
         * Return true if node collapsed
         *
         * @returns {Boolean}
         */
        isCollapsed: function() {
            return $(this).hasClass('tabletree-collapsed');
        },
        /**
         * Return true if at least one of parent node is collapsed
         *
         * @returns {Boolean}
         */
        isOneOfParentsCollapsed: function() {
            var $this = $(this);
            if ($this.tabletree('isRoot')) {
                return false;
            } else {
                if ($this.tabletree('getParentNode').tabletree('isCollapsed')) {
                    return true;
                } else {
                    return $this.tabletree('getParentNode').tabletree('isOneOfParentsCollapsed');
                }
            }
        },
        /**
         * Expand node
         *
         * @returns {Node}
         */
        expand: function() {
            if (!this.tabletree('isLeaf') && !this.tabletree("isExpanded")) {
                this.trigger("expand");
                this.trigger("change");
                return this;
            }
            return this;
        },
        /**
         * Expand all nodes
         *
         * @returns {Node}
         */
        expandAll: function() {
            var $this = $(this);
            $this.tabletree('getRootNodes').tabletree('expandRecursive');
            return $this;
        },
        /**
         * Expand current node and all child nodes begin from current
         *
         * @returns {Node}
         */
        expandRecursive: function() {
            return $(this).each(function() {
                var $this = $(this);
                $this.tabletree('expand');
                if (!$this.tabletree('isLeaf')) {
                    $this.tabletree('getChildNodes').tabletree('expandRecursive');
                }
            });
        },
        /**
         * Collapse node
         *
         * @returns {Node}
         */
        collapse: function() {
            return $(this).each(function() {
                var $this = $(this);
                if (!$this.tabletree('isLeaf') && !$this.tabletree("isCollapsed")) {
                    $this.trigger("collapse");
                    $this.trigger("change");
                }
            });
        },
        /**
         * Collapse all nodes
         *
         * @returns {Node}
         */
        collapseAll: function() {
            var $this = $(this);
            $this.tabletree('getRootNodes').tabletree('collapseRecursive');
            return $this;
        },
        /**
         * Collapse current node and all child nodes begin from current
         *
         * @returns {Node}
         */
        collapseRecursive: function() {
            return $(this).each(function() {
                var $this = $(this);
                $this.tabletree('collapse');
                if (!$this.tabletree('isLeaf')) {
                    $this.tabletree('getChildNodes').tabletree('collapseRecursive');
                }
            });
        },
        /**
         * Expand if collapsed, Collapse if expanded
         *
         * @returns {Node}
         */
        toggle: function() {
            var $this = $(this);
            if ($this.tabletree('isExpanded')) {
                $this.tabletree('collapse');
            } else {
                $this.tabletree('expand');
            }
            return $this;
        },
        /**
         * Rendering node
         *
         * @returns {Node}
         */
        render: function() {
            return $(this).each(function() {
                var $this = $(this);
                //if parent colapsed we hidden
                if ($this.tabletree('isOneOfParentsCollapsed')) {
                    $this.hide();
                } else {
                    $this.show();
                }
                if (!$this.tabletree('isLeaf')) {
                    $this.tabletree('renderExpander');
                    $this.tabletree('getChildNodes').tabletree('render');
                }
            });
        },
        /**
         * Rendering expander depends on node state
         *
         * @returns {Node}
         */
        renderExpander: function() {
            return $(this).each(function() {
                var $this = $(this);
                var expander = $this.tabletree('getSetting', 'getExpander').apply(this);
                if (expander) {

                    if (!$this.tabletree('isCollapsed')) {
                        expander.removeClass($this.tabletree('getSetting', 'expanderCollapsedClass'));
                        expander.addClass($this.tabletree('getSetting', 'expanderExpandedClass'));
                    } else {
                        expander.removeClass($this.tabletree('getSetting', 'expanderExpandedClass'));
                        expander.addClass($this.tabletree('getSetting', 'expanderCollapsedClass'));
                    }
                } else {
                    $this.tabletree('initExpander');
                    $this.tabletree('renderExpander');
                }
            });
        }
    };
    $.fn.tabletree = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.initTree.apply(this, arguments);
        } else {
            $.error('Method with name ' + method + ' does not exists for jQuery.tabletree');
        }
    };
    /**
     *  Plugin's default options
     */
    $.fn.tabletree.defaults = {
        initialState: 'expanded',
        saveState: false,
        saveStateMethod: 'cookie',
        saveStateName: 'tree-grid-state',
        expanderTemplate: '<span class="tabletree-expander"></span>',
        indentTemplate: '<span class="tabletree-indent"></span>',
        expanderExpandedClass: 'tabletree-expander-expanded',
        expanderCollapsedClass: 'tabletree-expander-collapsed',
        treeColumn: 0,
        getExpander: function() {
            return $(this).find('.tabletree-expander');
        },
        getNodeId: function() {
            var template = /tabletree-([A-Za-z0-9_-]+)/;
            if (template.test($(this).attr('class'))) {
                return template.exec($(this).attr('class'))[1];
            }
            return null;
        },
        getParentNodeId: function() {
            var template = /tabletree-parent-([A-Za-z0-9_-]+)/;
            if (template.test($(this).attr('class'))) {
                return template.exec($(this).attr('class'))[1];
            }
            return null;
        },
        getNodeById: function(id, tabletreeContainer) {
            var templateClass = "tabletree-" + id;
            return tabletreeContainer.find('tr.' + templateClass);
        },
        getChildNodes: function(id, tabletreeContainer) {
            var templateClass = "tabletree-parent-" + id;
            return tabletreeContainer.find('tr.' + templateClass);
        },
        gettabletreeContainer: function() {
            return $(this).closest('table');
        },
        getRootNodes: function(tabletreeContainer) {
            var result = $.grep(tabletreeContainer.find('tr'), function(element) {
                var classNames = $(element).attr('class');
                var templateClass = /tabletree-([A-Za-z0-9_-]+)/;
                var templateParentClass = /tabletree-parent-([A-Za-z0-9_-]+)/;
                return templateClass.test(classNames) && !templateParentClass.test(classNames);
            });
            return $(result);
        },
        getAllNodes: function(tabletreeContainer) {
            var result = $.grep(tabletreeContainer.find('tr'), function(element) {
                var classNames = $(element).attr('class');
                var templateClass = /tabletree-([A-Za-z0-9_-]+)/;
                return templateClass.test(classNames);
            });
            return $(result);
        },
        //Events
        onCollapse: null,
        onExpand: null,
        onChange: null

    };
})(jQuery);
