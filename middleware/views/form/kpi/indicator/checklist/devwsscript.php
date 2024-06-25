<script type="text/javascript">
var devIndicatorId = '<?php echo $this->selectedRow['src_record_id']; ?>';
var devAppType = '<?php echo $this->selectedRow['product_type_id']; ?>';
var devAppId = '<?php echo $this->selectedRow['id']; ?>';
var devOpenIndicatorId = '<?php echo issetParam($this->selectedRow['openindicatorid']); ?>';
var $devRightSidebar = $('.mv-developer-workspace-right-sidebar');
    
$(function() { 
    
    setTimeout(function() {
        $('.mv-developer-workspace-card').css('height', $(window).height() - 150);
    }, 10);
    
    _devNavigationSidebar();
    _devBpMainPanelHeight();
    
    $('.mv-developer-workspace').on('click', '.add-indicator-btn', function() {
        _processPostParam = 'filterid='+devIndicatorId+'&defaultGetPf=1';
        if (devAppType == '1000') {
            _processPostParam += '&isMobile=1';
        }
        callWebServiceByMeta('17089191176619', true, '', false, {callerType: 'developer_workspace', isMenu: false, positionTop: 0}, undefined, undefined, undefined, function() { 
            _devNavigationSidebarReload();
        });
    });
    
    $('.mv-developer-workspace').on('click', '.add-excel-btn', function() {
        var opts = {isContextMenu: false, devIndicatorId: devIndicatorId, devAppType: devAppType, callbackFunction: '_devNavigationSidebarReload'};
        var $active = $('.mv-developer-workspace-sidebar').find('.mv-developer-workspace-indicator.active');
        
        if ($active.length) {
            var kpiTypeId = $active.attr('data-type-id');
            opts.parentMenuId = $active.attr('data-indicator-id');
        }
        
        createMvStructureFromFile(this, '', opts);
    });
    
    $('.mv-developer-workspace').on('click', '.app-edit-btn', function() {
        var $a = $('<a />');
        $a.attr('class', 'no-dataview');
        $a.attr('data-rowdata', '{"ID": "'+devAppId+'"}');
                        
        manageKpiIndicatorValue($a, '2008', '16818054066034', true, {recordId: devAppId}, undefined, '_devWorkSpaceReload');
    });
    
    $('.mv-developer-workspace').on('click', '.preview-btn', function() {
        
        if (devAppType == '1000') {
            
            Core.blockUI({boxed: true, message: 'Loading...'});

            if ("WebSocket" in window) {
                var ws = new WebSocket("ws://localhost:58324/socket");

                ws.onopen = function () {
                    var currentDateTime = GetCurrentDateTime();
                    ws.send('{"command":"run_android_app", "dateTime":"' + currentDateTime + '", details: [{"key": "appname", "value": ""}]}');
                };

                ws.onmessage = function (evt) { 

                    var received_msg = evt.data;
                    var jsonData = JSON.parse(received_msg);

                    PNotify.removeAll();

                    if (jsonData.status == 'success') {
                        console.log(jsonData);
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: jsonData.description, 
                            type: 'error',
                            sticker: false
                        });
                    }

                    Core.unblockUI();
                };

                ws.onerror = function (event) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: event.code, 
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                };

                ws.onclose = function () {
                    console.log('Connection is closed...');
                    Core.unblockUI();
                };

            } else {

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: 'WebSocket NOT supported by your Browser!', 
                    type: 'error',
                    sticker: false
                });

                Core.unblockUI();
            }
            
        } else {
            var $active = $('.mv-developer-workspace-sidebar').find('.mv-developer-workspace-indicator.active');
            
            if ($active.length) {
                var kpiTypeId = $active.attr('data-type-id');
            
                if (kpiTypeId == '2020') {

                    var domainName = $('.mv-developer-workspace-right-sidebar').find('input[name="mvParam[URL]"]').val();
                    
                    if (domainName != '') {
                        iframeResponsiverDialog(domainName);
                    }
                } else if (devAppType == '1002') {

                    var domainName = $('.mv-developer-workspace-right-sidebar').find('input[name="mvParam[C2]"]').val();
                    
                    if (domainName != '') {
                        domainName = rtrim(domainName, '/');
                        
                        iframeResponsiverDialog(domainName);
                    }
                }
            }
        }
    });
    
    $('.mv-developer-workspace').on('click', 'a.mv-developer-workspace-indicator:not(.disabled)', function() {
        var $this = $(this), indicatorId = $this.attr('data-indicator-id');
        var $parent = $this.closest('.mv-developer-workspace-sidebar');
        var kpiTypeId = $this.attr('data-type-id');
        var $render = $('.mv-developer-workspace-render');
        
        $parent.find('.nav-link.active').removeClass('active');
        $this.addClass('active');
        
        if (kpiTypeId == '1120' || kpiTypeId == '1100') {
            
            $render.empty().append('<img src="assets/custom/img/landingpage/metaverse_developer_page.png" style="max-width: 100%">');
            _devRightSidebarProcessRender(null, indicatorId);
            return false;
            
        } /*else if (kpiTypeId == '2020') {
            $devRightSidebar.hide();
        }*/

        $.ajax({
            type: 'post',
            url: 'api/callDataview',
            data: {
                dataviewId: '17085010097299',
                criteriaData: {id: [{operator: '=', operand: indicatorId}]}
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },   
            success: function (data) {            
                if (data.status === 'success' && data.result[0]) {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdworkspace/renderWorkSpace',
                        data: {metaDataId: '17085010237759', dmMetaDataId: '16424766176721', selectedRow: data.result[0]},             
                        dataType: 'json',
                        success: function(data) {

                            if ($("link[href='middleware/assets/theme/" + data.theme + "/css/main.css']").length == 0) {
                                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.css"/>');
                            }

                            $render.empty().append(data.html).promise().done(function () {
                                Core.unblockUI();
                                $render.find('.close-btn').remove();
                                $render.find('ul.workspace-menu > li > a').css({"color":"#737373", "font-weight":"normal"});
                                Core.initAjax($render);                        
                            });
                        }
                    });  
                    
                    _devRightSidebarProcessRender(null, indicatorId);
                    
                    /*if (kpiTypeId != '2020') {
                        $devRightSidebar.show();
                    }*/
                } else {
                    Core.unblockUI();
                }
            }
        });                    
    }); 
    
    $('.mv-developer-workspace').on('click', '.div-objectdatagrid-1642419374729118 .datagrid-btable .datagrid-row', function() {
        var selectedRows = getDataViewSelectedRows('1642419374729118');
        var selectedRow = selectedRows[0];
        var mapId = selectedRow.mapid;
        var recordId = selectedRow.id;
        
        _devRightSidebarProcessRender(mapId, recordId);
    });
    
    $('.mv-developer-workspace').on('shown.bs.tab', 'a[href="#techdocument-tab"]', function() {
        
        var $techDoc = $('.mv-developer-workspace').find('#techdocument-tab');
        
        if ($techDoc.children().length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdobject/dataview/1710143065561114/0/json',
                data: {drillDownDefaultCriteria: 'filterindicatorid='+devIndicatorId, isIgnoreSetHeight: 1}, 
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (data.hasOwnProperty('Html')) {
                        $techDoc.empty().append(data.Html).promise().done(function () {
                            $techDoc.find('> .row > .col-md-12:eq(0)').remove();
                            Core.unblockUI();
                        });
                    } else {
                        $techDoc.removeClass('pl-3 pr-3');
                        $techDoc.empty().append(data.html).promise().done(function () {
                            
                            $techDoc.find('.dv-onecol-first-sidebar').css('height', $(window).height() - 90);
                            $techDoc.find('[data-part="dv-twocol-first-list"]').css('max-height', $(window).height() - 140);
                            
                            Core.unblockUI();
                        });
                    }
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });
        }
    });
    
    $('.mv-developer-workspace').on('click', '.reldetail', function() {
        var recordId = $(this).data('rowid');
        
        _devRightSidebarRelationProcessRender(recordId);
    });
    
    if (devOpenIndicatorId != '') {
        var $openIndicator = $('.mv-developer-workspace-indicator[data-indicator-id="'+devOpenIndicatorId+'"]');
        if ($openIndicator.length == 0) {
            $openIndicator = $('.mv-developer-workspace-indicator[data-indicator-id]:eq(0)');
        }
    } else {
        var $openIndicator = $('.mv-developer-workspace-indicator[data-indicator-id]:eq(0)');
    }
    
    if ($openIndicator.length) {
        $openIndicator.closest('li.nav-item-submenu').addClass('nav-item-open');
        $openIndicator.closest('ul.nav-group-sub').show();
        $openIndicator.trigger('click');
    }
});

function _devRightSidebarProcessRender(mapId, recordId) {
    if (mapId == null) {
            
        var processParam = {
            metaDataId: '17091132313599',
            dmMetaDataId: '1642419374729118', 
            isDialog: false,
            isSystemMeta: false,
            openParams: JSON.stringify({callerType: 'developer_workspace', isMenu: false, afterSaveNoAction: true}),
            fillDataParams: 'id='+recordId+'&defaultGetPf=1'
        };

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: processParam,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $devRightSidebar.empty().append(data.Html).promise().done(function () {
                    $devRightSidebar.find('.bp-btn-testcase, .bp-btn-back').hide();
                    $devRightSidebar.find('input[placeholder], textarea[placeholder]').attr('placeholder', '');
                    _devBpRightSidebarHeight($devRightSidebar);
                    Core.unblockUI();                    
                });
            }
        });
    } else {

        var processParam = {
            metaDataId: '17091133076179',
            dmMetaDataId: '1642419374729118', 
            isDialog: false,
            isSystemMeta: false,
            openParams: JSON.stringify({callerType: 'developer_workspace', isMenu: false, afterSaveNoAction: true}),
            fillDataParams: 'id='+recordId+'&defaultGetPf=1'
        };

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: processParam,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $devRightSidebar.empty().append(data.Html).promise().done(function () {
                    $devRightSidebar.find('.bp-btn-testcase, .bp-btn-back').hide();
                    _devBpRightSidebarHeight($devRightSidebar);
                    Core.unblockUI();                    
                });
            }
        });
    }
}

function _devRightSidebarRelationProcessRender(recordId) {
    var processParam = {
        metaDataId: '16660589496259',
        dmMetaDataId: '16425125540641', 
        isDialog: false,
        isSystemMeta: false,
        openParams: JSON.stringify({callerType: 'developer_workspace', isMenu: false, afterSaveNoAction: true}),
        fillDataParams: 'id='+recordId+'&defaultGetPf=1'
    };

    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: processParam,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $devRightSidebar.empty().append(data.Html).promise().done(function () {
                $devRightSidebar.find('.bp-btn-testcase, .bp-btn-back, .bp-btn-saveadd').hide();
                _devBpRightSidebarHeight($devRightSidebar);
                Core.unblockUI();                    
            });
        }
    });
}

function _devNavigationSidebar() {

    // Define default class names and options
    var navClass = 'mv-developer-workspace-sidebar',
        navItemClass = 'nav-item',
        navItemOpenClass = 'nav-item-open',
        navLinkClass = 'nav-link',
        navSubmenuClass = 'nav-group-sub',
        navSlidingSpeed = 250;

    // Configure collapsible functionality
    $('.' + navClass).each(function() {
        $(this).find('.' + navItemClass).has('.' + navSubmenuClass).children('.' + navItemClass + ' > ' + '.' + navLinkClass).not('.disabled').on('click', function (e) {
            e.preventDefault();

            // Simplify stuff
            var $target = $(this);

            // Collapsible
            if ($target.parent('.' + navItemClass).hasClass(navItemOpenClass)) {
                $target.parent('.' + navItemClass).removeClass(navItemOpenClass).children('.' + navSubmenuClass).slideUp(navSlidingSpeed);
            } else {
                $target.parent('.' + navItemClass).addClass(navItemOpenClass).children('.' + navSubmenuClass).slideDown(navSlidingSpeed);
            }

            // Accordion
            if ($target.parents('.' + navClass).data('nav-type') == 'accordion') {
                $target.parent('.' + navItemClass).siblings(':has(.' + navSubmenuClass + ')').removeClass(navItemOpenClass).children('.' + navSubmenuClass).slideUp(navSlidingSpeed);
            }
        });
    });

    // Disable click in disabled navigation items
    $(document).on('click', '.' + navClass + ' .disabled', function(e) {
        e.preventDefault();
    });
}
function _devNavigationSidebarReload() {
    $.ajax({
        type: 'post',
        url: 'mdform/developerWorkspaceSidebarReload',
        data: {indicatorId: devIndicatorId}, 
        success: function (data) {
            $('.mv-developer-workspace').find('.mv-developer-workspace-sidebar').empty().append(data).promise().done(function() {
                _devNavigationSidebar();
            });
        }
    });
}
function _devWorkSpaceReload(data) {
    devAppType = data.result.PRODUCT_TYPE_ID;
    $('.mv-developer-workspace').find('.app-name').text(data.result.BPA_NAME);
}
function _devBpMainPanelHeight() {
    $('.mv-developer-workspace-render').css('height', $(window).height() - 151);
}
function _devBpRightSidebarHeight($render) {
    var $layout = $render.find('.bp-layout');
    if ($layout.length) {
        $layout.css('height', $(window).height() - 210);
    }
}
function iframeResponsiverDialog(url) {
    var $dialogName = 'dialog-iframe-url';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), html = [], windowHeight = $(window).height() - 120;
    
    html.push('<div class="navbar navbar-dark bg-slate-700 navbar-component navbar-expand-xl" style="background-color: #3b4248;margin-bottom: 0px;">');
        html.push('<div class="navbar-collapse collapse justify-content-center">');
            html.push('<ul class="navbar-nav mv-developer-workspace-iframe-icons">');
                html.push('<li class="nav-item">');
                    html.push('<a href="javascript:;" class="navbar-nav-link" data-sizer="mobile" tabindex="-1">');
                        html.push('<i class="far fa-mobile"></i>');
                    html.push('</a>');
                html.push('</li>');
                html.push('<li class="nav-item">');
                    html.push('<a href="javascript:;" class="navbar-nav-link" data-sizer="tablet" tabindex="-1">');
                        html.push('<i class="far fa-tablet"></i>');
                    html.push('</a>');
                html.push('</li>');
                html.push('<li class="nav-item">');
                    html.push('<a href="javascript:;" class="navbar-nav-link" data-sizer="laptop" tabindex="-1">');
                        html.push('<i class="far fa-laptop"></i>');
                    html.push('</a>');
                html.push('</li>');
                html.push('<li class="nav-item">');
                    html.push('<a href="javascript:;" class="navbar-nav-link" data-sizer="desktop" tabindex="-1">');
                        html.push('<i class="far fa-desktop"></i>');
                    html.push('</a>');
                html.push('</li>');
                html.push('<li class="nav-item">');
                    html.push('<a href="javascript:;" class="navbar-nav-link active" data-sizer="wide" tabindex="-1">');
                        html.push('<i class="far fa-tv"></i>');
                    html.push('</a>');
                html.push('</li>');
            html.push('</ul>');
        html.push('</div>');
    html.push('</div>');
    
    html.push('<div class="sizer">');
        html.push('<div class="view-wrapper" style="height: '+windowHeight+'px">');
            html.push('<iframe src="'+url+'" frameborder="0" style="height: '+windowHeight+'px"></iframe>');
        html.push('</div>');
    html.push('</div>');

    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        dialogClass: 'dev-dialog-iframe', 
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'PAGE preview',
        width: $(window).width() - 5,
        height: $(window).height() - 5,
        modal: true,
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        }
    });
    $dialog.dialog('open');
    
    $dialog.on('click', 'a[data-sizer]', function() {
        var $this = $(this), sizer = $this.attr('data-sizer'), $parent = $this.closest('.navbar-nav');
        var $viewer = $dialog.find('.view-wrapper, iframe');
        var $iframe = $dialog.find('iframe');
        var windowHeight = $(window).height() - 120;
        
        $parent.find('.navbar-nav-link.active').removeClass('active');
        $this.addClass('active');
        
        $iframe.css('transform', 'scale(1)');
        
        if (sizer == 'mobile') {
            $viewer.css({'width': '360px', 'height': windowHeight});
        } else if (sizer == 'tablet') {
            $viewer.css({'width': '576px', 'height': windowHeight});
        } else if (sizer == 'laptop') {
            $viewer.css({'width': '992px', 'height': windowHeight});
        } else if (sizer == 'desktop') {
            $viewer.css({'width': '1200px', 'height': windowHeight});
        } else if (sizer == 'wide') {
            $viewer.css({'width': '100%', 'height': windowHeight});
        }
    });
}
</script>