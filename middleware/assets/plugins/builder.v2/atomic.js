var builderWidgetManagment = true;
var 
    isTest = 1;
    _addWidth = 0;
    _addHeight = 0;

var _oldIcon = new Array();
var Atomic = function() {
    
    var main = function (el) {
        var _btns = actionBtns();
        actionButtons = $(_btns);
        var _elementSelector = $(el).find('#pageList > div');
        _elementSelector.find('.standart-section > .action-btns').remove();
        _elementSelector.find('.standart-section').append(actionButtons);

        $(el).closest('.layout-builder-v0').find('.ui-sortable-handle').each(function (i, r) { 
            if ($(r).children().length < 1) {
                $(r).remove();
            }
        })
    };

    var pageEmpty = function (el) {
        if ($(el).find('#pageList > div').size() == 0 ) {
            $(el).find('#start').show();
            $(el).find('#pageList').addClass('empty');
        } else {
            $(el).find('#start').hide();
            $(el).find('#pageList').removeClass('empty');
        }
    };
    
    var allEmpty = function (el) {
    
        var allEmpty = false;
    
        if ($(el).find('#pageList li').size() == 0 ) {
            allEmpty = true;
        } else {
            allEmpty = false;
        }
    
        if( allEmpty ) {
            /* 
            $(el).find('.saveLayoutBtn').each(function(){
                $(this).addClass('disabled');
            });
            */
        } else {
            /* $(el).find('.saveLayoutBtn').each(function(){
                $(this).removeClass('disabled');
            }); */
        }
    
    };
    
    var makeDraggable = function (mainId, el) {
        
        $(mainId).find('#elements li:not(.chooseWidget)').each(function() {
            $(this).draggable({
                helper: function() {
                    return $('<div style="height: 100px; width: 300px; background: #F9FAFA; box-shadow: 5px 5px 1px rgba(0,0,0,0.1); text-align: center; line-height: 100px; font-size: 28px; color: #16A085"><span class="fa fa-list"></span></div>');
                },
                revert: 'invalid',
                appendTo: 'body',
                connectToSortable: el,
                stop: function(event, ui){
                    $(this).addClass('selected-item');
                    pageEmpty(el);
                    allEmpty(el);
                },
                start: function() {
                    var mainSelector = $(this).closest('.build-page');
                    mainSelector.find('.selected-item').removeClass('selected-item');
                }
            });
        });
        
        $(mainId).find('#left-panel #control-studio li.nav-item:not(.nav-item-submenu)').each(function() {
            $(this).draggable({
                helper: function(e) {
                    var contentHtml = JSON.parse(html_entity_decode($(e.currentTarget).data('contenthtml')), 'ENT_QUOTES');
                    var htmlObject = $(contentHtml);
                    return htmlObject;
                },
                revert: 'invalid',
                appendTo: 'body',
                connectToSortable: el,
                stop: function(event, ui){
                    $(this).addClass('selected-item');
                    pageEmpty(el);
                    allEmpty(el);
                },
                start: function() {
                    var mainSelector = $(this).closest('.build-page');
                    mainSelector.find('.selected-item').removeClass('selected-item');
                }
            });
        }); 
        
    };

    var makeSortable = function (el, uniqId) {
        console.log('not use');
        return false;
        
        console.log(el);
        el.droppable({
            drop: function( event, ui ) {
                var elementUniqId = getUniqueId('no');
                var contentHtml = JSON.parse(html_entity_decode(ui.draggable.data('contenthtml')), 'ENT_QUOTES');
                $('.temp_' + uniqId).empty().append(contentHtml).promise().done(function () {
                    $('.temp_' + uniqId).children().attr('data-id', elementUniqId);
                    $('.temp_' + uniqId).children().attr('data-target', 'target');
                    
                    $(event.originalEvent.target).append($('.temp_' + uniqId).html()).promise().done(function () {
                        $('.temp_' + uniqId).empty();
                        var elementName = ui.draggable.data('content');
                        
                        $('#structureTreeView_' + uniqId).jstree().create_node(uniqId ,  { "id" : elementUniqId, "text" : elementName}, "last", function(){
                            console.log("added = " + elementUniqId);
                        });
                    });
                });
            }
        });
    };

    var render = function (uniqId) {
        if (typeof window['graphJson' + uniqId] !== 'undefined') {
            var grapJson = window['graphJson' + uniqId];
            var layoutSelector = $('#layout-builder'+ uniqId),
                page = layoutSelector.find('#page' + uniqId);
            
            $.each(grapJson, function (key, rowData) {
                
                if (typeof rowData['html'] !== 'undefined' && rowData['html']) {
                    page.find('div[data-block-uniqid="'+ key +'"]').append(rowData['html']);
                }
                
                if (typeof rowData['widgetId'] !== 'undefined' && rowData['widgetId']) {
                    page.find('div[data-block-uniqid="'+ key +'"]').append('<iframe src="'+URL_APP + 'mdwidget/playWidgetStandart/' + rowData['widgetId'] +'/widget" scrolling="no" style="height: 100%; width: 100%;" frameborder="0"><iframe>');
                }

                switch (rowData['blockType']) {
                    case 'chart':
                        layoutSelector.find('div[data-block-uniqid="'+ key +'"]').append('<div id="itemDragin-'+key +'" class="row w-100 h-100"></div>').promise().done(function () {
                            if (typeof rowData['addintionalConfig'] !== 'undefined') {
                                var chartOption = JSON.parse(rowData['addintionalConfig']);
                                var chartDom = document.getElementById(chartOption['elemId']);
                                echarts.dispose(chartDom);
                                var myChart = echarts.init(chartDom);
                                chartOption && myChart.setOption(chartOption);
                                
                                setInterval(() => {
                                    myChart.resize();
                                }, 2000);
                            }
                        });
                        break;
                
                    default:
                        break;
                }
            });
        }
    };

    var selectWidget = function (uniqId, srcSection, trgSection) {
        var indicatorId = $('div[data-uniqid="'+ uniqId +'"]').find('input[name="indicatorId"]').val();
        var $trgSection = $(trgSection);
        if ($(srcSection).find('img[data-rowdata]').length < 1) {
            PNotify.removeAll();
            new PNotify({
                title: 'Санамж',
                text: 'Сонгосон <b>Section</b> шаардлагатай мэдээлэл олдсонгүй.',
                type: 'warning', 
                sticker: false
            });
            return false;
        }

        var rowData = JSON.parse($(srcSection).find('img[data-rowdata]').attr('data-rowdata'));
        var  frameUniqId = getUniqueId('no');
        $trgSection.attr('data-widgetid', rowData['id']);

        if ($trgSection.find('input[data-path="rowState"]').length > 0) {
            $trgSection.find('input[data-path="rowState"]').val('modified');
            $trgSection.find('textarea[data-path="trgindicatorid"]').val(rowData['id']);
            $trgSection.find('img').attr('src', $(srcSection).find('img[data-rowdata]').attr('src'));
        } else {
            var _html = '';
            _html += '<div class="indicatormap_data d-none">';
                _html += '<input type="hidden" data-path="rowState" name="rowState[]" value="added" />';
                _html += '<textarea data-path="srcindicatorid" name="param[pageindicatormap.srcindicatorid][][0]">'+ indicatorId +'</textarea>';
                _html += '<textarea data-path="trgindicatorid" name="param[pageindicatormap.trgindicatorid][][0]">'+ rowData['id'] +'</textarea>';
                _html += '<input type="hidden" data-path="semantictypeid" name="param[pageindicatormap.semantictypeid][][0]" value="69" />';
                if (window['indicatorMapKeys' + uniqId]) {
                    $.each(window['indicatorMapKeys' + uniqId], function (kIndex, kRow) {
                        if (kRow !== 'trgindicatorid' && kRow !== 'srcindicatorid'  && kRow !== 'semantictypeid') {
                            _html += '<textarea type="hidden" data-path="'+ kRow +'" name="param[pageindicatormap.'+ kRow +'][][0]"></textarea>';
                        }
                    })
                }
                /* _html += '<textarea data-path="ordernumber" name="param[pageindicatormap.ordernumber][][0]"></textarea>';
                _html += '<textarea data-path="id" name="param[pageindicatormap.id][][0]"></textarea>'; */
            _html += '</div>';
            _html += '<img src="'+$(srcSection).find('img[data-rowdata]').attr('src') + '" class="pull-left w-100"/>';
            $trgSection.addClass('iframe-section').empty().append(_html);
        }

        var _layoutBuilder = $trgSection.closest('.layout-builder-v0');
        _layoutBuilder.find('input[name="widgetId['+ $trgSection.attr('data-block-uniqid') +']"]').val(rowData['id']);
    };

    var selectHtml = function (uniqId, srcSection, trgSection) {
        var itemSelector = srcSection.find('.widgetTypes');
        var rowData = itemSelector.attr('data-rowdata');
        console.log(uniqId, rowData, itemSelector);
        var _html = mainHtml(uniqId, rowData, itemSelector);

        trgSection.append(_html).promise().done(function () {
            console.log('appended');
        });
    };  

    var mainHtml = function (uniqId, rowData, itemSelector) {
        var __html = '';
        var _mainBuilderPage = itemSelector.closest('.layout-builder-v0'),
            windowUniqId = _mainBuilderPage.data('uniqid');
        var dataSrc = htmlentities(JSON.stringify(window['setDefaultRowData' + windowUniqId]), 'ENT_QUOTES', 'UTF-8');
        switch (itemSelector.attr('data-src')) {
            case 'widget':
                html += '<div class="col-md-12 zoomer-cover iframe-section" data-block-uniqid="'+ uniqId +'">';
                    _html += '<div class="indicatormap_data d-none">';
                        _html += '<input type="hidden" data-path="rowState" name="rowState[]" value="modified" />';
                        _html += '<textarea data-path="srcindicatorid" name="param[pageindicatormap.srcindicatorid][][0]">'+ indicatorId +'</textarea>';
                        _html += '<textarea data-path="trgindicatorid" name="param[pageindicatormap.trgindicatorid][][0]">'+ rowData['id'] +'</textarea>';
                        if (window['indicatorMapKeys' + uniqId]) {
                            $.each(window['indicatorMapKeys' + uniqId], function (kIndex, kRow) {
                                if (kRow !== 'trgindicatorid' && kRow !== 'srcindicatorid'  && kRow !== 'semantictypeid') {
                                    _html += '<textarea type="hidden" data-path="'+ kRow +'" name="param[pageindicatormap.'+ kRow +'][][0]"></textarea>';
                                }
                            })
                        }
                    _html += '</div>';
                    html +=  '<img src="'+itemSelector.attr('src') + '" class="w-auto pull-left h-100 mx-auto" style="max-height: 330px;"/>';
                html+= '</div>';
                break;
            case 'stats':
                __html += '<div class="col standart-section zoomer-cover" data-section="stats" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                        $.each(window['setDefaultRowData' + windowUniqId], function (dataIndex, dataValue) {
                            __html += '<div class="col">';
                                __html += '<div class="text-center d-flex flex-column justify-content-center align-items-center py-3">';
                                    __html += '<div class="bs-icon-xl bs-icon-circle bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block mb-2 bs-icon lg">';
                                        __html += '<i class="fa fa fa-brain"></i>';
                                    __html += '</div>';
                                    __html += '<div class="px-3">';
                                        __html += '<h2 class="fw-bold mb-0">'+ dataValue['position1Value'] +'</h2>';
                                        __html += '<p class="mb-0">'+ dataValue['position2Value'] +'</p>';
                                    __html += '</div>';
                                __html += '</div>';
                            __html += '</div>';
                        });
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'badge':
                __html += '<div class="col standart-section zoomer-cover" data-section="badge" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                        __html += '<span class="badge badge-primary w-100">Primary</span>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'alert':
                __html += '<div class="col standart-section zoomer-cover" data-section="alert" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                            __html += '<div class="alert alert-primary border-0 alert-dismissible w-100">';
                                __html += '<button type="button" class="close" data-dismiss="alert"><span>×</span></button>';
                                __html += '<span class="font-weight-semibold">Morning!</span> We\'re glad to <a href="#" class="alert-link">see you again</a> and wish you a nice day.';
                            __html += '</div>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'progress':
                __html += '<div class="col standart-section zoomer-cover" data-section="progress" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                        __html += '<div class="progress w-100">';
                            __html += '<div class="progress-bar" style="width: 50%">';
                                __html += '<span class="sr-only">50% Complete</span>';
                            __html += '</div>';
                        __html += '</div>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'progress_circle':
                __html += '<div class="col standart-section zoomer-cover" data-section="progress_circle" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                        __html += '<div class="w-100">';
                            __html += '<div class="progress_circle" data-progress="36" style="--progress: 36deg;">36%</div>';
                        __html += '</div>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'button':
                __html += '<div class="col standart-section zoomer-cover" data-section="button" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                        __html += '<button type="button" class="btn btn-outline-primary w-100">Hoverable</button>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'logo':
                __html += '<div class="col standart-section zoomer-cover" data-section="logo" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="row" data-tagname="col" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2" style="width: 100%">';
                        __html += '<img src="projects/assets/elements/logo.png" data-rowdata=""/>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'accordion':
                __html += '<div id="accordion-group">';
                    __html += '<div class="card mb-0 rounded-bottom-0">';
                        __html += '<div class="card-header">';
                            __html += '<h6 class="card-title">';
                                __html += '<a data-toggle="collapse" class="text-default" href="#accordion-item-group1" aria-expanded="true">Accordion Item #1</a>';
                            __html += '</h6>';
                        __html += '</div>';
                        __html += '<div id="accordion-item-group1" class="collapse show" data-parent="#accordion-group" style="">';
                            __html += '<div class="card-body">';
                                __html += 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.';
                            __html += '</div>';
                        __html += '</div>';
                    __html += '</div>';
                    __html += '<div class="card mb-0 rounded-0 border-y-0">';
                        __html += '<div class="card-header">';
                            __html += '<h6 class="card-title">';
                                __html += '<a class="text-default collapsed" data-toggle="collapse" href="#accordion-item-group2" aria-expanded="false">Accordion Item #2</a>';
                            __html += '</h6>';
                        __html += '</div>';
                        __html += '<div id="accordion-item-group2" class="collapse" data-parent="#accordion-group" style="">';
                            __html += '<div class="card-body">';
                                __html += 'Тon cupidatat skateboard dolor brunch. Тesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda.';
                            __html += '</div>';
                        __html += '</div>';
                    __html += '</div>';
                    __html += '<div class="card rounded-top-0">';
                        __html += '<div class="card-header">';
                            __html += '<h6 class="card-title">';
                                __html += '<a class="text-default collapsed" data-toggle="collapse" href="#accordion-item-group3" aria-expanded="false">Accordion Item #3</a>';
                            __html += '</h6>';
                        __html += '</div>';
                        __html += '<div id="accordion-item-group3" class="collapse" data-parent="#accordion-group" style="">';
                            __html += '<div class="card-body">';
                                __html += '3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it.';
                            __html += '</div>';
                        __html += '</div>';
                    __html += '</div>';
                __html += '</div>';
                break;
            case 'table': 
                __html += '<div class="col standart-section zoomer-cover" data-section="table" data-block-uniqid="'+ getUniqueId('no') +'">';
                    __html += '<div class="table-responsive">';
                        __html += '<table class="table">';
                            __html += '<thead>';
                                __html += '<tr>';
                                    __html += '<th>Position1Value</th>';
                                    __html += '<th>Position2Value</th>';
                                __html += '</tr>';
                            __html += '</thead>';
                            __html += '<tbody data-tagname="tbody" data-itemcount="'+ window['setDefaultRowData' + windowUniqId].length +'" data-colcount="2">';
                            $.each(window['setDefaultRowData' + windowUniqId], function (dataIndex, dataValue) {
                                __html += '<tr>';
                                    __html += '<td>'+ dataValue['position1Value'] +'</td>';
                                    __html += '<td>'+ dataValue['position2Value'] +'</td>';
                                __html += '</tr>';
                            });
                            __html += '</tbody>';
                        __html += '</table>';
                    __html += '</div>';
                __html += '</div>';
                
                break;
            case 'col2':
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                break;
            case 'col3':
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                break;
            case 'col4':
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                break;
            default:
                __html += '<div class="col standart-section zoomer-cover" data-section="column" data-block-uniqid="'+ getUniqueId('no') +'"></div>';
                break;
        }
        return __html;
    }

    var actionBtns = function () {
        return '';
        var _btns = '<div class="action-btns position-absolute right-12 top-0">'
                /* _btns += '<button type="button" class="btn btn-secondary resetBlock" title="'+ plang.get('refresh_btn') +'" data-type="block"><i class="fa fa-refresh"></i></button>' */
                _btns += '<button type="button" class="btn btn-danger deleteBlock" title="'+ plang.get('delete_btn') +'" data-type="block"><span class="fa fa-trash"></span></button>'
                _btns += '<button type="button" class="btn btn-warning editBlock" title="'+ plang.get('edit_btn') +'" data-type="block"><i class="fa fa-edit"></i></button>'
                _btns += '<button type="button" class="btn btn-secondary copyBlock" title="'+ plang.get('copy_btn') +'" data-type="block"><i class="far fa-copy"></i></button>'
                /* _btns += '<button type="button" class="btn btn-secondary addRowBlock" title="'+ plang.get('addrow') +'" data-type="block"><i class="far fa-list"></i></button>' */
            _btns += '</div>'

        return _btns;
    }

    var callExtensions = function (uniqId) {

        Core.initFancybox($('#layout-builder'+ uniqId), uniqId);
        
        $.contextMenu({
            selector: '#layout-builder'+ uniqId +' .chooseWidget',
            callback: function(key, opt) {
                if (key === 'chooseWidget') {
                    var _selectedSection = $('#layout-builder'+ uniqId).find('.standart-section.section-edit');
                    if (_selectedSection.length < 1) {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Санамж',
                            text: 'Сонгосон <b>Section</b> олдсонгүй.',
                            type: 'warning', 
                            sticker: false
                        });
                        return false;
                    } else {
                        selectWidget(uniqId, opt.$trigger, _selectedSection);
                    }
                } 
            },
            items: {
                "chooseWidget": {name: plang.get('Сонгох'), icon: 'check'}
            }
        });

        $.contextMenu({
            selector: '#layout-builder'+ uniqId +' .chooseHtml',
            callback: function(key, opt) {
                if (key === 'chooseHtml') {
                    var _selectedSection = $('#layout-builder'+ uniqId).find('.standart-section.section-edit');
                    if (_selectedSection.length < 1) {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Санамж',
                            text: 'Сонгосон <b>Section</b> олдсонгүй.',
                            type: 'warning', 
                            sticker: false
                        });
                        return false;
                    } else {
                        selectHtml(uniqId, opt.$trigger, _selectedSection);
                    }
                } 
            },
            items: {
                "chooseHtml": {name: plang.get('Сонгох'), icon: 'check'}
            }
        });

        $('#layout-builder'+ uniqId +' [data-content]').qtip({
            content: {
                text: function(event, api) {
                    var content = '';
                    content += '<p> '
                        content += $(event.currentTarget).attr('data-content');
                    content += '</p>';
                    return content;
                }
            },
            position: {
                effect: false,
                my: 'left center',
                at: 'right center',
                viewport: $(window) 
            },
            show: {
                effect: false, 
                delay: 700
            },
            hide: {
                effect: false, 
                fixed: true,
                delay: 70
            }, 
            style: {
                classes: 'qtip-bootstrap qtip-atomicwidget',
                width: 200, 
                tip: {
                    width: 12,
                    height: 7
                }
            }
        });

        qtipCallExtension('#layout-builder'+ uniqId +' .jstree-anchor', uniqId);

    };

    var qtipCallExtension = function (body, uniqId) {

        $(body).qtip({
            content: {
                text: function(event, api) {
                    var nodeId = ($(event.currentTarget).attr('id')).replace('_anchor', '');
                    var node = $('#studioTreeView_'+ uniqId).jstree(true).get_node(nodeId);
                    var content = '';
                    
                    if (typeof node.original !== 'undefined' && typeof node.original.pic !== 'undefined') {
                        content += '<p> '
                            content += '<img src="' + node.original.pic + '" width="150" />';
                        content += '</p>';
                    } else {
                        content += '<p> '
                            content += 'Энэ бол Boostrap '
                            // content += 'This is the Bootstrap Row component.'; 
                            content += '<b>' + node.text+ '</b> component.';
                        content += '</p>';
                    }

                    return content;
                }
            },
            position: {
                effect: false,
                my: 'left center',
                at: 'right center',
                viewport: $(window) 
            },
            show: {
                effect: false, 
                delay: 700
            },
            hide: {
                effect: false, 
                fixed: true,
                delay: 70
            }, 
            style: {
                classes: 'qtip-bootstrap qtip-atomicwidget',
                width: 200, 
                tip: {
                    width: 12,
                    height: 7
                }
            }
        });
    }

    var buildHtmlByIndicators = function (kpiTypeIndicators, html, uniqId) {
        var defaultVal = '';
        $.each(kpiTypeIndicators, function (i, r) {
            defaultVal = (typeof r.DEFAULT_VALUE && r.DEFAULT_VALUE) ? r.DEFAULT_VALUE : '';
            if (defaultVal && typeof eval(defaultVal) == 'object')
                defaultVal = JSON.stringify(eval(defaultVal));

            if (typeof r.children !== 'undefined' && r.children) {
                html += buildHtmlByIndicators(r.children, '', uniqId);
            } else {
                switch (r.CODE) {
                    case 'sectionCol':
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ plang.get(r.CODE) +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<select class="form-control form-control-sm mt-0" name="sectionCol['+ blockUniqId +']" data-path="'+ r.CODE +'">';
                                    html += '<option value="">- Сонгох -</option>';
                                    for (var i = 1; i <= 12; i++) {
                                        html += '<option value="'+ i +'">col-'+ i +'</option>';
                                    }
                                html += '</select>';
                            html += '</div>';
                        html += '</div>';
                        break;
                    case 'series.data':
                    case 'json':
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="sectionCol">'+ plang.get(r.CODE) +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<div class="input-group">';
                                    html += '<textarea tabindex="" class="form-control form-control-sm mt-0 expression_editorInit" readonly="readonly" data-path="'+ r.CODE +'" data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder="">'+ defaultVal +'</textarea>';
                                    html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpExpressionEditor(this);"><i class="far fa-code"></i></button></span> ';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        break;
                    case 'html':
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="sectionCol">'+ plang.get(r.CODE) +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<div class="input-group">';
                                    html += '<textarea tabindex="" class="form-control form-control-sm mt-0 " readonly="readonly" data-path="'+ r.CODE +' data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder=""></textarea>';
                                    html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpFieldTextEditorClickToEdit(this, \'1\', \'setHtml_'+ uniqId +'\');"><i class="far fa-code"></i></button></span> ';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        break;
                    default:
                        html += '<div class="form-group row">';
                            html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ plang.get(r.CODE) +' :</label>';
                            html += '<div class="col-md-8">';
                                html += '<div class="input-group">';
                                    html += '<input type="text" class="form-control form-control-sm mt-0" data-path="'+ r.CODE +'" />';
                                    html += '<span class="input-group-append">';
                                        html += '<button class="btn grey-cascade" type="button" onclick="clearKpiTypeAttr'+ uniqId +'(this);"><i class="far fa-trash"></i></button>';
                                    html += '</span> ';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        break;
                }
            }
        });
        return html;
    };

    var renderIndicatorHtml = function (blockUniqId, kpiIndicatorData, uniqId, configEditing) {
        var __html = '<div class="item-config" data-item-cf="' + blockUniqId +'" style="display: none">';
            __html += '<input type="hidden" name="blockUniqId[]" value="'+ blockUniqId +'">';
            if (kpiIndicatorData) {
                __html += '<div class="card-group-control card-group-control-left">';
                    $.each(kpiIndicatorData, function (i, r) {
                        __html += '<div class="card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1 conf_'+ r.ID +'">';
                            __html += '<div class="card-header h-auto m-0 px-1 py-2 bg-root-color">';
                                __html += '<h6 class="card-title pull-left w-100">';
                                    __html += '<a class="text-white w-100 pull-left collapsed" data-toggle="collapse" href="#collapsible-'+ r.ID +'-group" aria-expanded="true">'+ plang.get(r.CODE + '_collapse') +'</a>';
                                __html += '</h6>';
                            __html += '</div>';
                            __html += '<div id="collapsible-'+ r.ID +'-group" class="collapse">';
                                __html += '<div class="card-body">';
                                    __html += '<div class="col-md-12">';
                                        if (typeof r.children !== 'undefined' && r.children) {
                                            __html += buildHtmlByIndicators(r.children, '', uniqId);
                                        } else {
                                            switch (r.CODE) {
                                                case 'sectionCol':
                                                    __html += '<div class="form-group row">';
                                                        __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ plang.get(r.CODE) +' :</label>';
                                                        __html += '<div class="col-md-8">';
                                                            __html += '<select class="form-control form-control-sm" name="sectionCol['+ blockUniqId +']" data-path="'+ r.CODE +'">';
                                                                __html += '<option value="">- Сонгох -</option>';
                                                                for (var i = 1; i <= 12; i++) {
                                                                    __html += '<option value="'+ i +'">col-'+ i +'</option>';
                                                                }
                                                            __html += '</select>';
                                                        __html += '</div>';
                                                    __html += '</div>';
                                                    break;
                                                case 'html':
                                                    __html += '<div class="form-group row">';
                                                        __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ plang.get(r.CODE) +' :</label>';
                                                        __html += '<div class="col-md-8">';
                                                            __html += '<div class="input-group">';
                                                                __html += '<textarea tabindex="" class="form-control form-control-sm " data-path="html" readonly="readonly" data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder=""></textarea>';
                                                                __html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpFieldTextEditorClickToEdit(this, \'1\', \'setHtml_'+ uniqId +'\');"><i class="far fa-code"></i></button></span> ';
                                                            __html += '</div>';
                                                        __html += '</div>';
                                                    __html += '</div>';
                                                    break;
                                                default:
                                                    __html += '<div class="form-group row">';
                                                        __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ plang.get(r.CODE) +' :</label>';
                                                        __html += '<div class="col-md-8">';
                                                            __html += '<div class="input-group">';
                                                                __html += '<input type="text" class="form-control form-control-sm" data-path="'+ r.CODE +'" />';
                                                                __html += '<span class="input-group-append">';
                                                                    __html += '<button class="btn grey-cascade" type="button" onclick="clearKpiTypeAttr'+ uniqId +'(this);"><i class="far fa-trash"></i></button>';
                                                                __html += '</span> ';
                                                            __html += '</div>';
                                                        __html += '</div>';
                                                    __html += '</div>';
                                                    break;
                                            }
                                        }
                                    __html += '</div>';
                                __html += '</div>';
                            __html += '</div>';
                        __html += '</div>';
                    });
                __html += '</div>';
            }
            __html += '<div class="card-group-control card-group-control-left">';
                __html += '<div class="card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1 conf_'+ blockUniqId +'">';
                    __html += '<div class="card-header h-auto m-0 px-1 py-2 bg-root-color">';
                        __html += '<h6 class="card-title pull-left w-100">';
                            __html += '<a class="text-white w-100 pull-left" data-toggle="collapse" href="#collapsible-'+ blockUniqId +'-group" aria-expanded="true">'+ plang.get('Appearance') +'</a>';
                        __html += '</h6>';
                    __html += '</div>';
                    __html += '<div id="collapsible-'+ blockUniqId +'-group" class="collapse show">';
                        __html += '<div class="card-body">';
                            $.each(configEditing, function (ckey, cRow) {
                                switch (cRow) {
                                    case 'section':
                                        __html += '<div class="col-md-12">';
                                            __html += '<div class="form-group row">';
                                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">Col [1~12] :</label>';
                                                __html += '<div class="col-md-8">';
                                                    __html += '<div class="input-group">';
                                                        __html += '<select class="form-control form-control-sm" name="sectionCol" data-path="sectionCol" id="sectionCol">';
                                                                __html += '<option value="">- Сонгох -</option>';
                                                                for (var i = 1; i <= 12; i++) {
                                                                    __html += '<option value="'+ i +'">col-'+ i +'</option>';
                                                                }
                                                            __html += '</select>';
                                                    __html += '</div>';
                                                __html += '</div>';
                                            __html += '</div>';
                                        __html += '</div>';
                                        break;
                                    case 'html':
                                        __html += '<div class="col-md-12">';
                                            __html += '<div class="form-group row">';
                                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">Source :</label>';
                                                __html += '<div class="col-md-8">';
                                                    __html += '<div class="input-group">';
                                                        __html += '<textarea tabindex="" class="form-control form-control-sm " data-path="html" readonly="readonly" data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder=""></textarea>';
                                                        __html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpFieldTextEditorClickToEdit(this, \'1\', \'setHtml_'+ uniqId +'\');"><i class="far fa-code"></i></button></span> ';
                                                    __html += '</div>';
                                                __html += '</div>';
                                            __html += '</div>';
                                        __html += '</div>';
                                        break;
                                    default:
                                        if (cRow === 'dataPosition') {
                                            if (typeof window['setDefaultRowData' + uniqId] !== 'undefined' && typeof window['setDefaultRowData' + uniqId][0] !== 'undefined') {
                                                __html += '<div class="col-md-12">';
                                                    __html += '<div class="form-group row">';
                                                        __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">dataPosition:</label>';
                                                        __html += '<div class="col-md-8">';
                                                            __html += '<select class="form-control form-control-sm " name="dataPosition" data-path="dataPosition">';
                                                                __html += '<option value="">- Сонгох -</option>';
                                                                $.each(window['setDefaultRowData' + uniqId][0], function (dataIndex, dataValue) {
                                                                    __html += '<option value="'+ dataIndex +'">'+ dataValue +'</option>';
                                                                });
                                                            __html += '</select>';
                                                        __html += '</div>';
                                                    __html += '</div>';
                                                __html += '</div>';
                                            }
                                        } else {
                                            __html += '<div class="col-md-12">';
                                                __html += '<div class="form-group row">';
                                                    __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1">'+ cRow +' :</label>';
                                                    __html += '<div class="col-md-8">';
                                                        __html += '<div class="input-group">';
                                                            __html += '<input type="text" class="form-control form-control-sm" name="'+ cRow +'" data-path="'+ cRow +'" id="'+ cRow +'" value="" >'
                                                        __html += '</div>';
                                                    __html += '</div>';
                                                __html += '</div>';
                                            __html += '</div>';
                                        }
                                        break;
                                }
                            });
                            __html += '<div class="block-config-'+ blockUniqId +'"></div>';
                            __html += '<div class="form-group row -item-addinconfig">';
                                __html += '<label class="col-form-label col-md-4 text-right pr-0 pt-1" for="widgetId">Нэмэлт тохиргоо :</label>';
                                __html += '<div class="col-md-8">';
                                    __html += '<div class="input-group">';
                                        __html += '<textarea name="secNemgoo['+ blockUniqId +']" tabindex="" class="form-control form-control-sm expression_editorInit" data-path="secNemgoo" data-field-name="secNemgoo" data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder="Нэмэлт тохиргоо"></textarea>';
                                        __html += '<span class="input-group-append"><button class="btn grey-cascade" type="button" onclick="bpExpressionEditor(this);"><i class="far fa-code"></i></button></span> ';
                                    __html += '</div>';
                                __html += '</div>';
                            __html += '</div>';
                        __html += '</div>';
                    __html += '</div>';
                __html += '</div>';
            __html += '</div>';
        __html += '</div>';
        return __html;
    };

    var initEditBlock = function (element, uniqId) {
        
        var _this = element,
            blockUniqId = _this.attr('data-id');

        window['layoutBuilder' + uniqId].find('.top-action-btns > .table-attr').hide();
        window['layoutBuilder' + uniqId].find('.top-action-btns > .column-attr').hide();
        
        switch (_this.data('name')) {
            case 'table':
                window['layoutBuilder' + uniqId].find('.top-action-btns > .table-attr').show();
                break;
            case 'Row':
            case 'Col':
            case 'Column':
                window['layoutBuilder' + uniqId].find('.top-action-btns > .column-attr').show();
                break;
            default:
                break;
        }

        window['layoutBuilder' + uniqId].find('.top-action-btns').show();
        return;
        window['layoutBuilder' + uniqId].find('li[id="tab-item"]').show();
        window['layoutBuilder' + uniqId].find('li[id="tab-item"] > a').trigger('click');
        
        var colIn = (typeof _this.attr('data-in-col') !== 'undefined' && _this.attr('data-in-col')) ? _this.attr('data-in-col') : '';
        var __html =  renderIndicatorHtml(blockUniqId, window['kpiTypeIndicators' + uniqId], uniqId, window['configEditing' + uniqId]);
        
        var _itemFounder = window['layoutBuilder' + uniqId].find('.item-configration');
        _itemFounder.find('.item-config').hide();

        if (_itemFounder.find('div[data-item-cf="' + blockUniqId +'"]').length > 0) {
            _itemFounder.find('div[data-item-cf="' + blockUniqId +'"]').show();
        } else {
            _itemFounder.append(__html).promise().done(function () {
                _itemFounder.find('.item-config[data-item-cf="' + blockUniqId +'"]').show();
            });
        }
    };

    var initMoveable = function (element, options, uniqId) {
        var _this = $(element);
        const moveable = new Moveable(document.body);
        
        $.each(options, function (i, o) {
            moveable[i] = o;
        });

        moveable.target= element;

        /* moveable.updateTarget(); */
        moveable.updateSelectors();
        moveable.waitToChangeTarget().then(() => {
            
        });
        
        moveable.on("dragStart", (t) =>  {
        }).on("drag", (t) =>  {
            
            var width = parseInt(t.width),
                height = parseInt(t.height);

            t.target.style.width = `${width}px`;
            t.target.style.height = `${height}px`;

            t.target.style.transform = t.transform;
            setControlLayouting (t);
        });
        
        moveable.request('[data-scale="scale"]', { deltaWidth: 10, deltaHeight: 10 }, true);
        moveable.on("resizeStart", ({ target, clientX, clientY }) => {
            /* console.log("onResizeStart", target); */
        }).on("resize", (t) => {
            var width = parseInt(t.width),
                height = parseInt(t.height);

            t.target.style.width = `${width}px`;
            t.target.style.height = `${height}px`;
            
            t.target.style.transform = t.drag.transform;
            setControlLayouting (t);

        }).on("resizeEnd", (t) => {
            var $t = $(t.target);
            if ($t.find('canvas').length > 0) {
                var _c = $t.find('canvas:eq(0)'),
                    o_w = _c.data('w'),
                    o_h = _c.data('h'),
                    _h = t.lastEvent.offsetHeight,
                    _w = getWidthCalculate(_h, o_w, o_h);
            }
        });

        moveable.on("rotateStart", ({ target, clientX, clientY }) => {
            /* console.log("onRotateStart", target); */
        }).on("rotate", (t) => {
            
            var width = parseInt(t.width),
                height = parseInt(t.height);

            t.target.style.width = `${width}px`;
            t.target.style.height = `${height}px`;

            t.target.style.transform = t.transform;
            setControlLayouting (t, '1');
            
        }).on("rotateEnd", ({ target, isDrag, clientX, clientY }) => {
            /* console.log("onRotateEnd", target, isDrag); */
        });

        moveable.on("clipStart", (t) => {
        }).on("clip", (t) => {
            _addWidth = $(t.target).width() + t.distX + 2;
            _addHeight = $(t.target).height() + t.distY + 2;

        }).on("clipEnd", (t) => {
            t.target.style.width = `${_addWidth}px`;
            t.target.style.height = `${_addHeight}px`;
        });
    };

    var setControlLayouting = function (t, isRotate) {
        var _index = ((typeof t.target !== 'undefined' && typeof $(t.target).attr('data-index') !== 'undefined')) ? $(t.target).attr('data-index') : 0;
        var _mainSelectorControl = $('.layout-panel[data-index="'+ _index +'"]');
        var _cssText = t.cssText;
        
        if (_cssText.indexOf('width:') < 0) {
            _cssText = "width: " + t.width + "px; height: " + t.height + "px; " + _cssText
        }
    
        _mainSelectorControl.find('input[data-name="x"]').val(t.clientX);
        _mainSelectorControl.find('input[data-name="y"]').val(t.clientY);
        _mainSelectorControl.find('input[data-name="w"]').val(t.width);
        _mainSelectorControl.find('input[data-name="h"]').val(t.height);
        _mainSelectorControl.find('textarea[data-name="css_text"]').val(_cssText);
    
        if (typeof isRotate !== 'undefined' && isRotate) {
            _mainSelectorControl.find('input[data-name="r"]').val(parseInt(t.beforeRotate) + "°");
        }
    };
    
    var getWidthCalculate = function(_h, _nw, _nh) {
        var _w = (_h*_nw)/_nh;
        return _w;
    };

    var initDropzone = function (uniqId, targetElement, selectedRow) {
        try {
            
            if (typeof selectedRow['content'] !== 'undefined') {
                var elementUniqId = getUniqueId('no');
                var contentHtml = html_entity_decode(selectedRow['content']);
                
                $('.temp_' + uniqId).empty().append(contentHtml).promise().done(function () {
                    switch (selectedRow['text']) {
                       /*  case 'Button':
                            $('.temp_' + uniqId).children().children().attr('data-id', elementUniqId);
                            $('.temp_' + uniqId).children().children().attr('data-target', 'target');
                            $('.temp_' + uniqId).children().children().attr('data-name', selectedRow['text']);
    
                            break;
                         */
                        default:
                            $('.temp_' + uniqId).children().attr('data-id', elementUniqId);
                            $('.temp_' + uniqId).children().attr('data-target', 'target');
                            $('.temp_' + uniqId).children().attr('data-name', selectedRow['text']);
    
                            if (selectedRow.hasOwnProperty('type') && selectedRow.type) {
                                $('.temp_' + uniqId).children().attr('id', selectedRow.type + elementUniqId);
                                $('.temp_' + uniqId).children().attr('data-type', selectedRow.type);
                                $('.temp_' + uniqId).children().attr('data-widgetid', selectedRow.id);
                                switch (selectedRow.type) {
                                    case 'html':
                                        console.log('here html');
                                        break;
                                }
                            }
                            break;
                    }
                    
                    $(targetElement).append($('.temp_' + uniqId).html()).promise().done(function () {
                        
                        $('.temp_' + uniqId).empty();
                        var elementName = selectedRow['text'];
                        var appendUniqId = (typeof $(targetElement).data('id') !== 'undefined') ? $(targetElement).data('id') : uniqId;
                        $('#structureTreeView_' + uniqId).jstree().create_node(appendUniqId ,  { "id" : elementUniqId, "text" : elementName}, "last", function() {
                            console.log("added = " + elementUniqId);
                        });
                        switch (selectedRow['text']) {
                            case 'Accordion':
                                console.log($(targetElement).data('id'));
                                var tmpUniqId = getUniqueId('no');
                                $('#structureTreeView_' + uniqId).jstree().create_node(elementUniqId ,  { "id" : tmpUniqId, "text" : "Header"}, "last", function() {
                                    console.log("added = Accordion header " + tmpUniqId);
                                    $('div[data-id="'+ appendUniqId +'"]').find('[data-name="Header"]:eq(0)').attr('data-id', tmpUniqId);
                                    tmpUniqId = getUniqueId('no');
                                    $('#structureTreeView_' + uniqId).jstree().create_node(elementUniqId ,  { "id" : tmpUniqId, "text" : "Header element"}, "last", function() {
                                        console.log("added = Accordion header element" + tmpUniqId);
                                        $('div[data-id="'+ appendUniqId +'"]').find('[data-name="Header element"]:eq(0)').attr('data-id', tmpUniqId);
                                        tmpUniqId = getUniqueId('no');
                                        $('#structureTreeView_' + uniqId).jstree().create_node(elementUniqId ,  { "id" : tmpUniqId, "text" : "Body"}, "last", function() {
                                            console.log("added = " + tmpUniqId);
                                            $('div[data-id="'+ appendUniqId +'"]').find('[data-name="Body"]:eq(0)').attr('data-id', tmpUniqId);
                                        });
                                    });
                                    
                                });
                                break;
                        }
                        //check selected Types
                        if (selectedRow.hasOwnProperty('type') && selectedRow.type) {
                            switch (selectedRow.type) {
                                case 'echart':
                                    var jsonConfig = JSON.parse(selectedRow.json);
                                    var chartConfig = jsonConfig['chartConfig'];
                                    var option = JSON.parse(chartConfig['buildCharConfig']);
                                    
                                    var chartDom = document.getElementById(selectedRow.type + elementUniqId);
                                    var myChart = echarts.init(chartDom);
                                    option && myChart.setOption(option);
    
                                    break;
                            
                                default:
                                    break;
                            }
                        }
                    });
                });
            } else {
                /* PNotify.removeAll();
                new PNotify({
                    title: ' Анхааруулга',
                    text: 'Буруу өгө',
                    type: responseData.status, 
                    sticker: false
                }); */

            }

        } catch (error) {
            console.log(error);            
        }
    };

    var initOptionClearup = function (uniqId, $parent) {
        $parent.find('.item-configration .text-options').hide();
        $parent.find('.item-configration .dtl-options').hide();
        $parent.find('.item-configration .button-options').hide();
        $parent.find('.item-configration .image-options').hide();
        $parent.find('.item-configration .chart-options').hide();
        $parent.find('.item-configration .cicon-options').hide();
        $parent.find('.item-configration .list-options').hide();
        
        $parent.find('.item-configration input').val('');
        $parent.find('.item-configration select').val('');
    };

    var createAtomicInit = function (uniqId, $form) {
        var json = mapDomHtmlConvert(document.querySelector('#page' + uniqId), true);
        $form.ajaxSubmit({
            type: 'post',
            url: 'mdwidget/createAtomic',
            dataType: 'json',
            beforeSubmit: function(formData, jqForm, options) {
                formData.push(
                    { name: 'json', value: json }, 
                );
            },
            success: function (responseData) {

                PNotify.removeAll();
                new PNotify({
                    title: responseData.status,
                    text: responseData.message,
                    type: responseData.status, 
                    sticker: false
                });

                if (responseData.status === 'success') {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: 'mdwidget/atomicBuild/' + responseData.rowId,
                        beforeSend: function () {},
                        success: function (response) {
                            window['layoutBuilder' + uniqId].parent().empty().append(response.Html);
                        },
                        error: function(jqXHR, exception) {
                            Core.unblockUI();
                            Core.showErrorMessage(jqXHR, exception);
                        }
                    });
                }

                Core.unblockUI();
            },
            error: function(jqXHR, exception) {
                Core.unblockUI();
                Core.showErrorMessage(jqXHR, exception);
            }
        });
    };

    var mapDomHtmlConvert = function (element, json) {
        var treeObject = {};
            
        // If string convert to document Node
        if (typeof element === "string") {
            if (window.DOMParser) {
                parser = new DOMParser();
                docNode = parser.parseFromString(element,"text/xml");
            } else { // Microsoft strikes again
                docNode = new ActiveXObject("Microsoft.XMLDOM");
                docNode.async = false;
                docNode.loadXML(element); 
            } 
            element = docNode.firstChild;
        }
        
        //Recursively loop through DOM elements and assign properties to object
        function treeHTML(element, object) {
            object["type"] = element.nodeName;
            object["attributes"] = {};

            var nodeList = element.childNodes;
            if (nodeList != null) {
                if (nodeList.length) {
                    object["content"] = [];
                    for (var i = 0; i < nodeList.length; i++) {
                        if (nodeList[i].nodeType == 3) {
                            object["content"].push(nodeList[i].nodeValue);
                        } else {
                            object["content"].push({});
                            treeHTML(nodeList[i], object["content"][object["content"].length -1]);
                        }
                    }
                }
            }
            if (element.attributes != null) {
                if (element.attributes.length) {
                    for (var i = 0; i < element.attributes.length; i++) {
                        if (element.attributes[i].nodeName === 'style') {
                            const cssObj = camelCase(css2obj(element.attributes[i].nodeValue));
                            
                            object["attributes"]['styleFields'] = cssObj;
                        }
                        object["attributes"][element.attributes[i].nodeName] = element.attributes[i].nodeValue;
                    }
                }
            }

            object["attributes"]["data-type"] = element.nodeName;
        }
        
        function camelCase(obj) {
            var newObj = {};
            for (d in obj) {
            if (obj.hasOwnProperty(d)) {
                newObj[d.replace(/(\-\w)/g, function(k) {
                    return k[1].toUpperCase();
                })] = obj[d];
            }
            }
            return newObj;
        }
         
        const css2obj = css => {
            const r = /(?<=^|;)\s*([^:]+)\s*:\s*([^;]+)\s*/g, o = {};
            css.replace(r, (m,p,v) => o[p] = v);
            return o;
        };
          
        treeHTML(element, treeObject);
        console.log(treeObject);
        
        return (json) ? JSON.stringify(treeObject) : treeObject;
    };

    return {
        initMoveable: function(element, options, uniqId) {
            initMoveable(element, options, uniqId);
        },
        init: function(uniqId) {
            initOptionClearup(uniqId, $('#layout-builder'+ uniqId));
            main('#layout-builder'+ uniqId);
            pageEmpty('#layout-builder'+ uniqId);
            allEmpty('#layout-builder'+ uniqId);
            makeDraggable('#layout-builder'+ uniqId, '#layout-builder'+ uniqId +' #page' + uniqId);
            makeSortable($('#layout-builder'+ uniqId +' #pageList #page' + uniqId), uniqId);
            render(uniqId);
            callExtensions(uniqId)
        },
        initPageEmpty: function (uniqId) {
            pageEmpty('#layout-builder'+ uniqId);
        }, 
        initAllEmpty: function (uniqId) {
            allEmpty('#layout-builder'+ uniqId);
        }, 
        initMakeDraggable: function (uniqId) {
            makeDraggable(uniqId);
        }, 
        initMakeSortable: function (uniqId, uniqId) {
            makeSortable(uniqId, uniqId);
        }, 
        initRender: function (uniqId) {
            render(uniqId);
        },
        selectWidget: function (uniqId, srcSection, trgSection) {
            selectWidget(uniqId, srcSection, trgSection);
        },
        renderIndicatorHtml: function (blockUniqId, kpiIndicatorData, uniqId, configEditing) {
            return renderIndicatorHtml(blockUniqId, kpiIndicatorData, uniqId, configEditing);
        },
        initEditBlock: function (element, uniqId) {
            return initEditBlock(element, uniqId);
        },
        initDropzone: function (uniqId, targetElement, selectedRow) {
            initDropzone(uniqId, targetElement, selectedRow);
        },
        initOptionClearup: function (uniqId, $parent) {
            initOptionClearup(uniqId, $parent);
        },
        createAtomicInit: function (uniqId, $form) {
            createAtomicInit(uniqId, $form);
        },
        mapDomHtmlConvert: function () {
            mapDomHtmlConvert();
        },
        initQtipCallExtension: function (body, uniqId) {
            qtipCallExtension(body, uniqId);
        }
    };
    
} ();