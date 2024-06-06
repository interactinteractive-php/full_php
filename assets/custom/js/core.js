var Core = function() {

    // IE mode
    var isRTL = false;
    var isIE8 = false;
    var isIE9 = false;
    var isIE10 = false;

    var resizeHandlers = [];
    var assetsPath = 'assets/custom/';
    var globalImgPath = 'addon/img/';
    var globalPluginsPath = 'addon/plugins/';
    var globalCssPath = 'addon/css/';

    var brandColors = {
        'blue': '#89C4F4',
        'red': '#F3565D',
        'green': '#1bbc9b',
        'purple': '#9b59b6',
        'grey': '#95a5a6',
        'yellow': '#F8CB00'
    };

    var handleInit = function() {

        if ($('body').css('direction') === 'rtl') {
            isRTL = true;
        }

        isIE8 = !!navigator.userAgent.match(/MSIE 8.0/);
        isIE9 = !!navigator.userAgent.match(/MSIE 9.0/);
        isIE10 = !!navigator.userAgent.match(/MSIE 10.0/);

        if (isIE10) {
            $('html').addClass('ie10'); // detect IE10 version
        }

        if (isIE10 || isIE9 || isIE8) {
            $('html').addClass('ie'); // detect IE10 version
        }
    };

    // runs callback functions set by Core.addResponsiveHandler().
    var _runResizeHandlers = function() {
        // reinitialize other subscribed elements
        for (var i = 0; i < resizeHandlers.length; i++) {
            var each = resizeHandlers[i];
            each.call();
        }
    };

    // handle the layout reinitialization on window resize
    var handleOnResize = function() {
        var resize;
        if (isIE8) {
            var currheight;
            $(window).resize(function() {
                if (currheight == document.documentElement.clientHeight) {
                    return; //quite event since only body resized not window.
                }
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function() {
                    _runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.                
                currheight = document.documentElement.clientHeight; // store last body client height
            });
        } else {
            $(window).resize(function() {
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function() {
                    _runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.
            });
        }
    };

    // Handles portlet tools & actions
    var handlePortletTools = function() {

        $('body').on('click', '.card > .card-header > .caption.card-collapse:not(.dataview)', function(e) {
            var $this = $(this);
            var $thisParent = $this.parent();
            var $el = $this.closest(".card").children(".card-body");
            var $element = $thisParent.find("a.tool-collapse");
            if ($element.hasClass("collapse")) {
                $element.removeClass("collapse").addClass("expand");
                $el.css({ 'display': 'none' }).addClass("display-none");
            } else {
                $element.removeClass("expand").addClass("collapse");
                $el.css({ 'display': '' }).removeClass("display-none");
            }
        });
    };

    // Handles custom checkboxes & radios using jQuery Uniform plugin
    var handleUniform = function(element) {
        if (!$().uniform) {
            return;
        }
        element = (typeof element === 'undefined') ? $('body') : element;
        var test = $("input[type=checkbox]:not(.notuniform, .toggle, .md-check, .md-radiobtn, .make-switch, .icheck, input[id^='ui-multiselect-']), input[type=radio]:not(.notuniform, .toggle, .md-check, .md-radiobtn, .star, .make-switch, .icheck)", element);
        if (test.length > 0) {
            var len = test.length, i = 0;
            for (i; i < len; i++) {
                var $this = $(test[i]);
                if ($this.parents(".checker").length === 0) {
                    $this.show();
                    $this.uniform();
                }
            }
        }
    };

    var componentSwitchery = function(element) {
        if (typeof Switchery == 'undefined') {
            console.warn('Warning - switchery.min.js is not loaded.');
            return;
        }

        // Initialize multiple switches
        var elems = (typeof element !== 'undefined') ? Array.prototype.slice.call($(element).find('.form-check-input-switchery')) : Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
        elems.forEach(function(html) {
          var switchery = new Switchery(html);
        });

        // Colored switches
        var primary = document.querySelector('.form-check-input-switchery-primary');
        var switchery = new Switchery(primary, { color: '#2196F3' });

        var danger = document.querySelector('.form-check-input-switchery-danger');
        var switchery = new Switchery(danger, { color: '#EF5350' });

        var warning = document.querySelector('.form-check-input-switchery-warning');
        var switchery = new Switchery(warning, { color: '#FF7043' });

        var info = document.querySelector('.form-check-input-switchery-info');
        var switchery = new Switchery(info, { color: '#00BCD4'});
    };

    var handleMaterialDesign = function() {

        // Material design ckeckbox and radio effects
        $('body').on('click', '.md-checkbox > label, .md-radio > label', function() {
            var the = $(this);
            // find the first span which is our circle/bubble
            var el = $(this).children('span:first-child');

            // add the bubble class (we do this so it doesnt show on page load)
            el.addClass('inc');

            // clone it
            var newone = el.clone(true);

            // add the cloned version before our original
            el.before(newone);

            // remove the original so that it is ready to run on next click
            $("." + el.attr("class") + ":last", the).remove();
        });

        if ($('body').hasClass('page-md')) {
            // Material design click effect
            // credit where credit's due; http://thecodeplayer.com/walkthrough/ripple-click-effect-google-material-design       
            $('body').on('click', 'a.btn, button.btn, input.btn, label.btn', function(e) {
                var element, circle, d, x, y;

                element = $(this);

                if (element.find(".md-click-circle").length == 0) {
                    element.prepend("<span class='md-click-circle'></span>");
                }

                circle = element.find(".md-click-circle");
                circle.removeClass("md-click-animate");

                if (!circle.height() && !circle.width()) {
                    d = Math.max(element.outerWidth(), element.outerHeight());
                    circle.css({ height: d, width: d });
                }

                x = e.pageX - element.offset().left - circle.width() / 2;
                y = e.pageY - element.offset().top - circle.height() / 2;

                circle.css({ top: y + 'px', left: x + 'px' }).addClass("md-click-animate");
            });
        }

        // Floating labels
        var handleInput = function(el) {
            if (el.val() != "") {
                el.addClass('edited');
            } else {
                el.removeClass('edited');
            }
        }

        $('body').on('keydown', '.form-md-floating-label .form-control', function(e) {
            handleInput($(this));
        });
        $('body').on('blur', '.form-md-floating-label .form-control', function(e) {
            handleInput($(this));
        });
    }

    // Handles custom checkboxes & radios using jQuery iCheck plugin
    var handleiCheck = function() {
        if (!$().iCheck) {
            return;
        }

        $('.icheck').each(function() {
            var checkboxClass = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-grey';
            var radioClass = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-grey';

            if (checkboxClass.indexOf('_line') > -1 || radioClass.indexOf('_line') > -1) {
                $(this).iCheck({
                    checkboxClass: checkboxClass,
                    radioClass: radioClass,
                    insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
                });
            } else {
                $(this).iCheck({
                    checkboxClass: checkboxClass,
                    radioClass: radioClass
                });
            }
        });
    };

    // Handles Bootstrap switches
    var handleBootstrapSwitch = function(element) {
        if (!$().bootstrapSwitch) {
            return;
        }
        element = (typeof element === 'undefined') ? $('body') : element;
        $('.make-switch', element).bootstrapSwitch();
    };

    // Handles Bootstrap confirmations
    var handleBootstrapConfirmation = function() {
        if (!$().confirmation) {
            return;
        }
        $('[data-toggle=confirmation]').confirmation({ container: 'body', btnOkClass: 'btn-xs btn-success', btnCancelClass: 'btn-xs btn-danger' });
    }

    // Handles Bootstrap Accordions.
    var handleAccordions = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        element.on('shown.bs.collapse', '.accordion.scrollable', function(e) {
            Core.scrollTo($(e.target));
        });
    };

    // Handles Bootstrap Tabs.
    var handleTabs = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        if (location.hash) {
            var tabid = location.hash.substr(1);
            $('a[href="#' + tabid + '"]', element).parents('.tab-pane:hidden').each(function() {
                var tabid = $(this).attr("id");
                $('a[href="#' + tabid + '"]', element).click();
            });
            $('a[href="#' + tabid + '"]', element).click();
        }
        /*if ($().tabdrop) {
            setTimeout(function(){
                $('.tabbable-tabdrop .nav-pills, .tabbable-tabdrop .nav-tabs', element).tabdrop({
                    text: '<i class="fa fa-ellipsis-v"></i>'
                });
            }, 500);
        }*/
    };

    // Handles Bootstrap Top Menu Responsive.
    var handleTopMenuResponsive = function() {
        var metaTopMenu = $('.page-topbar-menu');
        if (metaTopMenu.length)
            metaTopMenu.menudrop({
                text: '<i class="fa fa-reorder"></i>'
            });

        $(window).resize(function() {
            var msectionSel = $('.page-topbar-menu');
            var msectionWidth = $(window).width() + 3;
            msectionSel.css('width', msectionWidth);

            //$('.page-topbar-menu > li > ul.dropdown-menu').css({'max-height': ($(window).height() - 60)+'px', 'overflow-y': 'auto', 'overflow-x': 'hidden'});
        });
        $(window).trigger('resize');

        // $('.page-topbar-menu > li > ul.dropdown-menu > .dropdown-submenu').on('hover', function(e){
        // });
    };

    // Handles Bootstrap Modals.
    var handleModals = function() {
        // fix stackable modal issue: when 2 or more modals opened, closing one of modal will remove .modal-open class. 
        $('body').on('hide.bs.modal', function() {
            if ($('.modal:visible').length > 1 && $('html').hasClass('modal-open') === false) {
                $('html').addClass('modal-open');
            } else if ($('.modal:visible').length <= 1) {
                $('html').removeClass('modal-open');
            }
        });

        // fix page scrollbars issue
        $('body').on('show.bs.modal', '.modal', function() {
            if ($(this).hasClass("modal-scroll")) {
                $('body').addClass("modal-open-noscroll");
            }
        });

        // fix page scrollbars issue
        $('body').on('hide.bs.modal', '.modal', function() {
            $('body').removeClass("modal-open-noscroll");
        });

        // remove ajax content and remove cache on modal closed 
        $('body').on('hidden.bs.modal', '.modal:not(.modal-cached)', function() {
            $(this).removeData('bs.modal');
        });
    };

    // Handles Bootstrap Dropdowns
    var handleDropdowns = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        element.on('click', '.dropdown-menu.hold-on-click', function(e) {
            e.stopPropagation();
        });
    };

    var handleAlerts = function() {
        $('body').on('click', '[data-close="alert"]', function(e) {
            $(this).parent('.alert').hide();
            $(this).closest('.note').hide();
            e.preventDefault();
        });

        $('body').on('click', '[data-close="note"]', function(e) {
            $(this).closest('.note').hide();
            e.preventDefault();
        });

        $('body').on('click', '[data-remove="note"]', function(e) {
            $(this).closest('.note').remove();
            e.preventDefault();
        });
    };

    // Handle Hower Dropdowns
    var handleDropdownHover = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        $('[data-hover="dropdown"]', element).not('.hover-initialized').each(function() {
            $(this).dropdownHover();
            $(this).addClass('hover-initialized');
        });
    };

    // Handles Bootstrap Popovers

    // last popep popover
    var lastPopedPopover;

    var handlePopovers = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        $('.popovers', element).popover();

        // close last displayed popover

        $(document).on('click.bs.popover.data-api', function(e) {
            if (lastPopedPopover) {
                lastPopedPopover.popover('hide');
            }
        });
    };

    // Handles scrollable contents using jQuery SlimScroll plugin.
    var handleScrollers = function() {
        Core.initSlimScroll('.scroller');
    };

    var handleInputType = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        Core.initInputType(element);
    };

    var handleBPInputType = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        Core.initBPInputType(element);
    };

    var handleFieldSetCollapse = function(element) {
        element = (typeof element === 'undefined') ? $('body') : element;
        Core.initFieldSetCollapse(element);
    };

    // Handles Image Preview using jQuery Fancybox plugin
    var handleFancybox = function(element) {
        if (!jQuery.fancybox) {
            return;
        }

        element = (typeof element === 'undefined') ? $('body') : element;
        var $fancyBoxButton = element.find('.fancybox-button');
        var $fancyBoxImg = element.find('.fancybox-img');

        if ($fancyBoxButton.length) {

            $("a[href^='data:image']", element).each(function() {
                $(this).fancybox({
                    content: $("<img/>").attr("src", this.href)
                });
            });
            
            $.fancybox.defaults.btnTpl.download2 = $.fancybox.defaults.btnTpl.download.replace('data-fancybox-download', '');
            
            $fancyBoxButton.fancybox({
                src: this.href,
                type: 'image',
                //loop: 'true',
                arrows: true,
                infobar : true,
                opts: {
                    prevEffect: 'none',
                    nextEffect: 'none',
                    titlePosition: 'over',
                    closeBtn: true,
                    helpers: {
                        overlay: {
                            locked: false,
                            showEarly: false
                        }
                    }
                },
                buttons: ['download2', 'zoom', 'close'], 
                beforeShow: function (instance, slide) {
                    var $toolbar = instance.$refs.container.find('.fancybox-toolbar');
                    $toolbar.find('.fancybox-button--right').remove();
                    $toolbar.prepend('<button class="fancybox-button fancybox-button--right" title="Баруун эргүүлэх"><i class="far fa-redo"></i></button>');
                    instance.$refs.container.find('.fancybox-button--download').attr('href', 'mdobject/downloadFile?file=' + (this.src).replace(URL_APP, '') + '&fDownload=1&contentId='+$(instance.current.opts.$orig).attr('data-contentid'));
                }
            });
        }
        
        if ($fancyBoxImg.length) {
            $fancyBoxImg.fancybox({
                src: this.href,
                type: 'image',
                arrows: true,
                infobar : true,
                opts: {
                    prevEffect: 'none',
                    nextEffect: 'none',
                    titlePosition: 'over',
                    closeBtn: true,
                    helpers: {
                        overlay: {
                            locked: false,
                            showEarly: false
                        }
                    }
                },
                buttons: ['zoom', 'close']
            });
        }
    };

    // Fix input placeholder issue for IE8 and IE9
    var handleFixInputPlaceholderForIE = function() {
        //fix html5 placeholder attribute for ie7 & ie8
        if (isIE8 || isIE9) { // ie8 & ie9
            // this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
            $('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function() {
                var input = $(this);

                if (input.val() === '' && input.attr("placeholder") !== '') {
                    input.addClass("placeholder").val(input.attr('placeholder'));
                }

                input.focus(function() {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                input.blur(function() {
                    if (input.val() === '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    };

    // Handle Select2 Dropdowns
    var handleSelect2 = function(el, options) {
        if ($().select2) {
            if (typeof options === "undefined" || options === null) {
                options = {};
            }
            $.extend(options, {
                placeholder: '-Сонгох-',
                allowClear: true,
                formatNoMatches: function() {
                    return 'Хайлт илэрцгүй';
                },
                width: 'resolve'
            });
            if (typeof el !== "undefined" && el !== null) {
                el.find('select.select2me').select2(options);
            } else {
                $('select.select2me').select2(options);
            }
        }
    };

    var handleTextAreaAutogrow = function(element) {
        if ($().autogrow) {
            element.find('textarea').autogrow();
        }
    };

    var initDialog = function(dialogName, html, config, callback) {
        if ($().dialog) {
            if (!$("#" + dialogName).length) {
                $('<div id="' + dialogName + '"></div>').appendTo('body');
            }

            var $dialog = $("#" + dialogName);

            $dialog.empty().append(html);

            var configDefault = {
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: '',
                width: 500,
                height: "auto",
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                }
            };

            $.extend(configDefault, config);

            $dialog.dialog(configDefault).dialogExtend({
                "closable": true,
                "maximizable": true,
                "minimizable": true,
                "collapsable": true,
                "dblclick": "maximize",
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });

            if (typeof callback === "function") {
                callback($dialog);
            }

            $dialog.dialog('open');
        }
    };

    //* END:CORE HANDLERS *//

    return {
        //main function to initiate the theme
        init: function() {
            //IMPORTANT!!!: Do not modify the core handlers call order.

            //Core handlers
            handleInit(); // initialize core variables
            handleOnResize(); // set and handle responsive    

            //UI Component handlers            
            handleUniform(); // hanfle custom radio & checkboxes
            handleBootstrapSwitch(); // handle bootstrap switch plugin
            componentSwitchery(); // Toggle Switchery
            handleScrollers(); // handles slim scrolling contents 
            handleFancybox(); // handle fancy box
            handleSelect2(); // handle custom Select2 dropdowns
            handlePortletTools(); // handles portlet action bar functionality
            handleAlerts(); //handle closabled alerts
            handleDropdowns(); // handle dropdowns
            handleTabs(); // handle tabs
            handlePopovers(); // handles bootstrap popovers
            handleAccordions(); //handles accordions 
            handleModals(); // handle modals

            // Hacks
            handleFixInputPlaceholderForIE(); //IE8 & IE9 input placeholder issue fix
            handleInputType(); // handle date inputs
            handleFieldSetCollapse();
            this.initAccountCodeMask();
            this.initStoreKeeperKeyCodeMask();
            this.initJUIDialogPopover();

            handleTopMenuResponsive(); // handle top menu responsive   
        },
        initAjax: function(element) {
            handleUniform(element); // handles custom radio & checkboxes     
            handleBootstrapSwitch(element); // handle bootstrap switch plugin
            handleDropdownHover(element); // handles dropdown hover       
            handleScrollers(); // handles slim scrolling contents 
            handleSelect2(element); // handle custom Select2 dropdowns
            handleFancybox(element); // handle fancy box
            handleDropdowns(element); // handle dropdowns
            handlePopovers(element); // handles bootstrap popovers
            handleAccordions(element); //handles accordions 
            handleInputType(element); // handle inputs
            handleFieldSetCollapse(element);
            handleTabs(element);
            componentSwitchery(element);
            this.initMaxLength(element); 
            this.initAccountCodeMask(element);
            this.initStoreKeeperKeyCodeMask(element);
        },
        initBPAjax: function(element) {   
            handleDropdownHover(element); // handles dropdown hover       
            handleFancybox(element); // handle fancy box
            handleDropdowns(element); // handle dropdowns 
            handleFieldSetCollapse(element);
            handleTabs(element);
        },
        initDVAjax: function(element) {
            handleUniform(element); // handles custom radio & checkboxes     
            handleDropdownHover(element); // handles dropdown hover       
            handleSelect2(element); // handle custom Select2 dropdowns
            handleFancybox(element); // handle fancy box
            handleDropdowns(element); // handle dropdowns 
            handleFieldSetCollapse(element);
            handleInputType(element); // handle inputs
            handleTabs(element);
            this.initMaxLength(element); 
        },
        initEntry: function(element) {
            handleUniform(element);
            handleDropdownHover(element);
            handleSelect2(element);
            handleFancybox(element);
            handleDropdowns(element);
            handleFieldSetCollapse(element);
            handleTabs(element);
            this.initMaxLength(element); 
            this.initDateInput(element);
            this.initDateTimeInput(element);
            this.initDateMaskInput(element);
            this.initDateTimeMaskInput(element);
            this.initDateMinuteInput(element);
            this.initDateMinuteMaskInput(element);
            this.initTimeInput(element);
            this.initTimerInput(element);
        },
        initNumber: function(element) {
            this.initNumberInput(element);
            this.initNotZeroIntInput(element);
            this.initLongInput(element);
        },
        initClean: function(element) {
            this.initDateInput(element);
            this.initDateTimeInput(element);
            this.initDateMaskInput(element);
            this.initDateTimeMaskInput(element);
            this.initDateMinuteInput(element);
            this.initDateMinuteMaskInput(element);
            this.initTimeInput(element);
            this.initTimerInput(element);
            this.initRegexMaskInput(element);
            this.initDecimalPlacesInput(element);
            this.initLongInput(element);
            this.initSelect2(element);
            this.initUniform(element);
        },
        initLogin: function() {
            handleInit(); // initialize core variables
            handleOnResize(); // set and handle responsive    
            handleUniform(); // hanfle custom radio & checkboxes
            handleScrollers(); // handles slim scrolling contents 
            handleFixInputPlaceholderForIE(); //IE8 & IE9 input placeholder issue fix
        },
        //init main components 
        initComponents: function() {
            this.initAjax();
        },
        initTabs: function(element) {
            handleTabs(element);
        },
        initBootstrapSwitch: function(element) {
            handleBootstrapSwitch(element);
        },
        initDialog: function(dialogName, html, config, callback) {
            initDialog(dialogName, html, config, callback);
        },
        setLastPopedPopover: function(el) {
            lastPopedPopover = el;
        },
        addResizeHandler: function(func) {
            resizeHandlers.push(func);
        },
        runResizeHandlers: function() {
            _runResizeHandlers();
        },
        scrollTo: function(el, offeset) {
            var pos = (el && el.length > 0) ? el.offset().top : 0;

            if (el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.page-header').height();
                }
                pos = pos + (offeset ? offeset : -1 * el.height());
            }

            $('html,body').animate({
                scrollTop: pos
            }, 'slow');
        },
        initSlimScroll: function(el) {
            $(el).each(function() {
                var $this = $(this);
                if ($this.attr("data-initialized")) {
                    return; // exit
                }

                var height;

                if ($this.attr("data-height")) {
                    height = $this.attr("data-height");
                } else {
                    height = $this.css('height');
                }

                $this.slimScroll({
                    allowPageScroll: true, // allow page scroll when the element scroll is ended
                    size: ($this.attr("data-handle-size") ? $this.attr("data-handle-size") : '7px'),
                    color: ($this.attr("data-handle-color") ? $this.attr("data-handle-color") : '#bbb'),
                    wrapperClass: ($this.attr("data-wrapper-class") ? $this.attr("data-wrapper-class") : 'slimScrollDiv'),
                    railColor: ($this.attr("data-rail-color") ? $this.attr("data-rail-color") : '#eaeaea'),
                    position: isRTL ? 'left' : 'right',
                    height: height,
                    alwaysVisible: ($this.attr("data-always-visible") == "1" ? true : false),
                    railVisible: ($this.attr("data-rail-visible") == "1" ? true : false),
                    disableFadeOut: true
                });

                $this.attr("data-initialized", "1");
            });
        },
        destroySlimScroll: function(el) {
            $(el).each(function() {
                if ($(this).attr("data-initialized") === "1") { // destroy existing instance before updating the height
                    $(this).removeAttr("data-initialized");
                    $(this).removeAttr("style");

                    var attrList = {};

                    // store the custom attribures so later we will reassign.
                    if ($(this).attr("data-handle-color")) {
                        attrList["data-handle-color"] = $(this).attr("data-handle-color");
                    }
                    if ($(this).attr("data-wrapper-class")) {
                        attrList["data-wrapper-class"] = $(this).attr("data-wrapper-class");
                    }
                    if ($(this).attr("data-rail-color")) {
                        attrList["data-rail-color"] = $(this).attr("data-rail-color");
                    }
                    if ($(this).attr("data-always-visible")) {
                        attrList["data-always-visible"] = $(this).attr("data-always-visible");
                    }
                    if ($(this).attr("data-rail-visible")) {
                        attrList["data-rail-visible"] = $(this).attr("data-rail-visible");
                    }

                    $(this).slimScroll({
                        wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                        destroy: true
                    });

                    var the = $(this);

                    // reassign custom attributes
                    $.each(attrList, function(key, value) {
                        the.attr(key, value);
                    });

                }
            });
        },
        scrollTop: function() {
            Core.scrollTo();
        },
        blockUI: function(options) {
            options = $.extend(true, {}, options);

            var html = '';
            if (options.animate) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
            } else if (options.iconOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif?v=1"></div>';
            } else if (options.icon2Only) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading.gif" align="left"></div>';
            } else if (options.textOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            } else {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif?v=1"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            }

            if (options.target) { // element blocking
                var el = $(options.target);
                if (el.height() <= ($(window).height())) {
                    options.cenrerY = true;
                }
                el.block({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                    fadeIn: 0, 
                    fadeOut: 0, 
                    css: {
                        top: '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                $.blockUI({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    fadeIn: 0, 
                    fadeOut: 0, 
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            }
        },
        existsBlockUI: function(options) {
            if ($('.blockOverlay').length == 0) {
                Core.blockUI(options);
            }
        },
        unblockUI: function(target) {
            if (target) {
                $(target).unblock({
                    onUnblock: function() {
                        $(target).css('position', '');
                        $(target).css('zoom', '');
                    }
                });
            } else {
                $.unblockUI();
            }
        },
        startPageLoading: function(options) {
            if (options && options.animate) {
                $('.page-spinner-bar').remove();
                $('body').append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
            } else {
                $('.page-loading').remove();
                $('body').append('<div class="page-loading"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>' + (options && options.message ? options.message : 'Loading...') + '</span></div>');
            }
        },
        stopPageLoading: function() {
            $('.page-loading, .page-spinner-bar').remove();
        },
        alert: function(options) {

            options = $.extend(true, {
                container: "", // alerts parent container(by default placed after the page breadcrumbs)
                place: "append", // "append" or "prepend" in container 
                type: 'success', // alert's type
                message: "", // alert's message
                close: true, // make alert closable
                reset: true, // close all previouse alerts first
                focus: true, // auto scroll to the alert after shown
                closeInSeconds: 0, // auto close after defined seconds
                icon: "" // put icon before the message
            }, options);

            var id = Core.getUniqueID("Core_alert");

            var html = '<div id="' + id + '" class="Core-alerts alert alert-' + options.type + ' fade in">' + (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : '') + (options.icon !== "" ? '<i class="fa-lg fa fa-' + options.icon + '"></i>  ' : '') + options.message + '</div>';

            if (options.reset) {
                $('.Core-alerts').remove();
            }

            if (!options.container) {
                if ($('body').hasClass("page-container-bg-solid")) {
                    $('.page-title').after(html);
                } else {
                    if ($('.page-bar').length > 0) {
                        $('.page-bar').after(html);
                    } else {
                        $('.page-breadcrumb').after(html);
                    }
                }
            } else {
                if (options.place == "append") {
                    $(options.container).append(html);
                } else {
                    $(options.container).prepend(html);
                }
            }

            if (options.focus) {
                Core.scrollTo($('#' + id));
            }

            if (options.closeInSeconds > 0) {
                setTimeout(function() {
                    $('#' + id).remove();
                }, options.closeInSeconds * 1000);
            }

            return id;
        },
        initUniform: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            handleUniform(element);
        },
        initFocusDateInput: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;

            element.on('focus', '.dateInit:not([readonly],[disabled])', function(e) {
                var $el = $(this);
                $el.inputmask("y-m-d");
                $el.datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    showOnFocus: false,
                    todayBtn: 'linked',
                    todayHighlight: true, 
                    language: sysLangCode
                }).off('keyup focus');
            });
        },
        initDateInput: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            var $el = element.find('input.dateInit');
            var $elInline = element.find('div.pf-inline-datepicker');
            
            if ($el.length) {
                $el.inputmask('y-m-d');
                $el.datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    showOnFocus: false,
                    todayBtn: 'linked',
                    todayHighlight: true, 
                    language: sysLangCode, 
                    daysOfWeekHighlighted: [0,6], 
                    templates:{leftArrow:'<i class="far fa-angle-left"></i>',rightArrow:'<i class="far fa-angle-right"></i>'}
                }).off('keyup focus');
            }
            
            if ($elInline.length) {
                $elInline.datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true, 
                    language: sysLangCode, 
                    daysOfWeekHighlighted: [0,6], 
                    templates:{leftArrow:'<i class="far fa-angle-left"></i>',rightArrow:'<i class="far fa-angle-right"></i>'}
                });
            }
        },
        initDateMonth: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            var $thisDate = element.find('input.monthInit');
            
            if ($thisDate.length) {
                $thisDate.inputmask('y-m');
                $thisDate.datepicker({
                    format: 'yyyy-mm',
                    autoclose: true,
                    showOnFocus: false,
                    todayBtn: 'linked',
                    startView: 'months', 
                    minViewMode: 'months', 
                    todayHighlight: true, 
                    language: sysLangCode, 
                    templates: {leftArrow:'<i class="far fa-angle-left"></i>',rightArrow:'<i class="far fa-angle-right"></i>'}
                }).off('keyup focus');
            }
        },
        initDateInputByElement: function(element) {
            element.inputmask("y-m-d");
            element.datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayBtn: 'linked',
                todayHighlight: true, 
                language: sysLangCode, 
                templates:{leftArrow:'<i class="far fa-angle-left"></i>',rightArrow:'<i class="far fa-angle-right"></i>'}
            });
        },
        initDateTimeInput: function(element) {
            /*var $element = (typeof element === 'undefined') ? $(document.body) : element;
            var $el = $element.find('input.datetimeInit');
            
            if ($el.length) {
                $el.inputmask('y-m-d h:s:s');
                $el.datetimepicker({
                    autoclose: true,
                    todayBtn: true,
                    ignoreReadonly: true,
                    format: "yyyy-mm-dd hh:ii:ss", 
                    language: sysLangCode
                });
            }*/
        },
        initTimeInput: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            /*element.find('input.timeInit').inputmask({ mask: '99:99', placeholder: '__:__' });*/
            element.find('input.timeInit').inputmask({
                mask: "h:s",
                placeholder: "__:__",
                alias: "datetime",
                hourFormat: "24"
            });
        },
        initTimesInput: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            element.find('input.timeInits').inputmask({ mask: '99:99:99', placeholder: '__:__:__' });
            /*$("input.timeInit", element).inputmask({
                mask: "h:s",
                placeholder: "__:__",
                alias: "datetime",
                hourFormat: "24"
            });*/
        },
        initTimerInput: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            var $timerInit = element.find('div.timerInit');
            
            if ($timerInit.length) {
                $timerInit.each(function() {
                    var $this = $(this), $thisHidden = $this.parent().find('input[type="hidden"]');

                    if ($thisHidden.val() != '' && $thisHidden.val() > 0) {
                        $this.countdown({
                            until: $thisHidden.val(),
                            compact: true,
                            description: '',
                            format: 'HMS',
                            onExpiry: function() {
                                var bpUniq = $this.closest('div.main-action-meta').attr('data-bp-uniq-id');
                                if (typeof window['timerComplete_' + bpUniq] === 'function') {
                                    window['timerComplete_' + bpUniq]();
                                }
                            },
                            onTick: function(periods) {
                                $thisHidden.val(periods[6] + (periods[5] * 60) + (periods[4] * 3600));

                                if ($this.closest('div.main-action-meta').attr('data-process-id') == '1560237446908' || $this.closest('div.main-action-meta').attr('data-process-id') == '1562316346363') {
                                    var time = (periods[4] < 10 ? '0' + periods[4] + ' цаг ' : periods[4] + ' цаг ') + (periods[5] < 10 ? '0' + periods[5] + ' минут ' : periods[5] + ' минут ') + (periods[6] < 10 ? '0' + periods[6] + ' секунт' : periods[6] + ' секунт');
                                    $.ajax({
                                        type: 'post',
                                        url: 'api/syncProcessTimer',
                                        data: { timeStr: time, type: $thisHidden.attr('data-path') },
                                        success: function() {}
                                    });
                                }
                            }
                        });
                        $this.countdown('pause');
                    } else {
                        $this.countdown({
                            until: 0,
                            compact: true,
                            description: '',
                            format: 'HMS',
                            onExpiry: function() {
                                var bpUniq = $this.closest('div.main-action-meta').attr('data-bp-uniq-id');
                                if (typeof window['timerComplete_' + bpUniq] === 'function') {
                                    window['timerComplete_' + bpUniq]();
                                }
                            },
                            onTick: function(periods) {
                                $thisHidden.val(periods[6] + (periods[5] * 60) + (periods[4] * 3600));

                                if ($this.closest('div.main-action-meta').attr('data-process-id') == '1560237446908' || $this.closest('div.main-action-meta').attr('data-process-id') == '1562316346363') {
                                    var time = (periods[4] < 10 ? '0' + periods[4] + ' цаг ' : periods[4] + ' цаг ') + (periods[5] < 10 ? '0' + periods[5] + ' минут ' : periods[5] + ' минут ') + (periods[6] < 10 ? '0' + periods[6] + ' секунт' : periods[6] + ' секунт');
                                    $.ajax({
                                        type: 'post',
                                        url: 'api/syncProcessTimer',
                                        data: { timeStr: time, type: $thisHidden.attr('data-path') },
                                        success: function() {}
                                    });
                                }
                            }
                        });
                    }
                });
            }
        },
        initTimeMaskInput: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            element.find('.timeMaskInit').inputmask({
                mask: "h:s",
                placeholder: "__:__",
                alias: "datetime",
                hourFormat: "24"
            });
        },
        initDateMinuteInput: function(element) {
            var $element = (typeof element === 'undefined') ? $(document.body) : element;
            var $el = $element.find('input.dateminuteInit');

            $el.datetimepicker({
                autoclose: true,
                todayBtn: true,
                isRTL: Core.isRTL(),
                format: "yyyy-mm-dd hh:ii",
                pickerPosition: (Core.isRTL() ? "bottom-right" : "bottom-left")
            });
        },
        initNumberInput: function(element) {
            var $element = (typeof element === 'undefined') ? $(document.body) : element;
            var $el = $element.find('.bigdecimalInit, .numberInit, .decimalInit');
            var len = $el.length, i = 0;
            
            for (i; i < len; i++) {
                var $this = $($el[i]);

                var minValue = '-999999999999999999999999999999.999999999999999999999999999999';
                var maxValue = '999999999999999999999999999999.999999999999999999999999999999';

                if ($this.hasAttr('data-v-min') && $this.attr('data-v-min') != '') {
                    minValue = $this.attr('data-v-min');
                }
                if ($this.hasAttr('data-v-max') && $this.attr('data-v-max') != '') {
                    maxValue = $this.attr('data-v-max');
                }

                $this.autoNumeric('init', { aPad: true, vMin: minValue, vMax: maxValue, mDec: 2 });

                if (typeof $this.attr('data-mdec') !== 'undefined' && $this.attr('data-mdec') != '') {
                    $this.autoNumeric('update', { mDec: $this.attr('data-mdec').toString().split('.')[0] });
                } 
            }
            /*$(".roundTowardZeroInit", element).autoNumeric('init', {aPad: true, mDec: 2, mRound: 'D', vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});*/
        },
        initDecimalPlacesInput: function(element, fraction) {
            var $element = (typeof element === 'undefined') ? $(document.body) : element,
                numberAPad = true,
                fractionNum = 2;
            if (typeof amountAPad !== 'undefined') {
                numberAPad = amountAPad;
            }
            if (typeof fraction !== 'undefined') {
                fractionNum = fraction;
            }
            $element.find('.bigdecimalInit, .numberInit, .decimalInit').autoNumeric('init', { aPad: numberAPad, mDec: fractionNum, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999' });
        },
        initLongInput: function(element) {
            var $element = (typeof element === 'undefined') ? $(document.body) : element;
            $element.find('.longInit, .integerInit').autoNumeric('init', { aSep: '', vMin: 0, vMax: 999999999999999999999999999999 });
        },
        initSetFractionRangeInput: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            $(".setFractionRangeInit", element).autoNumeric('init', { aPad: false, aSep: '', vMin: '0.0', vMax: '10.10' });
        },
        initNotZeroIntInput: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            $(".notZeroInt", element).each(function() {
                var $this = $(this);

                $this.autoNumeric('init', { aSep: '', vMin: 0, vMax: '999999999999999999999999999999.999999999999999999999999999999', mDec: 2 });
                if ($this.attr('data-m-dec') !== undefined) {
                    $this.autoNumeric('update', {"vMin": "-999999999999999999999999999999.999999999999999999999999999999"});
                } 
            });
        },
        initAmountInput: function(element) {
            
            var $element = (typeof element === 'undefined') ? $(document.body) : element;
            var $el = $element.find('.amountInit');
            var len = $el.length, i = 0;
            
            for (i; i < len; i++) {
                var $this = $($el[i]);

                var minValue = '-999999999999999999999999999999.999999999999999999999999999999';
                var maxValue = '999999999999999999999999999999.999999999999999999999999999999';

                if ($this.hasAttr('data-v-min') && $this.attr('data-v-min') != '') {
                    minValue = $this.attr('data-v-min');
                }
                if ($this.hasAttr('data-v-max') && $this.attr('data-v-max') != '') {
                    maxValue = $this.attr('data-v-max');
                }

                $this.autoNumeric('init', { aPad: '', vMin: minValue, vMax: maxValue, mDec: 2 });

                if (typeof $this.attr('data-mdec') !== 'undefined' && $this.attr('data-mdec') != '') {
                    $this.autoNumeric('update', { mDec: $this.attr('data-mdec').toString().split('.')[0] });
                } 
            }
        },
        initSelect2: function(element) {
            if ($().select2) {
                var $element = (typeof element === 'undefined') ? $(document.body) : element;
                var $el = $element.find('select.select2');

                $el.select2({
                    allowClear: true,
                    dropdownAutoWidth: true,
                    closeOnSelect: false,
                    escapeMarkup: function(markup) {
                        return markup;
                    }, 
                    formatNoMatches: function(term) {
                        return plang.get('msg_no_record_found');
                    }
                });
                
                function hideSelect2Keyboard(e){
                    var isTouch = (typeof isTouchEnabled === 'undefined') ? false : isTouchEnabled;
                    if (isTouch) {
                        $('.select2-search input, :focus').prop('focus', false).blur();
                    }
                }

                $el.on('select2-open', hideSelect2Keyboard);
                $el.on('select2-close', function() {
                    setTimeout(hideSelect2Keyboard, 50);
                });
            }
        },
        initSelect2WidthAutoFalse: function(element) {
            if ($().select2) {
                var $element = (typeof element === 'undefined') ? $(document.body) : element;
                var $el = $element.find('select.select2');

                $el.select2({
                    allowClear: true,
                    dropdownAutoWidth: false,
                    closeOnSelect: false
                });
            }
        },
        initDateMaskInput: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            $('.dateMaskInit', element).inputmask("y-m-d");
        },
        initDateTimeMaskInput: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            $('.dateTimeMaskInit', element).inputmask({
                mask: "y-m-d h:s:s",
                placeholder: "____-__-__ __:__:__",
                alias: "datetime",
                hourFormat: "24"
            });
        },
        initDateMinuteMaskInput: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            $('.dateMinuteMaskInit', element).inputmask({
                mask: "y-m-d h:s",
                placeholder: "____-__-__ __:__",
                alias: "datetime",
                hourFormat: "24"
            });
        },
        initRegexMaskInput: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            var $el = $('input[data-inputmask-regex], textarea[data-inputmask-regex]', element);
            var len = $el.length, i = 0;
            for (i; i < len; i++) {
                var $this = $($el[i]);
                $this.autoNumeric('destroy');
                $this.inputmask('Regex');
            }
        },
        initAccountCodeMask: function(element) {
            if (typeof accountCodeMask !== 'undefined') {
                
                var $element = (typeof element === 'undefined') ? $(document.body) : element;
                var opts = { mask: accountCodeMask };
                
                if (accountCodeMask.indexOf('^') !== -1) {
                    opts = {
                        mask: accountCodeMask, 
                        definitions: {
                            '^': {
                                validator: "[0-9*]"
                            }
                        }
                    };
                }
                
                $element.find("input[name='accountId_displayField'], input[name='filterAccountId_displayField'], input[name='apAccountId_displayField'], input.accountCodeMask, input[name*='accountcode']:not([name='bankaccountcode'])").inputmask(opts);
            }
        },
        initStoreKeeperKeyCodeMask: function(element) {
            if (typeof storeKeeperKeyCodeMask !== 'undefined') {
                var $element = (typeof element === 'undefined') ? $(document.body) : element;
                $element.find("input[name='storekeeperkeycode'], input[name='storeKeeperKeyId_displayField']").inputmask({ mask: storeKeeperKeyCodeMask });
            }
        },
        initTinymceEditor: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            var textEditorDefaultStyleString = '';
            if (typeof textEditorDefaultStyle !== 'undefined' && textEditorDefaultStyle) {
                textEditorDefaultStyleString = textEditorDefaultStyle;
            }                        
            
            if (element.find('textarea.text_editorInit').length) {
                $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() { 
                    $('.mce-menu, .mce-widget, .mce-tinymce.mce-tinymce-inline.mce-arrow.mce-container.mce-panel.mce-floatpanel[hidefocus="1"]').remove();
                    tinymce.dom.Event.domLoaded = true;
                    tinymce.baseURL = URL_APP + 'assets/custom/addon/plugins/tinymce';
                    tinymce.suffix = '.min';
                    tinymce.init({
                        selector: 'textarea.text_editorInit',
                        plugins: [
                            'autoresize',
                            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                            'searchreplace visualchars code fullscreen codesample importcss',
                            'media nonbreaking save table contextmenu directionality link codemirror',
                            'template paste textcolor colorpicker textpattern imagetools moxiemanager lineheight'
                        ],
                        toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | hr removeformat',
                        toolbar2: 'print preview | forecolor backcolor | subscript superscript | charmap codesample | fontselect fontsizeselect | lineheightselect | table | link | fullscreen code | link image',
                        fontsize_formats: '8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px', 
                        lineheight_formats: "1.0 1.15 1.5 2.0 2.5 3.0 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px",
                        hidden_input: false,
                        statusbar: false,
                        menubar: false,
                        toolbar_items_size: 'small',
                        image_advtab: true,
                        force_br_newlines: true,
                        force_p_newlines: false,
                        forced_root_block: '',
                        paste_data_images: true,
                        paste_remove_styles_if_webkit: false,
                        importcss_append: true,
                        paste_word_valid_elements: 'b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font,page',
                        document_base_url: URL_APP,
                        autoresize_max_height: (($(window).height() > 700) ? $(window).height() - 480 : $(window).height()-120),
                        content_css: URL_APP + 'assets/custom/css/print/tinymce.css',
                        images_upload_handler: function(blobInfo, success, failure) {
                            success("data:" + blobInfo.blob().type + ";base64," + blobInfo.base64());
                        },
                        codemirror: {
                            indentOnInit: true,
                            fullscreen: false,
                            path: 'codemirror',
                            config: {
                                mode: 'text/html',
                                styleActiveLine: true,
                                lineNumbers: true,
                                lineWrapping: true,
                                matchBrackets: true,
                                autoCloseBrackets: true,
                                indentUnit: 2,
                                foldGutter: true,
                                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                                extraKeys: {
                                    "F11": function(cm) {
                                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                                    },
                                    "Esc": function(cm) {
                                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                                    },
                                    "Ctrl-Q": function(cm) {
                                        cm.foldCode(cm.getCursor());
                                    },
                                    "Ctrl-Space": "autocomplete"
                                }
                            },
                            width: ($(window).width() - 20),
                            height: ($(window).height() - 120),
                            saveCursorPosition: false,
                            jsFiles: [
                                'mode/clike/clike.js',
                                'mode/htmlmixed/htmlmixed.js',
                                'mode/css/css.js',
                                'mode/xml/xml.js',
                                'addon/fold/foldcode.js',
                                'addon/fold/foldgutter.js',
                                'addon/fold/brace-fold.js',
                                'addon/fold/xml-fold.js',
                                'addon/fold/indent-fold.js',
                                'addon/fold/comment-fold.js',
                                'addon/hint/show-hint.js',
                                'addon/hint/xml-hint.js',
                                'addon/hint/html-hint.js',
                                'addon/hint/css-hint.js'
                            ]
                        },
                        setup: function(editor) {
                            editor.on('init', function() {
                                
                                var textEdata = $(editor.getElement()).val();
                                if (!textEdata.startsWith('<div class="append-textstyle') && textEditorDefaultStyleString) {
                                    editor.setContent('<div class="append-textstyle" style="'+textEditorDefaultStyleString+'">'+textEdata+'</div>');
                                }                              
                                
                                $(document).on('focusin', function(e) {
                                    if ($(e.target).closest(".mce-window, .moxman-window").length) {
                                        e.stopImmediatePropagation();
                                    }
                                });
                            });
                            editor.on('change', function(e) {
                                $(editor.getElement()).trigger('change');
                                tinymce.triggerSave();
                            });
                            editor.on('blur', function() {
                                setTimeout(function() {
                                    var $body = $('body');
                                    $body.find('.mce-tinymce.mce-tinymce-inline.mce-arrow.mce-container.mce-panel.mce-floatpanel[hidefocus="1"]').hide();
                                }, 5);
                            });
                        }
                    });

                    $(document).on('focusin', function(e) {
                        if ($(e.target).closest(".mce-window, .moxman-window").length) {
                            e.stopImmediatePropagation();
                        }
                    });
                });
            }
            
            if (element.find('textarea.text_editor_ckedtorInit').length) {
                
                window.CKEDITOR_BASEPATH = URL_APP + 'assets/custom/addon/plugins/ckeditor/';
                
                if (typeof CKEDITOR == 'undefined') {
                    $.cachedScript('assets/custom/addon/plugins/ckeditor/ckeditor.js?v=9').done(function() { 
                        CKEDITOR.timestamp = 'ABCD17';
                        CKEDITOR.disableAutoInline = true;
                        
                        CKEDITOR.replaceAll(function(textarea, config) {
                            if(textarea.className == "text_editor_ckedtorInit") {
                                if (!textarea.value.startsWith('<div class="append-textstyle') && textEditorDefaultStyleString) {
                                    textarea.value = '<div class="append-textstyle" style="'+textEditorDefaultStyleString+'">'+textarea.value+'</div>';
                                }                                
                                return true;
                            } else {
                                return false;
                            }
                        });                        
                    });
                } else {
                    CKEDITOR.disableAutoInline = true;
                    CKEDITOR.replaceAll('text_editor_ckedtorInit');
                }
            }
        },
        initIconPicker: function(element) {
            var $element = (typeof element === 'undefined') ? $('body') : element;
            var $el = $element.find('button.icon_pickerInit');
            
            if ($el.length) {
                if ($("link[href='assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css']").length == 0) {
                    $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                    
                    $.cachedScript("assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1").done(function() {
                        $el.iconpicker({
                            arrowPrevIconClass: 'fa fa-arrow-left',
                            arrowNextIconClass: 'fa fa-arrow-right'
                        });
                    });
                } else {
                    $el.iconpicker({
                        arrowPrevIconClass: 'fa fa-arrow-left',
                        arrowNextIconClass: 'fa fa-arrow-right'
                    });
                }
            }
        }, 
        initFieldSetCollapse: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            $("fieldset.collapsible", element).each(function() {
                var $thisFieldSet = $(this);

                if ($thisFieldSet.attr("data-initialized")) {
                    //$thisFieldSet.find('legend').find('span').html('<i class="icon-plus3 font-size-12"></i>');
                    return;
                }

                $thisFieldSet.find('legend').append(' <span><i class="icon-minus3"></i></span>');

                $thisFieldSet.find('legend').on('click', function() {
                    var $thisLegend = $(this);
                    var $divs = $thisLegend.siblings().not('script, style');

                    $divs.toggle(200, function() {
                        $thisLegend.find('span').html(function() {
                            return ($divs.is(':visible')) ? '<i class="icon-minus3"></i>' : '<i class="icon-plus3 font-size-12"></i>';
                        });
                    });

                    $(window).trigger('scroll');
                });

                $thisFieldSet.attr('data-initialized', '1');
            });
        },
        initTextareaAutoHeight: function(element) {
            element = (typeof element === 'undefined') ? $(document) : element;
            var $textAreas = element.find('.description_autoInit,.clobInit');
            /*setTimeout(function() {
                $textAreas.textareaAutoSize().autogrow();
            }, 100);*/
            
            if ($textAreas.length) {
                setTimeout(function() {
                    $textAreas.autoHeight();
                }, 10);
            }
        },
        initCodeView: function(element) {
            element = (typeof element === 'undefined') ? $(document) : element;
            var $codeViews = element.find('.bp-codeview-path');
            var len = $codeViews.length;
            
            if (len) {
                $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js', {async: false}).done(function() { 
                    if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.css']").length == 0) {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');
                    }
                    
                    var prettifyXml = function(sourceXml) {
                        var xmlDoc = new DOMParser().parseFromString(sourceXml, 'application/xml');
                        var xsltDoc = new DOMParser().parseFromString([
                                '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform">',
                                '  <xsl:strip-space elements="*"/>',
                                '  <xsl:template match="para[content-style][not(text())]">', // change to just text() to strip space in text nodes
                                '    <xsl:value-of select="normalize-space(.)"/>',
                                '  </xsl:template>',
                                '  <xsl:template match="node()|@*">',
                                '    <xsl:copy><xsl:apply-templates select="node()|@*"/></xsl:copy>',
                                '  </xsl:template>',
                                '  <xsl:output indent="yes"/>',
                                '</xsl:stylesheet>',
                        ].join('\n'), 'application/xml');

                        var xsltProcessor = new XSLTProcessor();    
                        xsltProcessor.importStylesheet(xsltDoc);
                        var resultDoc = xsltProcessor.transformToDocument(xmlDoc);
                        var resultXml = new XMLSerializer().serializeToString(resultDoc);
                        return resultXml;
                    };

                    $codeViews.each(function() {
                        var $this = $(this), $code = $this.html().trim(), mode = 'javascript';
                        
                        if ($code.indexOf('<?xml version="1.0"') !== -1 || $code.indexOf('<Document>') !== -1 || $code.indexOf('<document>') !== -1) {
                            mode = 'text/html';
                            var $unescaped = prettifyXml($code);
                        } else {
                            var $unescaped = $('<div/>').html($code).text();
                        }

                        $this.empty();

                        var codeViewer = CodeMirror(this, {
                            value: $unescaped,
                            mode: mode,
                            lineNumbers: true,
                            readOnly: true, 
                            styleActiveLine: true,
                            lineWrapping: true,
                            matchBrackets: true,
                            autoCloseBrackets: true,
                            indentUnit: 4,
                            theme: 'material', 
                            viewportMargin: 50
                        });
						
                        $this.find(".CodeMirror").css({'min-height': '40px', 'max-height': '800px'});

                        setTimeout(function() {
                            codeViewer.refresh();
                        }, 50);
                    });
                });
            }
        },
        initMxGraph: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            
            var $graphInputs = element.find('.mxgraph-load').filter(function() { return this.value != ''; });
    
            if ($graphInputs.length) {

                if (typeof isBpmEditorUiInit === 'undefined') {
                    $.getScript('middleware/assets/js/bpm/addon.js').done(function() {
                        bpmDiagramViewByElement($graphInputs);
                    });
                } else {
                    bpmDiagramViewByElement($graphInputs);
                }
            }
        }, 
        initScrollspy: function(element) {/*
            element = (typeof element === 'undefined') ? $(document.body) : element;
            
            var $sidebars = element.find('.sidebar-sticky.sidebar');
            
            if ($sidebars.length && $sidebars.closest('.content').length) {
                $sidebars.stick_in_parent({
                    offset_top: 70,
                    parent: '.content'
                });
            }*/
        }, 
        initBpToolbarSticky: function(element) {
            element = (typeof element === 'undefined') ? $(document.body) : element;
            
            var $sidebars = element.find('> form > .meta-toolbar:not(.is-bp-open-1, .not-sticky)');
            
            if ($sidebars.length && $sidebars.closest('.mv-checklist2-render-parent').length == 0 && ($sidebars.closest('.workspace-part').length == 0 
                && $sidebars.closest('.package-meta').length == 0 
                && $sidebars.closest('.not-sticky').length == 0)
                ) {
                
                setTimeout(function() {
                    $sidebars.stick_in_parent({
                        offset_top: 55
                    });
                }, 1);
            }
        },
        initMaxLength: function(element) {
            if ($().maxlength) {
                element = (typeof element === 'undefined') ? $('body') : element;
                var $inputs = element.find('input[data-maxlength=true], textarea[data-maxlength=true]');
                if ($inputs.length) {
                    $inputs.maxlength({
                        placement: 'top-left', 
                        warningClass: 'badge badge-info font-size-11',
                        limitReachedClass: 'badge badge-warning font-size-11'
                    });
                }
            }
        }, 
        initVoiceRecord: function(element) {
            if (element.find('.pf-voice-record').length && typeof Recorder == 'undefined') {
                $.getScript('assets/custom/addon/plugins/audio/recorder/recorder.js');
            }
        },
        initRangeSlider: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;
            var $inputs = element.find('input.rangeSliderInit');
            if ($inputs.length) {
                if ($().ionRangeSlider) {
                    subLoadIonRangeSlider($inputs);
                } else {
                    $('head').append('<link rel="stylesheet" type="text/css" href="assets/core/js/plugins/sliders/ion-rangeslider/ion.rangeSlider.min.css"/>');
                    $.cachedScript('assets/core/js/plugins/sliders/ion-rangeslider/ion.rangeSlider.min.js').done(function() {
                        subLoadIonRangeSlider($inputs);
                    });
                }
                function subLoadIonRangeSlider($inputs) {
                    $inputs.each(function() {
                        
                        var $this = $(this);
                        var rsIds = $this.attr('data-rs-ids');
                        var rsNames = $this.attr('data-rs-names');
                        var values = rsIds.split('|$|');
                        var p_values = rsNames.split('|$|');
                        var default_from = values.indexOf($this.val());
                        
                        $this.ionRangeSlider({
                            skin: 'flat', 
                            type: 'single',
                            grid: true,
                            from: default_from,
                            values: values,
                            p_values: p_values, 
                            prettify: function(n) {
                                var ind = values.indexOf(n);
                                return p_values[ind];
                            }, 
                            /*onChange: function(data) {
                                console.log(data.from_value);
                            }*/
                        });
                    });
                }
            }
        }, 
        initInputType: function(element) {
            this.initDateInput(element);
            this.initDateTimeInput(element);
            this.initDateMaskInput(element);
            this.initDateTimeMaskInput(element);
            this.initDateMonth(element);
            this.initNumberInput(element);
            this.initNotZeroIntInput(element);
            this.initLongInput(element);
            this.initSelect2(element);
            this.initUniform(element);
            this.initDateMinuteInput(element);
            this.initDateMinuteMaskInput(element);
            this.initTimeInput(element);
            this.initTimesInput(element);
            this.initTimerInput(element);
            this.initTextareaAutoHeight(element);
            this.initRegexMaskInput(element);
            this.initAccountCodeMask(element);
            this.initStoreKeeperKeyCodeMask(element);
            this.initScrollspy(element);
        },
        initBPInputType: function(element) {
            this.initDateInput(element);
            this.initDateTimeInput(element);
            this.initNumberInput(element);
            this.initLongInput(element);
            this.initSelect2(element);
            this.initUniform(element);
            this.initDateMinuteInput(element);
            this.initTimeInput(element);
            this.initTimerInput(element);
            this.initRegexMaskInput(element);
            this.initAccountCodeMask(element);
            this.initStoreKeeperKeyCodeMask(element);
            this.initTinymceEditor(element);
            this.initCodeView(element);
            this.initIconPicker(element);
            this.initMxGraph(element);
            this.initBpToolbarSticky(element);
            this.initMaxLength(element);
            this.initVoiceRecord(element);
            this.initRangeSlider(element);
            this.initTextareaAutoHeight(element);
        },
        initBPDtlInputType: function(element) {
            this.initDateInput(element);
            this.initDateTimeInput(element);
            this.initNumberInput(element);
            this.initLongInput(element);
            this.initSelect2(element);
            this.initUniform(element);
            this.initDateMinuteInput(element);
            this.initTimeInput(element);
            this.initTimerInput(element);
            this.initTextareaAutoHeight(element);
            this.initRegexMaskInput(element);
            this.initAccountCodeMask(element);
            this.initStoreKeeperKeyCodeMask(element);
            this.initIconPicker(element);
            this.initMaxLength(element);
            this.initTinymceEditor(element);
        },
        initCodeHighlight: function(element) {
            element = (typeof element === 'undefined') ? $('body') : element;

            if ($('pre code', element).length > 0) {

                $("link[href='assets/custom/addon/plugins/code-highlight/highlight/styles/default.css']").remove();
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/code-highlight/highlight/styles/default.css"/>');

                $.getScript(URL_APP + 'assets/custom/addon/plugins/code-highlight/highlight/highlight.pack.js', function() {
                    $('pre code', element).each(function(i, block) {
                        hljs.highlightBlock(block);
                    });
                });
            }
        },
        initPulsate: function(element) {
            if ($().pulsate) {
                element = (typeof element === 'undefined') ? $('body') : element;

                var properties = {
                    backgroundColor: '#E26A6A'
                };

                $('.pulsate-bg-red', element).pulse(properties, {
                    duration: 800,
                    pulses: 200000,
                    interval: 1
                });
            }
        },
        initComponentSwitchery: function(element) {
            componentSwitchery(element);
        },
        initJUIDialogPopover: function() {
            $.widget("ui.dialog", $.ui.dialog, {
                _allowInteraction: function(event) {
                    if (this._super(event)) {
                        return true;
                    }
                    if (event.target.ownerDocument != this.document[0]) {
                        return true;
                    }
                    if ($(event.target).closest(".popover").length) {
                        return true;
                    }
                    if ($(event.target).closest(".cke_dialog").length) {
                        return true;
                    }
                    if ($(event.target).closest(".cke").length) {
                        return true;
                    }
                },
                _moveToTop: function(event, silent) {
                    if (!event || !this.options.modal) {
                        this._super(event, silent);
                    }
                }
            });
        },
        destroyIconPicker: function() {
            $('.iconpicker').each(function() {
                $(this).popover('dispose');
            });
        },
        //wrCoreer function to update/sync jquery uniform checkbox & radios
        updateUniform: function(els) {
            $.uniform.update(els); // update the uniform checkbox & radios UI after the actual input control state changed
        },
        //public function to initialize the fancybox plugin
        initFancybox: function(element) {
            handleFancybox(element);
        },
        //public helper function to get actual input value(used in IE9 and IE8 due to placeholder attribute not supported)
        getActualVal: function(el) {
            el = $(el);
            if (el.val() === el.attr("placeholder")) {
                return '';
            }
            return el.val();
        },
        //public function to get a paremeter by name from URL
        getURLParameter: function(paramName) {
            var searchString = window.location.search.substring(1),
                i, val, params = searchString.split("&");

            for (i = 0; i < params.length; i++) {
                val = params[i].split("=");
                if (val[0] == paramName) {
                    return unescape(val[1]);
                }
            }
            return null;
        },
        // check for device touch support
        isTouchDevice: function() {
            try {
                document.createEvent("TouchEvent");
                return true;
            } catch (e) {
                return false;
            }
        },
        // To get the correct viewport width based on  http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
        getViewPort: function() {
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }

            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        },
        getUniqueID: function(prefix) {
            return prefix + '_' + Math.floor(Math.random() * (new Date()).getTime());
        },
        // check IE8 mode
        isIE8: function() {
            return isIE8;
        },
        // check IE9 mode
        isIE9: function() {
            return isIE9;
        },
        //check RTL mode
        isRTL: function() {
            return isRTL;
        },
        // check IE8 mode
        isAngularJsApp: function() {
            return (typeof angular == 'undefined') ? false : true;
        },
        getAssetsPath: function() {
            return assetsPath;
        },
        setAssetsPath: function(path) {
            assetsPath = path;
        },
        setGlobalImgPath: function(path) {
            globalImgPath = path;
        },
        getGlobalImgPath: function() {
            return assetsPath + globalImgPath;
        },
        setGlobalPluginsPath: function(path) {
            globalPluginsPath = path;
        },
        getGlobalPluginsPath: function() {
            return assetsPath + globalPluginsPath;
        },
        getGlobalCssPath: function() {
            return assetsPath + globalCssPath;
        },
        // get layout color code by color name
        getBrandColor: function(name) {
            if (brandColors[name]) {
                return brandColors[name];
            } else {
                return '';
            }
        },
        getResponsiveBreakpoint: function(size) {
            // bootstrap responsive breakpoints
            var sizes = {
                'xs': 480, // extra small
                'sm': 768, // small
                'md': 900, // medium
                'lg': 1200 // large
            };

            return sizes[size] ? sizes[size] : 0;
        },
        getFullDateFromDate: function() {
            var date = new Date(),
                day = date.getDate(),
                month = date.getMonth() + 1,
                hour = date.getHours(),
                minute = date.getMinutes(),
                second = date.getSeconds();

            if (day < 10) {
                day = '0' + day;
            }
            if (month < 10) {
                month = '0' + month;
            }
            if (hour < 10) {
                hour = '0' + hour;
            }
            if (minute < 10) {
                minute = '0' + minute;
            }
            if (second < 10) {
                second = '0' + second;
            }

            var displayDate = date.getFullYear() + '-' + month + '-' + day + " " + hour + ":" + minute +
                ":" + second;
            return displayDate;
        }, 
        initHeaderTabFix: function() {
            var $multiTab = $('.card-multi-tab > .card-header');

            if ($multiTab.length) {
                
                var $multiTabClone = $multiTab.clone(), 
                    $activeTab = $multiTabClone.find('.card-multi-tab-navtabs > li > a.active'), 
                    prevTabText = $activeTab.text();
            
                if (prevTabText != '') {
                    var prevTabHtml = $activeTab.html();
                    $multiTabClone.find('.card-multi-tab-navtabs > li > a.active').html(prevTabHtml.replace('/'+prevTabText+'/g', ucfirst(prevTabText.toLowerCase())));
                }
                
                $('.m-tab').html($multiTabClone);
                $multiTab.remove();
            }
        },
        showErrorMessage: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: msg,
                type: 'error',
                sticker: false
            });
        }
    };

}();

$.fn.extend({
    autoHeight: function () {
        function autoHeight_(element) {
            return $(element).css({
                'height': 'auto',
                'overflow-y': 'hidden', 
                'min-height': '25px'
            }).height(element.scrollHeight);
        }
        return this.each(function () {
            autoHeight_(this).on('input', function () {
                autoHeight_(this);
            });
        });
    }
});

$.expr[':'].attrNoCase = function(node, stackIndex, properties) {
    var args = properties[3].split(',').map(function(arg) {
        return arg.replace(/^\s*["']|["']\s*$/g, '');
    });
    if ($(node).attr(args[0])) {
        return $(node).attr(args[0]).toLowerCase() == args[1].toLowerCase();
    }
};

$.cachedScript = function(url, options) {
 
    // Allow user to set any option except for dataType, cache, and url
    options = $.extend(options || {}, {
        dataType: 'script',
        cache: true,
        url: url
    });

    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return $.ajax(options);
};
window.loadMultiScripts = (scripts) => {
    return scripts.reduce((currentPromise, scriptUrl) => {
        return currentPromise.then(() => {
            return new Promise((resolve, reject) => {
                var script = document.createElement('script');
                script.async = true;
                script.src = scriptUrl;
                script.onload = () => resolve();
                document.getElementsByTagName('head')[0].appendChild(script);
            });
        });
    }, Promise.resolve());
};

$.fn.isHScrollable = function () {
    return this[0].scrollWidth > this[0].clientWidth;
};
$.fn.isVScrollable = function () {
    return this[0].scrollHeight > this[0].clientHeight;
};
$.fn.isScrollable = function () {
    return this[0].scrollWidth > this[0].clientWidth || this[0].scrollHeight > this[0].clientHeight;
};

/**
 * 
 * Init javascript class Globe Dictionary
 */
var plang = function() {
    return {
        get: function(globeCode) {
            try {
                var globeCodeLower = globeCode.toLowerCase();

                if (jsGlobeDictionary.hasOwnProperty(globeCodeLower)) {
                    return jsGlobeDictionary[globeCodeLower];
                } else {
                    return globeCode;
                }

            } catch (e) {
                return globeCode;
            }
        },
        getVar: function(globeCode, variables) {
            try {
                var globeCodeLower = globeCode.toLowerCase();

                if (jsGlobeDictionary.hasOwnProperty(globeCodeLower)) {

                    var globeText = jsGlobeDictionary[globeCodeLower];

                    for (var i in variables) {
                        globeText = globeText.replace('[' + i + ']', variables[i]);
                    }

                    return globeText;
                } else {
                    return globeCode;
                }
            } catch (e) {
                return globeCode;
            }
        },
        isExisting: function(globeCode) {
            if (!globeCode) {
                return false;
            }
            try {
                var globeCodeLower = globeCode.toLowerCase();

                if (jsGlobeDictionary.hasOwnProperty(globeCodeLower)) {
                    return true;
                } else {
                    return false;
                }

            } catch (e) {
                return false;
            }
        },
        getDefault: function(globeCode, defaultTxt) {
            if (plang.isExisting(globeCode)) {
                return plang.get(globeCode);
            }

            return defaultTxt;
        }
    }
}();

var getLeftMenuCount = function(isParentIgnore, element) {

    if (document.hidden) {
        return false;
    }

    var $element = (typeof element === 'undefined') ? $(document.body) : element;

    if (isParentIgnore) {
        var $countMetas = $element.find('.left-menu-count-meta:not([data-depth="0"])');
    } else {
        var $countMetas = $element.find('.left-menu-count-meta');
    }

    if ($countMetas.length > 0) {

        var countMetaIds = '-', listCriteria = '-', metaRecordIds = '-';

        $.each($countMetas, function(idx, lmcm) {
            var countMetaId = $(lmcm).attr('data-counmetadataid');
            var criteriaMetaId = $(lmcm).attr('data-listmetadatacriteria');
            var recordMetaId = $(lmcm).attr('data-id');
            if (countMetaId) {
                countMetaIds += countMetaId + '-';
                listCriteria += (typeof criteriaMetaId !== 'undefined' ? criteriaMetaId : '') + '-';
                metaRecordIds += (typeof recordMetaId !== 'undefined' ? recordMetaId : '') + '-';
            }
        });
        
        getLeftMenuCountAjax(countMetaIds, $element, listCriteria, metaRecordIds);
    }
};

var getLeftMenuCountAjax = function(ids, element, criteria, metaRecordIds) {

    var idsMatches = ids.match(/\-(.*?)\-/);
    var criteriaMatches = criteria.match(/\-(.*?)\-/);
    var recordMetaIdMatches = metaRecordIds.match(/\-(.*?)\-/);

    if (idsMatches) {
        var countMetaId = idsMatches[1],
            criMetaId = '',
            recordMetaId = '';

        if (countMetaId) {
            
            var postData = {countMetaDataIds: countMetaId, nult: 1};
            
            if (element.hasClass('ws-area')) {
                var workSpaceIdAttr = element.attr('id').split('-'), 
                    workSpaceId = workSpaceIdAttr[2], 
                    workSpaceParams = element.find('div.ws-hidden-params').find('input[type=hidden]').serialize();

                postData.workSpaceId = workSpaceId;
                postData.workSpaceParams = workSpaceParams;
            }
            
            if (criteriaMatches) {
                criMetaId = criteriaMatches[1];
                recordMetaId = recordMetaIdMatches[1];
                postData.listmetadatacriteria = criMetaId;
            }

            $.ajax({
                type: 'post',
                url: 'mdmenu/getLeftMenuCount',
                data: postData,
                dataType: 'json',
                success: function(data) {

                    $.each(data, function(countMetaDataId, count) {
                        if (count != '0') {
                            var beforeCount = element.find('span[data-counmetadataid=' + countMetaDataId + ']').html();
                            if (recordMetaId) {                                
                                element.find('span[data-id=' + recordMetaId + ']').html(count);
                            } else {
                                element.find('span[data-counmetadataid=' + countMetaDataId + ']').html(count);
                            }
                            
                            if (usePushNotification && parseInt(beforeCount) < parseInt(count)) {
                                
                                var originalTitle = element.find('span[data-counmetadataid=' + countMetaDataId + ']').closest('a').attr('data-original-title');
                                if (typeof Push == 'undefined') {
                                    console.warn('Warning - Push.js is not loaded.');
                                    return;
                                }

                                Push.config({
                                    serviceWorker: 'serviceWorker.min.js', // Sets a custom service worker script
                                    fallback: function (payload) {
                                        // Code that executes on browsers with no notification support
                                        // "payload" is an object containing the 
                                        // title, body, tag, and icon of the notification 
                                    }
                                });
                                var titleNoti = "Танд " + (typeof originalTitle !== 'undefined' ? originalTitle + ' дээр' : '') + " (" + count + ") мэдээлэл байна.";
                                Push.create("Сайн байна уу!", {
                                    body: titleNoti,
                                    //icon: 'pic.jpg',
                                    timeout: 5000,
                                    onClick: function () {
                                        window.focus();
                                        this.close();
                                    }
                                });
                                
                                if (typeof pageTitleNotification != 'undefined') {
                                    pageTitleNotificationTitleChanged = true;
                                    pageTitleNotification.on(titleNoti, 1000); bpSoundPlay('ring');
                                }
                                
                            }
                        } else {
                            if (recordMetaId) {                                
                                element.find('span[data-id=' + recordMetaId + ']').html('');
                            } else {
                                element.find('span[data-counmetadataid=' + countMetaDataId + ']').html('');
                            }                            
                        }
                    });

                    getLeftMenuCountAjax(ids.replace('-' + countMetaId, ''), element, criteria.replace('-' + criMetaId, ''), metaRecordIds.replace('-' + recordMetaId, ''))
                }
            });
        }
    }
};

$(function() {

    $('body').on('shown.bs.popover', function(e) {
        if (typeof $(e.target).attr('data-path') === 'undefined') {
            $('.popover').css('left', ($(e.target).width() - 20) + 'px');
        } else {
            $('.popover').css('left', ($(e.target).width() + 75) + 'px');
        }
    });

    var timerCellHover;
    
    $(document.body).on('mouseenter', '.card-multi-tab-navtabs .nav-link', function() {
        if ($('.body-left-menu-style').length) {
            return;
        }
        
        var self = this;
        
        timerCellHover = setTimeout(function() {            
            var $this = $(self);
            var cellText = $this.attr('data-title').trim();

            if (cellText != '') {
                $this.qtip({
                    content: {
                        text: '<div style="max-width:600px;max-height:500px;overflow-y:auto;overflow-x:hidden;">' + cellText + '</div>'
                    },
                    position: {
                        effect: false,
                        at: 'center left',
                        my: 'left center',
                        viewport: $(window) 
                    }, 
                    show: {
                        ready: true,
                        effect: false
                    },
                    hide: {
                        effect: false, 
                        fixed: true,
                        delay: 70
                    },
                    style: {
                        classes: 'qtip-bootstrap',
                        tip: {
                            width: 10,
                            height: 5
                        }
                    }, 
                    events: {
                        hidden: function(event, api) {
                            api.destroy(true);
                        }
                    }
                });
            }
        }, 100);
    });
    
    $(document.body).on('mouseleave', '.card-multi-tab-navtabs .nav-link', function() {
        if ($('.body-left-menu-style').length) {
            return;
        }        
        if (timerCellHover) {
            clearTimeout(timerCellHover);
        }
    });    

    var breaks = [];

    function updateNav() {
        
        var $nav = $('.pf-topnavbar-menu');
        var $btn = $('.pf-topnavbar-menu .pf-topnavbar-menu-morebtn');
        var $vlinks = $('.pf-topnavbar-menu > .page-topbar > ul');
        var $lastitems = $('.pf-topnavbar-menu > .page-topbar > ul li.dropdown:nth-last-child(-n+3)');
        var $hlinks = $('.pf-topnavbar-menu .hidden-links');
        var availableSpace = $btn.hasClass('d-none') ? $nav.width() : $nav.width() - $btn.width() - 30;
        var $dropdownitem =  $('.pf-topnavbar-menu > .page-topbar > ul li.dropdown');
        
        if ($dropdownitem.length > 7) {
            $lastitems.find('.dropdown-menu').addClass("dropdown-menu-right");
            $lastitems.find('.dropdown-submenu').addClass("dropdown-submenu-left");
        }
        if ($vlinks.width() == 0) {
            var _vlinks = $('.pf-topnavbar-menu > .page-topbar > ul:not(.hidden-links)').children();
            if (_vlinks.length > 0) {
                /* _vlinks.prependTo($hlinks); */
                $('.hidden-left-links > ul').append(_vlinks).promise().done(function () {
                    /* $btn.removeClass('d-none'); */
                });
            }
        } 
        else {
            if ($vlinks.width() > availableSpace) {

                breaks.push($vlinks.width());
                
                if ($vlinks.children().last().hasClass("dropdown")) {
                    $vlinks.children().last().addClass("dropdown-submenu").addClass("dropdown-submenu-left");
                    $vlinks.children().last().find("li").addClass("dropdown-submenu-left");
                }

                $vlinks.children().last().prependTo($hlinks);

                if ($btn.hasClass('d-none')) {
                    $btn.removeClass('d-none');
                }

            } else {

                if (availableSpace > breaks[breaks.length - 1]) {

                    if ($vlinks.children().first().hasClass("dropdown")) {
                        $vlinks.children().first().removeClass("dropdown-submenu").removeClass("dropdown-submenu-left");
                        $vlinks.children().first().find("li").removeClass("dropdown-submenu-left");
                    }
                    $vlinks.children().first().find(' > a').removeClass("dropdown-item");

                    $hlinks.children().first().appendTo($vlinks);
                    breaks.pop();
                }

                if (breaks.length < 1) {
                    $btn.addClass('d-none');
                } else {
                    $btn.removeClass('d-none');
                }
            }
        }

        if ($vlinks.width() > availableSpace) {
            updateNav();
        }
    }

    $(window).resize(function() {
        updateNav();
    });
   
    updateNav();
    
    var $pageSidebar = $('.page-sidebar');
    
    if ($pageSidebar.find("ul > li > ul > li.active").html() !== undefined) {
        
        $pageSidebar.find("ul ul li.active").parent().show();
        
        var $parent = $pageSidebar.find("ul ul li.active").parent().parent();
        
        $parent.parent().show();
        $parent.addClass("open active");
        $parent.find("span.arrow:eq(0)").addClass("open");
        
        var $parentLi = $parent.parents("li");
        
        $parentLi.addClass("active open");
        $parentLi.find("span.arrow:eq(0)").addClass("open");
        
        if ($parentLi.find("a:eq(0)").hasClass("vr-menu-new-area")) {
            $('.page-sidebar-menu > li').not($parentLi).hide();
            $parentLi.find("a > i").addClass("vr-main-menu-click").hide();
            $parentLi.find("a:eq(0)").prepend('<i class="fa fa-arrow-circle-left vr-main-menu-back" title="Back"></i>');
        }
    }

    $(document.body).on('click', '.page-sidebar-back-menu', function() {
        var $this = $(this);
        if ($this.hasAttr('data-top-menu')) {
            $.ajax({
                type: 'post',
                url: 'mdmenu/firstLevelMenu',
                beforeSend: function() {
                    Core.blockUI({target: $(".page-topbar"), animate: true});
                },
                success: function(data) {
                    $(".page-topbar").empty().append(data);
                },
                error: function() {
                    alert("Error");
                }
            }).done(function() {
                $(window).trigger('resize');
                Core.unblockUI();
            });
        } else {
            $.ajax({
                type: 'post',
                url: 'mdmenu/firstLevelMenu',
                beforeSend: function() {
                    Core.blockUI({target: $(".sidebar-left-menu"), animate: true});
                },
                success: function(data) {
                    $(".sidebar-left-menu").empty().append(data);
                },
                error: function() {
                    alert("Error");
                }
            }).done(function() {
                //Core.initSlimScroll($('.page-sidebar-menu:not([data-no-scroll])'));
                $(window).trigger('resize');
                Core.unblockUI();
            });
        }
    });

    var $navSidebar = $('.nav-sidebar');
    $navSidebar.on('click', 'li > a', function(e) {

        var $this = $(this);

        $(".sidebar-content").animate({
            scrollTop: ($this.position()).top
        }, 'slow');

        e.preventDefault();
    });

    // disable backspace
    $(document).keydown(function(e) {
        var preventKeyPress;
        if (e.keyCode == 8) {
            var d = e.srcElement || e.target;
            switch (d.tagName.toUpperCase()) {
                case 'TEXTAREA':
                    preventKeyPress = d.readOnly || d.disabled;
                    break;
                case 'INPUT':
                    preventKeyPress = d.readOnly || d.disabled ||
                        (d.attributes["type"] && $.inArray(d.attributes["type"].value.toLowerCase(), ["radio", "checkbox", "submit", "button"]) >= 0);
                    break;
                case 'DIV':
                case 'TD':
                case 'SPAN':
                    preventKeyPress = d.readOnly || d.disabled || !(d.attributes["contentEditable"] && d.attributes["contentEditable"].value == "true");
                    break;
                default:
                    preventKeyPress = true;
                    break;
            }
        } else
            preventKeyPress = false;

        if (preventKeyPress)
            e.preventDefault();
    });

    $(document.body).on('paste', 'input.bigdecimalInit:not(:has(table.bprocess-table-dtl), [readonly], [disabled])', function(e) {
        var source;
        if (window.clipboardData !== undefined) {
            source = window.clipboardData;
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData('Text'), split = data.split("\n");
        if (split.length > 1) {
            var $this = $(this);
            $this.autoNumeric('set', split[0].trim());
        }
    });
    $(document.body).on('focus', 'input.bigdecimalInit:not([data-mdec]), input.bigdecimalInit[data-mdec=""], input.numberInit, input.decimalInit, input.amountInit, input.integerInit', function(e) {
        var $this = $(this);
        var res = $this.val().split('.'), firstNum = res[0], scaleNum = res[1];

        if (parseInt(scaleNum) == 0) {
            $this.val(firstNum).attr('data-prevent-change', '');
        }
        if (typeof $this.attr('data-prevent-select-and-caret') === 'undefined') {
            $this.select();
        }
    });
    $(document.body).on('keydown', 'input.bigdecimalInit, input.numberInit, input.decimalInit, input.amountInit, input.integerInit', function(e) {
        var $this = $(this);
        if (typeof $this.attr('data-prevent-change') !== 'undefined') {
            $this.removeAttr('data-prevent-change');
        }
    });
    $(document.body).on('dblclick', 'input.bigdecimalInit, input.numberInit, input.decimalInit, input.amountInit, input.integerInit', function(e) {
        var $this = $(this);
        if (typeof $this.attr('data-prevent-change') !== 'undefined') {
            $this.removeAttr('data-prevent-change');
        }
    });
    $(document.body).on('focusin', 'input.bigdecimalInit[data-mdec][data-mdec!=""]', function(e) {
        var $this = $(this);
        var mdecStr = $this.attr('data-mdec').toString();
        
        if (mdecStr.length > 2) {
            var mdec = mdecStr.split('.')[1];
        } else {
            var mdec = mdecStr.split('.')[0];
        }

        var setOption = JSON.parse('{"mDec": ' + mdec + '}'),
            $nextInput = $this.next('input[type=hidden]');
        var nextInputVal = ($nextInput.length) ? $nextInput.val() : $this.val();

        $this.autoNumeric('update', setOption);
        $this.autoNumeric('set', nextInputVal);

        var res = $this.val().split('.'),
            firstNum = res[0],
            scaleNum = res[1];

        if (parseInt(scaleNum) == 0) {
            $this.val(firstNum);
        }

        $this.attr('data-prevent-change', '');
        
        if (typeof $this.attr('data-prevent-select-and-caret') === 'undefined') {
            $this.select();
            $this.caret({ start: 0, end: 50 });
        }

        return e.preventDefault();
    });
    $(document.body).on('focusout', 'input.bigdecimalInit[data-mdec][data-mdec!=""]', function(e) {
        var $this = $(this);
        var mdec = $this.attr('data-mdec').toString().split('.')[0];
        $this.autoNumeric('update', {"mDec": mdec});
        return e.preventDefault();
    });
    $(document.body).on('focus', 'input.datetimeInit:not([readonly],[disabled])', function(e) {
        var $this = $(this);
        $this.inputmask('y-m-d h:s:s');
        $this.datetimepicker({
            autoclose: true,
            todayBtn: true,
            ignoreReadonly: true,
            keyboardNavigation: false, 
            format: "yyyy-mm-dd hh:ii:ss", 
            language: sysLangCode
        });
        return e.preventDefault();
    });

    $(document.body).on('focus', 'input.dateminuteInit', function(e) {
        var $this = $(this);
        $this.inputmask('y-m-d h:s');
        $this.datetimepicker({
            autoclose: true,
            todayBtn: true,
            ignoreReadonly: true,
            keyboardNavigation: false, 
            format: "yyyy-mm-dd hh:ii", 
            language: sysLangCode
        });
        return e.preventDefault();
    });

    $(document.body).on('click', 'input.notZeroInt', function(e) {
        var $this = $(this);
        var res = $this.val().split('.');
        if (parseInt(res[1]) == 0) {
            $this.val(res[0]);
        }
        $this.select();
        return e.preventDefault();
    });
    $(document.body).on('change', '.fileInit, .base64Init, .multi_fileInit, .multi_file_styleInit', function(e) {
        if (e.isTrigger === undefined) {
            var $this = $(this);
            if ($this.attr('data-valid-extension') && ($this.val() != '' || $this.closest('.file-input').length)) {
                var getExtension = $this.attr('data-valid-extension');
                if ($.trim(getExtension) !== '') {
                    var removeWhiteSpace = getExtension.replace(/\s+/g, '');
                    if (!$this.hasExtension(removeWhiteSpace.split(','))) {
                        alert('Та (' + getExtension + ') эдгээр файлаас сонгоно уу!');
                        $this.val('');
                        
                        if ($this.closest('.file-input').length) {
                            $this.closest('.file-input').find('.fileinput-remove-button').trigger('click');
                        }
                        return false;
                    }
                }
            }
            if ($this.attr('data-max-file-size') && ($this.val() != '' || $this.closest('.file-input').length)) {
                var getFileSizeKB = (this.files[0].size / 1024).toFixed(3);
                var limitSize = parseInt($this.attr('data-max-file-size'), 10);
                if (getFileSizeKB > limitSize) {
                    alert('Та хамгийн ихдээ ' + limitSize + 'KB хэмжээтэй файл оруулна');
                    $this.val('');

                    if ($this.closest('.file-input').length) {
                        $this.closest('.file-input').find('.fileinput-remove-button').trigger('click');
                    }                    
                    return false;
                }
            }
        }
    });
    $(document.body).on('click', 'form#wsForm .fileinput-remove-button', function() {
        $(this).closest(".boot-file-input-wrap").find("input[type=hidden]").val('');
    });
    $(document.body).on('click', '.fancybox-button--right', function() {
        var $this = $(this);
        
        if ($this.hasAttr('data-rotate-deg')) {
            $this.attr('data-rotate-deg', Number($this.attr('data-rotate-deg')) + 1);
        } else {
            $this.attr('data-rotate-deg', 1);
        }
        
        var rotateImager = $this.attr('data-rotate-deg');
        var deg = 90 * rotateImager;
        
        $('.fancybox-container img').css({'mozTransform': 'rotate(+' + deg + 'deg)', 'webkitTransform': 'rotate(+' + deg + 'deg)'});
    });
    $(document.body).on('click', '.dateElement > .input-group-btn', function(e) {
        if (e.hasOwnProperty('originalEvent') && e.timeStamp !== 0 && (typeof e.clientX !== 'undefined' && e.clientX !== 0)) {
            $(this).closest(".dateElement").find(".dateInit:not([readonly],[disabled]), .monthInit:not([readonly],[disabled])").datepicker('show');
        }
    });
    $(document.body).on('keydown', '.dateInit:not([readonly],[disabled])', function(e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        var $val = $this.val();

        if ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 96 && keyCode <= 105)) {

            var cursorStart = $this.caret().start;

            if (cursorStart == '0' && $val.substring(0, 1) != '_') { /* Year-1 */
                $this.caret({ start: 0, end: 1 });
            } else if (cursorStart == '1' && $val.substring(1, 2) != '_') { /* Year-2 */
                $this.caret({ start: 1, end: 2 });
            } else if (cursorStart == '2' && $val.substring(2, 3) != '_') { /* Year-3 */
                $this.caret({ start: 2, end: 3 });
            } else if (cursorStart == '3' && $val.substring(3, 4) != '_') { /* Year-4 */
                $this.caret({ start: 3, end: 4 });
            } else if (cursorStart == '4') { /* Month-1 */

                if (keyCode == 50 || keyCode == 98) {
                    $this.val(substr_replace($val, '02', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 51 || keyCode == 99) {
                    $this.val(substr_replace($val, '03', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 5, 2));
                    $this.setCursorPosition(8);
                } else {
                    $this.caret({ start: 5, end: 6 });
                }

            } else if (cursorStart == '5' && $val.substring(5, 6) == '_') { /* Month-1 */

                if (keyCode == 50 || keyCode == 98) {
                    $this.val(substr_replace($val, '02', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 51 || keyCode == 99) {
                    $this.val(substr_replace($val, '03', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                }

            } else if (cursorStart == '5' && $val.substring(5, 6) != '_') { /* Month-1 */

                if (keyCode == 48 || keyCode == 96) {

                    var $monthSecondVal = $val.substring(6, 7);

                    if ($monthSecondVal == '0') {
                        $this.val(substr_replace($val, '01', 5, 2));
                        $this.setCursorPosition(6);
                    } else {
                        $this.caret({ start: 5, end: 6 });
                    }

                } else if (keyCode == 49 || keyCode == 97) {

                    var $monthSecondVal = $val.substring(6, 7);

                    if ($monthSecondVal == '3' || $monthSecondVal == '4' || $monthSecondVal == '5' ||
                        $monthSecondVal == '6' || $monthSecondVal == '7' || $monthSecondVal == '8' || $monthSecondVal == '9') {
                        $this.val(substr_replace($val, '10', 5, 2));
                        $this.setCursorPosition(6);
                    } else {
                        $this.caret({ start: 5, end: 6 });
                    }

                } else if (keyCode == 50 || keyCode == 98) {
                    $this.val(substr_replace($val, '02', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 51 || keyCode == 99) {
                    $this.val(substr_replace($val, '03', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 5, 2));
                    $this.setCursorPosition(8);
                } else {
                    $this.caret({ start: 5, end: 6 });
                }

            } else if (cursorStart == '6' && $val.substring(6, 7) != '_') { /* Month-2 */

                $this.caret({ start: 6, end: 7 });

            } else if (cursorStart == '7') { /* Day-1 */
                
                if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 8, 2));
                    $this.setCursorPosition(10);
                } else {
                    $this.caret({ start: 8, end: 9 });
                }

            } else if (cursorStart == '8' && $val.substring(8, 9) == '_') { /* Day-1 */
                
                if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 8, 2));
                    $this.setCursorPosition(10);
                    return false;
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 8, 2));
                    $this.setCursorPosition(10);
                    return false;
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 8, 2));
                    $this.setCursorPosition(10);
                    return false;
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 8, 2));
                    $this.setCursorPosition(10);
                    return false;
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 8, 2));
                    $this.setCursorPosition(10);
                    return false;
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 8, 2));
                    $this.setCursorPosition(10);
                    return false;
                }

            } else if (cursorStart == '8' && $val.substring(8, 9) != '_') { /* Day-1 */

                if (keyCode == 48 || keyCode == 96) {

                    var $daySecondVal = $val.substring(9, 10);

                    if ($daySecondVal == '0') {
                        $this.val(substr_replace($val, '01', 8, 2));
                        $this.setCursorPosition(9);
                    } else {
                        $this.caret({ start: 8, end: 9 });
                    }

                } else if (keyCode == 51 || keyCode == 99) {

                    var $daySecondVal = $val.substring(9, 10);

                    if ($daySecondVal == '2' || $daySecondVal == '3' || $daySecondVal == '4' || $daySecondVal == '5' ||
                        $daySecondVal == '6' || $daySecondVal == '7' || $daySecondVal == '8' || $daySecondVal == '9') {
                        $this.val(substr_replace($val, '30', 8, 2));
                        $this.setCursorPosition(9);
                    } else {
                        $this.caret({ start: 8, end: 9 });
                    }

                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 8, 2));
                    $this.setCursorPosition(10);
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 8, 2));
                    $this.setCursorPosition(10);
                } else {
                    $this.caret({ start: 8, end: 9 });
                }

            } else if (cursorStart == '9' && $val.substring(9, 10) != '_') { /* Day-2 */
                $this.caret({ start: 9, end: 10 });
            }

            $this.attr('data-focusout-change', '1'); /*2019.06.19 added*/
        }
        
        if (keyCode === 13 && $this.inputmask('isComplete')) {

            $this.data({ date: $val });
            $this.datepicker('update');
            $this.attr('data-focusout-change', '1');

        } else if (keyCode === 107 && $this.inputmask('isComplete')) {

            var modifyDate = dateModify($val, '+1 day');
            $this.data({ date: modifyDate });
            $this.datepicker('update', date('Y-m-d', strtotime(modifyDate)));
            $this.attr('data-focusout-change', '1');

        } else if (keyCode === 109 && $this.inputmask('isComplete')) {

            var modifyDate = dateModify($val, '-1 day');
            $this.data({ date: modifyDate });
            $this.datepicker('update', date('Y-m-d', strtotime(modifyDate)));
            $this.attr('data-focusout-change', '1');

        } else if (keyCode === 8 || keyCode === 46) {
            $this.attr('data-focusout-change', '1');
        }
        
        setTimeout(function() {
            if ($this.inputmask('isComplete')) {
                var lastDate = $this.val(), 
                    year = lastDate.substring(0, 4), 
                    month = lastDate.substring(5, 7), 
                    day = lastDate.substring(8, 10), 
                    lastDay = new Date(year, month, 0).getDate();
                
                if (parseFloat(day) > lastDay) {
                    var gerCursorPos = e.target.selectionStart;       
                    $this.val(year + '-' + month + '-' + lastDay);             
                    $this.setCursorPosition(gerCursorPos);
                }
                
                if ($this.hasAttr('data-mindate') && $this.attr('data-mindate')) {
                    
                    var inputDate = new Date($this.val());
                    var minDate = $this.attr('data-mindate');

                    if (inputDate < new Date(minDate)) {
                        $this.val(minDate);        
                        $this.data({ date: minDate });
                        $this.datepicker('update');
                    }
                }
                
                if ($this.hasAttr('data-maxdate') && $this.attr('data-maxdate')) {
                    
                    var inputDate = new Date($this.val());
                    var maxDate = $this.attr('data-maxdate');

                    if (inputDate > new Date(maxDate)) {
                        $this.val(maxDate);        
                        $this.data({ date: maxDate });
                        $this.datepicker('update');
                    }
                }
            }
        }, 1);

        $this.datepicker('hide');
    });
    $(document.body).on('keydown', '.monthInit:not([readonly],[disabled])', function(e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        var $val = $this.val();

        if ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 96 && keyCode <= 105)) {

            var cursorStart = $this.caret().start;

            if (cursorStart == '0' && $val.substring(0, 1) != '_') { /* Year-1 */
                $this.caret({ start: 0, end: 1 });
            } else if (cursorStart == '1' && $val.substring(1, 2) != '_') { /* Year-2 */
                $this.caret({ start: 1, end: 2 });
            } else if (cursorStart == '2' && $val.substring(2, 3) != '_') { /* Year-3 */
                $this.caret({ start: 2, end: 3 });
            } else if (cursorStart == '3' && $val.substring(3, 4) != '_') { /* Year-4 */
                $this.caret({ start: 3, end: 4 });
            } else if (cursorStart == '4') { /* Month-1 */

                if (keyCode == 50 || keyCode == 98) {
                    $this.val(substr_replace($val, '02', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 51 || keyCode == 99) {
                    $this.val(substr_replace($val, '03', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 5, 2));
                    $this.setCursorPosition(8);
                } else {
                    $this.caret({ start: 5, end: 6 });
                }

            } else if (cursorStart == '5' && $val.substring(5, 6) == '_') { /* Month-1 */

                if (keyCode == 50 || keyCode == 98) {
                    $this.val(substr_replace($val, '02', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 51 || keyCode == 99) {
                    $this.val(substr_replace($val, '03', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 5, 2));
                    $this.setCursorPosition(8);
                    return false;
                }

            } else if (cursorStart == '5' && $val.substring(5, 6) != '_') { /* Month-1 */

                if (keyCode == 48 || keyCode == 96) {

                    var $monthSecondVal = $val.substring(6, 7);

                    if ($monthSecondVal == '0') {
                        $this.val(substr_replace($val, '01', 5, 2));
                        $this.setCursorPosition(6);
                    } else {
                        $this.caret({ start: 5, end: 6 });
                    }

                } else if (keyCode == 49 || keyCode == 97) {

                    var $monthSecondVal = $val.substring(6, 7);

                    if ($monthSecondVal == '3' || $monthSecondVal == '4' || $monthSecondVal == '5' ||
                        $monthSecondVal == '6' || $monthSecondVal == '7' || $monthSecondVal == '8' || $monthSecondVal == '9') {
                        $this.val(substr_replace($val, '10', 5, 2));
                        $this.setCursorPosition(6);
                    } else {
                        $this.caret({ start: 5, end: 6 });
                    }

                } else if (keyCode == 50 || keyCode == 98) {
                    $this.val(substr_replace($val, '02', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 51 || keyCode == 99) {
                    $this.val(substr_replace($val, '03', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 52 || keyCode == 100) {
                    $this.val(substr_replace($val, '04', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 53 || keyCode == 101) {
                    $this.val(substr_replace($val, '05', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 54 || keyCode == 102) {
                    $this.val(substr_replace($val, '06', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 55 || keyCode == 103) {
                    $this.val(substr_replace($val, '07', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 56 || keyCode == 104) {
                    $this.val(substr_replace($val, '08', 5, 2));
                    $this.setCursorPosition(8);
                } else if (keyCode == 57 || keyCode == 105) {
                    $this.val(substr_replace($val, '09', 5, 2));
                    $this.setCursorPosition(8);
                } else {
                    $this.caret({ start: 5, end: 6 });
                }

            } else if (cursorStart == '6' && $val.substring(6, 7) != '_') { /* Month-2 */

                $this.caret({ start: 6, end: 7 });
            }

            $this.attr('data-focusout-change', '1'); /*2019.06.19 added*/
        }
        
        if (keyCode === 13 && $this.inputmask('isComplete')) {

            $this.data({ date: $val });
            $this.datepicker('update');
            $this.attr('data-focusout-change', '1');

        } else if (keyCode === 107 && $this.inputmask('isComplete')) {

            var modifyDate = dateModify($val, '+1 day');
            $this.data({ date: modifyDate });
            $this.datepicker('update', date('Y-m', strtotime(modifyDate)));
            $this.attr('data-focusout-change', '1');

        } else if (keyCode === 109 && $this.inputmask('isComplete')) {

            var modifyDate = dateModify($val, '-1 day');
            $this.data({ date: modifyDate });
            $this.datepicker('update', date('Y-m', strtotime(modifyDate)));
            $this.attr('data-focusout-change', '1');

        } else if (keyCode === 8 || keyCode === 46) {
            $this.attr('data-focusout-change', '1');
        }
        
        setTimeout(function() {
            if ($this.inputmask('isComplete')) {
                var lastDate = $this.val(), 
                    year = lastDate.substring(0, 4), 
                    month = lastDate.substring(5, 7), 
                    day = lastDate.substring(8, 10), 
                    lastDay = new Date(year, month, 0).getDate();
                
                if (parseFloat(day) > lastDay) {
                    var gerCursorPos = e.target.selectionStart;       
                    $this.val(year + '-' + month);             
                    $this.setCursorPosition(gerCursorPos);
                }
                
                if ($this.hasAttr('data-mindate') && $this.attr('data-mindate')) {
                    
                    var inputDate = new Date($this.val());
                    var minDate = $this.attr('data-mindate');

                    if (inputDate < new Date(minDate)) {
                        $this.val(minDate);        
                        $this.data({ date: minDate });
                        $this.datepicker('update');
                    }
                }
                
                if ($this.hasAttr('data-maxdate') && $this.attr('data-maxdate')) {
                    
                    var inputDate = new Date($this.val());
                    var maxDate = $this.attr('data-maxdate');

                    if (inputDate > new Date(maxDate)) {
                        $this.val(maxDate);        
                        $this.data({ date: maxDate });
                        $this.datepicker('update');
                    }
                }
            }
        }, 1);

        $this.datepicker('hide');
    });
    $(document.body).on('change', '.dateInit:not([readonly],[disabled]), .monthInit:not([readonly],[disabled])', function(e, isTriggered) {
        var $this = $(this);
        if (!isTriggered && $this.inputmask('isComplete')) {
            $this.data({ date: $this.val() });
            $this.datepicker('update');
        }
        $this.datepicker('hide');
    });
    $(document.body).on('focus', '.dateInit:not([readonly],[disabled])', function(e) {
        var $this = $(this);
        $this.inputmask('y-m-d');
        $this.select();
        return e.preventDefault();
    });
    $(document.body).on('focus', '.monthInit:not([readonly],[disabled])', function(e) {
        var $this = $(this);
        $this.inputmask('y-m');
        $this.select();
        return e.preventDefault();
    });
    $(document.body).on('focusout', '.dateInit[data-focusout-change="1"]:not([readonly],[disabled]), .monthInit[data-focusout-change="1"]:not([readonly],[disabled])', function(e) {
        var $this = $(this);
        $this.removeAttr('data-focusout-change');
        $this.trigger('changeDate');
    });
    $(document.body).on('changeDate', '.pf-inline-datepicker', function() {
        var $this = $(this);
        $this.find('.inlineDateInit').val($this.datepicker('getFormattedDate')).trigger('change');
    });

    $(document.body).on('keydown', '.datetimeInit:not([readonly],[disabled])', function(e) {
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);

        if (keyCode === 13 && $this.inputmask('isComplete')) {
            $this.trigger('changeDate');
            $this.datetimepicker('hide');
        }
    });

    function e() {
        var e = $(window).height() - $("body > .navbar").outerHeight() - $("body > .navbar + .navbar").outerHeight() - $("body > .navbar + .navbar-collapse").outerHeight() - $(".page-header").outerHeight();
        $(".page-container").attr("style", "min-height:" + e + "px")
    }
    $(".panel-heading, .page-header-content, .panel-body").has("> .heading-elements").append('<a class="heading-elements-toggle"><i class="icon-menu"></i></a>'), $(".heading-elements-toggle").on("click", function() {
        $(this).parent().children(".heading-elements").toggleClass("visible")
    }), $(document).on("click", ".dropdown-content", function(e) {
        e.stopPropagation()
    }), $(".navbar-nav .disabled a").on("click", function(e) {
        e.preventDefault(), e.stopPropagation()
    }), $('.dropdown-content a[data-toggle="tab"]').on("click", function() {
        $(this).tab("show")
    }), $(".sidebar-mobile-main-toggle").on("click", function(e) {
        e.preventDefault(), $('body').toggleClass("sidebar-mobile-main").removeClass("sidebar-mobile-secondary sidebar-mobile-opposite")
        $('.iconbar').toggleClass('slide-m-menu');
        if ($('.hidden-left-links').hasClass('slide-m-menu-left')) $('.iconbar').removeClass('slide-m-menu-left');
    }), $(".sidebar-mobile-main-toggle-left").on("click", function(e) {
        e.preventDefault(),
        $('.hidden-left-links').toggleClass('slide-m-menu-left');
        if ($('.iconbar').hasClass('slide-m-menu')) $('.iconbar').removeClass('slide-m-menu');
    }), $(".sidebar-mobile-secondary-toggle").on("click", function(e) {
        e.preventDefault(), $('body').toggleClass("sidebar-mobile-secondary").removeClass("sidebar-mobile-main sidebar-mobile-opposite")
    }), $(".sidebar-mobile-opposite-toggle").on("click", function(e) {
        e.preventDefault(), $('body').toggleClass("sidebar-mobile-opposite").removeClass("sidebar-mobile-main sidebar-mobile-secondary")
    });

    if ($().contextMenu) {
        $.contextMenu({
            selector: 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li, div.m-tab > .tabbable-line > .card-multi-tab-navtabs > li',
            callback: function(key, opt) {
                if (key === 'app_tab_close') {
                    var _this = opt.$trigger;
                    multiTabCloseConfirm(_this.find('a'));
                }
            },
            items: {
                "app_tab_close": { name: plang.get('close_btn'), icon: "times-circle" }
            }
        });
    }
    
    $(document.body).on('show.bs.tab', 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > a, div.m-tab > .tabbable-line > .card-multi-tab-navtabs > li > a', function() {
        var mmid = Core.getURLParameter('mmid');
        if (mmid) {
            var $this = $(this), $parent = $this.closest('ul'), $activeTab = $parent.find('a[data-toggle="tab"].active');
            
            if ($activeTab.length) {
                var currentUrl = (window.location.href).replace(URL_APP, '');
                $activeTab.attr('data-push-url', currentUrl);
            }
        }
    });

    $(document.body).on('shown.bs.tab', 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > a, div.m-tab > .tabbable-line > .card-multi-tab-navtabs > li > a', function() {
        $(window).trigger('resize');
        
        var $this = $(this);
        var $containerTab = $('body').find('div' + $this.attr('href'));
        
        if ($this.hasAttr('data-push-url')) {
            window.history.pushState('module', 'Veritech ERP', $this.attr('data-push-url'));
        }
        
        if (typeof $containerTab !== 'undefined' && typeof $containerTab.attr('of-call-function') !== 'undefined' && typeof $containerTab.attr('of-call-function') !== 'undefined') {
            window[$containerTab.attr('of-call-function')]($containerTab.attr('of-call-function-element'), '1');
            $containerTab.removeAttr('of-call-function');
            $containerTab.removeAttr('of-call-function-element');
        }
    });
    $(document.body).on('click', 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > a > span:not(".custom-close-tab"), div.m-tab > .tabbable-line > .card-multi-tab-navtabs > li > a > span:not(".custom-close-tab"), div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > ul > li > a > span, div.m-tab > .tabbable-line > .card-multi-tab-navtabs > li > ul > li > a > span', function(e) {
        e.preventDefault();
        multiTabCloseConfirm($(this).closest('a'));
    });
    $(document.body).on('dblclick', 'div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > a, div.card-multi-tab > .tabbable-line > .card-multi-tab-navtabs > li > ul > li > a, div.m-tab > .tabbable-line > .card-multi-tab-navtabs > li > a', function(e) {
        if ($(this).find('.custom-close-tab').length) {
            return;
        }

        e.preventDefault();
        multiTabCloseConfirm($(this).closest('a'));
    });

    $(document.body).on('hover', '.datahovertext', function() {
        var $targetTag = $(this);
        if ($targetTag.attr('datahover-status') == 'no') {
            $targetTag.attr('datahover-status', 'yes');
            $targetTag.html('<span style="' + $targetTag.attr('data-textstyle') + '">' + $targetTag.attr('data-mousehover') + '</span>');
        } else {
            $targetTag.attr('datahover-status', 'no');
            $targetTag.html('<i style="' + $targetTag.attr('data-textstyle') + '" class="' + $targetTag.attr('data-mouseout') + '"></i>');
        }
    });

    $(document.body).on('click', 'li > a.li-criteriaCondition', function() {
        var $thisTarget = $(this);
        var $thisTargetCondition = $thisTarget.attr('data-criteria-condition');

        var $thisParentAttr = $thisTarget.closest('span');
        $thisParentAttr.find('input[type="hidden"]:first').val($thisTargetCondition);
        $thisParentAttr.find('button:first').html($thisTargetCondition);
    });

    $(document.body).on('click', 'a.div-accordionToggler', function() {
        var _this = $(this);
        if (_this.attr('data-toggler-status') === 'open') {
            _this.attr('data-toggler-status', 'close');
            _this.children().removeClass('fa-angle-up').addClass('fa-angle-down');
        } else {
            _this.attr('data-toggler-status', 'open');
            _this.children().removeClass('fa-angle-down').addClass('fa-angle-up');
        }
        $('.' + _this.attr('data-toggler-class')).slideToggle();
    });

    $(document.body).on('click', '.input-group-criteria .criteria-condition-btn', function() {
        $(this).closest('#default-criteria-form').children().addClass('criteria-overflow-notauto');
    });

    $(document.body).on('click', '.dropdown-menu-display > li', function() {
        $(this).closest('#default-criteria-form').children().removeClass('criteria-overflow-notauto');
    });

    $(document.body).on('click', '.star-rating:not(.not-click) > li', function() {
        var $this = $(this);
        var $closestRating = $this.closest('ul.star-rating');
        var $closestAllAttr = $closestRating.find('li');
        var $attrValue = $closestRating.find('input[type="hidden"]');

        $attrValue.val($this.attr('data-id')).trigger('change');

        $.each($closestAllAttr, function(index, row) {
            var $clo = $(row).find('i');
            if (index <= ($this.index() - 1)) {
                $clo.attr('class', 'icon-star-full2').attr('style', 'color: orange; cursor: pointer;');
            } else {
                $clo.attr('class', 'icon-star-empty3').attr('style', 'color: #CCC; cursor: pointer;');
            }
        });
    });

    $(document.body).on('click', '.copy-link-bn', function() {
        var $this = $(this),
            $temp = $('<input>');
        $('body').append($temp);

        if (typeof $this.data('url') !== 'undefined') {
            $temp.val(encodeURI($this.data('url'))).select();
            document.execCommand('copy');
        }

        $temp.remove();
    });

    $(document).keyup(function(e) {
        if (e.which == 27) {
            $('.fiscalPeriodList-jtree').addClass('hidden');
        }
    });

    $(document).click(function(e) {
        if ($(e.target).parents('.fiscalPeriod-list-jtree').length === 0) {
            $('.fiscalPeriodList-jtree').addClass('hidden');
        }
    });

    $('a.hdr-open-notification-list').on('click', function() {
        var $this = $(this), $list = $("ul.hdr-user-notification-list"),
            $blockSelector = $list.closest('.dropdown-menu');

        if ($this.closest('.dropdown').hasClass('show')) return;
        $list.removeClass('no-data');
        $('.hdr-notification-search').parent().addClass('d-none');

        $.ajax({
            type: 'post',
            url: 'mdnotification/getNotificationList',
            data: {q: $('.hdr-notification-search').val()},
            beforeSend: function() {
                setTimeout(function(){
                    Core.blockUI({target: $blockSelector, animate: false, iconOnly: true});                
                }, 10);
            },
            success: function(data) {
                if (data.length > 43) {
                    $('.hdr-notification-search').parent().removeClass('d-none');
                    $('.hdr-notification-search').focus().select();
                }
                $list.empty().append(data);
                $this.find('span.badge').remove();
                Core.unblockUI($blockSelector);             
            }
        });
    });

    var notifiRequestTimer, notifiRequest = null;
    $(document).on('keyup', '.hdr-notification-search', function(e) {
        var $self = $(this);
        eventDelay(function() {
            notifiRequestTimer && clearTimeout(notifiRequestTimer);
            notifiRequestTimer = setTimeout(function() {

                if (notifiRequest != null) {
                    notifiRequest.abort();
                }                

                var $list = $("ul.hdr-user-notification-list"),
                    $blockSelector = $list.closest('.dropdown-menu');        
                notifiRequest = $.ajax({
                    type: 'post',
                    url: 'mdnotification/getNotificationList',
                    data: {q: $self.val()},
                    beforeSend: function() {
                        Core.blockUI({target: $blockSelector, animate: false, iconOnly: true});                
                    },
                    success: function(data) {
                        notifiRequest = null;
                        $list.empty().append(data);
                        Core.unblockUI($blockSelector);        
                        $list.removeClass('no-data');
                    }
                });
            }, 200);
        }, 500);
    });       

    var notifiListPager = 2;
    $('.hdr-notification-body').scroll(function () {
        
        var $scrollable = $(this);
        
        var div = $scrollable.get(0);
        var divScrollTop = div.scrollTop;
        var divClientHeight = div.clientHeight;
        var divPlus = divScrollTop + divClientHeight;
        var divScrollHeight = div.scrollHeight;
            
        if ((divScrollTop + divClientHeight >= divScrollHeight) || (divScrollHeight > divPlus && (divScrollHeight - divPlus) < 20)) {
            
            var $list = $("ul.hdr-user-notification-list"), 
                $notifiSelector = $list.closest('.dropdown-menu');

            if ($list.hasClass('no-data')) {
                return;
            }

            $.ajax({
                type: 'post',
                url: 'mdnotification/getNotificationList',
                async: false,
                data: {q: $('.hdr-notification-search').val(), p: notifiListPager},
                beforeSend: function() {
                    Core.blockUI({target: $notifiSelector, animate: false, iconOnly: true});                
                },
                success: function(data) {
                    $list.append(data);
                    Core.unblockUI($notifiSelector);             
                    notifiListPager++;
                    if (data.length === 43) {
                        $list.addClass('no-data');
                    }                    
                }
            });                   
        }
    });
    
    $(document.body).on('click', '.pf-module-sidebar > a', function() {
        var elem = this, $this = $(elem), 
            moduleId = $this.data('moduleid'), 
            moduleName = $this.data('original-title'), 
            webUrl = $this.data('weburl'),
            urlTrg = $this.data('urltrg'),
            bookmarkUrl = $this.data('bookmarkurl'),
            actionMetaDataId = $this.data('actionmetadataid'), 
            actionMetaTypeId = $this.data('actionmetatypeid'),
            isKpiIndicator = false,
            postData = {moduleId: moduleId};
            
        if (webUrl && urlTrg == '_alwaysblank') {
            window.open(webUrl, '_blank');
            return;
        }
            
        if (bookmarkUrl && urlTrg == '_alwaysblank') {
            window.open(bookmarkUrl, '_blank');
            return;
        }
        
        if (webUrl != '' && (webUrl.indexOf('https://') !== -1 || webUrl.indexOf('http://') !== -1 || webUrl == 'appmenu/kpi')) {
            window.location.href = webUrl;
            return;
        }
        
        if ($this.hasAttr('data-kpi-indicator')) {
            isKpiIndicator = true;
            postData.isKpiIndicator = 1;
        }
        
        $this.closest('.pf-module-sidebar').find('a.active').removeClass('active');
        $this.addClass('active');
            
        $.ajax({
            type: 'post',
            url: 'mdmenu/getTopMenuByModuleId', 
            data: postData, 
            dataType: 'json', 
            success: function(data) {
                
                if (data.topMenu != '') {
                    
                    var $module = $('.page-module-name'), $topMenuWrap = $('.page-topbar'), 
                        $moreMenuWrap = $topMenuWrap.next('div');
                    
                    $moreMenuWrap.find('ul.dropdown-menu').empty();
                    $moreMenuWrap.find('.pf-topnavbar-menu-morebtn').addClass('d-none');
                    
                    $topMenuWrap.empty().append(data.topMenu).promise().done(function() {
                        
                        breaks = [];
                        updateNav();
                        $('.page-actions').remove();
                        $module.text(moduleName).after(data.quickMenu);
                        
                        if (data.hasOwnProperty('openMenuId') && data.openMenuId) {
                            
                            var $menuElement = $topMenuWrap.find('a[data-meta-data-id="'+data.openMenuId+'"]');
                            if ($menuElement.length) {
                                $menuElement.click();
                            }
                            
                        } else {
                            
                            if (!actionMetaDataId || !actionMetaTypeId) {
                            
                                var $navBar = $topMenuWrap.find('> ul.navbar-nav'), 
                                    $layoutMenu = $navBar.find('> li:not(.not-module-menu):eq(0) > a[onclick*="\'layout\'"]');

                                if ($layoutMenu.length) {

                                    $layoutMenu.click();

                                } else {

                                    var $defaultOpenMenu = $navBar.find('[data-default-open="true"]:eq(0)');

                                    if ($defaultOpenMenu.length) {
                                        $defaultOpenMenu.click();
                                    }
                                } 
                            } 
                        }
                        
                        if (isKpiIndicator == false) {
                            
                            getLeftMenuCount(false, $topMenuWrap);
                            window.history.pushState('module', moduleName + ' - Veritech ERP', 'appmenu/module/'+moduleId+'?mmid='+moduleId);
                            
                        } else {
                            window.history.pushState('module', moduleName + ' - Veritech ERP', 'appmenu/module/'+moduleId+'?kmid='+moduleId);
                        }
                    });
                }
            }
        });
        
        if (webUrl != '') {
            
            appMultiTab({weburl: webUrl, metaDataId: strtolower(str_replace('/', '', webUrl)), title: moduleName, type: 'selfurl', tabReload: true}, elem);
            
        } else if (actionMetaDataId && actionMetaTypeId) {
            
            if (actionMetaTypeId == '200101010000010') {
                
                var bookmarkUrl = $this.data('bookmarkurl'), 
                    bookmarkTrg = $this.data('bookmarktrg'), 
                    bookmarkUrlLower = bookmarkUrl.toLowerCase();
                
                if (bookmarkUrlLower == 'government/intranet' || bookmarkUrlLower == 'government/mail/') {
                    appMultiTab({weburl: bookmarkUrl, metaDataId: str_replace('/', '', bookmarkUrlLower), title: moduleName, type: 'selfurl', tabReload: true}, this);
                } else {
                    appMultiTab({weburl: bookmarkUrl, metaDataId: str_replace('/', '', bookmarkUrlLower), title: moduleName, type: 'selfurl'}, this);
                }
                
            } else if (actionMetaTypeId == '200101010000011') {
                
                callWebServiceByMeta(actionMetaDataId, true, '', false, {callerType: $this.data('code'), isMenu: true});
                
            } else if (actionMetaTypeId == '200101010000016') {

                appMultiTab({metaDataId: actionMetaDataId+'', title: moduleName, type: 'dataview', proxyId: ''}, this);
                
            } else if (actionMetaTypeId == '200101010000033') {
                
                appMultiTab({metaDataId: actionMetaDataId+'', title: moduleName, type: 'package'}, this);
                
            } else if (actionMetaTypeId == '200101010000035') {
                
                appMultiTab({metaDataId: actionMetaDataId+'', title: moduleName, type: 'statement'}, this);
                
            } else if (actionMetaTypeId == '200101010000036') {
                
                appMultiTab({metaDataId: actionMetaDataId+'', title: moduleName, type: 'layout'}, this);
            } 
        }
        
        $('[data-popup="tooltip"]').tooltip('hide');
    });
    
    $('.touch-screen-switch-btn').on('click', function() {
        var $this = $(this), $body = $('body'), isTouchMode = 0;
        
        if ($body.hasClass('touch-screen-switch')) {
            $this.html('<i class="icon-touch font-weight-bold"></i> Touch mode on');
        } else {
            $this.html('<i class="icon-touch font-weight-bold"></i> Touch mode off');
            isTouchMode = 1;
        }
        
        $body.toggleClass('touch-screen-switch');
        
        $.ajax({
            type: 'post',
            url: 'mduser/saveUserTouchMode', 
            data: {isTouchMode: isTouchMode}, 
            dataType: 'json'
        });
    });
    /*
    $(document.body).on('click', '.sidebar-mobile-component-toggle', function() {
        $('.sidebar-sticky .sidebar').trigger('sticky_kit:detach');
    });
    */
    $(document.body).on('shown.bs.dropdown', '.pf-topnavbar-menu > .page-topbar > .navbar-nav', function(e) {
        
        var $this = $(e.target);
        var $dropdown = $this.find('> a.dropdown-toggle').next('.dropdown-menu:eq(0)');
        
        if ($dropdown.length) {
            setTimeout(function() {
                if ($dropdown.is(':visible')) {
                    var menuHeight = $dropdown.outerHeight();
                    var menuOffsetTop = $dropdown.offset().top;
                    if ((menuHeight + menuOffsetTop) > $(window).height()) {
                        $dropdown.addClass('scrollable-menu');
                    }
                }
            }, 1);
        }
    });
    $(document.body).on('mouseenter', '.pf-topnavbar-menu > .page-topbar > .navbar-nav .scrollable-menu .nav-item.dropdown-submenu', function() {
        var $this = $(this), $subMenu = $this.find('> .dropdown-menu:eq(0)');
        
        if ($subMenu.length) {
            var menuTop = $this.offset().top, menuHeight = $subMenu.height(), 
                menuWindowHeight = menuTop + menuHeight, windowHeight = $(window).height(), 
                menuLeft = $this.offset().left + $this.width();
            
            if (menuWindowHeight > windowHeight) {
                menuTop = menuTop - (menuWindowHeight - windowHeight) - 5;
            }
                
            $subMenu.css({'position': 'fixed', 'top': menuTop, 'left': menuLeft});
        }
    });
    $(document.body).on('click', '.mobile-header-menu', function() {
        var $hdr = $('.mobile-header-contents');
        if ($hdr.hasClass('header-mobile-open')) {
            $hdr.removeClass('header-mobile-open');
        } else {
            $hdr.addClass('header-mobile-open');
            $(document.body).on('click', '*', function(e) {
                if (!$(e.target).is('.mobile-header-contents, .mobile-header-contents *, .mobile-header-menu, .mobile-header-menu *')) { 
                    $hdr.removeClass('header-mobile-open');
                }
            });
        }
    });
    
    var getCountInterval = (typeof menuCountInterval != 'undefined' && menuCountInterval) ? menuCountInterval : 5;
    setInterval(getLeftMenuCount, 60000 * getCountInterval); //default: 5 minute
    setTimeout(function() {
        getLeftMenuCount(true);
    }, 1000);
});

function isTouchEnabledFnc() { 
    return ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0); 
} 
function clearConsole() {
    /*if (window.console || window.console.firebug) {
        console.clear();
    }*/
}
function fluidDialog($this) {
    
    var dialog = $this.find('.ui-dialog-content').data('ui-dialog');
    var wWidth = $(window).width();

    // Check window width against dialog width.
    if (typeof dialog !== 'undefined' && wWidth < (parseInt(dialog.options.width, 10) + 50)) {
        // Keep dialog from filling entire screen
        $this.css('max-width', '100%');
        $this.css('width', '100%');

        // Reposition dialog.
        dialog.option('position', dialog.options.position);
    } 
    
    var $visible = $('.ui-dialog:visible'), length = $visible.length;
    
    if (length > 1) {
        
        var maxZ = 0, dialogIndex = Number($this.css('zIndex'));
        
        $visible.each(function() {
            var thisZ = Number($(this).css('zIndex'));
            thisZ = (thisZ === 'auto' ? (maxZ + 1) : thisZ);
            if (thisZ > maxZ) maxZ = thisZ;
        });
        
        if (dialogIndex <= maxZ) {
            $this.next('.ui-widget-overlay').css('zIndex', (maxZ + 1));
            $this.css('zIndex', (maxZ + 2));
        }
    }
}
$(document).on('dialogopen', '.ui-dialog', function () {
    fluidDialog($(this));
});

function showGridMessage($dg, msg_no_record_found, isSubGrid) {
    var $panel = $dg.datagrid('getPanel');
    var $vc = $panel.children('div.datagrid-view');
    $vc.children('div.datagrid-empty').remove();
    
    if (!$dg.datagrid('getRows').length) {
        
        var msgNoRecordFound = plang.get((typeof msg_no_record_found != 'undefined' && msg_no_record_found) ? msg_no_record_found : 'msg_no_record_found');
        var d = $('<div class="datagrid-empty"></div>').html(msgNoRecordFound).appendTo($vc);
        var topSize = $panel.find("div.datagrid-header").height() + 8;

        d.css({
            position: 'absolute',
            left: 0,
            top: topSize,
            width: '100%',
            textAlign: 'center'
        });
        setTimeout(function() {
            var $datagridView2 = $vc.find('.datagrid-view2'),
                $datagridHeaderWidth = $datagridView2.find('.datagrid-header-inner > .datagrid-htable').width(),
                $datagridBtable = $datagridView2.find('.datagrid-btable'),
                $focusedFilter = $(document.activeElement);
            $datagridBtable.css('width', $datagridHeaderWidth + 'px');
            $datagridBtable.find('tbody > tr').css('height', '5px');

            if ($focusedFilter.hasClass('datagrid-filter')) {
                var $dbody = $datagridView2.find('.datagrid-body'),
                    $dbodyWidth = Number($dbody.width()),
                    $leftSize = Number($focusedFilter.closest('td').position().left),
                    $prevCells = $focusedFilter.closest('td').prevAll('td'),
                    $cellWidth = 0;

                $prevCells.each(function() {
                    $cellWidth += $(this).width();
                });

                if ($dbodyWidth < $leftSize) {
                    $dbody.get(0).scrollLeft = $cellWidth;
                }
            }
            
            $vc.find('.datagrid-body table, .datagrid-body .datagrid-group').css('visibility', 'hidden');
            $dg.datagrid('getPager').pagination({total: 0});
            
            if (typeof isSubGrid != 'undefined' && isSubGrid) {
                var headerHeight = $datagridView2.find('> .datagrid-header').height();
                if (headerHeight > 0) {
                    $vc.css('height', headerHeight + 30);
                } else {
                    $vc.css('height', 33);
                }
            }
        }, 0);
    }
}
function showTreeGridMessage($dg, msg_no_record_found) {
    var $panel = $dg.datagrid('getPanel');
    var $vc = $panel.children('div.datagrid-view');
    $vc.children('div.datagrid-empty').remove();

    if (!$dg.treegrid('getData').length) {
        
        var msgNoRecordFound = plang.get((typeof msg_no_record_found != 'undefined' && msg_no_record_found) ? msg_no_record_found : 'msg_no_record_found');
        var d = $('<div class="datagrid-empty"></div>').html(msgNoRecordFound).appendTo($vc);
        var topSize = $panel.find("div.datagrid-header").height() + 8;

        d.css({
            position: 'absolute',
            left: 0,
            top: topSize,
            width: '100%',
            textAlign: 'center'
        });
        
        setTimeout(function() {
            $vc.find('.datagrid-body table').css('visibility', 'hidden');
            $dg.datagrid('getPager').pagination({total: 0});
        }, 1);
    }
}
function renderAppChildMenu(parentId) {
    var $pageSidebar = $(".page-sidebar");
    $.ajax({
        type: 'post',
        url: 'mdmenu/childMenu',
        data: { parentId: parentId },
        beforeSend: function() {
            Core.blockUI({
                target: $pageSidebar,
                animate: true
            });
        },
        success: function(data) {
            $pageSidebar.empty().append(data);
            App.initNavigations($pageSidebar);
            /*createContentHtmlEvent($pageSidebar, parentId);*/
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initSlimScroll($('.page-sidebar-menu:not([data-no-scroll])'));
        $(window).trigger('resize');
        Core.unblockUI();
    });
}
function renderAppChildTopMenu(parentId) {
    $.ajax({
        type: 'post',
        url: 'mdmenu/childMenu',
        data: { parentId: parentId },
        beforeSend: function() {
            Core.blockUI({
                target: $(".page-topbar"),
                animate: true
            });
        },
        success: function(data) {
            var $pageSidebar = $(".page-topbar");
            $pageSidebar.empty().html(data);
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        $(window).trigger('resize');
        Core.unblockUI();
    });
}
function createContentHtmlEvent($pageSidebar, parentId) {
    if (ENVIRONMENT === 'development' && $.contextMenu) {
        $pageSidebar.find('ul.page-sidebar-menu');
        $.contextMenu({
            selector: 'ul.page-sidebar-menu li:not(:has(> ul))',
            build: function($triggerElement, e) {
                if ($triggerElement.find('a').attr('onclick').indexOf('mdcontentui/contentHtmlRender') !== -1) {
                    return renderContentContextmenu('засах', function(_this) {
                        _this.find('a').trigger('click');
                    });
                } else {
                    return renderContentContextmenu('нэмэх', function(_this) {
                        contentHtmlRenderNewMode(_this, parentId);
                    });
                }
            }
        });
    }
}
function renderContentContextmenu(cmdTxt, callback) {
    return {
        callback: function(key, opt) {
            if (key === 'content_html_execute') {
                var _this = opt.$trigger;
                callback(_this);
            }
        },
        items: {
            "content_html_execute": { name: "Контент " + cmdTxt, icon: "html5" }
        }
    };
}
function contentHtmlRenderNewMode(_this, parentId) {
    var param = {};
    param['weburl'] = 'mdcontentui/contentHtmlRender/';
    param['metaDataId'] = 'mdcontentuicontenthtmlrender' + _this.find('a').data('meta-data-id');
    param['title'] = _this.find('a > span').text() + ' - Контент нэмэх';
    param['type'] = 'selfurl';
    appMultiTab(param, _this.find('a'), function(div, param) {
        div.append('<contenthtml data-title="' + param.title + '" data-menu-id="' + _this.find('a').data('meta-data-id') + '" data-module-id="' + parentId + '"></contenthtml>');
    });
}
function multiTabActiveAutoClose() {
    var $activeTab = $('.card-multi-tab-navtabs > li > a.active');
    var $li = $activeTab.closest('li');
    $li.attr('data-type', 'layout');

    multiTabCloseConfirm($activeTab);
}
function multiTabCloseConfirm(elem, type) {
    var $li = elem.closest('li');
    var $tabType = (typeof isAlwaysConfirmCloseTab !== 'undefined' && isAlwaysConfirmCloseTab) ? 'true' : $li.attr('data-type');
    
    if ($tabType == 'dataview' || $tabType == 'layout' || $tabType == 'content' || $tabType == 'package' || $tabType == 'filepreview' || $tabType == 'selfurl') {
        if ($('.posTimerInit').length && $('.posTimerInit').is(':visible')) {
            $('.posTimerInit').countdown('destroy');
        }
        $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div' + elem.attr('href')).remove();
        var $prevLi = $li.prev('li:not(.tabdrop)');
        if ($prevLi.length === 0) {
            var $prevLi = $li.next('li:not(.tabdrop)');
        }
        if (elem.data('qtip')) {
            elem.qtip('destroy', true);
        }
        $li.remove();
        $prevLi.find('a').tab('show');

        return;
    }

    if (typeof type !== 'undefined' && type == '1') {
        $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div' + elem.attr('href')).remove();
        var $prevLi = $li.prev('li:not(.tabdrop)');
        if ($prevLi.length === 0) {
            var $prevLi = $li.next('li:not(.tabdrop)');
        }
        
        if (elem.data('qtip')) {
            elem.qtip('destroy', true);
        }
        
        $li.remove();
        updateMultiTabs();
        $prevLi.find('a').tab('show');
        
    } else {
        
        var $dialogName = 'dialog-window-close-confirm';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdcommon/windowCloseConfirm',
                dataType: "json",
                async: false,
                success: function(data) {
                    $dialog.empty().append(data.Html);
                },
                error: function() {
                    alert("Error");
                }
            });
        } else {
            var $dialog = $('#' + $dialogName);
        }
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 330,
            height: "auto",
            modal: true,
            close: function() {
                $dialog.empty().dialog('destroy').remove();
            },               
            buttons: [{
                    text: plang.get('yes_btn'),
                    'class': 'btn green-meadow btn-sm',
                    click: function() {
                        $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div' + elem.attr('href')).remove();
                        var $prevLi = $li.prev('li:not(.tabdrop)');
                        if ($prevLi.length === 0) {
                            var $prevLi = $li.next('li:not(.tabdrop)');
                        }
                        if (elem.data('qtip')) {
                            elem.qtip('destroy', true);
                        }
                        $li.remove();
                        $prevLi.find('a').tab('show');
                        updateMultiTabs();
                        $dialog.dialog('close');
                    }
                },
                {
                    text: plang.get('no_btn'),
                    'class': 'btn blue-madison btn-sm',
                    click: function() {
                        if ($('.ui-widget-overlay').length > 1) {
                            $('.ui-widget-overlay').remove();
                        }                        
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');
    }
}
function appMultiTab(param, elem, callback) {
    
    if (typeof sysTabLimitCount !== 'undefined' && sysTabLimitCount) {
        var $sysTabLi = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs > li');
        PNotify.removeAll();
        if ($sysTabLi.length == sysTabLimitCount) {
            new PNotify({
                title: 'Warning',
                text: plang.get('sysTabLimitCountInfoMsg'),
                type: 'warning',
                addclass: 'pnotify-center',
                sticker: false
            });
            return;
        }
    }
    
    var metaDataId = param.metaDataId, 
        isTabReload = param.hasOwnProperty('tabReload') ? param.tabReload : false;
    
    if (typeof elem !== 'undefined') {
        $(elem).closest('ul.page-sidebar-menu').find('li').removeClass('active');
        $(elem).closest('li').addClass('active');
    }
    
    if (typeof vr_top_menu !== 'undefined' && vr_top_menu) {

        var $tabMainContainer = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');

        if ($tabMainContainer.length == 0) {
            $("div.pf-header-main-content").html('<div class="col-md-12">' +
                '<div class="card light shadow card-multi-tab">' +
                '<div class="card-body">' +
                '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

            $('div.m-tab').html('<div class="card-header header-elements-inline tabbable-line">' +
                '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                '</div>');

            $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
        }

        if (!$.trim($('.topnavbarmenumode').html()).length) {
            $('.topnavbarmenumode').html('<div class="list-icons">' +
                '<a class="list-icons-item" data-action="fullscreen" title="↑UP↑"></a>' +
                '</div>'
            );
        }

    } else {
        var $tabMainContainer = $("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
        if ($tabMainContainer.length == 0) {
            $("div.pf-header-main-content").html('<div class="col-md-12">' +
                '<div class="card light shadow card-multi-tab">' +
                '<div class="card-header header-elements-inline tabbable-line">' +
                '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                '<div class="header-elements">' +
                '<div class="list-icons">' +
                '<a class="list-icons-item" data-action="fullscreen"></a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="card-body">' +
                '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

            $tabMainContainer = $('body').find("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
        }
    }

    metaDataId = metaDataId.replace(/,/g, '').replace(/\[/g, '').replace(/\]/g, '').replace(/&/g, '').replace(/=/g, '').replace(/\//g, '').replace(/\?/g, '').replace(' ', '');
    
    if (metaDataId.indexOf('mdformindicatorlist') !== -1 || metaDataId.indexOf('mdformindicatordatalist') !== -1) {
        metaDataId = metaDataId.replace(/\D/g, '');
    }
    
    var $tabElement = $tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']");
    var tabElementLength = $tabElement.length;
    
    if (tabElementLength > 0 && !isTabReload) {
        $tabElement.tab('show');
    } else {
        if (!isTabReload && typeof $('div.multi-tab > ul.collapsed-multi-tabs').find("a[href='#app_tab_" + metaDataId + "']") !== 'undefined' && $('div.multi-tab > ul.collapsed-multi-tabs').find("a[href='#app_tab_" + metaDataId + "']").length > 0) {
            $('div.multi-tab > ul.collapsed-multi-tabs').find("a[href='#app_tab_" + metaDataId + "']").tab('show');
            return;
        }
        
        if (tabElementLength == 0) {
            
            param.title = ucfirst((param.title).toLowerCase());

            $('.header-tab').find('.card-multi-tab-navtabs > li > a').each(function(){
                var mtext = $(this).text();
                if (param.title.toLowerCase().trim() == mtext.toLowerCase().trim()) {
                    param.title += ' /' + $("a[onclick^=\"appMultiTab({metaDataId: '"+metaDataId+"\"]").closest('ul').parent().find('>a').text() + '/';
                }
            });
            
            var $ul = $tabMainContainer;
            var $container = $("div.card-multi-tab > div.card-body > div.card-multi-tab-content");
            var $li = $('<li />', {
                "class": "nav-item",
                "data-type": param.type
            });
            var $a = $('<a />', {
                "class": "nav-link",
                "href": '#app_tab_' + metaDataId,
                "data-toggle": 'tab',
                "data-title": (param.hasOwnProperty('dataTitle') ? param.dataTitle : ''),
                "html": '<i class="fa fa-caret-right"></i> ' + param.title + '<span><i class="fa fa-times-circle"></i></span>'
            });

            $li.append($a);

            var $div = $('<div />', {
                "id": 'app_tab_' + metaDataId,
                "class": "tab-pane"
            });
            
        } else {
            var $div = $('#app_tab_' + metaDataId);
            var $a = $tabElement;
            
            if (isTabReload) {
                param.title = ucfirst((param.title).toLowerCase());
                $a.html('<i class="fa fa-caret-right"></i> ' + param.title + '<span><i class="fa fa-times-circle"></i></span>');
            }
        }

        if (param.type == 'dataview') {
                    
            $.ajax({
                type: 'post',
                url: 'mdobject/dataview/' + metaDataId,
                data: { proxyId: param.proxyId },
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $div.empty().append(data + '<div class="clearfix"/>');
                }
            }).done(function() {
                if (typeof callback === 'function') {
                    callback($div, param);
                }
                $ul.append($li);
                $container.append($div);
                $a.tab('show');
                
                var listUrl = 'mdobject/dataview/'+metaDataId;
                var mmid = Core.getURLParameter('mmid');

                if (mmid) {
                    listUrl += '?mmid=' + mmid;
                }

                window.history.pushState('module', 'Veritech ERP', listUrl);
                $a.attr('data-push-url', listUrl);
                
                Core.unblockUI();
            });
        } else if (param.type == 'statement') {
            var postData = {};
            if (param.hasOwnProperty('criteria')) {
                postData.postData = param.criteria;
            }
            $.ajax({
                type: 'post',
                url: 'mdstatement/index/' + metaDataId,
                data: postData, 
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $div.empty().append(data + '<div class="clearfix"/>');
                }
            }).done(function() {
                $ul.append($li);
                $container.append($div);
                $a.tab("show");
                Core.unblockUI();
            });
        } else if (param.type == 'workspace') {
            var selectedRow;
            if (param.hasOwnProperty('recordId')) {
                selectedRow = {id : param.recordId};
            }

            $.ajax({
                type: 'post',
                url: 'mdworkspace/index/' + metaDataId,
                data: { param: param, selectedRow: selectedRow },
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $div.empty().append(data + '<div class="clearfix"/>');
                }
            }).done(function() {
                $ul.append($li);
                $container.append($div);
                $a.tab("show");
                Core.unblockUI();
            });
        } else if (param.type == 'content') {
            $.ajax({
                type: 'post',
                url: 'mdlayout/index/' + metaDataId,
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $div.empty().append(data + '<div class="clearfix"/>');
                }
            }).done(function() {
                $ul.append($li);
                $container.append($div);
                Core.initAjax($div);
                $a.tab("show");
                Core.unblockUI();
            });
        } else if (param.type == 'package') {
            $.ajax({
                type: 'post',
                url: 'mdobject/package/' + metaDataId,
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $div.empty().append(data + '<div class="clearfix"/>');
                }
            }).done(function() {
                $ul.append($li);
                $container.append($div);
                $a.tab("show");
                Core.unblockUI();
            });
        } else if (param.type == 'layout') {
            $.ajax({
                type: 'post',
                url: 'mdlayoutrender/index/' + metaDataId,
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    var jsonObj = JSON.parse(data);
                    if ('Html' in Object(jsonObj)) {
                        $div.empty().append(jsonObj.Html + '<div class="clearfix"/>');
                    } else {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }
            }).done(function() {
                $ul.append($li);
                $container.append($div);
                $a.tab("show");
                Core.unblockUI();
            });
        } else if (param.type == 'calendar') {
            $.ajax({
                type: 'post',
                url: 'mdcalendar/calendarRenderByPost',
                data: {metaDataId: metaDataId},
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    var jsonObj = JSON.parse(data);
                    if ('Html' in Object(jsonObj)) {
                        $div.empty().append(jsonObj.Html + '<div class="clearfix"/>');
                    } else {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }
            }).done(function() {
                $ul.append($li);
                $container.append($div);
                $a.tab("show");
                Core.unblockUI();
            });
        } else if (param.type == 'taskflow') {
            $.getScript(URL_APP + 'assets/custom/addon/plugins/jsplumb/jsplumb.min.js', function() {
                if ($("link[href='assets/custom/addon/plugins/jsplumb/css/style.v3.css']").length == 0) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v3.css"/>');
                }            
                $.getScript(URL_APP + 'middleware/assets/js/mdprocessflowview.js', function() {                   
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/metaProcessWorkflow/'+metaDataId,
                        data: {
                            isViewTaskFlow: 1
                        },
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },                        
                        success: function(dataHtml) {
                            $div.empty().append(dataHtml + '<div class="clearfix"/>');
                            $div.find('.taskflow-bp-action-btn').addClass('hidden');
                            $div.find('.heigh-editor').css({background: "none", border: "none"});
                        }
                    }).done(function() {
                        $ul.append($li);
                        $container.append($div);
                        $a.tab("show");
                        Core.unblockUI();
                    });
                });
            });
            
        } else if (param.type == 'iframe') {
            
            var windowHeight = $(window).height() - 112;
            var iframeHtml = '<iframe src="'+param.weburl+'" frameborder="0" allowfullscreen style="width: 100%;height: '+windowHeight+'px;"></iframe>';
            
            $div.empty().append(iframeHtml + '<div class="clearfix"/>');
            $ul.append($li);
            $container.append($div);
            $a.tab('show');
            
            Core.unblockUI();
            
        } else if (param.type == 'kpi') {
            
            if (param.kpitypeid == '1') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorKnowledge/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '1130') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorDashboard/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '1060') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorOneChart/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '2006' || param.kpitypeid == '2009') {

                if (typeof isKpiIndicatorScript === 'undefined') {
                    $.getScript('middleware/assets/js/addon/indicator.js').done(function() {
                        manageKpiIndicatorValue(this, param.kpitypeid, metaDataId, false, {isIgnoreRunButton: 1});
                    });
                } else {
                    manageKpiIndicatorValue(this, param.kpitypeid, metaDataId, false, {isIgnoreRunButton: 1});
                }
                
                Core.unblockUI();
                
            } else if (param.kpitypeid == '2010') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorStatement/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '2012') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorIframe/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '2014') { 
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorProcessWidget/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '13') { 
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorRender/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
                
            } else if (param.kpitypeid == '16818054066154') { 
                
                mvProductRenderInit(this, null, metaDataId);
                
            } else {
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorDataList/' + metaDataId,
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function() {
                    $ul.append($li);
                    $container.append($div);
                    $a.tab('show');
                    Core.unblockUI();
                });
            }
            
        } else if (param.type == 'selfurl') {

            var _webLowerUrl = (param.weburl).toLowerCase();

            if (_webLowerUrl == 'mdgl/clearingtrans' ||
                _webLowerUrl == 'mdgl/cashrate' ||
                _webLowerUrl == 'mdgl/billrate2' ||
                _webLowerUrl == 'mdgl/billrate') {
                $.ajax({
                    url: "assets/custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"/>');
                    }
                }).done(function() {
                    $.ajax({
                        url: "assets/custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "middleware/assets/js/mdgl.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                });
            } else if(_webLowerUrl == 'government/meetingcalendar') {
                if (typeof isMultiCalendar === 'undefined') {
                    $.getScript(URL_APP + 'assets/custom/gov/multiselect.js').done(function() {
                        omsMeetingCalendar();
                    });
                } else {
                    omsMeetingCalendar();
                }
                
                return;
            } else if (_webLowerUrl == 'mdtime/timebalancev2' || _webLowerUrl == 'mdtime/mergetimebalancev2') {
                $.ajax({
                    url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tablesorter/css/theme.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                    }
                }).done(function() {
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.widgets.min.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/widgets/widget-sortTbodies.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/jquery-easyui/datagrid-automergecells.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "middleware/assets/js/time/timeBalanceV2.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                });

            } else if (_webLowerUrl == 'mdtimestable/timebalancev3') {
                $.ajax({
                    url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tablesorter/css/theme.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                    }
                }).done(function() {
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.widgets.min.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/widgets/widget-sortTbodies.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/jquery-easyui/datagrid-automergecells.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "middleware/assets/js/time/timeV3/timeBalanceV3.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCZCNSSfKeatvY1-QpSc_ShPyWmk7lEx4M&sensor=false&language=mn",
                        dataType: "script",
                        cache: true
                    });
                });

            } else if (_webLowerUrl == 'mdtimestable/timebalancev4') {
                $.ajax({
                    url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tablesorter/css/theme.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                    }
                }).done(function() {
                    if (typeof TIMEBALANCEV4 === 'undefined') {
                        $.ajax({
                            url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.widgets.min.js",
                            dataType: "script",
                            cache: true,
                            async: false
                        });
                        $.ajax({
                            url: "assets/custom/addon/plugins/tablesorter/js/widgets/widget-sortTbodies.js",
                            dataType: "script",
                            cache: true,
                            async: false
                        });
                        $.ajax({
                            url: "assets/custom/addon/plugins/jquery-easyui/datagrid-automergecells.js",
                            dataType: "script",
                            cache: true,
                            async: false
                        });
                        $.ajax({
                            url: "middleware/assets/js/time/timeV4/timeBalanceV4.js",
                            dataType: "script",
                            cache: false,
                            async: false
                        });
                    }
                });

            } else if (_webLowerUrl == 'mdtime/timebalance' || _webLowerUrl == 'mdtime/golomttimebalance' || _webLowerUrl == 'mdtime/timebalance_new' || _webLowerUrl == 'mdtime/golomttimebalancenew' || _webLowerUrl == 'mdtime/timebalance_mod') {
                $.ajax({
                    url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tablesorter/css/theme.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                    }
                }).done(function() {
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.widgets.min.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/widgets/widget-sortTbodies.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "middleware/assets/js/time/tnaTimeBalance.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                });

            } else if (_webLowerUrl == 'mdtime/timeemployeeplan' || _webLowerUrl == 'mdtime/golomttimeemployeeplan' || _webLowerUrl == 'mdtime/timeemployeeplan/1') {
                $.ajax({
                    url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tablesorter/css/theme.bootstrap.css"/>');
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                    }
                }).done(function() {
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/jquery.tablesorter.widgets.min.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "assets/custom/addon/plugins/tablesorter/js/widgets/widget-sortTbodies.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                    $.ajax({
                        url: "middleware/assets/js/time/tnaplan.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                });
            } else if (_webLowerUrl == 'mdtimestable/timeemployeeplanv2') {
                if (typeof tnaTimeEmployeePlanData === 'undefined') {
                    $.ajax({
                        url: "middleware/assets/js/time/timePlanV2.js?v="+Date.now(),
                        dataType: "script",
                        cache: true,
                        async: false,
                        beforeSend: function() {
                            $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                        }
                    }).done(function() {
                    });
                }
            } else if (_webLowerUrl == 'nddbookprint') {
                $.ajax({
                    url: "middleware/assets/js/salary/ndd.js",
                    dataType: 'script',
                    cache: false,
                    async: false
                }).done(function() {
                    nddBookPrint(elem, 'social');
                });
                return;
            } else if (_webLowerUrl == 'emddbookprint') {
                $.ajax({
                    url: "middleware/assets/js/salary/ndd.js",
                    dataType: 'script',
                    cache: false,
                    async: false
                }).done(function() {
                    nddBookPrint(elem, 'medical');
                });
                return;
            } else if (_webLowerUrl == 'mdpos') {
                $.ajax({
                    url: "assets/custom/addon/plugins/jquery-fixedheadertable/jquery.fixedheadertable.min.js",
                    dataType: "script",
                    cache: true,
                    async: false,
                    beforeSend: function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/css/pos/style.css"/>');
                    }
                }).done(function() {
                    $.ajax({
                        url: "assets/custom/addon/plugins/scannerdetection/jquery.scannerdetection.js",
                        dataType: "script",
                        cache: true,
                        async: false
                    });
                });
            } else if (_webLowerUrl == 'mdlayout/treetemplate/' || _webLowerUrl == 'mdlayout/treetemplate') {
                $.ajax({
                    url: "assets/core/js/plugins/visualization/d3/d3.min.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
            } else if (_webLowerUrl == 'mdasset/intranet13' || _webLowerUrl == 'mdasset/intranet15') {
                $.ajax({
                    url: "assets/core/js/plugins/visualization/echarts/echarts.min.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/charts/echarts/pies_donuts.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/charts/echarts/areas.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/charts/echarts/columns_waterfalls.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/visualization/d3/d3.min.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/visualization/d3/d3_tooltip.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/forms/styling/switchery.min.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/core/js/plugins/pickers/daterangepicker.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/custom/js/dashboard.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'middleware/assets/css/intranet/style.v'+ getUniqueId(1) +'.css"/>');
                
            } else if (_webLowerUrl === 'government/mail/' 
                    || _webLowerUrl === 'government/documentation/' 
                    || _webLowerUrl === 'government/unitdashboard/' 
                    || _webLowerUrl === 'government/agentdashboard/' 
                    || _webLowerUrl === 'mdintranet/intranet1' 
                    || _webLowerUrl === 'government/dashboardv1/') {
                
                $.getScript(URL_APP + 'assets/custom/addon/admin/pages/scripts/app.js', function() {});
                $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'middleware/assets/css/intranet/style.v'+ getUniqueId(1) +'.css"/>');
                
            } else if (_webLowerUrl === 'corp/invoice' || _webLowerUrl === 'corp/payload' || _webLowerUrl === 'corp/issue') {
                $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'projects/assets/custom/css/corp.v'+ getUniqueId(1) +'.css"/>');
            } else if (_webLowerUrl === 'mddoc/documentecmmaplistnew') {
                delete param.type;
                delete param.title;
                delete param.selectedRow;
            } else if (_webLowerUrl.indexOf('mdobject/dataview/') !== -1 && _webLowerUrl.indexOf('&dv[') !== -1) {
                
                var urlArr = _webLowerUrl.split('mdobject/dataview/');
                var urlAndArr = urlArr[1].split('&');
                var dvId = urlAndArr[0];
                var dataGridLink = window['objectdatagrid_' + dvId];
                
                if (typeof dataGridLink !== 'undefined') {
                    
                    try {
                        
                        var criteria = urlArr[1].replace(dvId + '&', '');
                        criteria = criteria.replace(/dv\[/g, '').trim();
                        criteria = criteria.replace(/\]/g, '').trim();
                        var criteriaJson = JSON.stringify(qryStrToObj(criteria));
                        var opts = dataGridLink.datagrid('options');
                        var dvSearchParam = opts.queryParams;
                        
                        dvSearchParam['uriParams'] = criteriaJson;

                        appMultiTabOpenTab(dvId);
                        dataViewLoadByElement(dataGridLink, dvSearchParam);
                        
                        var offsetTop = $('#object-value-list-' + dvId).offset().top;
                        
                        if (offsetTop > 300) {
                            $('html, body').animate({
                                scrollTop: offsetTop
                            }, 500);
                        }
                        
                    } catch (e) { console.log(e); }
                    
                    return;
                }
            }
            
            $.ajax({
                type: 'post',
                url: param.weburl,
                data: param,
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    try {
                        var jsonObj = JSON.parse(data);
                        if ('Html' in Object(jsonObj)) {
                            $div.empty().append(jsonObj.Html + '<div class="clearfix"/>');
                        } else {
                            if ('html' in Object(jsonObj)) {
                                $div.empty().append(jsonObj.html + '<div class="clearfix"/>');
                            } else {
                                $div.empty().append(data + '<div class="clearfix"/>');
                            }
                        }
                    } catch (err) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }

                    if (typeof callback === 'function') {
                        if (typeof jsonObj === 'undefined') {
                            jsonObj = {};
                        }
                        callback($div, param, jsonObj);
                    }
                }
            }).done(function(data) {

                if (!isTabReload || tabElementLength == 0) {
                    $ul.append($li);
                    $container.append($div);
                }
                
                Core.initAjax($div);
                $a.tab('show');
                if (getConfigValue('showReturnErrorText') == '1') {
                    try {
                        setTimeout(function() {
                            var jsonObj = JSON.parse(data);
                            if ('status' in Object(jsonObj) && jsonObj['status'] === 'error') {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: jsonObj['text'],
                                    type: jsonObj['status'],
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                                multiTabCloseConfirm($li.find('a.active'), '1');
                                if (typeof param['dataViewId'] !== 'undefined') {
                                    $('a[href="#'+ $('div.div-objectdatagrid-' + param['dataViewId']).closest('.tab-pane').attr('id') +'"]').trigger('click')
                                }
                                return false;
                            }
                        }, 50)
    
                    } catch (err) {
                        console.log(err);
                    }
                }
                Core.unblockUI();
            });
        }
        
        updateMultiTabs();
    }
}
function appMultiTabBusinessProcess(htmlContent, metaDataId, title) {
    
    if (typeof sysTabLimitCount !== 'undefined' && sysTabLimitCount) {
        var $sysTabLi = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs > li');
        PNotify.removeAll();
        if ($sysTabLi.length == sysTabLimitCount) {
            new PNotify({
                title: 'Warning',
                text: plang.get('sysTabLimitCountInfoMsg'),
                type: 'warning',
                addclass: 'pnotify-center',
                sticker: false
            });
            return;
        }
    }
    
    if (typeof vr_top_menu !== 'undefined' && vr_top_menu) {

        var $tabMainContainer = $("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");

        if ($tabMainContainer.length == 0) {
            $("div.pf-header-main-content").empty().append('<div class="col-md-12">' +
                '<div class="card light shadow card-multi-tab">' +
                '<div class="card-body">' +
                '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

            $('div.m-tab').empty().append('<div class="card-header header-elements-inline tabbable-line tabbable-tabdrop">' +
                '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                '</div>');

            $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
        }

    } else {
        var $tabMainContainer = $("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");

        if ($tabMainContainer.length == 0) {
            $("div.pf-header-main-content").empty().append('<div class="col-md-12">' +
                '<div class="card light shadow card-multi-tab">' +
                '<div class="card-header header-elements-inline tabbable-line tabbable-tabdrop">' +
                '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                '<div class="header-elements">' +
                '<div class="list-icons">' +
                '<a class="list-icons-item" data-action="fullscreen"></a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="card-body">' +
                '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

            $tabMainContainer = $('body').find("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
        }
    }

    if ($tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']").length > 0) {
        var $container = $('body').find("div.card-multi-tab > div.card-body > div.card-multi-tab-content");

        $container.find("div#app_tab_" + metaDataId).empty().append(htmlContent);
        $tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']").tab('show');

    } else {
        
        Core.blockUI({message: 'Loading...', boxed: true});
        
        title = ucfirst((title).toLowerCase());
        
        var $ul = $tabMainContainer;
        var $container = $("div.card-multi-tab > div.card-body > div.card-multi-tab-content");
        var $li = $('<li />', { "class": 'nav-item',"data-type": 'process' });

        var $a = $('<a />', {
            "class": 'nav-link',
            "href": '#app_tab_' + metaDataId,
            "data-toggle": 'tab',
            "title": title,
            "html": '<i class="fa fa-caret-right"></i> ' + title + '<span><i class="fa fa-times-circle"></i></span>'
        });

        $li.append($a);

        var $div = $('<div />', {
            "id": 'app_tab_' + metaDataId,
            "class": "tab-pane"
        });

        $div.empty().append(htmlContent + '<div class="clearfix"/>');
        $ul.append($li);

        $container.append($div).promise().done(function() {
            $a.tab('show');
            Core.initBPAjax($div);
            Core.unblockUI();
        });
    }

    $(".scroll-to-top").trigger("click");

    return;
}
function appMultiTabByContent(param, callback) {
    
    if (typeof sysTabLimitCount !== 'undefined' && sysTabLimitCount) {
        var $sysTabLi = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs > li');
        PNotify.removeAll();
        if ($sysTabLi.length == sysTabLimitCount) {
            new PNotify({
                title: 'Warning',
                text: plang.get('sysTabLimitCountInfoMsg'),
                type: 'warning',
                addclass: 'pnotify-center',
                sticker: false
            });
            return;
        }
    }
    
    var metaDataId = param.metaDataId;

    if (typeof vr_top_menu !== 'undefined' && vr_top_menu) {

        var $tabMainContainer = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        if ($tabMainContainer.length == 0) {
            $("div.pf-header-main-content").empty().append('<div class="col-md-12">' +
                '<div class="card light shadow card-multi-tab">' +
                '<div class="card-body">' +
                '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

            $("div.m-tab").empty().append('<div class="card-header header-elements-inline tabbable-line">' +
                '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                '</div>');

            $tabMainContainer = $('body').find('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        }
    } else {
        var $tabMainContainer = $('div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        if ($tabMainContainer.length == 0) {
            $("div.pf-header-main-content").empty().append('<div class="col-md-12">' +
                '<div class="card light shadow card-multi-tab">' +
                '<div class="card-header header-elements-inline tabbable-line">' +
                '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                '<div class="header-elements">' +
                '<div class="list-icons">' +
                '<a class="list-icons-item" data-action="fullscreen"></a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="card-body">' +
                '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

            $tabMainContainer = $('body').find('div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        }
    }

    var $container = $('div.card-multi-tab > div.card-body > div.card-multi-tab-content');
    var $div = $('<div />', {
        "id": 'app_tab_' + metaDataId,
        "class": "tab-pane"
    });
    var $tabElement = $tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']");
    
    param.title = ucfirst((param.title).toLowerCase());
    
    if ($tabElement.length > 0) {
        
        if (param.hasOwnProperty('tabNameReload') && param.tabNameReload) {
            $tabElement.html('<i class="fa fa-caret-right"></i> ' + param.title + '<span><i class="fa fa-times-circle"></i></span>');
        }

        if (param.type == 'newprocess' || param.type == 'filepreview') {

            $tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']").tab('show');
            Core.unblockUI();

        } else {

            $div.html(param.content + '<div class="clearfix"/>').promise().done(function() {
                $container.find('#app_tab_' + metaDataId).empty().append($div);
                if (param.type == 'process') {
                    Core.initBPAjax($div);
                } else {
                    Core.initAjax($div);
                }
                $tabMainContainer.find("a[href='#app_tab_" + metaDataId + "']").tab('show');
                Core.unblockUI();
            });
        }

    } else {

        var $ul = $tabMainContainer;
        var $li = $('<li />', {
            "class": 'nav-item',
            "data-type": param.type
        });
        var $a = $('<a />', {
            "class": 'nav-link',
            "href": '#app_tab_' + metaDataId,
            "data-toggle": 'tab',
            "title": param.title,
            "data-title": (param.hasOwnProperty('dataTitle') ? param.dataTitle : ''),
            "html": '<i class="fa fa-caret-right"></i> ' + param.title + '<span><i class="fa fa-times-circle"></i></span>'
        });

        $li.append($a);

        $div.empty().append(param.content + '<div class="clearfix"/>').promise().done(function() {
            $ul.append($li);
            $container.append($div);

            if (param.hasOwnProperty('weburl') && param.weburl == 'mdgl/edit_entry') {
                Core.initEntry($div);
            } else if (param.hasOwnProperty('weburl') && param.weburl == 'mdgl/view_entry') {
                Core.initEntry($div);
            } else {
                if (param.type == 'newprocess' || param.type == 'process') {
                    Core.initBPAjax($div);
                } else if (param.type != 'filepreview') {
                    Core.initAjax($div);
                }
            }
            
            if (typeof callback === 'function') {
                callback($div);
            }            

            $a.tab('show');
            Core.unblockUI();
        });
    }

    return;
}
function appMultiTabOpenTab(tabId) {
    if (typeof vr_top_menu !== 'undefined' && vr_top_menu) {
        var $tabMainContainer = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        if ($tabMainContainer.length == 0) {
            $tabMainContainer = $('body').find('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        } else if ($('a[href="#package-tab-' + tabId + '"]').length) {
            $('a[href="#package-tab-' + tabId + '"]').tab('show');
            Core.unblockUI();
            return;
        }
    } else {
        var $tabMainContainer = $('div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        if ($tabMainContainer.length == 0) {
            $tabMainContainer = $('body').find('div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs');
        }
    }

    if ($tabMainContainer.find("a[href='#app_tab_" + tabId + "']").length) {
        $tabMainContainer.find("a[href='#app_tab_" + tabId + "']").tab('show');
        Core.unblockUI();
    }

    return;
}
function elemHeight(elem, minus, plus) {
    var gridPosition = $(elem).position().top;
    var windowHeight = $(window).height();
    if (windowHeight < 750) {
        var gridHeight = 500;
    } else {
        var gridHeight = windowHeight - gridPosition - minus + plus;
    }
    return gridHeight;
}
function linkCheck(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status != 404;
}
function imageExist(url) {
    var img = new Image();
    img.src = url;
    return img.height != 0;
}
function onUserImgError(source) {
    source.src = "assets/custom/addon/admin/layout4/img/user.png";
    source.onerror = "";
    return true;
}
function onUserImgErrorDocDoc(source) {
    source.src = "assets/custom/addon/admin/layout4/img/user.png";
    source.onerror = "";
    return true;
}
function onBankImgError(source) {
    source.src = "assets/custom/addon/admin/layout4/img/user.png";
    source.onerror = "";
    return true;
}
function onFileImgError(source) {
    source.src = "assets/custom/addon/img/document/barimts.jpg";
    source.onerror = "";
    return true;
}
function onDocError(source) {
    source.src = "assets/custom/addon/img/document/barimts.jpg";
    source.onerror = "";
    return true;
}
function onUserLogoError(source) {
    source.src = "assets/custom/addon/img/user.png";
    source.onerror = "";
    return true;
}
function onUserImageError(source) {
    source.src = "assets/custom/img/user.png";
    source.onerror = "";
    return true;
}
function hasFileExtension(input) {
    if (!$(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx"])) {
        alert(plang.get('valid_file_info')+' '+plang.get('tm_please_select'));
        $(input).val('');
    }
}
function hasImportFileExtension(input) {
    if (!$(input).hasExtension(["txt"])) {
        alert('Please choose an txt file!');
        $(input).val('');
    }
}
function hasPhotoExtension(input) {
    if (!$(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "svg","x-png", "bmp"])) {
        alert(plang.get('msg_select_image'));
        $(input).val('');
    }
}
function hasExcelExtension(input) {
    if (!$(input).hasExtension(["xls", "xlsx"])) {
        alert('Please choose an XLS or XLSX file!');
        $(input).val('');
    }
}
function getRowNumericValue(_thisRow, name, number) {
    if (_thisRow.find(name).length > 0) {
        if (_thisRow.find(name).val() == 0)
            return 0;
        return typeof number === 'undefined' ? _thisRow.find(name).autoNumeric('get') : Number(_thisRow.find(name).autoNumeric('get'));
    }
    return 0;
}
function pureNumberFormat(v) {
    return new Intl.NumberFormat().format(v);
}
function pureNumber(v) {
    return Number(v.replace(/[,]/g, ''));
}
function dateFormatter(format, val) {
    if (val !== "" && val !== null && val !== 'null' && val !== undefined && val !== 'undefined') {
        if (val === 'footer') {
            return '';
        }
        var timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;
        var match = timeRegex.test(val);
        if (match) {
            return val;
        }
        return date(format, strtotime(val));
    }
    return '';
}
// DE error print
// param 1: Object Ajax response data
// param 2: String Error table id
// param 3: String Window id
// param 4: String Child dtl key value
function printErrorDaEl(data, tableId, windowId, childDtl) {
    if (typeof data !== 'undefined' && typeof tableId !== 'undefined' && typeof windowId !== 'undefined') {
        var table = $('#' + tableId + '_wrapper', windowId).find('#' + tableId + ' tbody');
        var fixedCols = $('#' + tableId + '_wrapper', windowId).find('.DTFC_Cloned tbody');
        var childDtl = typeof childDtl === 'undefined' ? 'itemkey' : childDtl.toLowerCase();

        if (typeof data.message === 'object') {
            var errList = "<dt>" + data.text + "</dt>";
            table.find("tr").removeClass("validation-error-tr");
            fixedCols.find("tr").removeClass("validation-error-tr");
            $.each(data.message, function(key, val) {
                table.find("tr:eq(" + key + ")").addClass("validation-error-tr");
                fixedCols.find("tr:eq(" + key + ")").addClass("validation-error-tr");
                var _k = ++key;
                errList += "<dt>" + _k + ". мөрөн дээр дараах алдаа байна</dt>";
                if (typeof this.value !== 'undefined')
                    errList += "<dd class='ml10'>" + this.key + " " + this.value + "</dd>";
                else {
                    $.each(val, function(k, v) {
                        var _t = this.elements;
                        var _kk = ++k;
                        if (this.key.toLowerCase() !== childDtl)
                            errList += "<dd class='ml10'>" + _k + "." + _kk + " [" + this.key + "] - " + this.value + "</dd>";
                        else {
                            --_kk;
                            errList += "<dt class='ml10'>" + this.key + "</dt>";
                            $.each(_t, function(kk, vv) {
                                errList += "<dd class='ml20'>" + _k + "." + _kk + "." + (++kk) + " [" + this.key + "] - " + this.value + "</dd>";
                            });
                        }
                    });
                }
            });
            new PNotify({
                title: 'Error',
                text: '<dl>' + errList + '</dl>',
                type: 'error',
                sticker: false
            });
        } else {
            new PNotify({
                title: 'Error',
                text: data.message,
                type: 'error',
                sticker: false
            });
        }
    } else
        new PNotify({
            title: 'Error',
            text: 'printErrorDaEl() parameter дутуу байна!',
            type: 'error',
            sticker: false
        });
}
function metaPasswordShow(elem) {
    var $this = $(elem);
    if ($this.hasClass("show-password")) {
        $("#passwordHash").attr('type', 'password');
        $this.find('i').removeClass("fa-eye-slash");
        $this.find('i').addClass("fa-eye");
        $this.removeClass("show-password");
    } else {
        $("#passwordHash").attr('type', 'text');
        $this.find('i').removeClass("fa-eye");
        $this.find('i').addClass("fa-eye-slash");
        $this.addClass("show-password");
    }
}
function setNumberToFixed(num) {
    //return Number(Math.round(num+'e'+decimal_fixed_num)+'e-'+decimal_fixed_num);
    return Number(Number(num).toFixed(decimal_fixed_num).replace(/\.?0+$/, ''));
}
function numberToFixed(num) {
    /**
     * Бутархай тооны хөрвүүлэлт дээр энэ функцыг ашиглахгүй байна уу!
     * ex: 12167097326.13 тоон дээр шалгаж үзэж болно
     */
    return Number(num).toFixed(decimal_fixed_num).replace(/\.?0+$/, '');
}
function autoHeight_grow(element) {
    var offset = element.offsetHeight - element.clientHeight;
    element.style.height = (element.scrollHeight + offset) + "px";
}
function changePassword(no_nowpassword) {
    var $dialogName = '#dialog-change-password';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $($dialogName);

    $.ajax({
        type: 'post',
        url: 'profile/changePasswordForm',
        dataType: 'json',
        data: {no_nowpassword: no_nowpassword},
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                minWidth: 500,
                height: 'auto',
                modal: true,
                closeOnEscape: isCloseOnEscape,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        "class": 'btn btn-sm green-meadow',
                        click: function() {
                            
                            var $form = $("#form-change-password");
                            var minLength = 8;
                            
                            if (typeof $form.attr('minlength') != 'undefined' && $form.attr('minlength') != '') {
                                minLength = $form.attr('minlength');
                            }
                            
                            $.validator.addMethod(
                                "regex",
                                function(value, element, regexp) {
                                    if (regexp.constructor != RegExp) {
                                        regexp = new RegExp(regexp);
                                    } else if (regexp.global) {
                                        regexp.lastIndex = 0;
                                    }
                                    return this.optional(element) || regexp.test(value);
                                },
                                plang.getDefault('user_requirements_password', 'Хамгийн багадаа '+minLength+' тэмдэгт, том жижиг үсэг, тоо болон тусгай тэмдэгт оролцсон байх')
                            );

                            $form.validate({
                                rules: {
                                    currentPassword: {
                                        required: true
                                    },
                                    newPassword: {
                                        required: true,
                                        minlength: minLength,
                                        regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})'
                                    },
                                    confirmPassword: {
                                        required: true,
                                        minlength: minLength,
                                        equalTo: "#newPassword",
                                        regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})'
                                    }
                                },
                                messages: {
                                    currentPassword: {
                                        required: plang.get('user_insert_password')
                                    },
                                    newPassword: {
                                        required: plang.get('user_insert_password'),
                                        minlength: plang.get('user_minlenght_password')
                                    },
                                    confirmPassword: {
                                        required: plang.get('user_insert_password'),
                                        minlength: plang.get('user_minlenght_password'),
                                        equalTo: plang.get('user_equal_password')
                                    }
                                }
                            });

                            if ($form.valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'profile/changePassword',
                                    data: $form.serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status === 'success') {
                                            $dialog.dialog("close");
                                        }
                                        Core.unblockUI();
                                    },
                                    error: function() {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        "class": 'btn btn-sm blue-hoki',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}
function changeUsername() {
    var $dialogName = '#dialog-change-username';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $($dialogName);

    $.ajax({
        type: 'post',
        url: 'profile/changeUsernameForm',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                minWidth: 500,
                height: 'auto',
                modal: true,
                closeOnEscape: isCloseOnEscape,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        "class": 'btn btn-sm green-meadow',
                        click: function() {
                            if ($("#form-change-username").valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'profile/changeUsername',
                                    data: $("#form-change-username").serialize(),
                                    dataType: "json",
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status === 'success') {
                                            $dialog.dialog("close");
                                        }
                                        Core.unblockUI();
                                    },
                                    error: function() {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        "class": 'btn btn-sm blue-hoki',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}
function convertFormatNumber(value) {
    if (!isNaN(value)) {
        var num = Number(value);
        return formatNumberToString(num);
    } else {
        var num = Number(0);
        return formatNumberToString(num);
    }
}
function formatNumberToString(number) {
    return number.toFixed(2).replace(/./g, function(c, i, a) {
        return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
    });
}
function createDialogOverlay(zIndex) {
    $('<div class="ui-widget-overlay" style="z-index: ' + (zIndex - 1) + '"></div>').appendTo("body");
}
function replaceInnerHTML(oldDiv, html) {
    var newDiv = oldDiv.cloneNode(false);
    newDiv.innerHTML = html;
    oldDiv.parentNode.replaceChild(newDiv, oldDiv);
}
function convertDataElementToArray(dataElement) {
    var array = {};

    for (var i = 0; i < dataElement.length; i++) {
        array[dataElement[i]['key']] = $.trim(dataElement[i]['value']);
    }

    return array;
}
function mergeObjs(def, obj) {
    if (typeof obj == 'undefined') {
        return def;
    } else if (typeof def == 'undefined') {
        return obj;
    }
    for (var i in obj) {
        if (obj[i] != null && obj[i].constructor == Object) {
            def[i] = mergeObjs(def[i], obj[i]);
        } else {
            def[i] = obj[i];
        }
    }
    return def;
}
function getUniqueId(prefix) {
    var d = new Date().getTime();
    d += (parseInt(Math.random() * 100)).toString();
    if (undefined === prefix) {
        prefix = 'uid-';
    } else if (prefix === 'no') {
        prefix = '';
    }
    d = prefix + d;
    return d;
}
function capitalizeFirstLetter(string) {
    if (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    return '';
}
function qryStrToObj(str) {
    try {
        var pairs = str.split('&');
        var result = {};
        pairs.forEach(function(pair) {
            pair = pair.split('=');
            var name = pair[0];
            var value = pair[1];
            if (name.length) {
                if (result[name] !== undefined) {
                    if (!result[name].push) {
                        result[name] = [result[name]];
                    }
                    result[name].push(value || '');
                } else {
                    result[name] = value || '';
                }
            }
        });
        return (result);
    } catch (e) {
        return null;
    }
}
function qryStrToObjUniqueKey(str) {
    try {
        var pairs = str.split('&');
        var result = {};
        pairs.forEach(function(pair) {
            pair = pair.split('=');
            var name = pair[0];
            var value = pair[1];
            if (name.length && result[name] === undefined) {
                result[name] = value || '';
            }
        });
        return (result);
    } catch (e) {
        return null;
    }
}
function initfocusTextarea(elem) {
    var $this = $(elem);
    var $table = $this.closest('table');

    if ($table.hasClass('bprocess-table-row') || $table.hasClass('cool-row')) {
        $this.css('height', '58px');
        return;
    }

    var $parent = $this.parent(),
        elemWidth = $parent.width() + 2,
        position = $this.position();

    $parent.css({
        width: elemWidth,
        position: 'relative'
    });
    $this.css({
        position: 'absolute',
        width: elemWidth,
        left: position.left,
        top: position.top,
        height: '58px',
        'z-index': 100
    });
}
function initblurTextarea(elem) {
    var $this = $(elem);
    var $parent = $this.parent();
    $this.css({
        position: '',
        left: '',
        top: '',
        'z-index': '',
        height: '28px'
    });
    $parent.css({
        position: ''
    });
}
function disableScrolling() {
    var x = window.scrollX;
    var y = window.scrollY;
    window.onscroll = function() { window.scrollTo(x, y); };
}
function enableScrolling() {
    window.onscroll = function() {};
}
function sleepFunction(secs) {
    secs = (+new Date) + secs * 1000;
    while ((+new Date) < secs);
}
function clickItemFancyBox(elem, imgUrl) {
    if (imgUrl !== '') {

        $.fancybox.open({
            src: imgUrl,
            type: 'image',
            opts: {
                prevEffect: 'none',
                nextEffect: 'none',
                titlePosition: 'over',
                closeBtn: true,
                caption: function(instance, item) {
                    var caption = $(this).data('caption') || '';
                    caption = (caption.length ? caption + '<br />' : '') + '<a href="mdobject/downloadFile?file=' + (imgUrl).replace(URL_APP, '') + '&fDownload=1" >Татах</a>';

                    return caption;
                },
                // afterLoad: function() {
                //     this.title = '<a href="mdobject/downloadFile?file=' + (fileUrl).replace(URL_APP, '') + '&fDownload=1" target="_blank">Татах</a>';
                // },
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }
        });
        // $.fancybox({
        //     content: $('<img class="fancybox-image"/>').attr('src', imgUrl), 
        //     prevEffect: 'none',
        //     nextEffect: 'none',
        //     titlePosition: 'over',
        //     closeBtn: true,
        //     afterLoad: function() {
        //         this.title = '<a href="mdobject/downloadFile?file=' + (imgUrl).replace(URL_APP, '') + '&fDownload=1" target="_blank">Татах</a>';
        //     },
        //     helpers: {
        //         overlay: {
        //             locked: false
        //         }
        //     }
        // });
    }
    return;
}
function detectHtmlStr(htmlStr) {
    if (typeof htmlStr !== 'undefined' && htmlStr != null && /<(?=.*? .*?\/ ?>|br|hr|input|!--|wbr)[a-z]+.*?>|<([a-z]+).*?<\/\1>/i.test(htmlStr))
        return '';
    return htmlStr;
}
function htmlToStr(htmlStr) {
    var htmlStrde = html_entity_decode(htmlStr);
    return htmlStrde.replace(/<\/?[^>]+(>|$)/g, "");
}
$(window).resize(function() {
    $(".ui-dialog-content").dialog("option", "position", "top");
    updateMultiTabs();
});

var $breakTabs = [];
updateMultiTabs();
function updateMultiTabs() {
    try {
        
        var $mTab = $('.m-tab');
        var $ulinksMultiTab = $('.m-tab > .tabbable-line > ul.card-multi-tab-navtabs');
        var $uhlinksMultiTab = $('.m-tab .hidden-links');
        
        var $htmlMultiTabsNav = '<div class="card-header header-elements-inline tabbable-line multi-tab">'
                                    + '<ul class="nav nav-pills nav-tabs collapsed-multi-tabs">' //card-multi-tab-navtabs
                                        + '<div class="btn-group dropup tab_collapsed pr-1">'
                                            + '<button type="button" class="multi-tab-morebtn btn btn-secondary dropdown-toggle bg-transparent" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="icon-menu7 ml-2"></i></button>'
                                            + '<div class="hidden-links dropdown-menu dropdown-menu-right collapsed-tabs"></div>'
                                        + '</div>'
                                    + '</ul>'
                                + '</div>'
                                + '<style type="text/css">'
                                    + '.hidden-links.collapsed-tabs li a{border: none !important; background: none !important;}'
                                + '</style>';

        if (!$mTab.find('.multi-tab').length) {
            $($htmlMultiTabsNav).appendTo($mTab);
        }

        var $multiTabBtn = $('.m-tab .multi-tab-morebtn');
        var $availableSpace = $multiTabBtn.hasClass('hidden') ? $mTab.width()- 50 : $mTab.width() - $multiTabBtn.width() - 50;
        
        if ($ulinksMultiTab.width() > $availableSpace) {

            $breakTabs.push($ulinksMultiTab.children().last().width());
            
            $ulinksMultiTab.children().last().prependTo($uhlinksMultiTab).promise().done(function () {
                $uhlinksMultiTab.find('a').removeClass("active show ").attr('style', 'border:none !important; background: #FFF !important;');
                $uhlinksMultiTab.find('i.fa-times-circle').addClass("hidden");
            });

            if ($multiTabBtn.hasClass('hidden')) {
                $multiTabBtn.removeClass('hidden');
            }

        } else {
            
            if (typeof $breakTabs[$breakTabs.length - 1] !== 'undefined' && $availableSpace > $breakTabs[$breakTabs.length - 1]) {
                var $uhlinkFirstTab = $uhlinksMultiTab.children().first();
                
                $uhlinkFirstTab.find('i').removeClass("hidden");
                $uhlinkFirstTab.find('a').removeAttr('style');
                
                $uhlinkFirstTab.appendTo($ulinksMultiTab).promise().done(function () {
                    $breakTabs.pop();
                });
            }

            if ($breakTabs.length < 1) {
                $multiTabBtn.addClass('hidden');
            } else {
                $multiTabBtn.removeClass('hidden');
            }
        }

        if ($ulinksMultiTab.width() > $availableSpace) {
            updateMultiTabs();
        }
        
    } catch(err) {
        console.log('updateMultiTabs : ' + err);
    }
}
function objectGroupBy(array, group) {
    var hash = Object.create(null), result = [];

    array.forEach(function (a) {
        if (!hash[a[group]]) {
            hash[a[group]] = [];
            result.push(hash[a[group]]);
        }
        hash[a[group]].push(a);
    });
    
    return result;
}
function htmlToImageTagFilter(node) {
    var $node = $(node);
    return (!$node.hasClass('domtoimage-ignore') && !$node.hasClass('btn-group') && !$node.hasClass('link-card-more') && !$node.hasClass('criteria-object-dv'));
}
function isString(value) {
    return typeof value === 'string' || value instanceof String;
}
function isNumber(value) {
    return typeof value === 'number' && isFinite(value);
}
function isNumeric(str) {
    return (typeof str === 'number' && isFinite(str)) || (!isNaN(str) && !isNaN(parseFloat(str)));
}
function isArray(value) {
    return value && typeof value === 'object' && value.constructor === Array;
}
function isFunction(value) {
    return typeof value === 'function';
}
function isObject(value) {
    return value && typeof value === 'object' && value.constructor === Object;
}
function isNull(value) {
    return value === null;
}
function isUndefined(value) {
    return typeof value === 'undefined';
}
function isBoolean(value) {
    return typeof value === 'boolean';
}
function isRegExp(value) {
    return value && typeof value === 'object' && value.constructor === RegExp;
}
function isError(value) {
    return value instanceof Error && typeof value.message !== 'undefined';
}
function isDate(value) {
    return value instanceof Date;
}
function isSymbol(value) {
    return typeof value === 'symbol';
}
function bytesReadableFileSize(bytes, decimals) {
    if(bytes == 0) return '0 Bytes';
    var k = 1024,
       dm = decimals || 2,
       sizes = ['bytes', 'kb', 'mb', 'gb'],
       i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
function encodeEmojis(str) {
    var rex = /[\u{1f300}-\u{1f5ff}\u{1f900}-\u{1f9ff}\u{1f600}-\u{1f64f}\u{1f680}-\u{1f6ff}\u{2600}-\u{26ff}\u{2700}-\u{27bf}\u{1f1e6}-\u{1f1ff}\u{1f191}-\u{1f251}\u{1f004}\u{1f0cf}\u{1f170}-\u{1f171}\u{1f17e}-\u{1f17f}\u{1f18e}\u{3030}\u{2b50}\u{2b55}\u{2934}-\u{2935}\u{2b05}-\u{2b07}\u{2b1b}-\u{2b1c}\u{3297}\u{3299}\u{303d}\u{00a9}\u{00ae}\u{2122}\u{23f3}\u{24c2}\u{23e9}-\u{23ef}\u{25b6}\u{23f8}-\u{23fa}]/ug;
    var updated = str.replace(rex, match => '<emoji>&#x'+match.codePointAt(0).toString(16)+';</emoji>');
    
    var emoticons = {
        ':D': '<emoji>&#x1F603;</emoji>',
        ':d': '<emoji>&#x1F603;</emoji>',
        ':P': '<emoji>&#x1F60B;</emoji>',
        ':p': '<emoji>&#x1F60B;</emoji>',
        ':)': '<emoji>&#x1F642;</emoji>',
        '<3': '<emoji>&#x2764;&#xFE0F;</emoji>',
        '(Y)': '<emoji>&#x1F44D;</emoji>',
        '(y)': '<emoji>&#x1F44D;</emoji>',
        ':(': '<emoji>&#x1F641;</emoji>',
        ':O': '<emoji>&#x1F632;</emoji>',
        ':o': '<emoji>&#x1F632;</emoji>',
        ':*': '<emoji>&#x1F618;</emoji>',
        ';)': '<emoji>&#x1F609;</emoji>',
        ';(': '<emoji>&#x1F625;</emoji>',
        '(n)': '<emoji>&#x1F44E;</emoji>',
    };
    
    for (var smile in emoticons) {
        updated = updated.replace(smile, emoticons[smile]);
    }
  
    return updated;
}