/* global Core */

var Layout = function() {

    var layoutImgPath = 'admin/layout4/img/';
    var layoutCssPath = 'admin/layout4/css/';
    var resBreakpointMd = Core.getResponsiveBreakpoint('md');
    
    var handleSidebarAndContentHeight = function () {
        var content = $('.page-content');
        var sidebar = $('.page-sidebar');
        var body = $('body');
        var height;

        if (body.hasClass("page-footer-fixed") === true && body.hasClass("page-sidebar-fixed") === false) {
            var available_height = Core.getViewPort().height - $('.page-footer').outerHeight() - $('.page-header').outerHeight();
            var sidebar_height = sidebar.outerHeight();
            if (sidebar_height > available_height) {
                available_height = sidebar_height + $('.page-footer').outerHeight();
            }
            if (content.height() < available_height) {
                content.css('min-height', available_height);
            }
        } else {
            if (body.hasClass('page-sidebar-fixed')) {
                height = _calculateFixedSidebarViewportHeight();
                if (body.hasClass('page-footer-fixed') === false) {
                    height = height - $('.page-footer').outerHeight();
                }
            } else {
                var headerHeight = $('.page-header').outerHeight();
                var footerHeight = $('.page-footer').outerHeight();

                if (Core.getViewPort().width < resBreakpointMd) {
                    height = Core.getViewPort().height - headerHeight - footerHeight;
                } else {
                    height = sidebar.height() + 20;
                }

                if ((height + headerHeight + footerHeight) <= Core.getViewPort().height) {
                    height = Core.getViewPort().height - headerHeight - footerHeight;
                }
            }
            content.css('min-height', height);
        }
    };
    
    // Handle sidebar menu links
    var handleSidebarMenuActiveLink = function(mode, el) {
        var url = location.hash.toLowerCase();    

        var menu = $('.page-sidebar-menu');

        if (mode === 'click' || mode === 'set') {
            el = $(el);
        } else if (mode === 'match') {
            menu.find("li > a").each(function() {
                var path = $(this).attr("href").toLowerCase();       
                // url match condition         
                if (path.length > 1 && url.substr(1, path.length - 1) == path.substr(1)) {
                    el = $(this);
                    return; 
                }
            });
        }

        if (!el || el.size() == 0) {
            return;
        }

        if (el.attr('href').toLowerCase() === 'javascript:;' || el.attr('href').toLowerCase() === '#') {
            return;
        }        

        var slideSpeed = parseInt(menu.data("slide-speed"));
        var keepExpand = menu.data("keep-expanded");

        // disable active states
        menu.find('li.active').removeClass('active');
        menu.find('li > a > .selected').remove();

        if (menu.hasClass('page-sidebar-menu-hover-submenu') === false) {
            menu.find('li.open').each(function(){
                if ($(this).children('.sub-menu').size() === 0) {
                    $(this).removeClass('open');
                    $(this).find('> a > .arrow.open').removeClass('open');
                }                             
            }); 
        } else {
             menu.find('li.open').removeClass('open');
        }

        el.parents('li').each(function () {
            $(this).addClass('active');
            $(this).find('> a > span.arrow').addClass('open');

            if ($(this).parent('ul.page-sidebar-menu').size() === 1) {
                $(this).find('> a').append('<span class="selected"></span>');
            }
            
            if ($(this).children('ul.sub-menu').size() === 1) {
                $(this).addClass('open');
            }
        });

        if (mode === 'click') {
            if (Core.getViewPort().width < resBreakpointMd && $('.page-sidebar').hasClass("in")) { // close the menu on mobile view while laoding a page 
                $('.page-header .responsive-toggler').click();
            }
        }
    };
    
    var handleWorkSpaceSidebarMenuActiveLink = function(mode, el) {
        var url = location.hash.toLowerCase();    

        var menu = $('.page-sidebar-menu');

        if (mode === 'click' || mode === 'set') {
            el = $(el);
        } else if (mode === 'match') {
            menu.find("li > a").each(function() {
                var path = $(this).attr("href").toLowerCase();       
                // url match condition         
                if (path.length > 1 && url.substr(1, path.length - 1) == path.substr(1)) {
                    el = $(this);
                    return; 
                }
            });
        }

        if (!el || el.size() == 0) {
            return;
        }

        if (el.attr('href').toLowerCase() === 'javascript:;' || el.attr('href').toLowerCase() === '#') {
            return;
        }        

        var slideSpeed = parseInt(menu.data("slide-speed"));
        var keepExpand = menu.data("keep-expanded");

        // disable active states
        menu.find('li.active').removeClass('active');
        menu.find('li > a > .selected').remove();

        if (menu.hasClass('page-sidebar-menu-hover-submenu') === false) {
            menu.find('li.open').each(function(){
                if ($(this).children('.sub-menu').size() === 0) {
                    $(this).removeClass('open');
                    $(this).find('> a > .arrow.open').removeClass('open');
                }                             
            }); 
        } else {
             menu.find('li.open').removeClass('open');
        }

        el.parents('li').each(function () {
            $(this).addClass('active');
            $(this).find('> a > span.arrow').addClass('open');

            if ($(this).parent('ul.page-sidebar-menu').size() === 1) {
                $(this).find('> a').append('<span class="selected"></span>');
            }
            
            if ($(this).children('ul.sub-menu').size() === 1) {
                $(this).addClass('open');
            }
        });
    };

    // Handle sidebar menu
    var handleSidebarMenu = function() {
      var $pageSidebar = $('.page-sidebar');
        $pageSidebar.on('click', 'li > a', function(e) {

            var the = $(this);
            var menu = $('.page-sidebar-menu');
            var parent = $(this).parent().parent();
            
            if (the.hasClass('vr-menu-new-area')) {
                var theLi = the.closest("li");
                $('.page-sidebar-menu > li').not(theLi).hide();
                theLi.find("a > i").addClass("vr-main-menu-click").hide();
                theLi.find("a:eq(0)").prepend('<i class="fa fa-arrow-circle-left vr-main-menu-back" title="Back"></i>');
            }
            
            if (Core.getViewPort().width >= resBreakpointMd && $(this).parents('.page-sidebar-menu-hover-submenu').size() === 1) { // exit of hover sidebar menu
                return;
            }
            
            if ($(this).next().hasClass('sub-menu') === false) {
                if (Core.getViewPort().width < resBreakpointMd && $pageSidebar.hasClass("in")) { // close the menu on mobile view while laoding a page 
                    $('.page-header .responsive-toggler').click();
                }
                return;
            }
            
            if ($(this).next().hasClass('sub-menu always-open')) {
                return;
            }

            var sub = $(this).next();

            var autoScroll = menu.data("auto-scroll");
            var slideSpeed = parseInt(menu.data("slide-speed"));
            var keepExpand = menu.data("keep-expanded");

            if (keepExpand !== true) {
                parent.children('li.open').children('a').children('.arrow').removeClass('open');
                parent.children('li.open').children('.sub-menu:not(.always-open)').slideUp(slideSpeed);
                parent.children('li.open').removeClass('open');
            }

            var slideOffeset = -200;

            if (sub.is(":visible")) {
                $('.arrow', $(this)).removeClass("open");
                $(this).parent().removeClass("open");
                sub.slideUp(slideSpeed, function() {
                    if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                        if ($('body').hasClass('page-sidebar-fixed')) {
                            menu.slimScroll({
                                'scrollTo': (the.position()).top
                            });
                        } else {
                            Core.scrollTo(the, slideOffeset);
                        }
                    }
                });
            } else {
                $('.arrow', $(this)).addClass("open");
                $(this).parent().addClass("open");
                
                sub.slideDown(slideSpeed, function() {
                    if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                        if ($('body').hasClass('page-sidebar-fixed')) {
                            menu.slimScroll({
                                'scrollTo': (the.position()).top
                            });
                        } else {
                            Core.scrollTo(the, slideOffeset);
                        }
                    }
                });
                
                sub.promise().done(function() {
                    if (sub.height() == 0) {
                        sub.css('height', 'auto');
                    }
                });
            }

            e.preventDefault();
        });
        
        $pageSidebar.on('click', 'i.vr-main-menu-back', function(e) {
            var the = $(this);
            var theLi = the.closest("li");
            theLi.find(".vr-main-menu-back").remove();
            theLi.find(".vr-main-menu-click").show();
            $('.page-sidebar-menu > li').show();
            theLi.removeClass("open");
            theLi.find("ul.sub-menu").hide();
            theLi.find("span.open").removeClass("open");
            e.stopPropagation();
        });

        // handle ajax links within sidebar menu
        $pageSidebar.on('click', ' li > a.ajaxify', function(e) {
            e.preventDefault();
            Core.scrollTop();

            var url = $(this).attr("href");
            var menuContainer = $('.page-sidebar ul');
            var pageContent = $('.page-content');
            var pageContentBody = $('.page-content .page-content-body');

            menuContainer.children('li.active').removeClass('active');
            menuContainer.children('arrow.open').removeClass('open');

            $(this).parents('li').each(function() {
                $(this).addClass('active');
                $(this).children('a > span.arrow').addClass('open');
            });
            $(this).parents('li').addClass('active');

            if (Core.getViewPort().width < resBreakpointMd && $pageSidebar.hasClass("in")) { // close the menu on mobile view while laoding a page 
                $('.page-header .responsive-toggler').click();
            }

            Core.startPageLoading();

            var the = $(this);

            $.ajax({
                type: "GET",
                cache: false,
                url: url,
                dataType: "html",
                success: function(res) {

                    if (the.parents('li.open').size() === 0) {
                        $('.page-sidebar-menu > li.open > a').click();
                    }

                    Core.stopPageLoading();
                    pageContentBody.html(res);
                    Layout.fixContentHeight(); // fix content height
                    Core.initAjax(); // initialize core stuff
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Core.stopPageLoading();
                    pageContentBody.html('<h4>Could not load the requested content.</h4>');
                }
            });
        });

        // handle ajax link within main content
        $('.page-content').on('click', '.ajaxify', function(e) {
            e.preventDefault();
            Core.scrollTop();

            var url = $(this).attr("href");
            var pageContent = $('.page-content');
            var pageContentBody = $('.page-content .page-content-body');

            Core.startPageLoading();

            if (Core.getViewPort().width < resBreakpointMd && $pageSidebar.hasClass("in")) { // close the menu on mobile view while laoding a page 
                $('.page-header .responsive-toggler').click();
            }

            $.ajax({
                type: "GET",
                cache: false,
                url: url,
                dataType: "html",
                success: function(res) {
                    Core.stopPageLoading();
                    pageContentBody.html(res);
                    Layout.fixContentHeight(); // fix content height
                    Core.initAjax(); // initialize core stuff
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    pageContentBody.html('<h4>Could not load the requested content.</h4>');
                    Core.stopPageLoading();
                }
            });
        });
        
        createContentHtmlEvent($pageSidebar, $pageSidebar.find('.page-sidebar-back-menu').data('module-id'));
    };
    
    var handleWorkSpaceSidebarMenu = function() {
        $('.ws-page-sidebar').on('click', 'li > a', function(e) {
            if (Core.getViewPort().width >= resBreakpointMd && $(this).parents('.page-sidebar-menu-hover-submenu').size() === 1) { // exit of hover sidebar menu
                return;
            }

            if ($(this).next().hasClass('sub-menu') === false) {
                if (Core.getViewPort().width < resBreakpointMd && $('.ws-page-sidebar').hasClass("in")) { // close the menu on mobile view while laoding a page 
                    $('.page-header .responsive-toggler').click();
                }
                return;
            }

            if ($(this).next().hasClass('sub-menu always-open')) {
                return;
            }

            var parent = $(this).parent().parent();
            var the = $(this);
            var menu = $('.ws-page-sidebar .page-sidebar-menu');
            var sub = $(this).next();

            var autoScroll = menu.data("auto-scroll");
            var slideSpeed = parseInt(menu.data("slide-speed"));
            var keepExpand = menu.data("keep-expanded");

            if (keepExpand !== true) {
                parent.children('li.open').children('a').children('.arrow').removeClass('open');
                parent.children('li.open').children('.sub-menu:not(.always-open)').slideUp(slideSpeed);
                parent.children('li.open').removeClass('open');
            }

            var slideOffeset = -200;
            if (sub.is(":visible")) {
                $('.arrow', $(this)).removeClass("open");
                $(this).parent().removeClass("open");
                sub.slideUp(slideSpeed, function() {
                    if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                        if ($('body').hasClass('page-sidebar-fixed')) {
                            menu.slimScroll({
                                'scrollTo': (the.position()).top
                            });
                        } else {
                            Core.scrollTo(the, slideOffeset);
                        }
                    }
                });
            } else {
                $('.arrow', $(this)).addClass("open");
                $(this).parent().addClass("open");
                sub.slideDown(slideSpeed, function() {
                    if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                        if ($('body').hasClass('page-sidebar-fixed')) {
                            menu.slimScroll({
                                'scrollTo': (the.position()).top
                            });
                        } else {
                            Core.scrollTo(the, slideOffeset);
                        }
                    }
                });
                
                sub.promise().done(function() {
                    if (sub.height() == 0) {
                        sub.css('height', 'auto');
                    }
                });
            }
            e.preventDefault();
        });
    };

    // Helper function to calculate sidebar height for fixed sidebar layout.
    var _calculateFixedSidebarViewportHeight = function() {
        var sidebarHeight = Core.getViewPort().height - $('.page-header').outerHeight();
        if ($('body').hasClass("page-footer-fixed")) {
            sidebarHeight = sidebarHeight - $('.page-footer').outerHeight();
        }

        return sidebarHeight - 40;
    };

    // Handles fixed sidebar
    var handleFixedSidebar = function() {
        var menu = $('.page-sidebar-menu:not([data-no-scroll])');

        Core.destroySlimScroll(menu);

        if ($('.page-sidebar-fixed').size() === 0) {
            return;
        }

        if (Core.getViewPort().width >= resBreakpointMd) {
            menu.attr("data-height", _calculateFixedSidebarViewportHeight());
            Core.initSlimScroll(menu);
        }
    };

    // Handles sidebar toggler to close/hide the sidebar.
    var handleFixedSidebarHoverEffect = function () {
        var body = $('body');
        if (body.hasClass('page-sidebar-fixed')) {
            $('.page-sidebar').on('mouseenter', function () {
                if (body.hasClass('page-sidebar-closed')) {
                    $(this).find('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
                    $(this).find('.page-sidebar-menu-title').show();
                }
            }).on('mouseleave', function () {
                if (body.hasClass('page-sidebar-closed')) {
                    $(this).find('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
                    $(this).find('.page-sidebar-menu-title').hide();
                }
            });
        }
    };

    // Hanles sidebar toggler
    var handleSidebarToggler = function() {
        var body = $('body');
        if ($.cookie && typeof $.cookie('sidebar_closed') === 'undefined') {
            $.cookie('sidebar_closed', '0');
        }                
        if ($.cookie && $.cookie('sidebar_closed') === '1' && Core.getViewPort().width >= resBreakpointMd) {
            $('body').addClass('page-sidebar-closed');
            $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
            body.find('.page-sidebar-menu-title').hide();
            $("body").find(".widgets").hide();
        }

        // handle sidebar show/hide
        $('body').on('click', '.sidebar-toggler', function(e) {
            var sidebar = $('.page-sidebar');
            var sidebarMenu = $('.page-sidebar-menu');
            $(".sidebar-search", sidebar).removeClass("open");

            if (body.hasClass("page-sidebar-closed")) {
                body.removeClass("page-sidebar-closed");
                sidebarMenu.removeClass("page-sidebar-menu-closed");
                body.find('.page-sidebar-menu-title').show();
                $("body").find(".widgets").show();
                if ($.cookie) {
                    $.cookie('sidebar_closed', '0');
                }
            } else {
                body.addClass("page-sidebar-closed");
                sidebarMenu.addClass("page-sidebar-menu-closed");
                body.find('.page-sidebar-menu-title').hide();
                if (body.hasClass("page-sidebar-fixed")) {
                    sidebarMenu.trigger("mouseleave");
                }
                $("body").find(".widgets").hide();
                if ($.cookie) {
                    $.cookie('sidebar_closed', '1');
                }
            }
            
            $(window).trigger('resize');
        });

        handleFixedSidebarHoverEffect();

        // handle the search bar close
        $('.page-sidebar').on('click', '.sidebar-search .remove', function(e) {
            e.preventDefault();
            $('.sidebar-search').removeClass("open");
        });

        // handle the search query submit on enter press
        $('.page-sidebar .sidebar-search').on('keypress', 'input.form-control', function(e) {
            if (e.which == 13) {
                $('.sidebar-search').submit();
                return false; //<---- Add this line
            }
        });

        // handle the search submit(for sidebar search and responsive mode of the header search)
        $('.sidebar-search .submit').on('click', function(e) {
            e.preventDefault();
            if ($('body').hasClass("page-sidebar-closed")) {
                if ($('.sidebar-search').hasClass('open') === false) {
                    if ($('.page-sidebar-fixed').size() === 1) {
                        $('.page-sidebar .sidebar-toggler').click(); //trigger sidebar toggle button
                    }
                    $('.sidebar-search').addClass("open");
                } else {
                    $('.sidebar-search').submit();
                }
            } else {
                $('.sidebar-search').submit();
            }
        });

        // handle close on body click
        if ($('.sidebar-search').size() !== 0) {
            $('.sidebar-search .input-group').on('click', function(e) {
                e.stopPropagation();
            });

            $('body').on('click', function() {
                if ($('.sidebar-search').hasClass('open')) {
                    $('.sidebar-search').removeClass("open");
                }
            });
        }
    };
    
    // Hanles sidebar toggler
    var handleVrSidebarToggler = function() {
        if ($('.page-sidebar').length > 0) {
            var body = $('body');
            /*var sidebarMenuWrapper = $('.page-sidebar-wrapper');*/
            
            if ($.cookie && typeof $.cookie('vr_sidebar_closed') === 'undefined') {
                $.cookie('vr_sidebar_closed', '0');
            }                

            if ($.cookie && $.cookie('vr_sidebar_closed') === '1' && Core.getViewPort().width >= resBreakpointMd) {
                body.addClass("page-sidebar-closed");
                body.find('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
                body.find('.page-sidebar-menu-title').hide();
            }

            // handle sidebar show/hide
            $('body').on('click', 'a.vr-sidebar-toggler', function(e) {
                if ($('.page-sidebar').length > 0) {
                    if (!body.hasClass("page-sidebar-closed")) {
                        body.addClass("page-sidebar-closed");
                        body.find('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
                        body.find('.page-sidebar-menu-title').hide();
                        if ($.cookie) {
                            $.cookie('vr_sidebar_closed', '1');
                        }
                    } else {
                        body.removeClass("page-sidebar-closed");
                        body.find('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
                        body.find('.page-sidebar-menu-title').show();
                        if ($.cookie) {
                            $.cookie('vr_sidebar_closed', '0');
                        }
                    }
                    $(window).trigger('resize');
                }
            });
        }
    };

    // Handles the horizontal menu
    var handleHeader = function() {
        // handle search box expand/collapse        
        $('.page-header').on('click', '.search-form', function(e) {
            $(this).addClass("open");
            $(this).find('.form-control').focus();

            $('.page-header .search-form .form-control').on('blur', function(e) {
                $(this).closest('.search-form').removeClass("open");
                $(this).unbind("blur");
            });
        });

        // handle hor menu search form on enter press
        $('.page-header').on('keypress', '.hor-menu .search-form .form-control', function(e) {
            if (e.which == 13) {
                $(this).closest('.search-form').submit();
                return false;
            }
        });

        // handle header search button click
        $('.page-header').on('mousedown', '.search-form.open .submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest('.search-form').submit();
        });
    };

    // Handles the go to top button at the footer
    var handleGoTop = function() {
        var offset = 300;
        var duration = 300;

        if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) { // ios supported
            $(window).bind("touchend touchcancel touchleave", function(e) {
                if ($(this).scrollTop() > offset) {
                    $('.scroll-to-top').fadeIn(duration);
                } else {
                    $('.scroll-to-top').fadeOut(duration);
                }
            });
        } else { // general 
            $(window).scroll(function() {
                if ($(this).scrollTop() > offset) {
                    $('.scroll-to-top').fadeIn(duration);
                } else {
                    $('.scroll-to-top').fadeOut(duration);
                }
            });
        }

        $('.scroll-to-top').click(function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, duration);
            return false;
        });
    };
    var handleGoBottom = function() {
        var duration = 300;
        var $win = $(window);
        var $doc = $(document);
        var margin = 100;
        var height = $doc.height();
        var at_bottom = $win.scrollTop() + $win.height() > height - margin;

        if (!at_bottom) {
            $('.scroll-to-bottom').fadeIn(duration);
        } else {
            $('.scroll-to-bottom').fadeOut(duration);
        }
        
        $(window).scroll(function() {
            var at_bottom = $(window).scrollTop() + $(window).height() > $(document).height() - margin;
            if (!at_bottom) {
                $('.scroll-to-bottom').fadeIn(duration);
            } else {
                $('.scroll-to-bottom').fadeOut(duration);
            }
        });
        
        $('.scroll-to-bottom').click(function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(document).height()
            }, duration);
            return false;
        });
    };
    //* END:CORE HANDLERS *//

    return {

        // Main init methods to initialize the layout
        // IMPORTANT!!!: Do not modify the core handlers call order.

        initHeader: function() {
            handleHeader(); // handles horizontal menu    
        },

        setSidebarMenuActiveLink: function(mode, el) {
            handleSidebarMenuActiveLink(mode, el);
        },

        initSidebar: function() {
            //layout handlers
            handleFixedSidebar(); // handles fixed sidebar menu
            handleSidebarMenu(); // handles main menu
            handleSidebarToggler(); // handles sidebar hide/show
            handleVrSidebarToggler();

            if (Core.isAngularJsApp()) {      
                handleSidebarMenuActiveLink('match'); // init sidebar active links 
            }

            Core.addResizeHandler(handleFixedSidebar); // reinitialize fixed sidebar on window resize
        },
        
        initWorkSpaceSidebar: function() {
            handleWorkSpaceSidebarMenu(); // handles main menu
            
            if (Core.isAngularJsApp()) {      
                handleWorkSpaceSidebarMenuActiveLink('match'); // init sidebar active links 
            }
        },

        initContent: function() {
            return; 
        },

        initFooter: function() {
            handleGoTop(); //handles scroll to top functionality in the footer
            handleGoBottom();
        },
        initFooterBottom: function() {
            handleGoBottom(); //handles scroll to top functionality in the footer
        },

        init: function () {            
            this.initHeader();
            this.initSidebar();
            this.initContent();
            this.initFooter();
        },

        //public function to fix the sidebar and content height accordingly
        fixContentHeight: function() {
            return;
        },

        initFixedSidebarHoverEffect: function() {
            handleFixedSidebarHoverEffect();
        },

        initFixedSidebar: function() {
            handleFixedSidebar();
        },

        getLayoutImgPath: function() {
            return Core.getAssetsPath() + layoutImgPath;
        },

        getLayoutCssPath: function() {
            return Core.getAssetsPath() + layoutCssPath;
        }
    };

}();