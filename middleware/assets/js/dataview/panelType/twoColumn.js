var isDataViewPanelTwoColumn = true, isDataViewPanelTwoColReloadRow = null, isPrevPopupSearchLoad = false;

var buildSecondListByCheck = function (uniqId, mainDvId, listMetaDataId, rowId, $this, isIgnoreCheck, treeData, isHeaderGroupAction, firstRowClick) {
    
    var rowData = $this.data('rowdata');	
    if (rowData.hasOwnProperty('ispaid') && rowData.ispaid === '0' && getConfigValue('usePaymentForm') == '1') {

        if (typeof isProjectAddonScript === 'undefined') {
            $.getScript(URL_APP + 'projects/assets/custom/js/script.js').done(function() {
                $("head").append('<link rel="stylesheet" type="text/css" href="projects/assets/custom/css/style.css"/>');
                callPaymentForm(uniqId, rowData, mainDvId, listMetaDataId, rowId, $this, isIgnoreCheck, treeData, isHeaderGroupAction, firstRowClick);
            });
        } else {
            callPaymentForm(uniqId, rowData, mainDvId, listMetaDataId, rowId, $this, isIgnoreCheck, treeData, isHeaderGroupAction, firstRowClick);
        }

        return;
    }

    if (listMetaDataId) {

        if (!isIgnoreCheck && window['secondList_'+uniqId].hasAttr('data-loadrowid') && window['secondList_'+uniqId].attr('data-loadrowid') == rowId) {
            return;
        }
        
        if ($('.paneldv-filter-after-close-'+uniqId).length && isPrevPopupSearchLoad == false) {
            $('.paneldv-filter-after-close-'+uniqId).dialog('destroy').remove();
        }
        
        if (!isIgnoreCheck) {
            window['viewProcess_' + uniqId].empty();
            window['panelDv_' + uniqId].find('#dv-twocol-view-title').empty();
            window['panelDv_' + uniqId].find('[data-addon-left-title="1"]').empty();
        }

        buildSecondListByAjax(uniqId, mainDvId, $this.text(), listMetaDataId, rowId, treeData, isHeaderGroupAction, firstRowClick);
    }

    return;
};

var buildSecondListByAjax = function(uniqId, mainDvId, listName, listMetaDataId, rowId, treeData, isHeaderGroupAction, firstRowClick) {
    
    window['secondFilter_' + uniqId] = '';
    
    panelDrawTree(uniqId, mainDvId, listMetaDataId, listName, rowId, treeData, isHeaderGroupAction, firstRowClick);
};

var panelDvRefreshSecondList = function(uniqId, firstRowClick) {
    
    var $selectedRow = window['firstList_' + uniqId].find('.dv-twocol-f-selected');
    var $secondSelectedRow = window['secondList_' + uniqId].find('.dv-twocol-f-selected');
    
    if ($secondSelectedRow.length) {
        isDataViewPanelTwoColReloadRow = $secondSelectedRow;
    }
    
    buildSecondListByCheck(uniqId, null, $selectedRow.data('listmetadataid'), $selectedRow.data('id'), $selectedRow, true, undefined, undefined, firstRowClick);
};

var panelRowClickDvRefreshSecondList = function(uniqId, firstRowClick, isPrevPopupSearch) {
    
    var $selectedRow = window['firstList_' + uniqId].find('.dv-twocol-f-selected');
    var $secondSelectedRow = window['secondList_' + uniqId].find('.dv-twocol-f-selected');
    
    if ($secondSelectedRow.length) {
        isDataViewPanelTwoColReloadRow = $secondSelectedRow;
        $secondSelectedRow.click();
    }
    
    if (typeof isPrevPopupSearch != 'undefined' && isPrevPopupSearch) {
        isPrevPopupSearchLoad = true;
    }
    
    buildSecondListByCheck(uniqId, null, $selectedRow.data('listmetadataid'), $selectedRow.data('id'), $selectedRow, true, undefined, undefined, firstRowClick);
};

var panelDrawTree = function(uniqId, mainDvId, listMetaDataId, listName, loadedRowId, treeData, isHeaderGroupAction, firstRowClick) {
    
    window['secondList_'+uniqId].attr({'data-listmetadataid': listMetaDataId, 'data-loadrowid': loadedRowId});

    window['secondListName_'+uniqId].text(listName);
    window['secondList_'+uniqId].html('<div id="objectdatagrid-'+listMetaDataId+'" data-treeid="panelTreeView_'+uniqId+'" class="tree-demo mt6 dv-twocol-panel-tree"></div>');
    
    var selectMetaDataCriteria;
    
    if (typeof isHeaderGroupAction == 'undefined') {
        var $twoColSelectedRow = window['firstList_'+uniqId].find('.dv-twocol-f-selected');
        selectMetaDataCriteria = $twoColSelectedRow.data('listmetadatacriteria');
    } else {
        selectMetaDataCriteria = window['groupColumn_'+uniqId].data('listmetadatacriteria');
    }
    
    $('div[data-treeid="panelTreeView_'+uniqId+'"]').jstree({
        "core": {
            "themes": {
                "responsive": false,
                "icons": false
            },
            "check_callback": function (op, node, par, pos, more) {
                return true;
            },
            "data": {
                "method": 'post', 
                "url": function (node) {
                    return 'mdobject/dvPanelChildDataTreeList';
                },
                "data": function (node) {
                    
                    var idcriteria = '';
                    
                    if (window['panelDv_'+uniqId].find('#two-column-filter-id').length) {
                        idcriteria = '&param[id]=' + window['panelDv_'+uniqId].find('#two-column-filter-id').attr('data-value');
                        window['panelDv_'+uniqId].find('#two-column-filter-id').remove();
                    }      
                    
                    if (node.id != '#') {
                        window['secondFilter_' + uniqId] = '';
                    } 
                    
                    var dataInput = {
                        'parent': node.id, 
                        'listMetaDataId': listMetaDataId, 
                        'criteria': selectMetaDataCriteria + window['secondFilter_' + uniqId], 
                        'params': window['panelDv_'+uniqId].find('.dv-paneltype-filter-form').serialize() + idcriteria
                    };
                    
                    if (typeof window['twoListPopupSearch_' + uniqId] != 'undefined' && window['twoListPopupSearch_' + uniqId]) {
                        dataInput['params'] = dataInput['params'] + window['twoListPopupSearch_' + uniqId];
                        window['twoListPopupSearch_' + uniqId] = null;
                    }
                    
                    if (isPrevPopupSearchLoad) {
                        
                        var $dvPanelPopupForm = $('.paneldv-filter-after-close-'+uniqId).find('form');
                        
                        if ($dvPanelPopupForm.length) {
                            dataInput['params'] = dataInput['params'] + '&isIgnoreParentNull=1&' + $dvPanelPopupForm.serialize();
                        }
                        
                        isPrevPopupSearchLoad = false;
                    }
                    
                    if (mainDvId && typeof window['filterObjectDtl_' + mainDvId] != 'undefined' && window['filterObjectDtl_' + mainDvId]) {
                        dataInput.filterObjectDtl = window['filterObjectDtl_' + mainDvId];
                    }
                    
                    if (typeof $twoColSelectedRow != 'undefined' 
                        && $twoColSelectedRow.data('rowdata') 
                        && $twoColSelectedRow.data('rowdata').hasOwnProperty('ischeckprocesspermission') 
                        && $twoColSelectedRow.data('rowdata').ischeckprocesspermission == '1') {
                    
                        dataInput.isCheckProcessPermission = 1;
                    }
                    
                    if (typeof $twoColSelectedRow != 'undefined' 
                        && $twoColSelectedRow.data('rowdata') 
                        && $twoColSelectedRow.data('rowdata').hasOwnProperty('secondlistmenuopendvid') 
                        && $twoColSelectedRow.data('rowdata').secondlistmenuopendvid) {
                        
                        var panelDvSecondId = Core.getURLParameter('pdsid');
                        
                        if (panelDvSecondId) {
                            dataInput.secondListMenuOpenDvId = $twoColSelectedRow.data('rowdata').secondlistmenuopendvid;
                            dataInput.panelDvSecondId = panelDvSecondId;
                        }
                    }
                    
                    return dataInput;
                },
                dataFilter: function (res) {
                    var res = JSON.parse(res);
                    var deleteProcessId = '';
                    
                    if (res.hasOwnProperty('gridoption') && res.gridoption) {
                        var gridoption = res.gridoption;
                        window['firstrowselect' + uniqId] = gridoption.FIRSTROWSELECT;
                        
                        $('div[data-treeid="panelTreeView_'+uniqId+'"]').attr('data-theme', gridoption.VIEWTHEME);
                    }
                    
                    if (res.hasOwnProperty('mainProcess') && res.mainProcess && window['dvTwoSecondListHdrActions_' + uniqId]) {

                        var mainProcess = res.mainProcess;
                        var addProcessId = null;

                        for (var pKey in mainProcess) {
                            
                            if (mainProcess[pKey].hasOwnProperty('IS_MAIN')) {
                                
                                if (mainProcess[pKey]['IS_MAIN'] == '1') {
                                    
                                    if (mainProcess[pKey]['GET_META_DATA_ID'] == null  
                                        && mainProcess[pKey]['META_TYPE_CODE'] == 'process' 
                                        && mainProcess[pKey]['ACTION_TYPE'] == 'insert') {

                                        addProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];

                                    } else if (mainProcess[pKey]['GET_META_DATA_ID']  
                                        && mainProcess[pKey]['META_TYPE_CODE'] == 'process' 
                                        && mainProcess[pKey]['ACTION_TYPE'] == 'delete') {

                                        deleteProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];
                                    }
                                }
                                
                            } else {
                            
                                if (mainProcess[pKey]['GET_META_DATA_ID'] == null  
                                    && mainProcess[pKey]['META_TYPE_CODE'] == 'process' 
                                    && mainProcess[pKey]['ACTION_TYPE'] == 'insert') {

                                    addProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];

                                } else if (mainProcess[pKey]['GET_META_DATA_ID']  
                                    && mainProcess[pKey]['META_TYPE_CODE'] == 'process' 
                                    && mainProcess[pKey]['ACTION_TYPE'] == 'delete') {

                                    deleteProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];
                                }
                            }
                        }
                        
                        if (typeof isHeaderGroupAction == 'undefined') {
                            
                            window['secondListName_'+uniqId].text(listName);
                            window['secondListName_'+uniqId].nextAll('.dv-panel-two-search').remove();
                            
                            var secondFilterObj = qryStrToObj(window['secondFilter_' + uniqId]);
                            var searchInputVisible = 'display:none;', 
                                searchInputVal = '';
                            
                            if (secondFilterObj.hasOwnProperty('filterVal') && secondFilterObj.filterVal) {
                                searchInputVisible = '';
                                searchInputVal = secondFilterObj.filterVal;
                            } else {
                                window['secondListName_'+uniqId].show();
                            }
                            
                            var twoColHdrAction = ['<div class="second-sidebar-search-box position-relative mr-auto w-100 dv-panel-two-search" style="'+searchInputVisible+'">'];
                                twoColHdrAction.push('<input type="search" class="form-control ml-0 second-sidebar-search-input" placeholder="Хайх..." value="'+searchInputVal+'">');
                            twoColHdrAction.push('</div>');

                            twoColHdrAction.push('<div class="d-flex flex-row ml-1 dv-panel-two-search">');
                                twoColHdrAction.push('<a href="javascript:void(0);" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2 mr-1 second-sidebar-search bg-grey-c0"><i class="icon-search4"></i></a>');

                            if (typeof treeData != 'undefined' && treeData.length) {

                                twoColHdrAction.push('<div class="btn-group mr-1">');
                                    twoColHdrAction.push('<button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></button>');
                                    twoColHdrAction.push('<div class="dropdown-menu dropdown-menu-right">');

                                        for (var key in treeData) {

                                            var listMetaDataCriteria = '';

                                            if (treeData[key].hasOwnProperty('listmetadatacriteria') && treeData[key]['listmetadatacriteria']) {
                                                listMetaDataCriteria = treeData[key]['listmetadatacriteria'];
                                            }

                                            twoColHdrAction.push('<a href="javascript:void(0);" ');
                                            twoColHdrAction.push('data-id="' + treeData[key][window['idField_' + uniqId]] + '" ');
                                            twoColHdrAction.push('data-listmetadataidgroup="' + treeData[key]['metadataid'] + '" ');
                                            twoColHdrAction.push('data-listmetadatacriteria="'+listMetaDataCriteria+'" ');
                                            twoColHdrAction.push('data-rowdata="'+htmlentities(JSON.stringify(treeData[key]), 'ENT_QUOTES', 'UTF-8')+'" ');
                                            twoColHdrAction.push('class="dropdown-item">');
                                            twoColHdrAction.push(treeData[key][window['nameField_' + uniqId]]);
                                            twoColHdrAction.push('</a>');
                                        }

                                    twoColHdrAction.push('</div>');
                                twoColHdrAction.push('</div>');
                            }

                            if (addProcessId) { 
                                twoColHdrAction.push('<a href="javascript:;" data-secondlistaddprocessid="'+addProcessId+'" class="btn btn-light bg-primary border-0 p-1 pl-2 pr-2 text-white"><i class="icon-plus2"></i></a>');
                            }
                                
                            twoColHdrAction.push('</div>');

                            window['secondListName_'+uniqId].after(twoColHdrAction.join(''));
                        }
                    }
                    
                    if (res.hasOwnProperty('popupSearch') && res.popupSearch) {
                        var $cardHdr = window['secondListName_'+uniqId].closest('.card-header');
                        $cardHdr.find('.second-sidebar-search').attr('data-popup-search', 1);
                    }
                    
                    if (res.hasOwnProperty('isWorkflow') && res.isWorkflow == '1') {
                        window['secondList_'+uniqId].attr('data-isworkflow', 1);
                    } else {
                        window['secondList_'+uniqId].attr('data-isworkflow', 0);
                    }
                    
                    if (window['dvTwoSecondListHdrActions_' + uniqId]) {
                        window['secondList_'+uniqId].attr('data-deleteprocessid', deleteProcessId);
                    }
                    
                    window['dvTwoSecondListHdrActions_' + uniqId] = true;
                
                    return JSON.stringify(res.rows);
                }
            }
        },
        "plugins": ['state', 'cookies', 'wholerow', 'dnd'], 
        "dnd": {
            "is_draggable": function (node) {
                if ((isObject(node) || isArray(node)) && node.hasOwnProperty(0)) {
                    var node = node[0];
                    var rowData = $(node.text).attr('data-rowdata');

                    if (rowData) {
                        if (typeof rowData !== 'object') {
                            var jsonObj = JSON.parse(html_entity_decode(rowData, "ENT_QUOTES"));
                        } else {
                            var jsonObj = rowData;
                        }
                        
                        if (jsonObj.hasOwnProperty('isignoredragdrop') && jsonObj.isignoredragdrop == '1') {
                            return false;
                        }
                    }
                }
                
                return true;
            }
        },
        
        /*"plugins": ['state', 'cookies', 'wholerow', 'dnd', 'search'], 
        "search": {
            "case_sensitive": false,
            "show_only_matches": true
        }*/
        
    }).bind("loaded.jstree", function (e, data) {
        
        var panelDvSecondId = Core.getURLParameter('pdsid');
        
        if (panelDvSecondId && window['dvTwoPanelLoad_' + uniqId] == false) {
            window['clickRowId_' + uniqId] = panelDvSecondId;
        }
        
        if (mainDvId && typeof window['filterObjectDtl_' + mainDvId] != 'undefined' && window['filterObjectDtl_' + mainDvId]) {
            
            var obj = window['filterObjectDtl_' + mainDvId];
            var last = Object.keys(obj)[Object.keys(obj).length - 1];
            var $jsfirstrow = $(e['currentTarget']);
            
            window['filterObjectDtl_' + mainDvId] = null;    
            
            var $row = $jsfirstrow.find('span[data-second-id="'+obj[last]['id']+'"]:eq(0)');
            var $rowParent = $row.closest('li#'+obj[last]['id']);
            
            $row.click();
            
            setTimeout(function() {
                $rowParent.find('.jstree-wholerow').addClass('jstree-wholerow-clicked');
                $rowParent.find('.jstree-anchor').addClass('jstree-clicked');
            }, 10);
            
        } else if (window['clickRowId_' + uniqId]) {
            
            var $jsTree = $(e['currentTarget']);
            var $row = $jsTree.find('span[data-second-id="'+window['clickRowId_' + uniqId]+'"]:eq(0)');
            
            if ($row.length) {
                var $rowParent = $row.closest('li#'+window['clickRowId_' + uniqId]);
                $row.click();
                
                setTimeout(function() {
                    
                    $jsTree.find('.jstree-wholerow-clicked, .jstree-clicked').removeClass('jstree-wholerow-clicked jstree-clicked');
                    
                    $rowParent.find('.jstree-wholerow').addClass('jstree-wholerow-clicked');
                    $rowParent.find('.jstree-anchor').addClass('jstree-clicked');
                }, 10);
                
                window['clickRowId_' + uniqId] = null;
                window['dvTwoPanelLoad_' + uniqId] = true;
            }
            
        } else if (window['firstrowselect' + uniqId] === 'true') {
            var $jsfirstrow = $(e['currentTarget']);
            if (typeof firstRowClick === 'undefined') {
                $jsfirstrow.find('[data-secondprocessid]:eq(0)').trigger('click');
            } else {
                setTimeout(function () {
                    $jsfirstrow.find('a.jstree-clicked [data-secondprocessid]:eq(0)').trigger('click');
                }, 1000);
            }
        } 
        
    }).bind("open_node.jstree", function (e, data) {
		
        var panelDvSecondId = Core.getURLParameter('pdsid');
        
        if (panelDvSecondId && window['dvTwoPanelLoad_' + uniqId] == false) {
            window['clickRowId_' + uniqId] = panelDvSecondId;
        }
		
        if (window['clickRowId_' + uniqId]) {
            
            var $jsTree = $(e['currentTarget']);
            var $row = $jsTree.find('span[data-second-id="'+window['clickRowId_' + uniqId]+'"]:eq(0)');
            
            if ($row.length) {
                var $rowParent = $row.closest('li#'+window['clickRowId_' + uniqId]);
                $row.click();
                
                setTimeout(function() {
                    
                    $jsTree.find('.jstree-wholerow-clicked, .jstree-clicked').removeClass('jstree-wholerow-clicked jstree-clicked');
                    
                    $rowParent.find('.jstree-wholerow').addClass('jstree-wholerow-clicked');
                    $rowParent.find('.jstree-anchor').addClass('jstree-clicked');
                }, 10);
				
                window['clickRowId_' + uniqId] = null;
                window['dvTwoPanelLoad_' + uniqId] = true;
            }
        }	
    });
};

var twoPanelSecondListSetParent = function(postData) {
    $.ajax({
        type: 'post',
        url: 'mdobject/treeViewSetParent',
        data: postData,
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },                            
        success: function(control) {
            Core.unblockUI();
        }
    });     
};

var twoPanelSecondListSetParentDisplayOrder = function(data) {
    var getId = data.data.nodes[0];
    var node = data.data.origin.get_node(getId);
    var parent = node.parent; 
    var $tree = $("li#"+ getId +".jstree-node").closest('.dv-twocol-panel-tree');
    
    if (typeof $tree == 'undefined' || (typeof $tree != 'undefined' && $tree.length == 0)) {
        var parent = node.parent; 
        $tree = $("li#"+ parent +".jstree-node").closest('.dv-twocol-panel-tree');
    }

    if ($tree.length) {

        var listMetaDataId = $tree.closest('[data-listmetadataid]').attr('data-listmetadataid');

        if (parent != '#') {

            var $li = $tree.find('li#'+parent);
            var rowData = $li.find('span.media:eq(0)').attr('data-rowdata');

            if (rowData) {

                if (!$li.hasClass('jstree-custom-folder-icon')) {
                    $li.addClass('jstree-custom-folder-icon');
                    $li.find('.d-flex > .mr5').remove();
                }

                var postData = {dataViewId: listMetaDataId, orderParam: rowData, parentId: parent, primaryId: getId};

                twoPanelSecondListSetParent(postData);
            }        
        } else {
            var $list = $tree.find('li#'+getId).find('span.media:eq(0)').attr('data-rowdata');

            if ($list) {
                var postData = {dataViewId: listMetaDataId, orderParam: $list, parentId: '', primaryId: getId};
                twoPanelSecondListSetParent(postData);
            }                
        }

        var $list = $tree.find('li#'+getId).closest('ul').children();
        var paramData = [], $this;

        $list.each(function(key, row){
            $this = $(this);

            paramData.push({
                'id': $this.attr('id'),
                'rowdata': $this.find('span.media').attr('data-rowdata')
            });
        });

        if (paramData.length) {
            $.ajax({
                type: 'post',
                url: 'mdobject/treeViewSetOrder',
                data: {dataViewId: listMetaDataId, orderParam: paramData},
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },                            
                success: function (control) {
                    Core.unblockUI();
                }
            });      
        }
    }
};

$(function() {
    var dvTwoColSecondListOldParent = 0;
    var dvTwoColSecondListOldPos = 0;

    $(document).on('dnd_start.vakata', function (event, data) {
        var getId = data.data.nodes[0];
        var $sel = $("li#"+ getId +".jstree-node");
        var $tree = $sel.closest('.dv-twocol-panel-tree');
        
        dvTwoColSecondListOldParent = $tree.jstree(true).get_node(getId).parent;
        dvTwoColSecondListOldPos = $sel.index();
    });
    
    $(document).on('dnd_stop.vakata',function(event, data) {
        
        var $elem = $(data.event.target);
        
        if ($elem.hasClass('wfposition') || $elem.hasClass('css-editor')) {
            return false;
        }
        
        var getId = data.data.nodes[0];
        var node = data.data.origin.get_node(getId);
        var rowData = $(node.text).attr('data-rowdata');
        var $tree = $("li#"+ getId +".jstree-node").closest('.dv-twocol-panel-tree');     
        
        if (typeof $tree == 'undefined' || (typeof $tree != 'undefined' && $tree.length == 0)) {
            var parent = node.parent; 
            $tree = $("li#"+ parent +".jstree-node").closest('.dv-twocol-panel-tree');
        }
        
        if (rowData) {
            
            if (typeof rowData !== 'object') {
                var jsonObj = JSON.parse(html_entity_decode(rowData, "ENT_QUOTES"));
            } else {
                var jsonObj = rowData;
            }

            if ($elem[0]["nodeName"] === "svg") {
                /**
                 * BPMN Tool create task lisenter
                 */
                if (typeof elementFactory !== "undefined") {
                    var root = canvas.getRootElement();
                    var crshape = CreateTask(),
                        rowData = jsonObj;

                    modeling.createShape(crshape, {x: data.event.offsetX, y: data.event.offsetY}, root);                
                    crshape.businessObject.set('processid', rowData.id);
                    modeling.updateProperties(crshape, { name: rowData.name.replace(/<[^>]*>?/gm, '') });     
                } else {
                  addCustomShape(data.event.offsetX, data.event.offsetY, rowData);
                }
                return false;
            }               
                
            if (jsonObj.hasOwnProperty('isdragdropconfirm') && jsonObj.isdragdropconfirm == '1') {
                
                var dialogName = '#dialog-twocolsecondlist-confirm';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName);

                $dialog.html('Та итгэлтэй байна уу?');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('msg_title_confirm'), 
                    width: 300,
                    height: 'auto',
                    modal: true,
                    close: function(e, ui) {
                        if (e.originalEvent && e.originalEvent.originalEvent && e.originalEvent.originalEvent.type == 'click') {
                            $tree.jstree(true).move_node(node, dvTwoColSecondListOldParent, dvTwoColSecondListOldPos);
                        }
                    },
                    buttons: [
                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                            $dialog.dialog('close');
                            twoPanelSecondListSetParentDisplayOrder(data);
                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $tree.jstree(true).move_node(node, dvTwoColSecondListOldParent, dvTwoColSecondListOldPos);
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            } else {
                twoPanelSecondListSetParentDisplayOrder(data);
            }
        }
    });  
});