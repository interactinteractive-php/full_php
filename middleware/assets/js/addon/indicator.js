var isKpiIndicatorScript = true;
var googleMapActiveWindow = null;
var kpIndicatorChart = {};

function manageKpiIndicatorValue(elem, kpiTypeId, indicatorId, isEdit, opt, callback, successCallback, srcIndicatorId) {
    
    var $this = $(elem), mainIndicatorId = $this.attr('data-main-indicatorid'), 
        saveBtnClass = '', mode = '';
        
    if (typeof srcIndicatorId !== 'undefined') {
        mainIndicatorId = srcIndicatorId;
    }
    
    if (typeof mainIndicatorId == 'undefined') {
        mainIndicatorId = indicatorId;
    }

    var postData = {
        param: {
            indicatorId: indicatorId, 
            actionType: $this.attr('data-actiontype')
        }
    };  

    var isNoDataview = false;
    var fcSelectedRow = [];
    
    if ($this.hasClass('no-dataview') && $this.attr('data-rowdata')) {
        isNoDataview = true;
        fcSelectedRow = [JSON.parse($this.attr('data-rowdata'))];
    } else if ($this.closest('.objectdatacustomgrid').length && $this.closest('.objectdatacustomgrid').find('.no-dataview').length) {
        isNoDataview = true;
        fcSelectedRow = $this.closest('.objectdatacustomgrid').find('.no-dataview.active').length ? [JSON.parse($this.closest('.objectdatacustomgrid').find('.no-dataview.active').attr('data-rowdata'))] : [];      
    }

    if (typeof opt == 'undefined' || (typeof opt != 'undefined' && isObject(opt) && Object.keys(opt).length == 0)) {
        
        if (isEdit) {
        
            var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);
            
            if (selectedRows.length) {

                var selectedRow = selectedRows[0];
                postData.param.mainIndicatorId = mainIndicatorId;
                postData.param.dynamicRecordId = selectedRow[window['idField_'+mainIndicatorId]];
                postData.param.idField = window['idField_'+mainIndicatorId];
                postData.selectedRow = selectedRow;

            } else {
                alert(plang.get('msg_pls_list_select'));
                return;
            }
        } 
    
    } else {
        
        if (opt.hasOwnProperty('mode')) {
            mode = opt.mode;
        }
        
        if (opt.hasOwnProperty('isIgnoreRunButton')) {
            postData.isIgnoreRunButton = opt.isIgnoreRunButton;
        }
        if (opt.hasOwnProperty('consolidateFillSelectedRow')) {

            var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);

            if (selectedRows.length) {
                postData.consolidateFillSelectedRows = selectedRows;
                postData.param.mainIndicatorId = mainIndicatorId;
            }

        } else if (opt.hasOwnProperty('transferSelectedRow')) {
            
            var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);
            
            if (selectedRows.length) {
                postData.transferSelectedRow = selectedRows[0];
            } else {
                alert(plang.get('msg_pls_list_select'));
                return;
            }
            
        } else if (opt.hasOwnProperty('transferParams') && isObject(opt.transferParams) && Object.keys(opt.transferParams).length) {

            postData.transferSelectedRow = opt.transferParams;
            
        } else if (opt.hasOwnProperty('fillSelectedRow') && opt.fillSelectedRow) {
            
            var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);
            
            if (selectedRows.length) {
                postData.fillSelectedRow = selectedRows[0];
                postData.param.mainIndicatorId = mainIndicatorId;
            } else if (mode !== 'create') {
                alert(plang.get('msg_pls_list_select'));
                return;
            }
            
        } else if (opt.hasOwnProperty('fillDynamicSelectedRow') && opt.fillDynamicSelectedRow) {
            
            var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);
            
            if (selectedRows.length) {
                postData.fillDynamicSelectedRow = selectedRows[0];
                postData.param.mainIndicatorId = mainIndicatorId;
            } else if (mode !== 'create') {
                alert(plang.get('msg_pls_list_select'));
                return;
            }
            
        } else {
            
            if (opt.hasOwnProperty('recordId')) {
                
                var recordId = opt.recordId;
                postData.param.dynamicRecordId = recordId;
                postData.param.idField = 'idField';
                
            } else if (mode == 'view') {
                
                var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);

                if (selectedRows.length) {

                    var selectedRow = selectedRows[0];
                    postData.param.dynamicRecordId = selectedRow[window['idField_'+mainIndicatorId]];
                    postData.param.idField = window['idField_'+mainIndicatorId];
                    postData.selectedRow = selectedRow;

                } else {
                    alert(plang.get('msg_pls_list_select'));
                    return;
                }
            }
        }                   
    }

    if (mode == 'view') {
        saveBtnClass = 'd-none';
    }
    
    if ($this.hasAttr('data-crud-indicatorid')) {
        postData.param.crudIndicatorId = $this.attr('data-crud-indicatorid');
    }
    
    var isMapId = false, isMapHidden = false, isSrcMap = false, isListRelation = false;
    
    if ($this.hasAttr('data-list-relation') && $this.attr('data-list-relation') == '1') {
        isListRelation = true;
    }
    
    if ($this.hasAttr('data-mapid') && $this.attr('data-mapid') != '') {
        
        var $widgetParent = $this.closest('[data-widget-parent]');
        
        postData.param.mapId = $this.attr('data-mapid');
        postData.param.hiddenParams = $widgetParent.find('input[name="hiddenParams"]').val();
        
        isMapId = true;
        
    } else if ($this.closest('.mv-value-map-render-child').length) {
        
        var $active = $this.closest('.mv-value-map-render-parent').find('ul.nav-sidebar > li.nav-item > a.nav-link.active');
        postData.param.mapHiddenParams = $active.attr('data-hidden-params');
        postData.param.mapHiddenSelectedRow = $active.attr('data-selected-row');
        isMapHidden = true;
        
    } else if ($this.closest('.mv-checklist-render-parent').length) {
        
        if (isListRelation == false) {
            var $checkListParent = $this.closest('.mv-checklist-render-parent');
            var $checkListActive = $checkListParent.find('ul.nav-sidebar a.nav-link.active[data-json]');
            var checkListRowJson = JSON.parse(html_entity_decode($checkListActive.attr('data-json'), 'ENT_QUOTES'));

            postData.mapSrcMapId = checkListRowJson.mapId;
            postData.mapSelectedRow = $checkListParent.find('input[data-path="headerParams"]').val();
            isSrcMap = true;
            
        } else {
            postData.param.isListRelation = 1;
        }
    }
    
    /*if ($this.hasAttr('data-statusconfig') && $this.attr('data-statusconfig') != '') {
        var statusConfig = $this.attr('data-statusconfig');
        postData.wfmStatusParams = JSON.parse(statusConfig);
    }*/
    
    $.ajax({
        type: 'post',
        url: 'mdform/kpiIndicatorTemplateRender',
        data: postData, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            
            if (data.status == 'success') {
                
                if (typeof callback !== 'undefined') {
                    Core.unblockUI();
                    window[callback](data);
                    return false;
                }
                var $dialogName = 'dialog-businessprocess-'+indicatorId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), dialogWidth = 950, dialogHeight = 'auto', dialogTitle = '', uniqId = data.uniqId;
                
                if (kpiTypeId == '1191') {
                    dialogTitle = data.name;
                } else if (mode == 'view') {
                    dialogTitle = data.name+' - '+plang.get('view_btn');
                } else {
                    if (isEdit) {
                        dialogTitle = data.name+' - '+plang.get('edit_btn');
                    } else {
                        dialogTitle = data.name+' - '+plang.get('add_btn');
                    }
                }
                
                if (data.windowWidth) {
                    dialogWidth = data.windowWidth;
                }
                
                if (data.windowHeight) {
                    dialogHeight = data.windowHeight;
                }
                
                var buttons = [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save ' + saveBtnClass, click: function (e) {

                        var $form = $dialog.find('form');    
                        var $dialogSaveBtn = $(e.target);

                        $dialogSaveBtn.attr('disabled', 'disabled').prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                        
                        if (kpiTypeId == '2009') {
                            
                            saveKpiIndicatorFormInit($dialogSaveBtn, uniqId, indicatorId, successCallback);
                            
                        } else if (bpFormValidate($form) && window['kpiIndicatorBeforeSave_' + uniqId]($dialogSaveBtn)) {

                            $form.ajaxSubmit({
                                type: 'post',
                                url: 'mdform/saveKpiDynamicDataByList',
                                dataType: 'json',
                                beforeSubmit: function(formData, jqForm, options) {

                                    if (isMapId) {
                                        formData.push({name: 'mapId', value: postData.param.mapId});
                                        formData.push({name: 'hiddenParams', value: postData.param.hiddenParams});
                                    }
                                    if (isMapHidden) {
                                        formData.push({name: 'mapHidden[params]', value: postData.param.mapHiddenParams});
                                        formData.push({name: 'mapHidden[selectedRow]', value: postData.param.mapHiddenSelectedRow});
                                    }
                                    if (isSrcMap) {
                                        formData.push({name: 'mapSrc[mapSrcMapId]', value: postData.mapSrcMapId});
                                        formData.push({name: 'mapSrc[mapSelectedRow]', value: postData.mapSelectedRow});
                                    }
                                    if (isListRelation && $this.closest('.mv-checklist-render-parent').length) {
                                        var $checkListParent = $this.closest('.mv-checklist-render-parent');
                                        var $checkListActive = $checkListParent.find('ul.nav-sidebar a.nav-link.active[data-json]');
                                        var checkListRowJson = JSON.parse(html_entity_decode($checkListActive.attr('data-json'), 'ENT_QUOTES'));
            
                                        formData.push({name: 'mapSrc[mapSrcMapId]', value: checkListRowJson.mapId});
                                        formData.push({name: 'mapSrc[mapSelectedRow]', value: $checkListParent.find('input[data-path="headerParams"]').val()});
                                    }

                                    if ($this.hasAttr('data-statusconfig') && $this.attr('data-statusconfig') != '') {

                                        var statusConfig = $this.attr('data-statusconfig');
                                        var statusConfigObj = JSON.parse(statusConfig);
                                        var wfmStatusId = selectedRows[0]['wfmstatusid'];

                                        statusConfigObj.mainindicatorid = mainIndicatorId;
                                        statusConfigObj.currentwfmstatusid = wfmStatusId;
                                        statusConfigObj.recordid = selectedRows[0][window['idField_'+mainIndicatorId]];

                                        formData.push({name: 'wfmStatusParams', value: JSON.stringify(statusConfigObj)});
                                    }
                                },
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (responseData) {

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: responseData.status,
                                        text: responseData.message,
                                        type: responseData.status,
                                        sticker: false, 
                                        addclass: pnotifyPosition
                                    });

                                    if (responseData.status == 'success') {
                                        window['kpiIndicatorAfterSave_' + uniqId]($this, responseData.status, responseData);
                                        $dialog.dialog('close');
                                        dataViewReload(mainIndicatorId);
                                        
                                        if (typeof successCallback !== 'undefined' && successCallback && typeof(window[successCallback]) === 'function') {
                                            window[successCallback](responseData);
                                        }
                                    } 

                                    Core.unblockUI();
                                }
                            });
                        }
                        
                        $dialogSaveBtn.removeAttr('disabled').find('i').remove();
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                        $dialog.dialog('close');
                    }}
                ];
                
                if (!isEdit && kpiTypeId != '2009') {
                    
                    buttons.splice(0, 0, {
                        text: plang.get('save_btn_add'),
                        class: 'btn btn-sm green-meadow bp-btn-saveadd',
                        click: function(e) {
                            var $form = $dialog.find('form');    
                            var $dialogSaveBtn = $(e.target);
                            
                            $dialogSaveBtn.attr('disabled', 'disabled').prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                            
                            if (bpFormValidate($form) && window['kpiIndicatorBeforeSave_' + uniqId]($dialogSaveBtn)) {

                                $form.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdform/saveKpiDynamicDataByList',
                                    dataType: 'json',
                                    beforeSubmit: function(formData, jqForm, options) {
                                        if (isMapId) {
                                            formData.push({name: 'mapId', value: postData.param.mapId});
                                            formData.push({name: 'hiddenParams', value: postData.param.hiddenParams});
                                        }
                                        if (isMapHidden) {
                                            formData.push({name: 'mapHidden[params]', value: postData.param.mapHiddenParams});
                                            formData.push({name: 'mapHidden[selectedRow]', value: postData.param.mapHiddenSelectedRow});
                                        }
                                        if (isSrcMap) {
                                            formData.push({name: 'mapSrc[mapSrcMapId]', value: postData.mapSrcMapId});
                                            formData.push({name: 'mapSrc[mapSelectedRow]', value: postData.mapSelectedRow});
                                        }
                                        if (isListRelation && $this.closest('.mv-checklist-render-parent').length) {
                                            var $checkListParent = $this.closest('.mv-checklist-render-parent');
                                            var $checkListActive = $checkListParent.find('ul.nav-sidebar a.nav-link.active[data-json]');
                                            var checkListRowJson = JSON.parse(html_entity_decode($checkListActive.attr('data-json'), 'ENT_QUOTES'));

                                            formData.push({name: 'mapSrc[mapSrcMapId]', value: checkListRowJson.mapId});
                                            formData.push({name: 'mapSrc[mapSelectedRow]', value: $checkListParent.find('input[data-path="headerParams"]').val()});
                                        }
                                    },
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function (data) {

                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false, 
                                            addclass: pnotifyPosition
                                        });

                                        if (data.status == 'success') {

                                            dataViewReload(mainIndicatorId);
                                            bpProcessFieldClear($form, indicatorId);

                                            if (typeof successCallback !== 'undefined') {
                                                window[successCallback]();
                                            }
                                        } 

                                        Core.unblockUI();
                                    }
                                });
                            }

                            $dialogSaveBtn.removeAttr('disabled').find('i').remove();
                        }
                    });
                }
                
                if (data.hasOwnProperty('helpContentId') && data.helpContentId !== null && data.helpContentId !== '') {
                    buttons.splice(0, 0, {
                        text: plang.get('menu_system_guide'),
                        class: 'btn btn-sm green-meadow float-left bp-btn-help',
                        click: function(e) {
                            redirectHelpContent($(e.target), data.helpContentId, indicatorId, 'mv_method');
                        }
                    });
                }
    
                $dialog.empty().append('<form method="post" enctype="multipart/form-data">' + data.html + '</form>');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: dialogTitle,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    open: function() {
                        if (mode == 'view') {
                            $dialog.find('.bp-add-one-row').parent().remove();
                            $dialog.find('.bp-remove-row, button.red, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                            $dialog.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                            $dialog.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                            $dialog.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                            
                            var $radioElements = $dialog.find("input[type='radio']");
                            if ($radioElements.length) {
                                $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                $radioElements.closest('.radio').addClass('disabled');
                            }
                            
                            var $checkElements = $dialog.find("input[type='checkbox']");
                            $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                            $checkElements.closest('.checker').addClass('disabled');
                        }
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
                }).dialogExtend({
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
                
                if (data.windowSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                
                $dialog.dialog('open');
            
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}
function mvNormalRelationRender(elem, kpiTypeId, mainIndicatorId, opt) {
    var $this = $(elem);
    var postData = {
        mainIndicatorId: mainIndicatorId, 
        methodIndicatorId: opt.methodIndicatorId, 
        structureIndicatorId: opt.structureIndicatorId
    };
    var mode = '';
    
    if (opt.hasOwnProperty('mode')) {
        mode = opt.mode;
        if (mode == 'read') {
            mode = 'view';
        }
        if (mode == 'update' || mode == 'view') {
            if (opt.hasOwnProperty('rows')) {
                var selectedRows = opt.rows;
            } else {
                var selectedRows = getDataViewSelectedRows(mainIndicatorId);
            }

            if (selectedRows.length) {

                var selectedRow = selectedRows[0];
                postData.dynamicRecordId = selectedRow[window['idField_'+mainIndicatorId]];
                postData.idField = window['idField_'+mainIndicatorId];
                postData.selectedRow = selectedRow;
                postData.mode = mode;

            } else {
                alert(plang.get('msg_pls_list_select'));
                return;
            }
        }
    }
    
    if (opt.hasOwnProperty('isFillRelation') && opt.isFillRelation != '') {
        postData.isFillRelation = opt.isFillRelation;
    }
    
    if ($this.hasAttr('data-actiontype') && $this.attr('data-actiontype') == 'create' && $this.closest('.mv-checklist-render-parent').length) {
        var $checkListParent = $this.closest('.mv-checklist-render-parent');
        var $checkListActive = $checkListParent.find('ul.nav-sidebar a.nav-link.active[data-json]');
        var checkListRowJson = JSON.parse(html_entity_decode($checkListActive.attr('data-json'), 'ENT_QUOTES'));
        
        postData.mapSrcMapId = checkListRowJson.mapId;
        postData.mapSelectedRow = $checkListParent.find('input[data-path="headerParams"]').val();
    }
    
    if ($this.hasAttr('data-statusconfig') && $this.attr('data-statusconfig') != '') {

        var statusConfig = $this.attr('data-statusconfig');
        var statusConfigObj = JSON.parse(statusConfig);
        var wfmStatusId = selectedRows[0]['wfmstatusid'];

        statusConfigObj.mainindicatorid = mainIndicatorId;
        statusConfigObj.currentwfmstatusid = wfmStatusId;
        statusConfigObj.recordid = selectedRows[0][window['idField_'+mainIndicatorId]];
        
        postData.wfmStatusParams = JSON.stringify(statusConfigObj);
    }
    
    $.ajax({
        type: 'post',
        url: 'mdform/mvNormalRelationRender',
        data: postData, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            if (data.status == 'success') {
                
                if (data.hasOwnProperty('widgetCode') && data.widgetCode == 'developer_workspace') {
                    developerWorkspace(mainIndicatorId, data);
                    return true;
                }
                
                var $dialogName = 'dialog-valuemap-'+mainIndicatorId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: '',
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    open: function() {
                        if (mode == 'view') {
                            $dialog.find('.bp-add-one-row').parent().remove();
                            $dialog.find('.bp-remove-row, button.red, button.green-meadow, button.bp-btn-check, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                            $dialog.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                            $dialog.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                            $dialog.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                            
                            if (!postData.hasOwnProperty('wfmStatusParams')) {
                                $dialog.find('button.bp-btn-save').remove();
                            }
                            
                            var $radioElements = $dialog.find("input[type='radio']");
                            if ($radioElements.length) {
                                $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                $radioElements.closest('.radio').addClass('disabled');
                            }
                            
                            var $checkElements = $dialog.find("input[type='checkbox']");
                            $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                            $checkElements.closest('.checker').addClass('disabled');
                        }
                        $dialog.parent().find(">.ui-dialog-buttonpane").remove();
                        $dialog.parent().find(">.ui-dialog-titlebar").remove();
                        var dh = $dialog.parent().find(">.ui-dialog-content").height() + 110;
                        $dialog.parent().find(">.ui-dialog-content").css("height", dh+"px");                        
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                }).dialogExtend({
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
                
                $dialog.dialogExtend('maximize');
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}
function developerWorkspace(mainIndicatorId, data) {
    var $dialogName = 'dialog-valuemap-'+mainIndicatorId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(data.html);
    $dialog.dialog({
        dialogClass: 'dev-dialog', 
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        open: function() {
            /* disableScrolling(); */
            $('body').addClass("overflow-hidden");
        }, 
        close: function() {
            /* enableScrolling(); */
            $('body').removeClass("overflow-hidden");
            //$('html, body').scrollTop(0);
            $dialog.empty().dialog('destroy').remove();
            
        }
    });
    $dialog.dialog('open');
    
    Core.unblockUI();
}
function mapKpiIndicatorValue(elem, kpiTypeId, mainIndicatorId, typeCode) {
    var $this = $(elem);
    var postData = {mainIndicatorId: mainIndicatorId, actionType: $this.attr('data-actiontype')}; 
    var selectedRows = getDataViewSelectedRows(mainIndicatorId);
    
    if (selectedRows.length) {

        var selectedRow = selectedRows[0];
        postData.dynamicRecordId = selectedRow[window['idField_'+mainIndicatorId]];
        postData.idField = window['idField_'+mainIndicatorId];
        postData.structureIndicatorId = $this.attr('data-structure-indicatorid');
        postData.typeCode = typeCode;
        postData.selectedRow = selectedRow;

    } else {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdform/mapKpiIndicatorValueRender',
        data: postData, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-valuemap-'+mainIndicatorId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    /*dialogClass: 'no-padding-dialog',*/
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: typeCode,
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                }).dialogExtend({
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
                
                $dialog.dialogExtend('maximize');
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}
function renderIframeIndicator(elem) {
    var dialogName = '#dialog-indicator-iframe';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);

    $dialog.html('<iframe style="width: 100%; height: 800px; border: 0" id="kpi-indicator-iframe" src="http://192.168.192.236/erp/mdprocess/kpiIndicatorRender?hash=VyRb826Wu4q4LAt6Jkf11zCzNFBxtttnmhtttHG/r/QH6FbhQqUpbTkMWtttnmhtttURdeqotttnmhttttSqQJk6rpLklGa5dafQg9bgoTZT9byw3kn7/U8kRpcgrtttnmhttt5cap7FeBAODSMNZkvttttnmhtttPOBqtttnmhtttrxA6dO6s3D8rLT8/Zc2Q2x1tttnmhtttV4RErGdH2brNPKB99PlcPY8mNG7qvF7TBLmfgG2DoxR3ShSoLcACHBj4kxv8g1hgttttntsutttttttntsuttt:VjYhKWZUbjddbl5lQnJmeQttttntsutttttttntsuttt"></iframe>');
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'), 
        width: 1200,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function() {
                var $iframe = $dialog.find('iframe');    
                var iframeWin = $iframe[0].contentWindow;
                iframeWin.postMessage('{"kpiIndicatorCommand": "save"}', $iframe.attr('src'));
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function microFlowConfirmationDialog(id, text, indicator, elem, uniqId, indicatorId) {
    var $dialogName = "dialog-microflow-confirmation";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog.empty().append(text);
    $dialog.dialog({
      cache: false,
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      title: "Confirm",
      width: 400,
      height: "auto",
      modal: true,
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: "Тийм",
          class: "btn green-meadow btn-sm",
          click: function () {
            var getResult = $.ajax({
                type: "post",
                url: "mdexpression/executeMicroFlowExpression",
                async: false,
                data: {
                  isConfirmation: 1,
                  flowId: id,
                  microIndicatorId: indicator.id
                },
                dataType: "json"
            });              
            try {
                getResult = getResult.responseJSON;
                if (getResult.status === "microflowConfirmation") {
                    var getFlowId = getResult.data.split('♥');
                    microFlowConfirmationDialog(getFlowId[0], getFlowId[2], getResult.indicator, elem, uniqId, indicatorId);
                } else {
                    saveKpiIndicatorFormInit(elem, uniqId, indicatorId);
                }    
            } catch (e) { }
            $dialog.dialog("close");
          }
        },
        {
          text: "Үгүй",
          class: "btn blue-madison btn-sm",
          click: function () {
            var getResult = $.ajax({
                type: "post",
                url: "mdexpression/executeMicroFlowExpression",
                async: false,
                data: {
                  isConfirmation: 0,
                  flowId: id,
                  microIndicatorId: indicator.id
                },
                dataType: "json"
            });              
            try {
                getResult = getResult.responseJSON;
                if (getResult.status === "microflowConfirmation") {
                    var getFlowId = getResult.data.split('♥');
                    microFlowConfirmationDialog(getFlowId[0], getFlowId[2], getResult.indicator, elem, uniqId, indicatorId);
                } else {
                    saveKpiIndicatorFormInit(elem, uniqId, indicatorId);
                }    
            } catch (e) { }         
            $dialog.dialog("close");
          }
        }
      ]
    });

    $dialog.dialog("open");
}
function saveKpiIndicatorFormInit(elem, uniqId, indicatorId, successCallback) {
    var $this = $(elem);
    
    if (uniqId == '') {
        var $parentForm = $this.closest('form');
        uniqId = $parentForm.find('[data-bp-uniq-id]').attr('data-bp-uniq-id');
    }
    
    var $form = $('div[data-bp-uniq-id="'+uniqId+'"]').closest('form');    

    if (bpFormValidate($form) && window['kpiIndicatorBeforeSave_' + uniqId]($this)) {

        $form.ajaxSubmit({
            type: 'post',
            url: 'mdform/saveKpiDynamicDataByList',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                if (data.status === 'microflowConfirmation') {
                    var getFlowId = data.data.split('♥');
                    microFlowConfirmationDialog(getFlowId[0], getFlowId[2], data.indicator, elem, uniqId, indicatorId);
                    Core.unblockUI();
                    return;
                }

                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });

                if (data.status == 'success') {
                    
                    window['kpiIndicatorAfterSave_' + uniqId]($this, data.status, data);
                    bpProcessFieldClear($form, indicatorId);
                } 

                Core.unblockUI();
            }
        });
    }
    
    if (typeof successCallback !== 'undefined') {
        window[successCallback]();
    }
}
function runKpiIndicatorInternalQueryInit(elem, uniqId, indicatorId) {
    var $this = $(elem);
    var $form = $this.closest('form');    

    if (window['kpiIndicatorBeforeSave_' + uniqId]($this) && bpFormValidate($form)) {

        $form.ajaxSubmit({
            type: 'post',
            url: 'mdform/runInternalQuery',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
                Core.unblockUI();
            }
        });
    }
}

function removeKpiIndicatorValue(elem, indicatorId, successCallback) {
    var $this = $(elem), mainIndicatorId = $this.attr('data-main-indicatorid');    
    if (typeof mainIndicatorId == 'undefined') {
        mainIndicatorId = indicatorId;
    }
    
    var isNoDataview = false 
    var fcSelectedRow = [];

    if ($this.hasClass('no-dataview') && $this.attr('data-rowdata')) {
        isNoDataview = true;
        fcSelectedRow =  [JSON.parse($this.attr('data-rowdata'))];
    } else if ($this.closest('.objectdatacustomgrid').length && $this.closest('.objectdatacustomgrid').find('.no-dataview').length) {
        isNoDataview = true;
        fcSelectedRow = $this.closest('.objectdatacustomgrid').find('.no-dataview.active').length ? [JSON.parse($this.closest('.objectdatacustomgrid').find('.no-dataview.active').attr('data-rowdata'))] : [];      
    }       

    var selectedRows = isNoDataview ? fcSelectedRow : getDataViewSelectedRows(mainIndicatorId);
        
    if (selectedRows.length) {

        var dialogName = '#dialog-kpiindicatorvalue-confirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);

        $dialog.html(plang.get('msg_delete_confirm'));
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'), 
            width: 300,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                    PNotify.removeAll();
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdform/removeKpiDynamicData',
                        data: {
                            listIndicatorId: mainIndicatorId, 
                            indicatorId: indicatorId, 
                            crudIndicatorId: $this.attr('data-crud-indicatorid'), 
                            selectedRows: selectedRows, 
                            idField: window['idField_'+mainIndicatorId]
                        }, 
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function (data) {
                            
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false, 
                                addclass: pnotifyPosition
                            });
                            $dialog.dialog('close');
                            
                            if (data.status == 'success') {
                                dataViewReload(mainIndicatorId);
                            }
                            
                            if (typeof successCallback !== 'undefined') {
                                window[successCallback]($this);
                            }
                            Core.unblockUI();
                        }
                    });
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');

    } else {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
}

function excelImportKpiIndicatorValue(elem, indicatorId) {
    var $this = $(elem), mainIndicatorId = $this.attr('data-main-indicatorid');    
    var dialogName = '#dialog-kpiindicatorvalue-excel';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName), form = [];
    
    form.push('<form method="post" enctype="multipart/form-data">');
        form.push('<div class="col-md-12 xs-form">');
        
            form.push('<div class="form-group row mt10">');
                form.push('<label class="col-form-label col-md-3 text-right pt-1">'+plang.get('excel_template_btn')+':</label>');
                form.push('<div class="col-md-9">');
                    form.push('<a href="javascript:;" onclick="downloadExcelKpiIndicatorValue(this, \''+indicatorId+'\')" class="btn btn-sm purple-plum"><i class="far fa-download"></i> '+plang.get('download_btn')+'</a>');
                form.push('</div>');
            form.push('</div>');
        
            form.push('<div class="form-group row mt20">');
                form.push('<label class="col-form-label col-md-3 text-right pt-1" for="is_data_translate">'+plang.get('exte_translate_btn')+':</label>');
                form.push('<div class="col-md-9">');
                    form.push('<input type="checkbox" value="1" name="is_data_translate" id="is_data_translate" class="mt6">');
                form.push('</div>');
            form.push('</div>');
            
            form.push('<div class="form-group row mt20">');
                form.push('<label class="col-form-label col-md-3 text-right pt-1"><span class="required">*</span>'+plang.get('excel_file_btn')+':</label>');
                form.push('<div class="col-md-9">');
                    form.push('<input type="file" name="excelFile" id="excelFile" class="form-control form-control-sm fileInit" required="required" data-valid-extension="xls, xlsx">');
                form.push('</div>');
            form.push('</div>');
            
        form.push('</div>');
    form.push('</form>');

    $dialog.html(form.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('pf_excel_import'), 
        width: 600,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('import_btn'), class: 'btn green-meadow btn-sm', click: function() {
                PNotify.removeAll();
                
                var $form = $dialog.find('form');
                $form.validate({ errorPlacement: function() {} });

                if ($form.valid()) {
                    
                    var $fileInput = $form.find('.fileInit');
                    var file = $fileInput[0].files[0];
                    var reader = new FileReader();
                    
                    reader.readAsText(file, 'UTF-8');

                    reader.onload = function () {
                        
                        $form.ajaxSubmit({
                            type: 'post',
                            url: 'mdform/excelImportKpiIndicatorValue',
                            dataType: 'json',
                            beforeSubmit: function(formData, jqForm, options) {
                                formData.push({ name: 'indicatorId', value: indicatorId });
                            },
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false, 
                                    delay: 1000000000 
                                });
                                if (data.status === 'success') {
                                    $dialog.dialog('close');
                                    dataViewReload(mainIndicatorId);
                                }
                                Core.unblockUI();
                            }
                        });
                    };

                    reader.onerror = function (err) {
                        var errorMsg = err.target.error.message;
                        
                        if (errorMsg.indexOf('file could not be read') !== -1 || errorMsg.indexOf('ERR_UPLOAD_FILE_CHANGED') !== -1) {
                            $fileInput.val('');
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Info',
                                text: 'Таны сонгосон файл дээр өөрчлөлт орсон тул та файлаа дахин сонгоно уу.',
                                type: 'info',
                                sticker: false, 
                                delay: 1000000000, 
                                addclass: 'pnotify-center'
                            });
                            Core.unblockUI();
                        }
                    };
                }
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function downloadExcelKpiIndicatorValue(elem, indicatorId) {
    
    Core.blockUI({message: 'Loading...', boxed: true});
    var isTranslate = $(elem).closest('form').find('input[name="is_data_translate"]').is(':checked') ? 1 : 0;
    
    $.fileDownload(URL_APP + 'mdform/downloadExcelImportTemplate', {
        httpMethod: 'post',
        data: {indicatorId: indicatorId, isDataTranslate: isTranslate}
    }).done(function() {
        Core.unblockUI();
    }).fail(function(response){
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: response,
            type: 'error',
            sticker: false
        });
        Core.unblockUI();
    });
}
function excelExportKpiIndicatorValue(elem, indicatorId) {

    Core.blockUI({message: 'Exporting...', boxed: true});
    
    var $dvParent = $('div#object-value-list-' + indicatorId);
    var getSortFields = getDataGridSortFields($dvParent);
    var postParams = {
        indicatorId: indicatorId,
        filterRules: getDataViewFilterRules(indicatorId, false), 
        sortFields: getSortFields, 
        total: window['objectdatagrid_' + indicatorId].datagrid('getPager').pagination('options').total
    };
    
    var $col = $dvParent.find('.kpidv-data-filter-col');
    var getFilterData = getKpiIndicatorFilterData(elem, $col);
    var q = window['objectdatagrid_' + indicatorId].datagrid('options').queryParams;
    
    postParams.filterData = getFilterData.filterData;
    
    if (q.hasOwnProperty('postHiddenParams') && q.postHiddenParams) {
        postParams.postHiddenParams = q.postHiddenParams;
    } 
    
    if (q.hasOwnProperty('drillDownCriteria') && q.drillDownCriteria) {
        postParams.drillDownCriteria = q.drillDownCriteria;
    } 
    
    if (q.hasOwnProperty('filter') && q.filter) {
        postParams.filter = q.filter;
    } 

    $.fileDownload(URL_APP + 'mdform/indicatorExcelExport', {
        httpMethod: 'POST',
        data: postParams 
    }).done(function() {
        Core.unblockUI();
    }).fail(function(response) {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: response,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });
        Core.unblockUI();
    });
}
function exportKpiIndicatorValue(elem, indicatorId) {
    var $this = $(elem), mainIndicatorId = $this.attr('data-main-indicatorid');    
    
    if (typeof mainIndicatorId == 'undefined') {
        mainIndicatorId = indicatorId;
    }
    
    var selectedRows = getDataViewSelectedRows(mainIndicatorId);
        
    if (selectedRows.length) {
        
        Core.blockUI({message: 'Exporting...', boxed: true});
        $.fileDownload(URL_APP + 'mdform/indicatorRowExport', {
            httpMethod: 'POST',
            data: {indicatorId: mainIndicatorId, selectedRows: selectedRows, idField: window['idField_'+mainIndicatorId]} 
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                addclass: pnotifyPosition,
                sticker: false
            });
            Core.unblockUI();
        });

    } else {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
}
function exportExcelOneLineKpiIndicatorValue(elem, indicatorId) {
    var $this = $(elem), mainIndicatorId = $this.attr('data-main-indicatorid');    
    
    if (typeof mainIndicatorId == 'undefined') {
        mainIndicatorId = indicatorId;
    }
    
    var selectedRows = getDataViewSelectedRows(mainIndicatorId);
        
    if (selectedRows.length) {
        
        Core.blockUI({message: 'Exporting...', boxed: true});
        $.fileDownload(URL_APP + 'mdform/indicatorRowExcelExportOneLine', {
            httpMethod: 'POST',
            data: {indicatorId: mainIndicatorId, selectedRows: selectedRows, idField: window['idField_'+mainIndicatorId]} 
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                addclass: pnotifyPosition,
                sticker: false
            });
            Core.unblockUI();
        });

    } else {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
}
function dataImportKpiIndicatorValue(elem, indicatorId, listIndicatorId) {
    $.ajax({
        type: 'post',
        url: 'mdform/importManagePopup',
        data: {mainIndicatorId: indicatorId, dataViewId: (typeof listIndicatorId != 'undefined') ? listIndicatorId : null},
        dataType: 'html',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var dialogName = '#dialog-kpiindicator-dataimport';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            var $dialog = $(dialogName);

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('Импорт'), 
                width: $(window).width(),
                height: $(window).height(),
                modal: true,
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}
function mvRecordRelationExcelExport(elem) {
    var $this = $(elem), $parentRow = $this.closest('tr');
    var $indicatorElem = $parentRow.find('input[name="metaDmRecordMaps[indicatorId][]"]');
    var $recordElem = $parentRow.find('input[name="metaDmRecordMaps[recordId][]"]');
        
    if ($indicatorElem.length && $recordElem.length && $indicatorElem.val() != '' && $recordElem.val() != '') {
        
        Core.blockUI({message: 'Exporting...', boxed: true});
        $.fileDownload(URL_APP + 'mdform/indicatorRowExport', {
            httpMethod: 'POST',
            data: {indicatorId: $indicatorElem.val(), selectedRow: {ID: $recordElem.val()}, idField: 'ID'} 
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                addclass: pnotifyPosition,
                sticker: false
            });
            Core.unblockUI();
        });

    } else {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
}
function addRowKpiIndicatorTemplate(elem) {
    var $this = $(elem);
    var $parent = $this.closest('div'), 
        $nextDiv = $parent.next('div'), 
        $table = $nextDiv.find('table.table:eq(0)');     
    
    var groupPath = $table.attr('data-table-path'), 
        $tbody = $table.find('> tbody'), 
        rowLimit = Number($this.attr('data-row-limit')),
        $form = $this.closest('[data-addonform-uniqid]'), 
        uniqId = '';
    
    if ($form.length) {
        uniqId = $form.attr('data-addonform-uniqid');
    } else {
        uniqId = $this.closest('.kpi-ind-tmplt-section[data-bp-uniq-id]').attr('data-bp-uniq-id');
    }
    
    var $script = $('script[data-template="rows"][data-uniqid="'+uniqId+'"][data-rows-path="'+groupPath+'"]');
    
    if ($this.hasClass('bp-add-one-row-num')) {
        var $addRowNum = $this;
    } else {
        var $addRowNum = $this.prev('input.bp-add-one-row-num');
    }
    
    if (rowLimit > 0) {
        var alreadyRowsLen = Number($tbody.find('> tr.bp-detail-row').length);
        if (rowLimit <= alreadyRowsLen) {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Мөрийн хязгаар дүүрсэн байна!',
                type: 'info',
                addclass: pnotifyPosition,
                sticker: false
            });      
            return;
        }
    }
    
    if ($addRowNum.length && $addRowNum.val() != '') {
        
        var addRowNumVal = Number($addRowNum.val());
        
        if (rowLimit > 0 && alreadyRowsLen > 0) {
            addRowNumVal = rowLimit - alreadyRowsLen;
        }
        
        var addingRows = ($script.text()).repeat(addRowNumVal);
        
        $tbody.append(addingRows).promise().done(function() {
            
            $addRowNum.val('');
            
            mvInitControls($tbody);
            
            $tbody.find('input:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete):visible:first').focus().select();

            setRowNumKpiIndicatorTemplate($tbody);
            
            if ($table.hasClass('bprocess-table-subdtl')) {
                var rowIndex = $tbody.closest('.bp-detail-row').index();
                kpiSetRowIndex($tbody, rowIndex);
            } else {
                kpiSetRowIndex($tbody);
            }
            
            var $rowEl = $tbody.find('> .bp-detail-row');
            var rowLen = $rowEl.length, rowi = 0;
                
            if (rowLen === 1) {
                
                window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true, true);

            } else if (rowLen > 1) {

                var rowLen = rowLen - 1;

                for (rowi; rowi < rowLen; rowi++) { 
                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true, false);
                }
                
                window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowLen]), groupPath, true, true);
            }
            
            bpDetailFreeze($table);
            window['dtlAggregateFunction_'+uniqId]();
        });
    
    } else {
        
        $tbody.append($script.text()).promise().done(function() {
            var $lastRow = $tbody.find('> .bp-detail-row:last');
            
            mvInitControls($lastRow);
            setRowNumKpiIndicatorTemplate($tbody);
            
            if ($table.hasClass('bprocess-table-subdtl')) {
                var rowIndex = $tbody.closest('.bp-detail-row').index();
                kpiSetRowIndex($tbody, rowIndex);
            } else {
                kpiSetRowIndex($tbody);
            }
            
            window['bpFullScriptsWithoutEvent_'+uniqId]($lastRow, groupPath, false, true);
            
            bpDetailFreeze($table);
            
            window['dtlAggregateFunction_'+uniqId]();
        });
    }
}

function generateKpiDataMart(elem, indicatorId) {
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdform/generateKpiDataMartByPost',
        data: {indicatorId: indicatorId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                addclass: pnotifyPosition,
                sticker: false
            });
            Core.unblockUI();
            
            if (data.status === 'success') {
                dataViewReload(indicatorId);
            }
        }
    });
}
function generateDataMartSqlView(elem, indicatorId) {
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdform/generateKpiDataMartByPost',
        data: {indicatorId: indicatorId, isSqlView: 1},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.status != 'success') {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
            
            Core.unblockUI();
            
            if (data.status === 'success') {
                
                var dialogName = '#dialog-kpiindicatorvalue-sql';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName), form = [];

                form.push('<div class="col-md-12 xs-form">');

                    form.push('<div class="form-group row mt20">');
                        form.push('<div class="col-md-12">');
                            form.push('<textarea class="form-control form-control-sm" rows="30" readonly>'+data.sql+'</textarea>');
                        form.push('</div>');
                    form.push('</div>');

                form.push('</div>');

                $dialog.html(form.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'SQL харах', 
                    width: 800,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
        }
    });
}
function generateKpiRawDataMart(elem, indicatorId) {
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdform/generateKpiDataMartByPost',
        data: {indicatorId: indicatorId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.hasOwnProperty('html') && data.html) {
                
                var $dialogName = 'dialog-businessprocess-'+indicatorId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), dialogWidth = 950, dialogHeight = 'auto', dialogTitle = data.name, uniqId = data.uniqId;
                
                if (data.windowWidth) {
                    dialogWidth = data.windowWidth;
                }
                
                if (data.windowHeight) {
                    dialogHeight = data.windowHeight;
                }
                
                var buttons = [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save', click: function (e) {

                        var $form = $dialog.find('form');    
                        var $dialogSaveBtn = $(e.target);

                        $dialogSaveBtn.attr('disabled', 'disabled').prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                        
                        if (window['kpiIndicatorBeforeSave_' + uniqId]($dialogSaveBtn) && bpFormValidate($form)) {

                            $form.ajaxSubmit({
                                type: 'post',
                                url: 'mdform/saveKpiDynamicDataByList',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (data) {

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        addclass: pnotifyPosition
                                    });

                                    if (data.status == 'success') {
                                        $dialog.dialog('close');
                                        dataViewReload(indicatorId);
                                    } 

                                    Core.unblockUI();
                                }
                            });
                        }
                        
                        $dialogSaveBtn.removeAttr('disabled').find('i').remove();
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                        $dialog.dialog('close');
                    }}
                ];
    
                $dialog.empty().append('<form method="post" enctype="multipart/form-data">' + data.html + '</form>');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: dialogTitle,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
                }).dialogExtend({
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
                
                if (data.windowSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });

                if (data.status === 'success') {
                    dataViewReload(indicatorId);
                }
            }
            
            Core.unblockUI();
        }
    });
}
function callWebServiceKpiIndicatorValue(elem, indicatorId) {
    PNotify.removeAll();
    
    var paramData = [];
    paramData.push({fieldPath: 'indicatorId', inputPath: 'indicatorId', value: indicatorId});
    
    $.ajax({
        type: 'post',
        url: 'mdform/callWebservice',
        data: {processCode: 'KPI_CALL_WEBSERVICE', indicatorId: indicatorId, paramData: paramData},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.hasOwnProperty('html') && data.html) {
                
                var $dialogName = 'dialog-businessprocess-'+indicatorId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), dialogWidth = 950, dialogHeight = 'auto', dialogTitle = data.name, uniqId = data.uniqId;
                
                if (data.windowWidth) {
                    dialogWidth = data.windowWidth;
                }
                
                if (data.windowHeight) {
                    dialogHeight = data.windowHeight;
                }
                
                var buttons = [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save', click: function (e) {

                        var $form = $dialog.find('form');    
                        var $dialogSaveBtn = $(e.target);

                        $dialogSaveBtn.attr('disabled', 'disabled').prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                        
                        if (window['kpiIndicatorBeforeSave_' + uniqId]($dialogSaveBtn) && bpFormValidate($form)) {

                            $form.ajaxSubmit({
                                type: 'post',
                                url: 'mdform/saveKpiDynamicDataByList',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (data) {

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        addclass: pnotifyPosition
                                    });

                                    if (data.status == 'success') {
                                        $dialog.dialog('close');
                                        dataViewReload(indicatorId);
                                    } 

                                    Core.unblockUI();
                                }
                            });
                        }
                        
                        $dialogSaveBtn.removeAttr('disabled').find('i').remove();
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                        $dialog.dialog('close');
                    }}
                ];
    
                $dialog.empty().append('<form method="post" enctype="multipart/form-data">' + data.html + '</form>');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: dialogTitle,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
                }).dialogExtend({
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
                
                if (data.windowSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });

                if (data.status == 'success') {
                    dataViewReload(indicatorId);
                } 
            }
            
            Core.unblockUI();
        }
    });
}
function mvExecuteCheckQuery(elem, indicatorId) {
    PNotify.removeAll();
    
    var paramData = [];
    paramData.push({fieldPath: 'indicatorId', inputPath: 'indicatorId', value: indicatorId});
    
    $.ajax({
        type: 'post',
        url: 'mdform/callWebservice',
        data: {processCode: 'executeCheckQuery', paramData: paramData},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            new PNotify({
                title: data.status,
                text: data.text,
                type: data.status,
                addclass: pnotifyPosition,
                sticker: false
            });
                
            if (data.status == 'success') {
                dataViewReload(indicatorId);
                bpVisiblePanelDataViewReload('secondList');
            } 
            
            Core.unblockUI();
        }
    });
}
function mvExecuteFixQuery(elem, indicatorId) {
    PNotify.removeAll();
    
    var paramData = [];
    paramData.push({fieldPath: 'indicatorId', inputPath: 'indicatorId', value: indicatorId});
    
    $.ajax({
        type: 'post',
        url: 'mdform/callWebservice',
        data: {processCode: 'executeFixQuery', paramData: paramData},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            new PNotify({
                title: data.status,
                text: data.text,
                type: data.status,
                addclass: pnotifyPosition,
                sticker: false
            });
                
            if (data.status == 'success') {
                dataViewReload(indicatorId);
                bpVisiblePanelDataViewReload('secondList');
            } 
            
            Core.unblockUI();
        }
    });
}
function pivotKpiIndicatorValue(elem, indicatorId) {

    var $dvParent = $('div#object-value-list-' + indicatorId);
    var getSortFields = getDataGridSortFields($dvParent);
    var postParams = {
        indicatorId: indicatorId,
        filterRules: getDataViewFilterRules(indicatorId, false), 
        sortFields: getSortFields, 
        total: window['objectdatagrid_' + indicatorId].datagrid('getPager').pagination('options').total, 
        isIgnorePopupSearch: 1
    };
    
    var $col = $dvParent.find('.kpidv-data-filter-col');
    var $activeList = $col.find('.list-group-item-action.active'); 
    var $namedParamList = $col.find('[data-named-param="1"]');
    var filterData = {};
    
    if ($activeList.length) {
        
        $activeList.each(function(i) {
            var $this = $(this);
            var colName = $this.attr('data-colname');
            var text = $this.find('span:eq(0)').text();
            var list = filterData[colName];
            
            if (list) {
                list.push(text);
            } else{
                filterData[colName] = [text];
            }
        });
    }    
    
    if ($namedParamList.length) {
        $namedParamList.each(function() {
            var $this = $(this), $input = $this.find('[data-path]');
            
            if ($input.length == 1) {
                var namedParam = $input.attr('data-path');
                
                if ($input.hasClass('bigdecimalInit') || $input.hasClass('longInit') || $input.hasClass('integerInit') || $input.hasClass('amountInit')) {
                    var filterVal = $input.autoNumeric('get');
                } else {
                    var filterVal = $input.val();
                }
                
                filterData[namedParam] = filterVal;
            }
        });
    }
    
    if (Object.keys(filterData).length > 0) {
        postParams.filterData = filterData;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdpivot/dataViewPivotView',
        data: postParams,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (data.status == 'success') {

                var $dialogName = 'dialog-dv-pivot-' + indicatorId;
                if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
                var $dialog = $('#' + $dialogName);
                var windowHeight = $(window).height();

                $dialog.empty().append(data.html);
                var $detachedChildren = $dialog.children().detach();

                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    draggable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: $(window).width() - 20,
                    height: windowHeight - 10,
                    modal: true,
                    position: {my: 'top', at: 'top+0'},
                    closeOnEscape: isCloseOnEscape,
                    open: function() {
                        $detachedChildren.appendTo($dialog);
                        Core.initSelect2($dialog);
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: plang.get('close_btn'),
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }

            Core.unblockUI();
        }
    });
}

function googleMapKpiIndicatorValue(elem, indicatorId, path) {
    var $this = $(elem);
    var $grid = $('.div-objectdatagrid-'+indicatorId);
    var $datagrid = $grid.find('> .panel-eui:eq(0)');
    var $gmap = $grid.find('> #md-map-canvas-'+indicatorId);
    
    if ($this.hasAttr('data-gmap')) {
        
        $this.removeAttr('data-gmap');
        $this.find('i').removeClass('fa-th-list').addClass('fa-map-marker');
        $gmap.hide();
        $datagrid.show();
        window['isGoogleMapView_' + indicatorId] = false;
        
    } else {
        
        $this.attr('data-gmap', '1');
        $this.find('i').removeClass('fa-map-marker').addClass('fa-th-list');
        $datagrid.hide();
        $gmap.show();
        
        $gmap.css('height', ($(window).height() - $grid.offset().top - 40));
        
        window['isGoogleMapView_' + indicatorId] = true;
        initGoogleMapCoordinateKpiIndicator(elem, indicatorId, path);
    }
}

function initGoogleMapCoordinateKpiIndicator(elem, indicatorId, path) {
    
    var $dvParent = $('div#object-value-list-' + indicatorId);
    var $col = $dvParent.find('.kpidv-data-filter-col');
    var $activeList = $col.find('.list-group-item-action.active'); 
    var filterData = {};
    
    if ($activeList.length) {
        
        $activeList.each(function() {
            var $this = $(this);
            var colName = $this.attr('data-colname');
            var text = $this.find('span:eq(0)').text();
            var list = filterData[colName];
            
            if (list) {
                list.push(text);
            } else{
                filterData[colName] = [text];
            }
        });
    } 
    
    var dvSearchParam = {
        indicatorId: indicatorId,
        filterData: filterData, 
        isGoogleMap: 1,
        page: 1, 
        rows: 50000
    };    
    
    $.ajax({
        type: 'post',
        url: 'mdform/indicatorDataGrid',
        data: dvSearchParam,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.status == 'success') {
                
                if (window.google && google.maps) {
                    kpiIndicatorGoogleMapViewLoad(indicatorId, data);
                } else {
                    $.getScript('https://maps.googleapis.com/maps/api/js?sensor=true&key=' + gmapApiKey + '&language='+sysLangCode).done(function() {
                        kpiIndicatorGoogleMapViewLoad(indicatorId, data);
                    });
                }
    
            } else {
                console.log(data);
            }
            
            Core.unblockUI();
        }
    });
}
function faUnicode(name) {
  var testI = document.createElement('i');
  var char;

  testI.className = 'fa ' + name;
  document.body.appendChild(testI);

  char = window.getComputedStyle(testI, ':before').content.replace(/'|"/g, '');

  testI.remove();

  return char.charCodeAt(0).toString(16);
}

function kpiIndicatorGoogleMapViewLoad(indicatorId, data, map) {
    
    if (typeof map == 'undefined') {
            
        var mapOptions = {
            zoom: 6,
            center: new google.maps.LatLng(47.919128, 106.917609),
            mapTypeId: google.maps.MapTypeId.HYBRID, 
            mapTypeControl: true,
            disableDefaultUI: false,
            mapTypeControlOptions: {
                position: google.maps.ControlPosition.TOP_LEFT, 
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            panControl: !0,
            panControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoomControl: !0,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            streetViewControl: !0,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: !0,
            scaleControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            }
        };
        var map = new google.maps.Map(document.getElementById('md-map-canvas-' + indicatorId), mapOptions);
    }
    
    window['kpiMapLayer_' + indicatorId] = [];    
    
    var polylineField = data.polylineField;
    var polylineConnectionField = data.polylineConnectionField;
    var showColumns = data.showColumns;
    var infowindow = new google.maps.InfoWindow({
        disableAutoPan: true,
        isHidden: false,
        pixelOffset: new google.maps.Size(0, -2),
        closeBoxURL: '',
        pane: 'mapPane',
        enableEventPropagation: true
    });
    googleMapActiveWindow = null;

    if (polylineField != '') {
    
        var bounds = new google.maps.LatLngBounds();
        var polygons = [];
        var arr = new Array();
        var rows = data.rows;
        var firstGroupKey = '';
        
        for (var i in rows) {
            
            var subRows = rows[i];
            var subRow = subRows[0];
            arr = [];

            /*if (firstGroupKey) {
                var lastElement = rows[firstGroupKey][rows[firstGroupKey].length - 1];
                var polylineRow = lastElement[polylineField];
                
                if (polylineRow != null && polylineRow != '' && (polylineRow.indexOf('|') !== -1 || polylineRow.indexOf(',') !== -1)) {
            
                    if (polylineRow.indexOf('|') !== -1) {
                        var polylineRowArr = polylineRow.split('|');
                    } else {
                        var polylineRowArr = polylineRow.split(',');
                    }
                
                    arr.push(new google.maps.LatLng(
                        parseFloat((polylineRowArr[0]).trim()),
                        parseFloat((polylineRowArr[1]).trim())
                    ));
                }           
            }*/

            for (var j = 0; j < subRows.length; j++) {
                
                var polylineRow = subRows[j][polylineField];
                
                if (polylineRow != null && polylineRow != '' && (polylineRow.indexOf('|') !== -1 || polylineRow.indexOf(',') !== -1 || polylineRow.indexOf(' ') !== -1)) {
            
                    if (polylineRow.indexOf('|') !== -1) {
                        var polylineRowArr = polylineRow.split('|');
                    } else if (polylineRow.indexOf(',') !== -1) {
                        var polylineRowArr = polylineRow.split(',');
                    } else {
                        var polylineRowArr = polylineRow.split(' ');
                    }
                
                    arr.push(new google.maps.LatLng(
                        parseFloat((polylineRowArr[0]).trim()),
                        parseFloat((polylineRowArr[1]).trim())
                    ));

                    bounds.extend(arr[arr.length - 1]);
                }
            }

            firstGroupKey = i;
            
            var color = '#FF0000';
            
            if (subRow['C4'] == 'Асфальтбетон') {
                color = '#6413e6';
            } else if (subRow['C4'] == 'Ердийн хөрсөн') {
                color = '#Ffd800';
            } else if (subRow['C4'] == 'Сайжруулсан хөрсөн' || subRow['C4'] == 'Сайжруулсан') {
                color = '#12ff00';
            } else if (subRow['C4'] == 'Цементбетон') {
                color = '#00ffea';
            } else if (subRow['C4'] == 'Төмөр зам') {
                color = '#266cff';
            } else if (subRow['C4'] == 'Авто зам') {
                color = '#c03376';
            } 
            
            polygons.push(new google.maps.Polyline({
                path: arr,
                strokeColor: color,
                strokeOpacity: 1,
                strokeWeight: 3,
                fillColor: color,
                fillOpacity: 1, 
                rowData: subRow
            }));
            
            var polygonsLength = polygons.length;
            
            polygons[polygonsLength - 1].setMap(map);

            google.maps.event.addListener(polygons[polygonsLength - 1], 'click', function(event) {
                
                if (googleMapActiveWindow) googleMapActiveWindow.close();
                
                infowindow.setContent(showGoogleMapInfoWindow(showColumns, this.get('rowData')));
                infowindow.open(map);
                infowindow.setPosition(event.latLng);
                map.panTo(event.latLng);

                googleMapActiveWindow = infowindow;
            });
        }
        
        window['kpiMapLayer_' + indicatorId] = polygons;
        
        google.maps.event.addListener(map, 'click', function(){
            infowindow.close(map);
        });
        
        map.fitBounds(bounds);
        map.setCenter(map.getCenter());
  
        return;
    }

    if (polylineConnectionField !== '') {
        var bounds = new google.maps.LatLngBounds();
        var polygons = [];
        var arr = new Array();
        var rows = data.rows;
        var firstGroupKey = '';
        var color = '#FF0000';

        for (var i in rows) {
            
            var subRows = rows[i];
            
            if (typeof subRows[polylineConnectionField] !== 'undefined' && subRows[polylineConnectionField]) {
                var polylineRow = JSON.parse(subRows[polylineConnectionField].replace(/&quot;/ig,'"'));
                
                polygons.push(new google.maps.Polyline({
                    path: polylineRow.coordinates,
                    strokeColor: polylineRow.color,
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    fillColor: polylineRow.color,
                    fillOpacity: 1, 
                    rowData: polylineRow
                }));
            
                var polygonsLength = polygons.length;
                
                polygons[polygonsLength - 1].setMap(map);
    
                google.maps.event.addListener(polygons[polygonsLength - 1], 'click', function(event) {
                    
                    if (googleMapActiveWindow) googleMapActiveWindow.close();
                    
                    infowindow.setContent(showGoogleMapInfoWindow(showColumns, this.get('rowData')));
                    infowindow.open(map);
                    infowindow.setPosition(event.latLng);
                    map.panTo(event.latLng);
    
                    googleMapActiveWindow = infowindow;
                });
            }
        }
        
        window['kpiMapLayer_' + indicatorId] = polygons;
        
        google.maps.event.addListener(map, 'click', function(){
            infowindow.close(map);
        });
        
        map.fitBounds(bounds);
        map.setCenter(new google.maps.LatLng(47.919128, 106.917609));
  
        return;
    }

    var rows = data.rows;
    var coordinateField = data.coordinateField;
    var polygonField = data.polygonField;
    var isAddonPhoto = data.isAddonPhoto;
    var color = (data.color != '' && data.color != null) ? data.color : '#E63D5F';
    var icon = data.icon ? String.fromCodePoint(parseInt(faUnicode(data.icon), 16)) : null;
    
    var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';
    var SQUARE_PIN = 'M22-48h-44v43h16l6 5 6-5h16z';
    var SHIELD = 'M18.8-31.8c.3-3.4 1.3-6.6 3.2-9.5l-7-6.7c-2.2 1.8-4.8 2.8-7.6 3-2.6.2-5.1-.2-7.5-1.4-2.4 1.1-4.9 1.6-7.5 1.4-2.7-.2-5.1-1.1-7.3-2.7l-7.1 6.7c1.7 2.9 2.7 6 2.9 9.2.1 1.5-.3 3.5-1.3 6.1-.5 1.5-.9 2.7-1.2 3.8-.2 1-.4 1.9-.5 2.5 0 2.8.8 5.3 2.5 7.5 1.3 1.6 3.5 3.4 6.5 5.4 3.3 1.6 5.8 2.6 7.6 3.1.5.2 1 .4 1.5.7l1.5.6c1.2.7 2 1.4 2.4 2.1.5-.8 1.3-1.5 2.4-2.1.7-.3 1.3-.5 1.9-.8.5-.2.9-.4 1.1-.5.4-.1.9-.3 1.5-.6.6-.2 1.3-.5 2.2-.8 1.7-.6 3-1.1 3.8-1.6 2.9-2 5.1-3.8 6.4-5.3 1.7-2.2 2.6-4.8 2.5-7.6-.1-1.3-.7-3.3-1.7-6.1-.9-2.8-1.3-4.9-1.2-6.4z';
    var ROUTE = 'M24-28.3c-.2-13.3-7.9-18.5-8.3-18.7l-1.2-.8-1.2.8c-2 1.4-4.1 2-6.1 2-3.4 0-5.8-1.9-5.9-1.9l-1.3-1.1-1.3 1.1c-.1.1-2.5 1.9-5.9 1.9-2.1 0-4.1-.7-6.1-2l-1.2-.8-1.2.8c-.8.6-8 5.9-8.2 18.7-.2 1.1 2.9 22.2 23.9 28.3 22.9-6.7 24.1-26.9 24-28.3z';
    var SQUARE = 'M-24-48h48v48h-48z';
    var SQUARE_ROUNDED = 'M24-8c0 4.4-3.6 8-8 8h-32c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h32c4.4 0 8 3.6 8 8v32z';
    var markerIconObj = {
        path: MAP_PIN, 
        scale: 0.6,
        strokeWeight: 0.2,
        strokeColor: 'black',
        strokeOpacity: 1,
        fillColor: color,
        fillOpacity: 1, 
        labelOrigin: new google.maps.Point(0, -27)
    };
    
    if (icon) {
        var markerObjLabel = {
            fontFamily: "'Font Awesome 5 Pro'",
            text: icon, 
            color: '#fff', 
            fontSize: '12px'
        };
    }
    
    var savedPolygonData = [];
    
    if (polygonField) {
        $('.indicator-polygon-data').attr('data-indicator-id', indicatorId);
        $('.md-map-filter-panel-indicator').show();
        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON
                    //google.maps.drawing.OverlayType.RECTANGLE
                ]
            },
            polylineOptions: shapeOptions,
            rectangleOptions: shapeOptions,
            circleOptions: shapeOptions,
            polygonOptions: shapeOptions
        });
        drawingManager.setMap(map);
        googleMapInitContextMenu();
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {

            var newShape = e.overlay;
            currentPolygon = newShape;
            currentPolygonIndicatorId = indicatorId;
            googleMapSetSelection(newShape);

            if (e.type == 'circle') {
                var option = drawingManager.get('circleOptions');
                googleMapDataList = '{"drawType": "circle", "color": "' + option.strokeColor + '", "center": {"lat": ' + e.overlay.getCenter().lat() + ', "lng": ' + e.overlay.getCenter().lng() + '}, "radius": ' + e.overlay.getRadius() + '}';
            } else if (e.type == 'polygon') {
                var option = drawingManager.get('polygonOptions');
                $.each(e.overlay.getPath().getArray(), function(key, value) {
                    if (key == '0') {
                        coordinate = '{"lat": ' + this.lat() + ', "lng": ' + this.lng() + '}';
                    } else {
                        coordinate = coordinate + ', {"lat": ' + this.lat() + ', "lng": ' + this.lng() + '}';
                    }
                });

                googleMapDataList = '{"drawType": "polygon", "color": "' + option.strokeColor + '", "center": {"lat": ' + map.getCenter().lat() + ', "lng": ' + map.getCenter().lng() + '}, "coordinates": [' + coordinate + ']}';
            } else if (e.type == 'polyline') {
                var option = drawingManager.get('polylineOptions');
                $.each(e.overlay.getPath().getArray(), function(key, value) {
                    if (key == '0') {
                        coordinate = '{"lat": ' + this.lat() + ', "lng": ' + this.lng() + '}';
                    } else {
                        coordinate = coordinate + ', {"lat": ' + this.lat() + ', "lng": ' + this.lng() + '}';
                    }
                });
                googleMapDataList = '{"drawType": "polyline", "color": "' + option.strokeColor + '", "center": {"lat": ' + map.getCenter().lat() + ', "lng": ' + map.getCenter().lng() + '}, "coordinates": [' + coordinate + ']}';
            } else {
                var option = drawingManager.get('rectangleOptions');
                var ne = e.overlay.getBounds().getNorthEast();
                var sw = e.overlay.getBounds().getSouthWest();
                coordinate = '{"R": ' + e.overlay.getBounds().R.R + ', "j": ' + e.overlay.getBounds().R.j + '}, {"R": ' + e.overlay.getBounds().j.R + ', "j": ' + e.overlay.getBounds().j.j + '}';
                googleMapDataList = '{"drawType": "rectangle", "color": "' + option.strokeColor + '", "center": {"lat": ' + map.getCenter().lat() + ', "lng": ' + map.getCenter().lng() + '}, "coordinates": [' + coordinate + ']}';
            }
            drawingManager.setDrawingMode(null);
            google.maps.event.addListener(e.overlay, 'rightclick', function(mouseEvent) {
                googleMapContextMenu.show(mouseEvent.latLng);
            });            
        });    
    }
    
    if (typeof window['kpiMarkerObject'] == 'undefined') {
        window['kpiMarkerObject'] = [];
    }
    
    var roId = (rows.length && rows[0].hasOwnProperty('RULE_CODE')) ? rows[0]['RULE_CODE'] : '';
    var rowsLength = rows.length, i = 0;
    
    for (i; i < rowsLength; i++) {
        var row = rows[i];
        var coordinateVal = row[coordinateField];

        if (polygonField) {
            var polygonVal = row[polygonField];
            if (polygonVal != '' && polygonVal != null && polygonVal.indexOf('{') !== -1) {           
                try {
                    polygonVal = JSON.parse(html_entity_decode(polygonVal, 'ENT_QUOTES'));
                    var indicatorPolygon = new google.maps.Polygon({
                        paths: polygonVal.coordinates,
                        poool: 1,
                        strokeColor: (row.REGION_COLOR ? row.REGION_COLOR : polygonVal.color),
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: (row.REGION_COLOR ? row.REGION_COLOR : polygonVal.color),
                        fillOpacity: 0.35
                    });    
                    indicatorPolygon.setMap(map);              
                    window['kpiMapLayer_' + indicatorId][row.SEGMENTATION_ID] = indicatorPolygon;                
                    savedPolygonData.push('<div class="mb10 mr-3 cursor-pointer ml1 polygon-row" data-rowdata="'+encodeURIComponent(JSON.stringify(row))+'" style="border-left: 4px solid '+row.REGION_COLOR+';" data-id="'+row.SEGMENTATION_ID+'">\n\
                        <div class="d-flex justify-content-between pt-1">\n\
                            <div class="ml-1"><input type="checkbox" checked id="visible_polygon_btn_'+row.SEGMENTATION_ID+'" class="notuniform visible_polygon_btn"/> <label class="ml-1" for="visible_polygon_btn_'+row.SEGMENTATION_ID+'">'+row.SEGMENTATION_NAME+'</label></div> \n\
                            <div><i class="show_polygon_marker_btn fa fa-map-marker" style="color:'+(row.REGION_COLOR ? row.REGION_COLOR : '#575757')+'" title="marker"></i> '+(roId == 1 ? '<i class="edit_polygon_btn fa fa-edit ml-1" style="color:'+(row.REGION_COLOR ? row.REGION_COLOR : '#575757')+'" title="засах"></i>' : '')+'</div>\n\
                        </div>\n\
                    </div>');

                    indicatorPolygon.addListener('click', function(event) {
                        $.ajax({
                            type: 'post',
                            url: 'mdobject/generateDataviewFields',
                            dataType: "json",
                            data: {metaDataId: "1714556994466811"},
                            beforeSend: function () {
                            },
                            success: function (data) {             
                                var dvFields = data;
                                $.ajax({
                                    type: "post",
                                    url: "api/callDataview",
                                    data: {
                                      dataviewId: "1714556994466811",
                                      criteriaData: {
                                        filterSegmentId: [{ operator: "=", operand: row.SEGMENTATION_ID }],
                                      },
                                    },
                                    dataType: "json",
                                    async: false,
                                    success: function (data) {
                                        if (data.status === "success" && data.result[0]) {                    
                                            var tbl = [];
                                            tbl.push('<table class="table table-bordered">');

                                            for (var s = 0; s < dvFields.length; s++) {
                                                tbl.push('<tr>');
                                                    tbl.push('<td style="background-color: #f5f5f5;">'+plang.get(dvFields[s]['LABEL_NAME'])+':</td>');
                                                    tbl.push('<td>'+dvFieldValueShow(data.result[0][dvFields[s]['FIELD_PATH']])+'</td>');
                                                tbl.push('</tr>');
                                            }

                                            tbl.push('</table>');

                                            $('div[data-id="'+row.SEGMENTATION_ID+'"]').find('.edit_polygon_btn').trigger('click');                    
                                            infowindow.setContent(tbl.join(''));
                                            infowindow.setPosition(event.latLng);
                                            infowindow.open(map);                  
                                        }
                                    }
                                });          
                            },
                            error: function () {
                                alert("Error");
                            }
                        });                    
                    });                

                } catch(e) {}
            }
        }
        
        if (coordinateVal != '' && coordinateVal != null && (coordinateVal.indexOf('|') !== -1 || coordinateVal.indexOf(',') !== -1)) {
            
            if (coordinateVal.indexOf('|') !== -1) {
                var coordinateArr = coordinateVal.split('|');
            } else if (coordinateVal.indexOf(',') !== -1) {
                var coordinateArr = coordinateVal.split(',');
            } else if (coordinateVal.indexOf(' ') !== -1) {
                var coordinateArr = coordinateVal.split(' ');
            } else {
                return;
            }
            
            if (coordinateArr.hasOwnProperty(0) && coordinateArr.hasOwnProperty(1)) {
                
                var lat = parseFloat((coordinateArr[0]).trim());
                var lng = parseFloat((coordinateArr[1]).trim());
                
                if (lat > lng) {
                    var tmpLat = lat;
                    lat = lng;
                    lng = tmpLat;
                }
                
                var markerObj = {
                    position: new google.maps.LatLng(lat, lng),
                    /*animation: google.maps.Animation.DROP,*/
                    map: map,
                    rowData: row
                };
                
                if (typeof row.markerphoto != 'undefined' && row.markerphoto) {
                    markerObj.icon = {
                        url: row.markerphoto,
                        scaledSize: new google.maps.Size(42, 42)
                    };
                } else if (rowsLength < 1000) {
                    markerObj.icon = markerIconObj;
                    
                    if (row.hasOwnProperty('MARKER_COLOR') && row.MARKER_COLOR) {
                        markerObj.icon.fillColor = row.MARKER_COLOR;
                    }
                    
                    if (icon) {
                        markerObj.label = markerObjLabel;
                    }
                }

                var marker = new google.maps.Marker(markerObj);    

                window['kpiMapLayer_' + indicatorId].push(marker); 
                window['kpiMarkerObject'].push(marker); 

                marker.addListener('click', (function(marker, showColumns) {
                    return function() {

                        if (googleMapActiveWindow) googleMapActiveWindow.close();

                        var markerData = marker.rowData;

                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        setTimeout(function(){ marker.setAnimation(null); }, 1000);

                        if (isAddonPhoto == '1') {

                            infowindow.open(map, marker);
                            infowindow.setContent('Loading...');

                            $.ajax({
                                type: 'post',
                                url: 'mdpreview/getEcmContentFiles',
                                data: {structureId: indicatorId, recordId: markerData.ID},
                                dataType: 'json',
                                success: function(response) {
                                    markerData.files = response;

                                    infowindow.setContent(showGoogleMapInfoWindow(showColumns, markerData));
                                    map.panTo(marker.getPosition());

                                    googleMapActiveWindow = infowindow;
                                }
                            });

                        } else {
                            infowindow.setContent(showGoogleMapInfoWindow(showColumns, markerData));
                            infowindow.open(map, marker);
                            map.panTo(marker.getPosition());

                            googleMapActiveWindow = infowindow;
                        }
                    };
                })(marker, showColumns));
            }
        }
    }
    
    if (Object.keys(savedPolygonData).length) {
        $('.indicator-polygon-data').html(savedPolygonData.join(''));
    }
    
    google.maps.event.addListener(map, 'click', function(){
        infowindow.close(map);
    });
    
    map.setCenter(map.getCenter());
}

function showGoogleMapInfoWindow(showColumns, rowData) {
    
    var tbl = [];
    
    tbl.push('<table class="table table-bordered">');
    
    for (var s in showColumns) {
        
        var showCol = showColumns[s];
        var columnName = showCol['COLUMN_NAME'];
        
        if (columnName != 'MODIFIED_DATE' && columnName != 'MODIFIED_USER_NAME') {
            
            if (columnName == 'CREATED_DATE' && rowData.hasOwnProperty('files') && (rowData.files).length) {
                
                var recordFiles = rowData.files;
                
                tbl.push('<tr>');
                    tbl.push('<td style="background-color: #f5f5f5;">'+plang.get('photo')+':</td>');
                    tbl.push('<td>');
                    
                    for (var f in recordFiles) {
                        
                        var recordFile = recordFiles[f];
                        var physicalPath = recordFile['PHYSICAL_PATH'];
                        var fileExtension = physicalPath.split('.').pop().toLowerCase();
                        var fileName = recordFile['FILE_NAME'];
                        
                        if (['mp4', 'ogg', 'avi', 'mov', 'm4p', 'm4v', 'mp3'].indexOf(fileExtension) !== -1) {
                            
                            var iconClass = 'fa-video';

                            if (fileExtension == 'mp3') {
                                iconClass = 'fa-volume-up';
                            }
            
                            tbl.push('<a href="' + physicalPath + '" class="btn btn-danger rounded-round d-inline-block mr-1 mb-1" data-fancybox data-type="video" data-width="840" data-height="560" title="'+fileName+'"><i class="far ' + iconClass + '"></i></a>');
                            
                        } else {
                            tbl.push('<a href="'+physicalPath+'" class="d-inline-block mr-1 mb-1" data-fancybox="images" data-rel="fancybox-button" title="'+fileName+'">');
                                tbl.push('<img src="'+physicalPath+'" style="height: 80px"/>');
                            tbl.push('</a>');
                        }
                    }
                    
                    tbl.push('</td>');
                tbl.push('</tr>');
            }
            
            tbl.push('<tr>');
                tbl.push('<td style="background-color: #f5f5f5;">'+plang.get(showCol['LABEL_NAME'])+':</td>');
                tbl.push('<td>'+dvFieldValueShow(rowData[showCol['COLUMN_NAME']])+'</td>');
            tbl.push('</tr>');
        }
    }
    
    tbl.push('</table>');
    
    return tbl.join('');
}

function kpiDataMartStackedColumnData(data, category, value, chartConfig) {
    var columnData = data;
    var categoryGroup = chartConfig.axisXGroup;
    var distinctColumnData = [], 
        distinctCheck = [], 
        distinctSeries = [];

    for (var b in columnData) {

        var groupField = columnData[b][category];

        if (!distinctCheck.hasOwnProperty(groupField)) {

            distinctCheck[groupField] = 1;  

            var combinedRow = {};
            combinedRow[category] = groupField;

            for (var c in columnData) {
                if (columnData[b][category] == columnData[c][category]) {
                    combinedRow[columnData[c][categoryGroup]] = columnData[c][value];
                    distinctSeries[columnData[c][categoryGroup]] = 1;
                }
            }

            distinctColumnData.push(combinedRow);
        }
    }
                
    return {data: distinctColumnData, series: distinctSeries};     
}
function kpiDataMartChartRender(obj) {
    
    var elemId = obj.elemId;
    var $mainSelector = $('#' + elemId).closest('.theme-builder');
    var chartConfig = obj.chartConfig;
    var type = chartConfig.type;
    var chartName = (chartConfig.hasOwnProperty('chartName')) ? chartConfig.chartName : type + '_chart';
    var category = chartConfig.axisX;
    var value = chartConfig.axisY;
    var aggregate = chartConfig.aggregate;
    var columnsConfig = obj.columnsConfig;
    var data = obj.data;
    var isRunInterval = false;
    var isLineChartConfig = false;
    var axisYdataType = columnsConfig[value];
    
    if (typeof $mainSelector.attr('data-kpi-layout') !== 'undefined' && $mainSelector.attr('data-kpi-layout') !== '0') {
        isLayoutBuilder = true;
        obj.isLayoutBuilder = true;
        var _parentSelector = $('#' + elemId).closest('.layout-builder-v0');
        kpiLayoutIndex = $mainSelector.attr('data-kpi-layout');
        elemId = _parentSelector.find('div.selected-box[data-kl-col="'+ kpiLayoutIndex +'"] [data-section-code="'+ kpiLayoutIndex +'"]').attr('id');
    }

    if (aggregate == 'COUNT' && (value == '' || value === null)) {
        value = 'COUNT_COL';
    }
            
    if (chartConfig.hasOwnProperty('lineChartConfig') && chartConfig.lineChartConfig) {
        isLineChartConfig = true;
    }
            
    if (obj.hasOwnProperty('isRunInterval') && obj.isRunInterval) {

        isRunInterval = true;

        if (kpIndicatorChart.hasOwnProperty(elemId)) {
            
            var prevChart = kpIndicatorChart[elemId];
            
            if (type == 'stacked_column') {
                var stackedData = kpiDataMartStackedColumnData(data, category, value, chartConfig);
                data = stackedData.data;
            }
            
            prevChart.data = data;
            return;
        }
    }
    
    if (type == 'pie') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.PieChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }

            chart.data = data;

            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = value;
            pieSeries.dataFields.category = category;
            pieSeries.slices.template.stroke = am4core.color('#fff');
            pieSeries.slices.template.strokeOpacity = 1;

            // This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;

            chart.hiddenState.properties.radius = am4core.percent(0);
        });
        
    } else if (type == 'donut') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.PieChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }

            chart.legend = new am4charts.Legend();

            // Доорхи legend-ийн гарах өндөр /2 мөр байхаар тохируулав/
            chart.legend.maxHeight = 80;
            chart.legend.scrollable = true;

            chart.data = data;

            // Доторх дугуйн хэмжээ
            chart.innerRadius = am4core.percent(45);

            var series = chart.series.push(new am4charts.PieSeries());

            // Чарт харуулах баганын тохиргоо
            series.dataFields.value = value;
            series.dataFields.category = category;

            // Чарт дата 10 аас их байгаа үед label ийг disable хийв
            if (chart.data && chart.data.length > 10) {
                series.labels.template.disabled = true;
            }
        });
        
    } else if (type == 'column') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;

            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.XYChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }

            chart.marginBottom = 0;
            chart.paddingTop = 8;
            chart.paddingBottom = 0;
            chart.paddingLeft = 0;
            chart.paddingRight = 0;

            chart.data = data;

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = category;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 300;
            categoryAxis.renderer.labels.template.wrap = true;
            categoryAxis.renderer.labels.template.maxWidth = 100;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.labels.template.endIndex = 5;
            
            chart.scrollbarX = new am4core.Scrollbar();
            chart.scrollbarX.startGrip.disabled = true;
            chart.scrollbarX.endGrip.disabled = true;
            chart.scrollbarX.exportable = false;
            
            /*categoryAxis.events.on('sizechanged', function(ev) {
                var axis = ev.target;
                var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
                axis.renderer.labels.template.maxWidth = cellWidth + 50;
                
                if (113 > axis.renderer.labels.template.maxWidth) {
                    categoryAxis.renderer.labels.template.truncate = true;
                } else {
                    axis.renderer.labels.template.maxWidth = 100;
                    categoryAxis.renderer.labels.template.truncate = false;
                }
            });*/
            
            categoryAxis.events.on("sizechanged", function (ev) {
                var axis = ev.target;
                var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
                axis.renderer.labels.template.maxWidth = cellWidth;
                if (axis.pixelWidth < 900) {
                    categoryAxis.renderer.labels.template.truncate = true;
                    axis.renderer.labels.template.maxWidth = 120;
                    if (axis.endIndex <= 20) {
                        chart.scrollbarX.end = 1;
                    } else {
                        if (axis.endIndex > 40) {
                            chart.scrollbarX.end = 1 / 4;
                        } else {
                            chart.scrollbarX.end = 1 / 2;
                        }
                    }
                } else {
                    axis.renderer.labels.template.maxWidth = 120;
                    categoryAxis.renderer.labels.template.truncate = false;
                    chart.scrollbarX.end = 1;
                }
            });
            
            categoryAxis.renderer.labels.template.adapter.add('textOutput', function(text) {
                return html_entity_decode(text);
            });

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var series = chart.series.push(new am4charts.ColumnSeries());
            series.sequencedInterpolation = true;
            series.dataFields.valueY = value;
            series.dataFields.categoryX = category;
            series.tooltipHTML = "{categoryX}: <strong>{valueY.formatNumber('#,###.##')}</strong>";
            series.columns.template.strokeWidth = 0;
            series.columns.template.width = am4core.percent(60);
            series.columns.template.maxWidth = 50;

            series.tooltip.pointerOrientation = 'vertical';

            //series.columns.template.column.cornerRadiusTopLeft = 10;
            //series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.fillOpacity = 0.8;

            var hoverState = series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;

            series.columns.template.adapter.add("fill", function(fill, target) {
                return chart.colors.getIndex(target.dataItem.index);
            });

            // Cursor
            chart.cursor = new am4charts.XYCursor();
            
            /*chart.scrollbarX = new am4core.Scrollbar();
            chart.scrollbarX.parent = chart.bottomAxesContainer;
            chart.scrollbarX.startGrip.hide();
            chart.scrollbarX.endGrip.hide();
            chart.scrollbarX.start = 0;
            chart.scrollbarX.end = 0.25;

            chart.zoomOutButton = new am4core.ZoomOutButton();
            chart.zoomOutButton.hide();*/
            
            if (isLineChartConfig) {
                
                var sliceValues = chart.data, 
                    lineChartCol = chartConfig.lineChartConfig.column, 
                    max = 0, v = 0, sliceValuesLen = chart.data.length, i = 0, 
                    lineChartColLabelName = columnsConfig[lineChartCol]['labelName'];

                for (i; i < sliceValuesLen; i++) {
                    v = Number(sliceValues[i][lineChartCol]);
                    if (max < v){max = v;}
                }
                
                max = max + 5;
                
                if (max > 100000) {
                    max = max + 10000;
                } else if (max > 10000) {
                    max = max + 1000;
                } else if (max > 5000) {
                    max = max + 500;
                }
                    
                var paretoValueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                paretoValueAxis.renderer.opposite = true;
                paretoValueAxis.min = 0;
                paretoValueAxis.max = max;
                paretoValueAxis.strictMinMax = true;
                paretoValueAxis.renderer.grid.template.disabled = true;
                paretoValueAxis.numberFormatter = new am4core.NumberFormatter();
                paretoValueAxis.numberFormatter.numberFormat = "#";
                paretoValueAxis.cursorTooltipEnabled = false;

                var paretoSeries = chart.series.push(new am4charts.LineSeries());
                paretoSeries.dataFields.valueY = lineChartCol;
                paretoSeries.dataFields.categoryX = category;
                paretoSeries.yAxis = paretoValueAxis;
                paretoSeries.name = lineChartColLabelName;
                paretoSeries.tooltipText = lineChartColLabelName + ": {valueY.formatNumber('#.0')}[/]";
                paretoSeries.showOnInit = true;
                paretoSeries.bullets.push(new am4charts.CircleBullet());
                paretoSeries.strokeWidth = 2;
                paretoSeries.stroke = new am4core.InterfaceColorSet().getFor("alternativeBackground");
                paretoSeries.strokeOpacity = 0.5;
                
                var bullet = paretoSeries.bullets.push(new am4charts.Bullet());
                bullet.fill = am4core.color("#fdd400");
                bullet.tooltipText = lineChartColLabelName + ": \n[/][#000 font-size: 14px]{valueY.formatNumber('#.0')}[/]";
                var circle = bullet.createChild(am4core.Circle);
                circle.radius = 4;
                circle.fill = am4core.color("blue");
                circle.strokeWidth = 3;
                
                var latitudeLabel = paretoSeries.bullets.push(new am4charts.LabelBullet());
                latitudeLabel.label.text = "{valueY.formatNumber('#.0')}";
                latitudeLabel.label.horizontalCenter = "left";
                latitudeLabel.label.dx = 10;
            }
        });
        
    } else if (type == 'line') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.XYChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }
            
            chart.marginBottom = 0;
            chart.paddingTop = 8;
            chart.paddingBottom = 0;
            chart.paddingLeft = 0;
            chart.paddingRight = 0;

            chart.data = data;

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = category;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 300;
            categoryAxis.renderer.labels.template.wrap = true;
            categoryAxis.renderer.labels.template.maxWidth = 110;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.labels.template.endIndex = 5;
            
            categoryAxis.renderer.labels.template.adapter.add("textOutput", function(text) {
                if (text && text.length > 21) {
                    return text.substr(0, 21) + '..';
                }
                return text;
            });

            /*categoryAxis.events.on("sizechanged", function (ev) {
                var axis = ev.target;
                var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
                axis.renderer.labels.template.maxWidth = cellWidth;
                if (axis.pixelWidth < 900) {
                    categoryAxis.renderer.labels.template.truncate = true;
                    axis.renderer.labels.template.maxWidth = 120;
                } else {
                    axis.renderer.labels.template.maxWidth = 120;
                    categoryAxis.renderer.labels.template.truncate = false;
                }
            });*/

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inversed = false;
            valueAxis.title.text = '';
            valueAxis.renderer.minLabelPosition = 0.01;
            valueAxis.min = 0;

            var series1 = chart.series.push(new am4charts.LineSeries());
            series1.dataFields.valueY = value;
            series1.dataFields.categoryX = category;
            series1.name = 'Value';
            series1.bullets.push(new am4charts.CircleBullet());
            series1.tooltipText = "{categoryX}: {valueY}";
            series1.legendSettings.valueText = "{valueY}";

            chart.cursor = new am4charts.XYCursor();
            chart.cursor.behavior = "zoomY";

            var hs1 = series1.segments.template.states.create("hover");
            hs1.properties.strokeWidth = 5;
            series1.segments.template.strokeWidth = 1;

            // Add legend
            /*chart.legend = new am4charts.Legend();
            chart.legend.itemContainers.template.events.on("over", function(event){
              var segments = event.target.dataItem.dataContext.segments;
              segments.each(function(segment){
                segment.isHover = true;
              })
            })

            chart.legend.itemContainers.template.events.on("out", function(event){
              var segments = event.target.dataItem.dataContext.segments;
              segments.each(function(segment){
                segment.isHover = false;
              })
            })*/
        });
        
    } else if (type == 'bar') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.XYChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }

            chart.paddingTop = 0;
            chart.paddingBottom = 0;
            chart.paddingLeft = 0;
            chart.paddingRight = 14;
            chart.data = data;

            // Create axes
            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = category;
            categoryAxis.renderer.inversed = true;

            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueX = value;
            series.dataFields.categoryY = category;
            series.name = "";
            series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
            series.columns.template.column.stroke = am4core.color("#fff");
            series.columns.template.column.strokeOpacity = 0.2;
            series.columns.template.maxHeight = 50;
            series.tooltip.pointerOrientation = 'vertical';
            
            series.columns.template.adapter.add("fill", function(fill, target) {
                return chart.colors.getIndex(target.dataItem.index);
            });
        }); 
        
    } else if (type == 'radar') {
        
        am4core.ready(function() {

            // Themes begin
            function am4themes_RadarTheme(target) {
                if (target instanceof am4core.ColorSet) {
                    target.list = [
                        am4core.color("#e91e63"),
                        am4core.color("#673ab7"),
                        am4core.color("#2196f3"),
                        am4core.color("#1ba68d"), 
                        am4core.color("#e7da4f"),
                        am4core.color("#E7DA4F")
                    ];
                }
            }
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            am4core.useTheme(am4themes_RadarTheme);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.RadarChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }

            chart.data = data;

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = category;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2);
            valueAxis.renderer.axisFills.template.fillOpacity = 0.05;

            /* Create and configure series */
            var series = chart.series.push(new am4charts.RadarSeries());
            series.dataFields.valueY = value;
            series.dataFields.categoryX = category;
            series.name = "";
            series.strokeWidth = 3;
            series.showOnInit = false;

        });
        
    } else if (type == 'pyramid') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.SlicedChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }
            
            chart.paddingTop = 10;
            chart.paddingBottom = 10;
            chart.paddingLeft = 0;
            chart.paddingRight = 0;
            chart.data = data;

            var series = chart.series.push(new am4charts.PyramidSeries());
            series.dataFields.value = value;
            series.dataFields.category = category;
            series.alignLabels = true;
            series.valueIs = "height";
        });
        
    } else if (type == 'clustered_column') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
            
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            //am4core.useTheme(am4themes_animated);

            var chart = am4core.create(elemId, am4charts.XYChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }
            
            chart.paddingTop = 6;
            chart.paddingBottom = 0;
            chart.paddingLeft = 0;
            chart.paddingRight = 0;
            chart.colors.step = 2;

            chart.legend = new am4charts.Legend();
            chart.legend.position = 'bottom';
            chart.legend.paddingTop = 0;
            chart.legend.paddingBottom = 0;
            chart.legend.labels.template.maxWidth = 95;
            
            var xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            xAxis.dataFields.category = category;
            xAxis.renderer.cellStartLocation = 0.1;
            xAxis.renderer.cellEndLocation = 0.9;
            xAxis.renderer.grid.template.location = 0;
            xAxis.renderer.minGridDistance = 30;
            xAxis.renderer.labels.template.horizontalCenter = "right";
            xAxis.renderer.labels.template.verticalCenter = "top";
            xAxis.renderer.labels.template.rotation = 310;
            xAxis.renderer.labels.template.wrap = true;
            xAxis.renderer.labels.template.maxWidth = 100;
            xAxis.renderer.labels.template.paddingTop = 0;
            xAxis.renderer.labels.template.paddingBottom = 0;
            xAxis.renderer.labels.template.marginTop = 0;
            xAxis.renderer.labels.template.marginBottom = 0;
            xAxis.renderer.labels.template.tooltipText = "{category}";
            
            xAxis.events.on("sizechanged", function(ev) {
                var axis = ev.target;
                var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
                axis.renderer.labels.template.maxWidth = cellWidth + 50;
                
                if (113 > axis.renderer.labels.template.maxWidth) {
                    xAxis.renderer.labels.template.truncate = true;
                } else {
                    axis.renderer.labels.template.maxWidth = 100;
                    xAxis.renderer.labels.template.truncate = false;
                }
            });

            var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
            /* yAxis.min = 0; */

            chart.data = data; 
            
            var valueArr = value.split(',');
            
            for (var i = 0; i < valueArr.length; i++) { 
                createSeriesClusteredColumn(valueArr[i], columnsConfig[valueArr[i]]['labelName']);
            }
            
            chart.cursor = new am4charts.XYCursor();
            
            /*chart.scrollbarX = new am4core.Scrollbar();
            chart.scrollbarX.parent = chart.bottomAxesContainer;
            chart.scrollbarX.startGrip.hide();
            chart.scrollbarX.endGrip.hide();
            chart.scrollbarX.start = 0;
            chart.scrollbarX.end = 0.25;

            chart.zoomOutButton = new am4core.ZoomOutButton();
            chart.zoomOutButton.hide();*/
            
            if (isLineChartConfig) {
                
                var sliceValues = chart.data, 
                    lineChartCol = chartConfig.lineChartConfig.column, 
                    max = 0, v = 0, sliceValuesLen = chart.data.length, i = 0, 
                    lineChartColLabelName = columnsConfig[lineChartCol]['labelName'];

                for (i; i < sliceValuesLen; i++) {
                    v = Number(sliceValues[i][lineChartCol]);
                    if (max < v){max = v;}
                }
                    
                var paretoValueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                paretoValueAxis.renderer.opposite = true;
                /*paretoValueAxis.min = 0;*/
                paretoValueAxis.max = max + 5;
                paretoValueAxis.strictMinMax = true;
                paretoValueAxis.renderer.grid.template.disabled = true;
                paretoValueAxis.numberFormatter = new am4core.NumberFormatter();
                paretoValueAxis.numberFormatter.numberFormat = "#'%'";
                paretoValueAxis.cursorTooltipEnabled = false;

                var paretoSeries = chart.series.push(new am4charts.LineSeries());
                paretoSeries.dataFields.valueY = lineChartCol;
                paretoSeries.dataFields.categoryX = category;
                paretoSeries.yAxis = paretoValueAxis;
                paretoSeries.name = lineChartColLabelName;
                paretoSeries.tooltipText = lineChartColLabelName + ": {valueY.formatNumber('#.0')}%[/]";
                paretoSeries.showOnInit = true;
                paretoSeries.bullets.push(new am4charts.CircleBullet());
                paretoSeries.strokeWidth = 2;
                paretoSeries.stroke = new am4core.InterfaceColorSet().getFor("alternativeBackground");
                paretoSeries.strokeOpacity = 0.5;
                
                var bullet = paretoSeries.bullets.push(new am4charts.Bullet());
                bullet.fill = am4core.color("#fdd400");
                bullet.tooltipText = lineChartColLabelName + ": \n[/][#000 font-size: 14px]{valueY.formatNumber('#.0')}%[/]";
                var circle = bullet.createChild(am4core.Circle);
                circle.radius = 4;
                circle.fill = am4core.color("blue");
                circle.strokeWidth = 3;
                
                var latitudeLabel = paretoSeries.bullets.push(new am4charts.LabelBullet());
                latitudeLabel.label.text = "{valueY.formatNumber('#.0')}";
                latitudeLabel.label.horizontalCenter = "left";
                latitudeLabel.label.dx = 10;

                //Cursor
                //chart.cursor = new am4charts.XYCursor();
                //chart.cursor.behavior = "panX";
            }
            
            function createSeriesClusteredColumn(value, name) {
                
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.sequencedInterpolation = true;
                series.dataFields.valueY = value;
                series.dataFields.categoryX = category;
                series.name = name;
                series.columns.template.tooltipText = "{name}: [bold]{valueY.formatNumber('#,###.##')}[/]";
                series.columns.template.maxWidth = 50;
                series.tooltip.pointerOrientation = 'vertical';

                series.events.on("hidden", arrangeColumns);
                series.events.on("shown", arrangeColumns);

                var bullet = series.bullets.push(new am4charts.LabelBullet());
                bullet.interactionsEnabled = false;
                bullet.label.truncate = false;
                bullet.label.hideOversized = false;
                bullet.label.text = "{valueY.formatNumber('#,###.##')}";
                bullet.label.fill = am4core.color('#000');
                bullet.label.rotation = -90; 

                return series;
            }
            
            function arrangeColumns() {

                var series = chart.series.getIndex(0);
                var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
                
                if (series.dataItems.length > 1) {
                    
                    var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
                    var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
                    var delta = ((x1 - x0) / chart.series.length) * w;
                    
                    if (am4core.isNumber(delta)) {
                        
                        var middle = chart.series.length / 2;
                        var newIndex = 0;
                        
                        chart.series.each(function(series) {
                            if (!series.isHidden && !series.isHiding) {
                                series.dummyData = newIndex;
                                newIndex++;
                            } else {
                                series.dummyData = chart.series.indexOf(series);
                            }
                        });
                        
                        var visibleCount = newIndex;
                        var newMiddle = visibleCount / 2;

                        chart.series.each(function(series) {
                            
                            var trueIndex = chart.series.indexOf(series);
                            var newIndex = series.dummyData;
                            var dx = (newIndex - trueIndex + middle - newMiddle) * delta;

                            series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                            series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                        });
                    }
                }
            }
            
        });
        
    } else if (type == 'stacked_column') {
        
        am4core.ready(function() {
            
            window.URL = URL_FN;
             
            am4core.unuseAllThemes();
            //am4core.useTheme(am4themes_material);
            
            var chart = am4core.create(elemId, am4charts.XYChart);
            
            kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig);
            
            if (isRunInterval) {
                kpIndicatorChart[elemId] = chart;
            }
            
            var stackedData = kpiDataMartStackedColumnData(data, category, value, chartConfig);
            var distinctSeries = stackedData.series;
            
            chart.paddingTop = 6;
            chart.paddingBottom = 0;
            chart.paddingLeft = 0;
            chart.paddingRight = 0;
            chart.data = stackedData.data;

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = category;
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.cellStartLocation = 0.1;
            categoryAxis.renderer.cellEndLocation = 0.9;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 300;
            categoryAxis.renderer.labels.template.wrap = true;
            categoryAxis.renderer.labels.template.maxWidth = 100;
            categoryAxis.renderer.labels.template.endIndex = 5;
            categoryAxis.renderer.labels.template.tooltipText = "{category}";
            categoryAxis.renderer.labels.template.paddingTop = 0;
            categoryAxis.renderer.labels.template.paddingBottom = 0;
            categoryAxis.renderer.labels.template.marginTop = 0;
            categoryAxis.renderer.labels.template.marginBottom = 0;
            
            categoryAxis.events.on('sizechanged', function(ev) {
                var axis = ev.target;
                var cellWidth = axis.pixelWidth / (axis.endIndex - axis.startIndex);
                axis.renderer.labels.template.maxWidth = cellWidth + 50;
                
                if (113 > axis.renderer.labels.template.maxWidth) {
                    categoryAxis.renderer.labels.template.truncate = true;
                } else {
                    axis.renderer.labels.template.maxWidth = 100;
                    categoryAxis.renderer.labels.template.truncate = false;
                }
            });

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inside = false;
            valueAxis.min = 0;

            function createSeries(field, name, category) {

                // Set up series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.name = name;
                series.dataFields.valueY = field;
                series.dataFields.categoryX = category;
                series.sequencedInterpolation = true;

                // Make it stacked
                series.stacked = true;

                // Configure columns
                series.columns.template.width = am4core.percent(60);
                series.columns.template.maxWidth = 50;
                series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY.formatNumber('#,###.##')}";

                // Add label
                var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "{valueY.formatNumber('#,###.##')}";
                labelBullet.label.fill = am4core.color("#fff");
                labelBullet.locationY = 0.5;
                labelBullet.label.hideOversized = true;

                return series;
            }
            
            for (var s in distinctSeries) {
                createSeries(s, s, category);
            }

            // Legend
            chart.legend = new am4charts.Legend();
            chart.legend.position = 'bottom';
            chart.legend.paddingTop = 0;
            chart.legend.paddingBottom = 0;
            chart.legend.labels.template.maxWidth = 95;
        });
        
    } else if (type == 'maps') { 
        
        am4core.ready(function() {
            
            am4core.unuseAllThemes();
            var country = chartConfig.mapsChartConfig.country;
            
            $.cachedScript('assets/custom/addon/plugins/amcharts4/maps.js').done(function() {
            $.cachedScript('assets/custom/addon/plugins/amcharts4/geodata/'+country+'Low.js').done(function() {
                
                var chart = am4core.create(elemId, am4maps.MapChart);
                
                chart.maxZoomLevel = 64;
                chart.geodata = window['am4geodata_'+country+'Low'];
                chart.projection = new am4maps.projections.AlbersUsa();
                
                var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

                //Set min/max fill color for each area
                polygonSeries.heatRules.push({
                    property: "fill",
                    target: polygonSeries.mapPolygons.template,
                    min: chart.colors.getIndex(1).brighten(1),
                    max: chart.colors.getIndex(1).brighten(-0.3)
                });
                
                var geodataFeatures = chart.geodata.features;
                var mapChartData = [], mapChartEmptyData = [];
                var isMapChartData = false;
                
                for (var d in data) {
                    
                    for (var g in geodataFeatures) {
                        if (data[d][category] == geodataFeatures[g]['id']) {
                            mapChartData.push({
                                id: data[d][category],
                                value: parseFloat(data[d][value])
                            });
                            isMapChartData = true;
                            break;
                        }
                    }
                }
                
                if (!isMapChartData) {
                    mapChartEmptyData = [
                        {
                            id: geodataFeatures[0]['id'], 
                            value: 0
                        }
                    ];
                }
                
                // Make map load polygon data (state shapes and names) from GeoJSON
                polygonSeries.useGeodata = true;
                polygonSeries.calculateVisualCenter = true;
                
                // Set heatmap values for each state
                polygonSeries.data = isMapChartData ? mapChartData : mapChartEmptyData;
                
                // Set up heat legend
                var heatLegend = chart.createChild(am4maps.HeatLegend);
                heatLegend.series = polygonSeries;
                heatLegend.align = "right";
                heatLegend.valign = "bottom";
                heatLegend.width = am4core.percent(20);
                heatLegend.marginRight = am4core.percent(4);
                heatLegend.minValue = 0;
                heatLegend.maxValue = 40000000;

                // Set up custom heat map legend labels using axis ranges
                var minRange = heatLegend.valueAxis.axisRanges.create();
                minRange.value = heatLegend.minValue;
                minRange.label.text = "Бага";
                var avgRange = heatLegend.valueAxis.axisRanges.create();
                avgRange.value = 20000000;
                avgRange.label.text = "Дунд";
                var maxRange = heatLegend.valueAxis.axisRanges.create();
                maxRange.value = heatLegend.maxValue;
                maxRange.label.text = "Их";

                // Blank out internal heat legend value axis labels
                heatLegend.valueAxis.renderer.labels.template.adapter.add("text", function(labelText) {
                    return "";
                });

                // Configure series tooltip
                var polygonTemplate = polygonSeries.mapPolygons.template;
                polygonTemplate.tooltipText = "{name}: {value}";
                polygonTemplate.nonScalingStroke = true;
                polygonTemplate.strokeWidth = 0.5;

                // Create hover state and set alternative fill color
                var hs = polygonTemplate.states.create("hover");
                //hs.properties.fill = am4core.color("#3c5bdc");
                hs.properties.fill = chart.colors.getIndex(1).brighten(-0.5);

                if (isMapChartData) {
                    
                    var labelSeries = chart.series.push(new am4maps.MapImageSeries());
                    labelSeries.dataFields.value = "value";
                    var labelTemplate = labelSeries.mapImages.template.createChild(am4core.Label);
                    labelTemplate.horizontalCenter = "middle";
                    labelTemplate.verticalCenter = "middle";
                    labelTemplate.fontSize = 10;
                    labelTemplate.nonScaling = true;
                    labelTemplate.interactionsEnabled = false;
                    labelTemplate.fill = am4core.color("#fff");
                    
                    /*var circle = labelTemplate.createChild(am4core.Circle);
                    circle.radius = 10;
                    circle.fill = am4core.color("#000");
                    circle.fillOpacity = 0.7;
                    circle.verticalCenter = "middle";
                    circle.horizontalCenter = "middle";
                    circle.nonScaling = true;*/
                                
                    polygonSeries.events.on("inited", function () {
                        var dataTypeAppendText = axisYdataType.showType === 'percent' ? '%' : '';
                        for (var i = 0; i < mapChartData.length; i++) {
                            var polygon = polygonSeries.getPolygonById(mapChartData[i]['id']);
                            
                            if (polygon) {
                                var polygonValue = polygon.dataItem.dataContext.value;
                                
                                if (polygonValue) {
                                    var label = labelSeries.mapImages.create();

                                    label.latitude = polygon.visualLatitude;
                                    label.longitude = polygon.visualLongitude;
                                    label.children.getIndex(0).text = formatCompactNumber(polygonValue)+dataTypeAppendText;
                                }
                            }
                        }
                    });
                }
                
            });
            });
        });
        
    } else if (type == 'card' || type == 'card_vertical') {
        
        var $elem = $('#' + elemId);
        
        if ($elem.length) {
            
            var html = [], cardAmountValue = 0;
            
            if (data.hasOwnProperty(0)) {
                var dataRow = data[0];
                if (dataRow.hasOwnProperty(value)) {
                    cardAmountValue = number_format(dataRow[value], 2, '.', ',');
                }
            }
            
            if (isRunInterval) {
                var $card = $elem.closest('.bl-section'), $amountValue = $card.find('.kpi-card-amount-value');
                
                if ($amountValue.length) {
                    $amountValue.text(cardAmountValue);
                    return;
                }
            }
            
            var labelText = chartConfig.labelText;
            
            if (columnsConfig.hasOwnProperty('labelText') && columnsConfig.labelText != '') {
                labelText = columnsConfig.labelText;
            }

            var cardConfigStyle = 'style="';

            if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                cardConfigStyle += 'background-color: '+chartConfig.bgColor + '; ';
            }

            if (chartConfig.width != null && chartConfig.width) {
                cardConfigStyle += 'min-width: ' + chartConfig.width + 'px; max-width: max-content !important;';
            }

            if (chartConfig.height != null && chartConfig.height) {
                cardConfigStyle += 'min-height: ' + chartConfig.height + 'px; max-height: max-content !important;';
            }

            if (chartConfig.gridBorderRadius != null && chartConfig.gridBorderRadius) {
                cardConfigStyle += 'border-radius: '+ chartConfig.gridBorderRadius +'px !important; ';
            }

            if (chartConfig.leftPadding != null && chartConfig.leftPadding) {
                cardConfigStyle += 'padding-left: '+ chartConfig.leftPadding +'px !important; ';
            }

            if (chartConfig.rightPadding != null && chartConfig.rightPadding) {
                cardConfigStyle += 'padding-right: '+ chartConfig.rightPadding +'px !important; ';
            }

            if (chartConfig.topPadding != null && chartConfig.topPadding) {
                cardConfigStyle += 'padding-top: '+ chartConfig.topPadding +'px !important; ';
            }

            if (chartConfig.bottomPadding != null && chartConfig.bottomPadding) {
                cardConfigStyle += 'padding-bottom: '+ chartConfig.bottomPadding +'px !important; ';
            }

            if (chartConfig.alignment != null && chartConfig.alignment) {
                cardConfigStyle += 'text-align: '+ chartConfig.alignment +' !important; ';
            }

            cardConfigStyle += '"';
            
            if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                html.push('<div class="card card-body bg-blue-400 p-2 pl15 pl15" '+ cardConfigStyle +'">');
            } else {
                html.push('<div class="card card-body p-2 pl15 pl15" '+ cardConfigStyle +'>');
            }
            
            if (type == 'card') {
                html.push('<div class="media">');
                html.push('<div class="media-body">');
                    html.push('<h3 class="mb-0 kpi-card-amount-value">'+cardAmountValue+'</h3>');
                    html.push('<span class="text-uppercase font-size-xs">'+labelText+'</span>');
                html.push('</div>');
                
                if (chartConfig.iconName != null && chartConfig.iconName != '') {
                    
                    html.push('<div class="ml-3 align-self-center">');
                    
                    if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                        html.push('<i class="far '+chartConfig.iconName+' opacity-75" style="font-size: 48px"></i>');
                    } else {
                        html.push('<i class="far '+chartConfig.iconName+' text-indigo-400" style="font-size: 48px"></i>');
                    }
                    
                    html.push('</div>');
                }
            
            html.push('</div>');
            } else {
                var iconTextStyle = 'style="',
                    footerTextStyle = 'style="',
                    headerTextStyle = 'style="';

                /* icon text style begin */
                if (chartConfig.iconFontSize != null && chartConfig.iconFontSize) {
                    iconTextStyle += 'font-size: '+ chartConfig.iconFontSize +'px; ';
                    
                } else {
                    iconTextStyle += 'font-size: 48px; ';
                }
                
                if (chartConfig.iconLeftPadding != null && chartConfig.iconLeftPadding) {
                    iconTextStyle += 'padding-left: '+ chartConfig.iconLeftPadding +'px !important; ';
                }

                if (chartConfig.iconRightPadding != null && chartConfig.iconRightPadding) {
                    iconTextStyle += 'padding-right: '+ chartConfig.iconRightPadding +'px !important; ';
                }

                if (chartConfig.iconTopPadding != null && chartConfig.iconTopPadding) {
                    iconTextStyle += 'padding-top: '+ chartConfig.iconTopPadding +'px !important; ';
                }

                if (chartConfig.iconBottomPadding != null && chartConfig.iconBottomPadding) {
                    iconTextStyle += 'padding-bottom: '+ chartConfig.iconBottomPadding +'px !important; ';
                }

                if (chartConfig.iconAlignment != null && chartConfig.iconAlignment) {
                    iconTextStyle += 'text-align: '+ chartConfig.iconAlignment +' !important; ';
                }
                /* header text style end */

                /* header text style begin */
                if (chartConfig.headFontSize != null && chartConfig.headFontSize) {
                    headerTextStyle += 'font-size: '+ chartConfig.headFontSize +'px !important; ';
                }
                
                if (chartConfig.headLeftPadding != null && chartConfig.headLeftPadding) {
                    headerTextStyle += 'padding-left: '+ chartConfig.headLeftPadding +'px !important; ';
                }

                if (chartConfig.headRightPadding != null && chartConfig.headRightPadding) {
                    headerTextStyle += 'padding-right: '+ chartConfig.headRightPadding +'px !important; ';
                }

                if (chartConfig.headTopPadding != null && chartConfig.headTopPadding) {
                    headerTextStyle += 'padding-top: '+ chartConfig.headTopPadding +'px !important; ';
                }

                if (chartConfig.headBottomPadding != null && chartConfig.headBottomPadding) {
                    headerTextStyle += 'padding-bottom: '+ chartConfig.headBottomPadding +'px !important; ';
                }

                if (chartConfig.headAlignment != null && chartConfig.headAlignment) {
                    headerTextStyle += 'text-align: '+ chartConfig.headAlignment +' !important; ';
                }
                /* header text style end */

                /* footer text style begin */
                if (chartConfig.footFontSize != null && chartConfig.footFontSize) {
                    footerTextStyle += 'font-size: '+ chartConfig.footFontSize +'px !important; ';
                }
                
                if (chartConfig.footLeftPadding != null && chartConfig.footLeftPadding) {
                    footerTextStyle += 'padding-left: '+ chartConfig.footLeftPadding +'px !important; ';
                }

                if (chartConfig.footRightPadding != null && chartConfig.footRightPadding) {
                    footerTextStyle += 'padding-right: '+ chartConfig.footRightPadding +'px !important; ';
                }

                if (chartConfig.footTopPadding != null && chartConfig.footTopPadding) {
                    footerTextStyle += 'padding-top: '+ chartConfig.footTopPadding +'px !important; ';
                }

                if (chartConfig.footBottomPadding != null && chartConfig.footBottomPadding) {
                    footerTextStyle += 'padding-bottom: '+ chartConfig.footBottomPadding +'px !important; ';
                }

                if (chartConfig.footAlignment != null && chartConfig.footAlignment) {
                    footerTextStyle += 'text-align: '+ chartConfig.footAlignment +' !important; ';
                }
                /* footer text style end */

                iconTextStyle += '"', footerTextStyle += '"', headerTextStyle += '"';

                html.push('<div class="media">');
                    html.push('<div class="media-body" style="display: grid">');
                    
                        if (chartConfig.iconName != null && chartConfig.iconName != '') {
                            
                            html.push('<div class="align-self-center">');
                            
                            if (chartConfig.bgColor != null && chartConfig.bgColor != '') {
                                html.push('<i class="far '+chartConfig.iconName+' opacity-75" '+ iconTextStyle +'></i>');
                            } else {
                                html.push('<i class="far '+chartConfig.iconName+' text-indigo-400" '+ iconTextStyle +'></i>');
                            }
                            
                            html.push('</div>');
                        }

                        html.push('<span class="text-uppercase font-size-xs" '+ headerTextStyle +'>'+labelText+'</span>');
                        html.push('<h3 class="mb-0 kpi-card-amount-value" '+ footerTextStyle +'>'+cardAmountValue+'</h3>');
                    html.push('</div>');
                    
                
                html.push('</div>');
            }
                
            html.push('</div>');
            
            var cardHtml = html.join('');
            
            if ($elem.hasClass('card-body')) {
                
                var $card = $elem.closest('.card');
                
                $card.addClass('d-none');
                $card.after(cardHtml);
                
            } else {
                $elem.empty().append(cardHtml);
            }
        }
    }
}

function formatCompactNumber(number) {
    const formatter = Intl.NumberFormat("en", { notation: "compact" });
    return formatter.format(number);
}

function kpiDataMartChartExport(elemId, chart, chartName, chartConfig, columnsConfig) {
    
    var type = chartConfig.type;
    var value = chartConfig.axisY;
    var aggregate = chartConfig.aggregate;
    
    if (aggregate == 'COUNT' && (value == '' || value === null)) {
        value = 'COUNT_COL';
        columnsConfig[value] = {'showType': 'decimal', 'labelName': 'Тоо'};
    }
    
    chart.exporting.menu = new am4core.ExportMenu();
    chart.exporting.title = chartName;
    chart.exporting.filePrefix = chartName;
    
    if (type == 'pyramid') {
        chart.exporting.menu.align = 'left';
        chart.exporting.menu.verticalAlign = 'top';
    }
    
    chart.exporting.menu.items = [{
        "label": "...",
        "menu": [
            {"type": "png", "label": "PNG"},
            {"type": "print", "label": "PRINT"}
        ]
    }];
    chart.exporting.menu.items[0].menu.push({
        label: "EXCEL",
        type: "custom",
        options: {
            callback: function() {

                chart.exporting.adapter.add('formatDataFields', function(data, target) {
                    var dataFields = data.dataFields;
                    for (var d in dataFields) {
                        if (d == 'null' || d.indexOf(',') !== -1) {
                            delete data.dataFields[d];
                        } else {
                            data.dataFields[d] = columnsConfig.hasOwnProperty(d) ? columnsConfig[d]['labelName'] : d;
                        }
                    }
                    return data;
                });

                chart.exporting.export('xlsx');
            }
        }
    });
    chart.exporting.menu.items[0].menu.push({
        label: "TABLE",
        type: "custom",
        options: {
            callback: function() {

                chart.exporting.adapter.add('formatDataFields', function(data, target) {
                    var dataFields = data.dataFields;
                    for (var d in dataFields) {
                        if (d == 'null' || d.indexOf(',') !== -1) {
                            delete data.dataFields[d];
                        } else {
                            data.dataFields[d] = columnsConfig.hasOwnProperty(d) ? columnsConfig[d]['labelName'] : d;
                        }
                    }
                    return data;
                });

                chart.exporting.adapter.add('data', function(data) {
                    
                    var dataLength = data.data.length;
                    var valueArr = value.split(',');
                    var isLineChartConfig = false;
            
                    if (chartConfig.hasOwnProperty('lineChartConfig')) {
                        isLineChartConfig = true;
                    }
            
                    for (var i = 0; i < dataLength; i++) {
                        
                        for (var c in valueArr) {
                            
                            var colName = valueArr[c];
                            
                            if (columnsConfig.hasOwnProperty(colName) && (columnsConfig[colName]['showType'] == 'number' 
                                || columnsConfig[colName]['showType'] == 'decimal' 
                                || columnsConfig[colName]['showType'] == 'bigdecimal') 
                                || columnsConfig[colName]['showType'] == 'percent') {
                            
                                data.data[i][colName] = number_format(data.data[i][colName], 2, '.', ',').replace(/\.?0+$/, '');
                            } 
                        }
                        
                        if (isLineChartConfig) {
                            var lineChartCol = chartConfig.lineChartConfig.column;
                            data.data[i][lineChartCol] = number_format(data.data[i][lineChartCol], 2, '.', ',').replace(/\.?0+$/, '');
                        }
                    }
                    return data;
                });

                chart.exporting.getHTML('html', {
                    tableClass: 'table table-bordered table-hover',
                    headerClass: 'font-weight-bold', 
                    addColumnNames: true
                }, false).then(function(html) {
                    kpiDataMartChartTableRender(elemId, chartName, chartConfig, columnsConfig, html);
                });
            }
        }
    });
}

function kpiDataMartChartTableRender(elemId, chartName, chartConfig, columnsConfig, html) {
    var $dialogName = 'dialog-kpidatamartcharttablerender';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    } 
    var $dialog = $('#' + $dialogName), $chart = $('#' + elemId);
    
    if ($chart.closest('.kpi-layout-chart').length == 0) {
        
        Core.blockUI({message: 'Loading...', boxed: true});
        
        setTimeout(function() {
            var chartIndicatorId = $chart.closest('[data-kpis-indicatorid]').attr('data-kpis-indicatorid'), result = '';

            $.ajax({
                type: 'post',
                url: 'mdform/indicatorOneChart/' + chartIndicatorId, 
                data: {isOnlyFormPrint: 1}, 
                async: false,
                success: function(data) {
                    result = data;
                }
            });

            if (result != '') {
                html = result + '<br />' + html;
            }
            
            kpiDataMartChartTableDialog($dialog, chartName, html);
        }, 100);
    } else {
        kpiDataMartChartTableDialog($dialog, chartName, html);
    }
}
function kpiDataMartChartTableDialog($dialog, chartName, html) {
    html = html.replace(/undefined/g, '');
    $dialog.empty().append(html);

    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: chartName + ' - Хүснэгтээр',
        width: 900,
        height: $(window).height() - 20,
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function() {
            Core.unblockUI();
        },
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
            text: plang.get('close_btn'),
            class: 'btn blue-madison btn-sm bp-btn-close',
            click: function() {
                $dialog.dialog('close');
            }
        }]
    });
    $dialog.dialog('open');
}

function removeKpiIndicatorRowsFromBasket(elem) {
    var e = jQuery.Event("keydown");
    e.keyCode = e.which = 13;
    $(elem).closest(".mv-popup-control").find(".lookup-code-autocomplete").val("").trigger(e);    
}

function chooseKpiIndicatorRowsFromBasket(elem, indicatorId, chooseType, funcName) {

    var $this = $(elem), chooseType = (chooseType == '') ? 'single' : chooseType, params = '';
    var $dialogName = 'dialog-dataview-selectable-' + indicatorId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var $parent = $this.closest('div.meta-autocomplete-wrap');    
    var $thisHidden = $parent.find('input[type="hidden"]');
    
    if (typeof $thisHidden.attr('data-in-param') !== 'undefined' && $thisHidden.attr('data-in-param') != '' && typeof $thisHidden.attr('data-in-lookup-param') !== 'undefined') {

        var _inputParam = $thisHidden.attr('data-in-param').split('|');
        var _lookupParam = $thisHidden.attr('data-in-lookup-param').split('|');
        var isBPDtlTbl = $this.closest('table').hasClass('bprocess-table-dtl');

        if ($parent.closest('.popup-parent-tag').length) {
            var $parentForm = $parent.closest('.popup-parent-tag');
        } else {
            var $parentForm = $this.closest('form');

            if ($parentForm.attr('id') == 'default-criteria-form') {
                $parentForm = $parentForm.closest('.main-dataview-container').find('form');
            } else if ($parentForm.closest('.selectable-dataview-grid').length) {
                $parentForm = $parentForm.closest('.selectable-dataview-grid').find('form');
            }
        }

        for (var i = 0; i < _inputParam.length; i++) {
            if (isBPDtlTbl) {
                if ($this.closest('tr').find("[data-path='" + _inputParam[i] + "']").length) {
                    var paramsVal = $this.closest('tr').find("[data-path='" + _inputParam[i] + "']");
                } else if ($this.closest('table').closest('tr').find("[data-path='" + _inputParam[i] + "']").length) {
                    var paramsVal = $this.closest('table').closest('tr').find("[data-path='" + _inputParam[i] + "']");
                } else {
                    var paramsVal = $parentForm.find("[data-path='" + _inputParam[i] + "']");
                }
            } else {
                var paramsVal = $parentForm.find("[data-path='" + _inputParam[i] + "']");
            }

            if (paramsVal.length) {
                var paramVal = '';

                if (paramsVal.prop('tagName') == 'SELECT') {
                    if (paramsVal.hasClass('select2')) {
                        paramVal = paramsVal.select2('val');
                    } else {
                        paramVal = paramsVal.val();
                    }
                } else {
                    if (paramsVal.length > 1) {

                        _lookupParam[i] = _lookupParam[i] + '[]';
                        paramVal = paramsVal.map(function() { return this.value; }).get().join(',');

                    } else if (paramsVal.hasClass('bigdecimalInit')) {
                        paramVal = paramsVal.autoNumeric('get');
                    } else {
                        paramVal = paramsVal.val();
                    }
                }

                params += _lookupParam[i] + '=' + paramVal + '&';
            }
        }
    }

    $.ajax({
        type: 'post',
        url: 'mdform/indicatorSelectableGrid',
        data: {indicatorId: indicatorId, chooseType: chooseType, params: params},
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
                width: '1200',
                height: 'auto',
                modal: true,
                closeOnEscape: isCloseOnEscape,
                close: function() {
                    enableScrolling();
                    $dialog.empty().dialog('destroy').remove();

                    var $prevDataGridElem = $('#objectdatagrid-' + indicatorId);

                    if ($prevDataGridElem.length) {
                        window['objectdatagrid_' + indicatorId] = $prevDataGridElem;
                    }
                },
                buttons: [{
                        text: plang.get('addbasket_btn'),
                        class: 'btn green-meadow btn-sm float-left',
                        click: function() {
                            if (chooseType === 'single' && $dialog.parent().find('.ui-dialog-buttonset > .datagrid-choose-btn').length > 0) {
                                window['basketCommonSelectableDataGrid_' + indicatorId]();
                                Core.blockUI({message: 'Loading...', boxed: true});
                                setTimeout(() => {
                                    $dialog.parent().find('.ui-dialog-buttonset > .datagrid-choose-btn').trigger('click');
                                    Core.unblockUI();
                                }, 1000);
                            } else {
                                window['basketCommonSelectableDataGrid_' + indicatorId]();
                            }
                        }
                    },
                    {
                        text: plang.get('choose_btn'),
                        class: 'btn blue btn-sm datagrid-choose-btn',
                        click: function() {
                            
                            PNotify.removeAll();
                            
                            var $basketGrid = $('#commonSelectableBasketDataGrid_' + indicatorId);
                            var countBasketList = $basketGrid.datagrid('getData').total;

                            if (countBasketList > 0) {
                                
                                Core.blockUI({message: 'Loading...', boxed: true});
                                
                                var rows = dataViewSelectedRowsResolver($basketGrid.datagrid('getRows'));
                                
                                if (typeof funcName === 'undefined') {
                                    kpiIndicatorRelationFillRows($this, indicatorId, rows, data.idField, data.codeField, data.nameField, chooseType);
                                } else {
                                    window[funcName]($this, indicatorId, rows, data.idField, data.codeField, data.nameField, chooseType);
                                }
                            
                                Core.unblockUI();
                                
                                $dialog.dialog('close');
                                
                            } else {
                                new PNotify({
                                    title: 'Info',
                                    text: plang.get('msg_pls_list_select'),
                                    type: 'info',
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            }
                        }
                    },
                    {
                        text: plang.get('close_btn'),
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });

            $dialog.dialog('open');
            $dialog.css('overflow-x', 'hidden');
            
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}
function mvFieldFillSelectedRows(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
    var $this = $(elem);
    if ($this.closest("div.bp-param-cell").length > 0) {
        var $parentCell = $this.closest("div.bp-param-cell");
    } else if ($this.closest("div.form-md-line-input").length > 0) {
        var $parentCell = $this.closest("div.form-md-line-input");
    } else {
        if ($this.closest("div.meta-autocomplete-wrap").length > 0) {
            var $parentCell = $this.closest("div.meta-autocomplete-wrap");
        } else {
            var $parentCell = $this.closest("td");
        }
    }
    
    if (chooseType === 'single' || chooseType === 'singlealways') {
        $parentCell.find("input[id*='_valueField'], input[id*='_displayField'], input[id*='_nameField']").val('').attr('title', '').removeClass('error');
        
        idField = idField.toLowerCase();
        codeField = codeField.toLowerCase();
        nameField = nameField.toLowerCase();
        
        var rowData = Object.fromEntries(
            Object.entries(rows[0]).map(([key, val]) => [key.toLowerCase(), val])
        );
        var codeFieldValue = rowData[codeField];
        
        if (typeof codeFieldValue !== 'undefined') {
            $parentCell.find("input[id*='_displayField']").val(codeFieldValue).attr('title', codeFieldValue);
        } else {
            $parentCell.find("input[id*='_displayField']").val(rowData[nameField]).attr('title', rowData[nameField]);
        }
        
        $parentCell.find("input[id*='_nameField']").val(rowData[nameField]).attr('title', rowData[nameField]);
        $parentCell.find("input[id*='_valueField']").attr('data-row-data', JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;'));
        $parentCell.find("input[id*='_valueField']").val(rowData[idField]).trigger('change');
    }
}
function kpiIndicatorRelationFillRows(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
    
    var html = [], $tbody = elem.closest('.reldetail').find('table.mv-record-map-tbl > tbody:eq(0)');
    var delete_btn = plang.get('delete_btn');
    var view_btn = plang.get('view_btn');
    var isAddonForm = false;
    
    if (elem.hasAttr('data-config')) {
        var configObj = elem.attr('data-config');
        if (typeof configObj !== 'object') {
            configObj = JSON.parse(html_entity_decode(configObj, "ENT_QUOTES"));
        } 
        if (Number(configObj.isAddonForm) > 0 && configObj.metaInfoIndicatorId != '') {
            var metaInfoIndicatorId = configObj.metaInfoIndicatorId;
            isAddonForm = true;
        }
    }

    for (var i in rows) {
        
        var row = rows[i], rowId = row[idField], rowName = row[nameField];
        var $checkRow = $tbody.find('> tr[data-basketrowid="'+rowId+'"]');
        var childRowData = '';
        
        if ($checkRow.length == 0) {
            
            if (isAddonForm) {
                childRowData = JSON.stringify(row);
            }
            
            html.push('<tr data-basketrowid="'+rowId+'">');
                html.push('<td style="height: 25px; max-width: 0;" class="text-left text-truncate">');
                
                    html.push('<input type="hidden" name="metaDmRecordMaps[indicatorId][]" value="'+indicatorId+'">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[recordId][]" value="'+rowId+'">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[mapId][]">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[rowState][]" value="added">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[childRecordId][]">');
                    html.push('<textarea class="d-none" name="metaDmRecordMaps[childRowData][]">'+childRowData+'</textarea>');
                    
                    html.push('<a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, \''+indicatorId+'\', \''+rowId+'\', \'view\');" class="font-size-14" title="'+view_btn+'">');
                        html.push('<i style="color:blue" class="far fa-file-search mr-1"></i>');
                        html.push(rowName);
                    html.push('</a>');
                    
                html.push('</td>');
                
                html.push('<td style="width: 60px" class="text-right">');
                
                    if (isAddonForm) {
                        html.push('<a href="javascript:;" onclick="kpiIndicatorRelationSubRows(this, \''+metaInfoIndicatorId+'\');" class="font-size-16 mr-3" title="Холбоос"><i style="color:#5c6bc0;" class="far fa-external-link-square"></i></a>');
                    }
                    html.push('<a href="javascript:;" onclick="kpiIndicatorRelationRemoveRows(this);" class="font-size-14" title="'+delete_btn+'"><i style="color:red" class="far fa-trash"></i></a>');
                    
                html.push('</td>');
            html.push('</tr>');
        }
    }
    
    $tbody.append(html.join(''));
}
function kpiIndicatorRelationSubRows(elem, metaInfoIndicatorId, recordId) {
    var $this = $(elem);
    var $cell = $this.closest('td');
    var $row = $this.closest('tr');
    var rowIndex = $row.index();
    var $dialogCell = $cell.find('.object-subkpi-dialog');
    var savedRecordId = (typeof recordId !== 'undefined') ? recordId : '';
    
    if (!$dialogCell.length) {        
        
        var postData = {
            selectedIds: [metaInfoIndicatorId], 
            subFormKeyName: 'metaDmRecordMapsSubForm', 
            rowIndex: rowIndex, 
            savedRecordId: savedRecordId
        };
        
        $.ajax({
            type: 'post',
            url: 'mdform/addonStructureForm',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                var renderData = data[0];
                
                $cell.append('<div class="hide object-subkpi-dialog"></div>');
                var $dialog = $cell.find('.object-subkpi-dialog');
                var hidden = '';
                
                if (renderData['recordId'] != '' && renderData['recordId'] != null) {
                    $row.find('input[name="metaDmRecordMaps[childRecordId][]"]').val(renderData['recordId']);
                }
                
                $dialog.append(renderData.form + hidden);

                $dialog.dialog({
                    appendTo: $cell,
                    cache: false,
                    resizable: true,
                    draggable: false,
                    bgiframe: true,
                    autoOpen: false,
                    dialogClass: 'sub-kpi-form',
                    title: renderData.name,
                    width: 1050, 
                    height: 'auto',
                    maxHeight: $(window).height() - 10, 
                    modal: true, 
                    closeOnEscape: isCloseOnEscape, 
                    create: function (event) {
                        $(event.target).parent().css('position', 'fixed'); 
                    },
                    resizeStart: function (event) {
                        $(event.target).parent().css('position', 'fixed'); 
                    },
                    resizeStop: function (event) {
                        $(event.target).parent().css('position', 'fixed'); 
                    }, 
                    close: function () { 
                        PNotify.removeAll();
                    },                                
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {

                            var validDtl = true;
                            var $formElements = $dialog.find('input,textarea,select').filter('[required="required"]');

                            $formElements.removeClass('error');

                            $formElements.each(function(){
                                var $elThis = $(this);
                                if ($elThis.val() == '') {
                                    $elThis.addClass('error');  
                                    validDtl = false;
                                }
                            });

                            if (validDtl) {

                                $dialog.dialog('close');

                            } else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                                    type: 'warning',
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            }
                        }}
                    ]
                });
                
                $dialog.parent().draggable();
                $dialog.dialog('open');
            },
            error: function () {
                alert("Error");
                Core.unblockUI();
            }
        }).done(function() {
            Core.unblockUI();
        });   
        
    } else {  
        $dialogCell.dialog('open');
    }
}
function kpiIndicatorRelationRemoveRows(elem) {
    var $row = $(elem).closest('tr');
    var rowState = $row.find('input[name="metaDmRecordMaps[rowState][]"]').val();
    
    if (rowState == 'added') {
        $row.remove();
    } else {
        $row.find('input[name="metaDmRecordMaps[rowState][]"]').val('removed');
        $row.hide();
    }
}
function kpiIndicatorRelationCollapse(elem) {
    var $this = $(elem), $icon = $this.find('i'), 
        $table = $this.closest('.reldetail').find('table.table');
    
    if ($icon.hasClass('fa-angle-down')) {
        $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
        $table.hide();
    } else {
        $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
        $table.show();
    }
}
function getKpiIndicatorFilterData(elem, parent) {
    
    if (typeof parent !== 'undefined') {
        
        var $col = parent;
        
    } else {
        
        var $this = $(elem), 
            $col = $this.closest('.list-group').length ? $this.closest('.list-group') : $this.closest('.filter-top-form-wrapper');
    
        if (!$this.hasClass('jstree-node')) {
            if ($this.hasClass('active')) {
                $this.removeClass('active');
                $this.find('i').removeClass('fas fa-check-square').addClass('far fa-square');
            } else {
                $this.addClass('active');
                $this.find('i').removeClass('far fa-square').addClass('fas fa-check-square');
            }
        }
    }
    
    var $activeList = $col.find('.list-group-item-action.active');
    var $betweenInputList = $col.find('[data-kpi-indicator-filter-between-input]');
    var $namedParamList = $col.find('[data-named-param="1"]');
    var $reportAggregateList = $col.find('[data-report-aggregate]');
    var indicatorId = $col.attr('data-indicatorid');
    var filterData = {}, groupingColumn = {}, forceFilterData = {}; 
    
    if ($activeList.length) {
        
        $activeList.each(function() {
            var $this = $(this);
            var colName = $this.attr('data-colname');
            var $span = $this.find('span:eq(0)');
            var text = $span.text();
            var list = filterData[colName];
            
            if ($span.hasAttr('data-value-mode') && $span.attr('data-value-mode') != '') {
                text = $span.attr('data-value-mode');
            }
            
            if (list) {
                list.push(text);
            } else{
                filterData[colName] = [text];
            }
        });
    } 
    
    if ($reportAggregateList.length) {
        $reportAggregateList.each(function() {
            var $this = $(this);
            var colName = $this.attr('data-colname');
            
            if (!filterData.hasOwnProperty(colName)) {
                var reportColName = $this.attr('data-report-colname');
                groupingColumn[reportColName] = 1;
            }
        });
    }
    
    if ($betweenInputList.length) {
        
        $betweenInputList.each(function() {
            var $this = $(this);
            var colName = $this.attr('data-kpi-indicator-filter-between-input');
            var $begin = $this.find('[data-kpi-indicator-filter-between="begin"]');
            var $end = $this.find('[data-kpi-indicator-filter-between="end"]');
            
            if (($begin.hasClass('bigdecimalInit') || $begin.hasClass('longInit') || $begin.hasClass('integerInit') || $begin.hasClass('amountInit')) 
                    && ($begin.val() != '' && $end.val() != '')) {
                
                var beginNumber = Number($begin.autoNumeric('get'));
                var endNumber = Number($end.autoNumeric('get'));
                
                var list = filterData[colName];
            
                if (list) {
                    list.push({begin: beginNumber, end: endNumber});
                } else {
                    filterData[colName] = [{begin: beginNumber, end: endNumber}];
                }
                
            } else if (($begin.hasClass('dateInit') || $begin.hasClass('dateminuteInit')) && ($begin.val() != '' && $end.val() != '')) {
                
                var beginDate = $begin.val();
                var endDate = $end.val();
                
                var list = filterData[colName];
            
                if (list) {
                    list.push({begin: beginDate, end: endDate});
                } else {
                    filterData[colName] = [{begin: beginDate, end: endDate}];
                }
            }
        });
    }
    
    if ($namedParamList.length) {
        $namedParamList.each(function() {
            var $this = $(this), $input = $this.find('[data-path]');
            
            if ($input.length == 1) {
                var namedParam = $input.attr('data-path');
                
                if ($input.hasClass('bigdecimalInit') || $input.hasClass('longInit') || $input.hasClass('integerInit') || $input.hasClass('amountInit')) {
                    var filterVal = $input.autoNumeric('get');
                } else {
                    var filterVal = $input.val();
                }
                
                filterData[namedParam] = filterVal;
            } else {
                var $clickItem = $this.find('.mv-filter-item-click.active[data-value!=""]');
                if ($clickItem.length) {
                    var $parent = $clickItem.closest('[data-filter-column]');
                    filterData[$parent.attr('data-filter-column')] = $clickItem.attr('data-value');
                }
            }
        });
    }
    
    return {indicatorId: indicatorId, filterData: filterData, forceFilterData: forceFilterData, groupingColumn: groupingColumn};
}

function filterKpiIndicatorToggleValue(elem) {
    var $this = $(elem);
    
    if ($this.hasClass('active')) {
        $this.removeClass('active');
        $this.find('i').removeClass('fas fa-check-square').addClass('far fa-square');
    } else {
        $this.addClass('active');
        $this.find('i').removeClass('far fa-square').addClass('fas fa-check-square');
    }
    
    var $parentFilter = $this.closest('.list-group');
    var getFilterData = getKpiIndicatorFilterData(elem, $parentFilter);
    var indicatorId = getFilterData.indicatorId;
    var filterData = getFilterData.filterData;
    
    mvFilterRelationLoadData(elem, indicatorId, filterData);
}

function bpExpCallKpiIndicatorForm(mainSelector, elem, indicatorId, recordId, mode) {
    manageKpiIndicatorValue(elem, '', indicatorId, true, {recordId: recordId, mode: mode});
}
function bpCallIndicatorProcess(mainSelector, elem, indicatorId, params) {
    var transferParams = {};
    if (params != '') {
        transferParams = qryStrToObj(params);
    }
    manageKpiIndicatorValue(elem, '', indicatorId, true, {transferParams: transferParams});
}

function kpiIndicatorExcelImport(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    
    if (typeof selectedRow == 'undefined' || (typeof selectedRow != 'undefined' && selectedRow.length == 0)) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    delete selectedRow.children;
    var paramObj = paramDataToObject(paramData);
    
    $.ajax({
        type: 'post',
        url: 'mdform/kpiIndicatorExcelImportForm',
        data: {param: paramObj, selectedRow: selectedRow}, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-kpiindicatorexcelimport';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
    
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'KPI Indicator excel import',
                    width: 600,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('import_btn'), class: 'btn btn-sm green-meadow', click: function () {
                            
                            PNotify.removeAll();
                
                            var $form = $dialog.find('form');
                            $form.validate({ errorPlacement: function() {} });

                            if ($form.valid()) {
                                $form.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdform/kpiIndicatorExcelImport',
                                    dataType: 'json',
                                    beforeSubmit: function(formData, jqForm, options) {
                                        formData.push({ name: 'indicatorId', value: data.indicatorId });
                                    },
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
                                            $dialog.dialog('close');
                                            bpVisiblePanelDataViewReload('secondList');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                            
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                Core.initLongInput($dialog);
                Core.initSelect2($dialog);
                
                $dialog.dialog('open');
            
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}
function mvColumnDrillDown(elem, indicatorId, columnName, rowIndex) {
    
    setTimeout(function() {
        
        var rowIndex = 0, rows = getDataViewSelectedRows(indicatorId);

        if (rows && rows.hasOwnProperty(0)) {
            $.ajax({
                type: 'post',
                url: 'mdform/getColumnDrillDownConfig',
                data: {indicatorId: indicatorId, columnName: columnName}, 
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (data.status == 'success') {

                        var rowData = rows[rowIndex], configData = data.data;
                        var origRow = Object.fromEntries(Object.entries(rowData).map(([key, val]) => [key.toLowerCase(), val]));
                        var isAccessDrillRow = false;
                        var configRow;
                        var configRows;
                        var linkIndicatorId;
                        var linkMetaDataId;
                        var showType;
                        var rowSort = Object.keys(origRow).map(function(k) { return { key: k }; }).sort(function(a, b) { return b.key.length - a.key.length; });
                        var row = {};
                        
                        for (var k in rowSort) {
                            row[rowSort[k]['key']] = origRow[rowSort[k]['key']];
                        }
                        
                        for (var c in configData) {
                            
                            if (isAccessDrillRow == false) {
                                
                                configRow = configData[c]['row'];
                                configRows = configData[c]['rows'];
                                linkIndicatorId = configRow['LINK_INDICATOR_ID'];
                                linkMetaDataId = configRow['LINK_META_DATA_ID'];
                                showType = configRow['SHOW_TYPE'];
                                var criteria = configRow['CRITERIA'];

                                if (criteria != '' && criteria != null) {
                                    criteria = criteria.toLowerCase();

                                    $.each(row, function(key, val) {
                                        if (criteria.indexOf(key) > -1) {
                                            row = (row === null) ? '' : val.toLowerCase();
                                            var regex = new RegExp('\\b' + key + '\\b', 'g');
                                            criteria = criteria.replace(regex, "'" + val.toString() + "'");
                                        }
                                    });

                                    try {
                                        if (eval(criteria)) {
                                            isAccessDrillRow = true;
                                        }
                                    } catch (err) { 
                                        isAccessDrillRow = false; 
                                        console.log(err); 
                                    }
                                } else {
                                    isAccessDrillRow = true;
                                }

                                if (isAccessDrillRow) {
                                    
                                    var isNewTab = (showType == 'newtab' || showType == 'newrender' || showType == 'tab') ? true : false;
                                    var drillDownCriteria = '';

                                    for (var r in configRows) {

                                        var srcParam = (configRows[r]['SRC_PARAM']) ? (configRows[r]['SRC_PARAM']).toLowerCase() : '', 
                                            trgParam = configRows[r]['TRG_PARAM'], 
                                            defaultValue = configRows[r]['DEFAULT_VALUE'];

                                        if (defaultValue != null && defaultValue != '') {
                                            drillDownCriteria += trgParam + '=' + defaultValue + '&';
                                        } else if (row.hasOwnProperty(srcParam)) {
                                            drillDownCriteria += trgParam + '=' + row[srcParam] + '&';
                                        }
                                    }

                                    drillDownCriteria = rtrim(drillDownCriteria, '&');
                                }
                            }
                        }
                        
                        if (isAccessDrillRow) {
                            
                            if (linkIndicatorId != '' && linkIndicatorId != null) {

                                var kpiTypeId = configRow['KPI_TYPE_ID'];

                                if (kpiTypeId == '1000' || kpiTypeId == '1020' || kpiTypeId == '1040' 
                                    || kpiTypeId == '1042' || kpiTypeId == '1043' || kpiTypeId == '1044' 
                                    || kpiTypeId == '1045' || kpiTypeId == '1160' || kpiTypeId == '1161' 
                                    || kpiTypeId == '2013' || kpiTypeId == '2016' || kpiTypeId == '2007' 
                                    || kpiTypeId == '16641793815766') {

                                    var drillPostParam = {indicatorId: linkIndicatorId, drillDownCriteria: drillDownCriteria, isJson: 1};

                                    if (isNewTab == false) {
                                        drillPostParam.isDrilldown = 1;
                                    }

                                    $.ajax({
                                        type: 'post',
                                        url: 'mdform/indicatorList/'+linkIndicatorId+'/1',
                                        data: drillPostParam,
                                        dataType: 'json', 
                                        success: function(content) {
                                            if (isNewTab) {
                                                appMultiTabByContent({ metaDataId: linkIndicatorId, title: content.title, type: 'indicator', content: content.html });
                                            } else {
                                                mvOpenDialog({ metaDataId: linkIndicatorId, title: content.title, type: 'indicatorList', content: content.html });
                                            }
                                        }
                                    });
                                } 

                            } else if (linkMetaDataId != '' && linkMetaDataId != null) {
                                gridDrillDownLink(elem, configRow['META_TYPE_CODE'], '', linkMetaDataId, '', indicatorId, columnName, linkMetaDataId, drillDownCriteria, isNewTab, undefined, configRow['DIALOG_WIDTH'], configRow['DIALOG_HEIGHT']);
                            }
                        
                        } else {
                            
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: 'Нөхцөл тохирохгүй байна!',
                                type: 'warning',
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                        }
                    }
                    
                    Core.unblockUI();
                }
            });
        }
    
    }, 2);
}
function mvOpenDialog(opts) {
    
    var $dialogName = 'dialog-mvopendialog-'+opts.metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), dialogWidth = 1500, dialogHeight = $(window).height() - 40;
    
    if (opts.hasOwnProperty('dialogWidth') && opts.dialogWidth) {
        dialogWidth = opts.dialogWidth;
    }
    
    if (opts.hasOwnProperty('dialogHeight') && opts.dialogHeight) {
        dialogHeight = opts.dialogHeight;
    }

    $dialog.empty().append(opts.content);
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: opts.title,
        width: dialogWidth,
        height: dialogHeight,
        modal: true,
        closeOnEscape: isCloseOnEscape, 
        position: {my: 'top', at: 'top+20'}, 
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
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
    
    if (opts.hasOwnProperty('isFullScreen') && opts.isFullScreen) {
        $dialog.dialogExtend('maximize');
    }

    $dialog.dialog('open');
    
    $dialog.bind('dialogextendminimize', function() {
        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').addClass('display-none');
    });
    $dialog.bind('dialogextendmaximize', function() {
        $(window).trigger('resize');
        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').removeClass('display-none');
    });
    $dialog.bind('dialogextendrestore', function() {
        $(window).trigger('resize');
        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').removeClass('display-none');
    });
}
function mvAddStructureFormCardView(elem) {
    var $this = $(elem), $form = $this.closest('form'), 
        mainIndicatorId = $form.find('input[name="kpiMainIndicatorId"]').val(), 
        selectLimit = Number($this.attr('data-limit'));
        
    $.ajax({
        type: 'post',
        url: 'mdform/getIndicatorMapBySemantic',
        data: {indicatorId: mainIndicatorId, semanticTypeId: '10000017'}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (data.hasOwnProperty(0)) {
                var html = [], dataLength = data.length;
                html.push('<div data-selected-obj="1"></div>');
                
                if (dataLength > 10) {
                    
                    const groupByCategory = data.reduce((acc, obj) => {
                        const key = obj['PARENT_NAME'];
                        if (!acc[key]) {
                            acc[key] = [];
                        }
                        acc[key].push(obj);
                        return acc;
                    }, {});

                    var columnNum = 3;
                    var categoryLength = Math.ceil(Object.keys(groupByCategory).length / columnNum);
                    var i = 1;
                    
                    html.push('<div class="row">');
                    
                    for (var i = 1; i <= columnNum; i++) {
                        
                        html.push('<div class="col-md-4">');
                        
                        var n = 1;
                        
                        for (var g in groupByCategory) {
                            
                            var itemList = groupByCategory[g];
                            var itemLength = itemList.length;
                            
                            html.push('<div class="mb-2">');
                                html.push('<h6 class="font-size-14 text-uppercase mt-2 mb-1 font-weight-bold text-grey-800"><i class="fad fa-folder-open text-orange mr-2"></i> '+g+' <span class="ml-1">('+itemLength+')</span></h6>');
                                html.push('<div class="dropdown-divider mb-2"></div>');
                                
                                for (var k in itemList) {
                                    html.push('<a href="javascript:;" class="dropdown-item mv-addon-item font-size-13" data-id="'+itemList[k]['ID']+'" style="padding: 0.3em 0.9rem;white-space: normal;line-height: normal;">');
                                        html.push('<i class="far fa-circle text-blue-400 font-size-15 obj-select-icon"></i> '+itemList[k]['NAME']);
                                    html.push('</a>');
                                }
                        
                            html.push('</div>');  
                            
                            delete groupByCategory[g];
                            
                            if (categoryLength == n) {
                                break;
                            }
                            
                            n++;
                        }
                        html.push('</div>');
                    }
                    
                    html.push('</div>');                  
        
                } else {
                    for (var i in data) {
                        html.push('<div class="card card-body d-inline-block ml-2 p-2 cursor-pointer mv-addon-item" style="width: 300px;" data-id="'+data[i]['ID']+'">');
                            html.push('<div class="media">');
                                html.push('<div class="mr-3 align-self-center">');
                                    html.push('<i class="far fa-circle text-blue-400 font-size-30 obj-select-icon"></i>');
                                html.push('</div>');
                                html.push('<div class="media-body">');
                                    html.push('<h6 class="font-weight-semibold mb-0 line-height-normal">'+data[i]['NAME']+'</h6>');
                                html.push('</div>');
                            html.push('</div>');
                        html.push('</div>');
                    }
                }
                
                var $dialogName = 'dialog-addstructurecardview';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
    
                $dialog.empty().append(html.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: $this.attr('data-tabname'),
                    width: 1280,
                    height: $(window).height(),
                    modal: true,
                    open: function () {
                        $dialog.on('click', '.mv-addon-item', function() {
                            var $cardThis = $(this), indicatorId = $cardThis.attr('data-id');
                            var $icon = $cardThis.find('.obj-select-icon');
                            if ($icon.hasClass('fa-circle')) {
                                $icon.removeClass('fa-circle text-blue-400').addClass('fa-check-circle text-success-600');
                                $cardThis.addClass('mv-obj-selected');
                            } else {
                                $icon.removeClass('fa-circle-check text-success-600').addClass('fa-circle text-blue-400');
                                $cardThis.removeClass('mv-obj-selected');
                            }
                            
                            if (selectLimit < Number($dialog.find('.mv-obj-selected').length)) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Сонгох лимитээс хэтэрсэн байна!',
                                    type: 'warning',
                                    sticker: false, 
                                    addclass: pnotifyPosition
                                });
                            }
                        });
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('add_btn'), class: 'btn btn-sm green-meadow', click: function () {
                                
                            PNotify.removeAll();
                            var $selectedObj = $dialog.find('.mv-obj-selected');
                            var length = Number($selectedObj.length);
                            
                            if (length > 0) {
                                var $addParent = $this.closest('.tab-pane').find('.mv-addon-structure-render');
                                var alreadyCount = Number($addParent.find('[data-addonform-id]').length) + length;
                                
                                if (selectLimit < length || selectLimit < alreadyCount) {
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Сонгох лимитээс хэтэрсэн байна!',
                                        type: 'warning',
                                        sticker: false, 
                                        addclass: pnotifyPosition
                                    });
                                    return;
                                }
                                
                                var selectedIds = [], i = 0;
                                
                                for (i; i < length; i++) { 
                                    var indId = $($selectedObj[i]).attr('data-id');
                                    /*if ($addParent.find('[data-addonform-id="'+indId+'"]').length == 0) {*/
                                        selectedIds.push(indId);
                                    /*}*/
                                }
                                
                                if (selectedIds.length) {
                                    var mainUniqId = $this.closest('[data-bp-uniq-id]').attr('data-bp-uniq-id');
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdform/addonStructureForm',
                                        data: {indicatorId: mainIndicatorId, selectedIds: selectedIds, mainUniqId: mainUniqId}, 
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(dataSub) {
                                            
                                            for (var d in dataSub) {
                                                
                                                var removeBtn = '<button type="button" class="btn btn-xs red" style="position: absolute;right: 20px;top: -3px;" onclick="mvAddStructureFormRemove(this);"><i class="far fa-trash"></i></button>';
                                                var hidden = '<input type="hidden" name="kpiAddonForm['+dataSub[d]['id']+'_'+dataSub[d]['uniqId']+']">';
                                                var appendHtml = '<div data-addonform-id="'+dataSub[d]['id']+'" data-addonform-recordid="" data-addonform-uniqid="'+dataSub[d]['uniqId']+'" style="position: relative">' + removeBtn + '<fieldset class="collapsible border-fieldset mt-2 mb-3"><legend>'+dataSub[d]['name']+'</legend>'+dataSub[d]['form']+hidden+'</fieldset></div>';
                                                
                                                $addParent.append(appendHtml);
                                            }
                                            
                                            Core.unblockUI();
                                        }
                                    });
                                }
                                
                                $dialog.dialog('close');
                            } else {
                                new PNotify({
                                    title: 'Warning',
                                    text: 'Сонголт хийнэ үү!',
                                    type: 'warning',
                                    sticker: false, 
                                    addclass: pnotifyPosition
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
            Core.unblockUI();
        }
    });
}
function mvChangeWfmStatus(elem, mainIndicatorId) {
    var $this = $(elem), statusConfig = $this.attr('data-statusconfig');
    var obj = JSON.parse(statusConfig);
    var methodIndicatorId = obj.indicatorid;
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdform/transferIndicatorAction',
        data: {mainIndicatorId: mainIndicatorId, methodIndicatorId: methodIndicatorId}, 
        dataType: 'json',
        success: function(data) {
            if (data.status == 'success') {
                
                var dataRow = data.data, isFillRelation = Number(dataRow.is_fill_relation), 
                    isNormalRelation = Number(dataRow.is_normal_relation);
                
                $this.attr({
                    'data-actiontype': dataRow.type_code, 
                    'data-main-indicatorid': mainIndicatorId,
                    'data-structure-indicatorid': dataRow.structure_indicator_id,
                    'data-crud-indicatorid': dataRow.crud_indicator_id
                });
                
                if (isNormalRelation > 0) {
                    mvNormalRelationRender($this, dataRow.kpi_type_id, mainIndicatorId, {methodIndicatorId: dataRow.crud_indicator_id, structureIndicatorId: dataRow.structure_indicator_id, mode: dataRow.type_code});
                } else {
                    
                    if (dataRow.structure_indicator_id == mainIndicatorId && isFillRelation == 0) {
                        manageKpiIndicatorValue($this, dataRow.kpi_type_id, mainIndicatorId, true);
                    } else {
                        var opt = {}, 
                            typeCode = dataRow.type_code, 
                            isEdit = (typeCode == 'create') ? false : true;

                        if (isFillRelation > 0) {
                            opt.fillSelectedRow = true;
                        }

                        manageKpiIndicatorValue($this, dataRow.kpi_type_id, dataRow.structure_indicator_id, isEdit, opt);
                    }
                }
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
        }
    });
}
function mvAddStructureFormRemove(elem) {
    var dialogName = '#dialog-addonstr-obj-confirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);

    $dialog.html(plang.get('msg_delete_confirm'));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'), 
        width: 300,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                
                var $row = $(elem).parent();
                var trgRecordId = $row.attr('data-addonform-recordid');
                
                if (trgRecordId != '') {
                    var $form = $row.closest('form');
                    var trgIndicatorId = $row.attr('data-addonform-id');
                    var srcIndicatorId = $form.find('input[name="kpiMainIndicatorId"]').val();
                    var srcRecordId = $form.find('input[name="kpiTblId"]').val();
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdform/removeAddonStructureForm',
                        data: {
                            srcIndicatorId: srcIndicatorId, 
                            srcRecordId: srcRecordId, 
                            trgIndicatorId: trgIndicatorId, 
                            trgRecordId: trgRecordId
                        }, 
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
                                sticker: false, 
                                addclass: pnotifyPosition
                            }); 
                            if (data.status == 'success') {
                                $row.remove();
                            }
                            Core.unblockUI();
                        }
                    });
                    
                } else {
                    $row.remove();
                }
                
                $dialog.dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}

function mvInitControls($elem) {
    Core.initNumberInput($elem);
    Core.initLongInput($elem);
    Core.initAmountInput($elem);
    Core.initDateInput($elem);
    Core.initDateTimeInput($elem);
    Core.initSelect2($elem);
    Core.initUniform($elem);
    Core.initDateMinuteInput($elem);
    Core.initTimeInput($elem);
    Core.initTextareaAutoHeight($elem);
    Core.initIconPicker($elem);
    return;
}
function kpiSetRowIndex($tbody, rowIndex) {
    var $el = $tbody.find('> .bp-detail-row');
    var len = $el.length, i = 0;
    
    if (typeof rowIndex !== 'undefined') {
        for (i; i < len; i++) { 
            var $subElement = $($el[i]).find('input, select, textarea');
            var slen = $subElement.length, j = 0;
            for (j; j < slen; j++) { 
                var $inputThis = $($subElement[j]);
                var $inputName = $inputThis.attr('name');
                if (typeof $inputName !== 'undefined') {
                    if ($inputThis.is('[multiple]') && $inputThis.hasClass('select2')) {
                        if ($inputName.indexOf('[][]') !== -1) {
                            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[\])(\[\])$/, '$1[' + rowIndex + '][' + i + ']$4'));
                        } else {
                            $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(\[[0-9]+\])(\[\])$/, '$1[' + rowIndex + '][' + i + ']$4'));
                        }
                    } else {
                        $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + rowIndex + ']$3'));
                    }
                }
            }
        }
    } else {
        for (i; i < len; i++) { 
            var $subElement = $($el[i]).find('input, select, textarea');
            var slen = $subElement.length, j = 0;
            for (j; j < slen; j++) { 
                var $inputThis = $($subElement[j]);
                var $inputName = $inputThis.attr('name');
                if (typeof $inputName !== 'undefined') {
                    $inputThis.attr('name', $inputName.replace(/^(.*)(\[[0-9]+\])(.*)$/, '$1[' + i + ']$3'));
                }
            }
        }
    }
    
    return;
}
function setRowNumKpiIndicatorTemplate(tbody) {
    var $rows = tbody.find('> tr:visible');
    if ($rows.length) {
        $rows.each(function(i) {
            $(this).find('.bp-dtl-rownumber:eq(0)').text(i + 1);
        });
    }
    return;
}
function setIndColVal(mainSelector, elem, setField, val) {
    var $setField = elem.closest('tr').find('[data-path="'+setField+'"]');
    
    if ($setField.length) {
        if ($setField.hasClass('longInit') || $setField.hasClass('integerInit') || $setField.hasClass('amountInit')) {
            $setField.autoNumeric('set', val).trigger('change');
        } else if ($setField.hasClass('kpiDecimalInit')) {
            $setField.next('input[type=hidden]').val(setNumberToFixed(val));
            $setField.autoNumeric('set', val).trigger('change');
        } else {
            $setField.val(val).trigger('change');
        }
    }
    return;
}
function getIndColVal(mainSelector, elem, getField) {
    var selectedVal = '';
    var $getField = elem.closest('tr').find('[data-path="'+getField+'"]');
    
    if ($getField.length) {
        if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit') 
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit') 
                || $getField.hasClass('kpiDecimalInit') 
                || $getField.hasClass('longInit') 
                || $getField.hasClass('amountInit')) { 

            selectedVal = Number($getField.autoNumeric('get'));

        } else {
            selectedVal = $getField.val();
            if (isNumeric(selectedVal)) {
                selectedVal = Number(selectedVal);
            }
        } 
    }
    return selectedVal;
}

function setIndCellVal(mainSelector, setField, val) {
    var $setField = mainSelector.find('[data-field-name="'+setField+'"]');
    
    if ($setField.length) {
        if ($setField.hasClass('longInit') || $setField.hasClass('integerInit') || $setField.hasClass('amountInit')) {
            $setField.autoNumeric('set', val).trigger('change');
        } else if ($setField.hasClass('kpiDecimalInit')) {
            $setField.next('input[type=hidden]').val(setNumberToFixed(val));
            $setField.autoNumeric('set', val).trigger('change');
        } else {
            $setField.val(val).trigger('change');
        }
    }
    return;
}
function getIndCellVal(mainSelector, getField) {
    var selectedVal = '';
    var $getField = mainSelector.find('[data-field-name="'+getField+'"]');
    
    if ($getField.length) {
        if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit') 
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit') 
                || $getField.hasClass('kpiDecimalInit') 
                || $getField.hasClass('longInit') 
                || $getField.hasClass('amountInit')) { 

            selectedVal = Number($getField.autoNumeric('get'));

        } else {
            selectedVal = $getField.val();
            
            if ($getField.hasClass('select2') && $getField.hasAttr('data-name')) {
                var dataName = $getField.attr('data-name');
                if (dataName != '') {
                    var $option = $getField.find('option:selected');
                    var rowData = $option.data('row-data');
                    selectedVal = rowData[dataName];
                }
            }
            
            if (isNumeric(selectedVal)) {
                selectedVal = Number(selectedVal);
            }
        } 
    }
    return selectedVal;
}

function setIndHdrVal(mainSelector, setField, val) {
    var $setField = mainSelector.find('[data-path="'+setField+'"]');
    
    if ($setField.length) {
        if ($setField.hasClass('longInit') || $setField.hasClass('integerInit') || $setField.hasClass('amountInit')) {
            $setField.autoNumeric('set', val).trigger('change');
        } else if ($setField.hasClass('kpiDecimalInit')) {
            $setField.next('input[type=hidden]').val(setNumberToFixed(val));
            $setField.autoNumeric('set', val).trigger('change');
        } else {
            $setField.val(val).trigger('change');
        }
    }
    return;
}
function getIndHdrVal(mainSelector, getField) {
    var selectedVal = '';
    var $getField = mainSelector.find('[data-path="'+getField+'"]');
    
    if ($getField.length) {
        if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit') 
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit') 
                || $getField.hasClass('kpiDecimalInit') 
                || $getField.hasClass('longInit') 
                || $getField.hasClass('amountInit')) { 

            selectedVal = Number($getField.autoNumeric('get'));

        } else {
            selectedVal = $getField.val();
            
            if ($getField.hasClass('select2') && $getField.hasAttr('data-name')) {
                var dataName = $getField.attr('data-name');
                if (dataName != '') {
                    var $option = $getField.find('option:selected');
                    var rowData = $option.data('row-data');
                    selectedVal = rowData[dataName];
                }
            }
            
            if (isNumeric(selectedVal)) {
                selectedVal = Number(selectedVal);
            }
        } 
    }
    return selectedVal;
}
function getIndDtlSum(mainSelector, parentField, fieldPath) {
    var $getField = mainSelector.find('table[data-col-name="'+parentField+'"] > tbody [data-col-path="'+fieldPath+'"]');
    if ($getField.length) {
        return Number($getField.sum());
    }
    return 0;
}

function hideIndCellVal(mainSelector, getField) {

    var $getField = mainSelector.find('[data-field-name="'+getField+'"]');
    
    if ($getField.length) {
        if ($getField.hasClass('numberInit') 
                || $getField.hasClass('decimalInit') 
                || $getField.hasClass('integerInit') 
                || $getField.hasClass('bigdecimalInit') 
                || $getField.hasClass('kpiDecimalInit') 
                || $getField.hasClass('longInit')) { 

            $getField.hide();

        } else if ($getField.hasClass('booleanInit')) {
            $getField.closest('.checker').hide();
        } else if ($getField.hasClass('amountInit')) {
            $getField.closest('.input-group').hide();
        } else {
            
            if ($getField.hasClass('select2')) {
                $getField.hide();
            } else {
                $getField.hide();
            }            
        } 
    }
    return;
}
function mvRowsExcelImportDetail(elem, indicatorId, rowId, groupPath) {
    var $this = $(elem);
    var dialogName = '#dialog-mvdtl-excelimport';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName), html = [];

    html.push('<form method="post" enctype="multipart/form-data">');
        html.push('<div class="form-group row mb-2">');
            html.push('<div class="col-md-12"><div class="alert alert-info">Та эхлээд загвар файл татна уу!</div></div>');
        html.push('</div>');
        html.push('<div class="form-group row mb-2">');
            html.push('<label class="col-md-3 col-form-label text-right pt-1 pr0"><span class="required">*</span> Эксель файл сонгох:</label>');
            html.push('<div class="col-md-9"><input type="file" class="form-control" name="excelFile" required="required" onchange="hasExcelExtension(this);" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></div>');
        html.push('</div>');
    html.push('</form>');

    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Эксель импорт',
        width: 600,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: 'Загвар татах', class: 'btn btn-danger btn-sm float-left', click: function () {
                
                Core.blockUI({message: 'Loading...', boxed: true});
    
                $.fileDownload(URL_APP + 'mdform/downloadExcelImportTemplate', {
                    httpMethod: 'post',
                    data: {indicatorId: indicatorId, parentId: rowId}
                }).done(function() {
                    Core.unblockUI();
                }).fail(function(response){
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: response,
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                });
            }},
            {text: 'Импорт хийх', class: 'btn green-meadow btn-sm', click: function () {
                    
                PNotify.removeAll();
                
                var $form = $dialog.find('form');
                $form.validate({ errorPlacement: function() {} });

                if ($form.valid()) {
                    
                    $form.ajaxSubmit({
                        type: 'post',
                        url: 'mdform/rowsImportExcel',
                        dataType: 'json',
                        beforeSubmit: function(formData, jqForm, options) {
                            formData.push({name: 'indicatorId', value: indicatorId});
                            formData.push({name: 'rowId', value: rowId});
                            formData.push({name: 'groupPath', value: groupPath});
                        },
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            
                            if (data.status == 'success') {
                                
                                var $form = $this.closest('[data-addonform-uniqid]'), 
                                    $parent = $this.closest('div'),    
                                    $nextDiv = $parent.next('div'),
                                    $table = $nextDiv.find('table.table:eq(0)'), 
                                    $tbody = $table.find('> tbody'),
                                    uniqId = '';

                                if ($form.length) {
                                    uniqId = $form.attr('data-addonform-uniqid');
                                } else {
                                    uniqId = $this.closest('.kpi-ind-tmplt-section[data-bp-uniq-id]').attr('data-bp-uniq-id');
                                }
    
                                var $html = $('<div />', {html: data.rows});
                                $html.children('.bp-detail-row')
                                        .removeClass('saved-bp-row')
                                        .removeAttr('data-savedrow')
                                        .addClass('added-bp-row display-none multi-added-row');

                                $tbody.append($html.html());

                                var $rowEl = $tbody.find('> .bp-detail-row.multi-added-row');
                                var rowLen = $rowEl.length, rowi = 0;
                                
                                mvInitControls($rowEl);

                                if (rowLen === 1) {

                                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true, true, 'excelimport');

                                } else if (rowLen > 1) {

                                    var rowLen = rowLen - 1;

                                    for (rowi; rowi < rowLen; rowi++) { 
                                        window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true, false, 'excelimport');
                                    }

                                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowLen]), groupPath, true, true, 'excelimport');
                                }
                                
                                $rowEl.removeClass('multi-added-row display-none');
                                
                                setRowNumKpiIndicatorTemplate($tbody);
                                kpiSetRowIndex($tbody);
                                bpDetailFreeze($table);
                                
                                window['dtlAggregateFunction_'+uniqId]();
                
                                $dialog.dialog('close');
                            } else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Error',
                                    text: data.message,
                                    type: 'error',
                                    sticker: false
                                });
                            }
                            
                            Core.unblockUI();
                        }, 
                        error: function(data) {
                            new PNotify({
                                title: data.responseJSON.status,
                                text: data.responseJSON.message,
                                type: data.responseJSON.status,
                                sticker: false
                            });
                            Core.unblockUI();
                        }
                    });
                }
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function mvRowsExcelImportTemplate(elem, indicatorId, rowId, columnPath, isTemplateRows) {
    
    if (isTemplateRows === 0) {
        mvRowsExcelImportDetail(elem, indicatorId, rowId, columnPath);
        return;
    }
    
    var $this = $(elem);   
    var dialogName = '#dialog-mvrows-excel';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName), form = [];
    
    form.push('<form method="post" enctype="multipart/form-data">');
        form.push('<div class="col-md-12 xs-form">');
            
            form.push('<div class="form-group row mt10">');
                form.push('<label class="col-form-label col-md-3 text-right pt-1"><span class="required">*</span>'+plang.get('excel_file_btn')+':</label>');
                form.push('<div class="col-md-9">');
                    form.push('<input type="file" name="excelFile" id="excelFile" class="form-control form-control-sm fileInit" required="required" data-valid-extension="xls, xlsx">');
                form.push('</div>');
            form.push('</div>');
            
            form.push('<div class="form-group row mt20 mb20">');
                form.push('<label class="col-form-label col-md-3 text-right pt-1"><span class="required">*</span>Sheet сонгох:</label>');
                form.push('<div class="col-md-9">');
                    form.push('<select class="form-control dropdownInput" name="sheetIndex" required="required">');
                        form.push('<option value="">- Сонгох -</option>');
                    form.push('</select>');
                form.push('</div>');
            form.push('</div>');
            
        form.push('</div>');
    form.push('</form>');

    $dialog.empty().append(form.join(''));
    
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('pf_excel_import'), 
        width: 600,
        height: 'auto',
        modal: true,
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('import_btn'), class: 'btn green-meadow btn-sm', click: function() {
                PNotify.removeAll();
                
                var $form = $dialog.find('form');
                $form.validate({ errorPlacement: function() {} });

                if ($form.valid()) {
                    
                    var $fileInput = $form.find('.fileInit');
                    var file = $fileInput[0].files[0];
                    var reader = new FileReader();
                    
                    reader.readAsText(file, 'UTF-8');

                    reader.onload = function () {
                        
                        $form.ajaxSubmit({
                            type: 'post',
                            url: 'mdform/mvImportRowsExcelFile',
                            dataType: 'json',
                            beforeSubmit: function(formData, jqForm, options) {
                                formData.push({ name: 'indicatorId', value: indicatorId });
                                formData.push({ name: 'rowId', value: rowId });
                                formData.push({ name: 'columnPath', value: columnPath });
                            },
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                PNotify.removeAll();
                                
                                if (data.status === 'success') {
                                    $dialog.dialog('close');
                                    
                                    var configs = data.configs, rows = data.rows;
                                    var $tbl = $this.closest('div[data-section-path]');
                                    var $tbodyRows = $tbl.find('table[data-table-path="'+columnPath+'"] > tbody > tr');
                                    var checkUniqueColumns = {}, setColumns = {}, i = 0, s = 0, rowObj = {};
                                    
                                    for (var c in configs) {
                                        var isUnique = configs[c]['IS_UNIQUE'];
                                        var colIndex = configs[c]['colIndex'] - 1;
                                        rowObj = {};
                                        
                                        rowObj['COLUMN_NAME_PATH'] = configs[c]['COLUMN_NAME_PATH'];
                                        rowObj['SHOW_TYPE'] = configs[c]['SHOW_TYPE'];
                                        rowObj['colIndex'] = colIndex;
                                        
                                        if (isUnique == '1') {
                                            checkUniqueColumns[i] = rowObj;
                                            i++;
                                        } else {
                                            setColumns[s] = rowObj;
                                            s++;
                                        }
                                    }
                                        
                                    $tbodyRows.each(function() {
                                        var $thisRow = $(this);
                                        
                                        for (var r in rows) {
                                            var isValidRow = true;
                                            var rowData = rows[r];
                                            
                                            for (var c in checkUniqueColumns) {
                                                var checkPath = checkUniqueColumns[c]['COLUMN_NAME_PATH'];
                                                var checkShowType = checkUniqueColumns[c]['SHOW_TYPE'];
                                                var colIndex = checkUniqueColumns[c]['colIndex'];
                                                var rowEqualVal = (rowData[colIndex]).trim().toLowerCase();
                                                
                                                var equalVal = $thisRow.find('> td[data-cell-path="'+checkPath+'"]').text().toLowerCase();

                                                if (equalVal != rowEqualVal) {
                                                    isValidRow = false;
                                                } 
                                            }
                                            
                                            if (isValidRow) {
                                                for (var s in setColumns) {
                                                    var setPath = setColumns[s]['COLUMN_NAME_PATH'];
                                                    var setColIndex = setColumns[s]['colIndex'];
                                                    var $setField = $thisRow.find('[data-path="'+setPath+'"]');
                                                    var setValue = rowData[setColIndex];
                                                    
                                                    if ($setField.length) {
                                                        
                                                        if ($setField.hasClass('longInit') 
                                                            || $setField.hasClass('integerInit') 
                                                            || $setField.hasClass('amountInit')) {
                                                        
                                                            setValue = setValue.replace(/,/g, '');
                                                            $setField.autoNumeric('set', setValue).trigger('change');
                                                        } else if ($setField.hasClass('kpiDecimalInit')) {
                                                            setValue = setValue.replace(/,/g, '');
                                                            $setField.next('input[type=hidden]').val(setNumberToFixed(setValue));
                                                            $setField.autoNumeric('set', setValue).trigger('change');
                                                        } else {
                                                            $setField.val(setValue).trigger('change');
                                                        }
                                                    }
                                                }
                                                break;
                                            }
                                        }
                                    });
                                    
                                    Core.unblockUI();
                                    
                                } else {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        delay: 1000000000, 
                                        addclass: 'pnotify-center'
                                    });
                                }
                                Core.unblockUI();
                            }
                        });
                    };

                    reader.onerror = function (err) {
                        var errorMsg = err.target.error.message;
                        
                        if (errorMsg.indexOf('file could not be read') !== -1 || errorMsg.indexOf('ERR_UPLOAD_FILE_CHANGED') !== -1) {
                            
                            $fileInput.val('');
                            
                            var $sheetCombo = $dialog.find('select[name="sheetIndex"]');
                            $sheetCombo.find('option:gt(0)').remove();
                        
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Info',
                                text: 'Таны сонгосон файл дээр өөрчлөлт орсон тул та файлаа дахин сонгоно уу.',
                                type: 'info',
                                sticker: false, 
                                delay: 1000000000, 
                                addclass: 'pnotify-center'
                            });
                            Core.unblockUI();
                        }
                    };
                }
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
    
    $dialog.on('change', '#excelFile', function() {
        var $fileInput = $(this), fileValue = $fileInput.val();
        
        if (fileValue) {
            var lowerExtension = fileValue.split('.').pop().toLowerCase();
            
            if (lowerExtension == 'xls' || lowerExtension == 'xlsx') {
                
                var $form = $dialog.find('form');
                var file = $fileInput[0].files[0];
                var reader = new FileReader();

                reader.readAsText(file, 'UTF-8');

                reader.onload = function () {

                    $form.ajaxSubmit({
                        type: 'post',
                        url: 'mdform/mvGetExcelFileSheet',
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
                                sticker: false, 
                                addclass: 'pnotify-center'
                            });
                            if (data.status === 'success') {
                                
                               var $sheetCombo = $dialog.find('select[name="sheetIndex"]');
                               $sheetCombo.find('option:gt(0)').remove();
                    
                                $.each(data.sheets, function (i, r) {
                                    $sheetCombo.append($("<option />").val(i).text(r));
                                });
                            }
                            Core.unblockUI();
                        }
                    });
                };

                reader.onerror = function (err) {
                    var errorMsg = err.target.error.message;

                    if (errorMsg.indexOf('file could not be read') !== -1 || errorMsg.indexOf('ERR_UPLOAD_FILE_CHANGED') !== -1) {
                        
                        $fileInput.val('');
                        
                        var $sheetCombo = $dialog.find('select[name="sheetIndex"]');
                        $sheetCombo.find('option:gt(0)').remove();
                               
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Info',
                            text: 'Таны сонгосон файл дээр өөрчлөлт орсон тул та файлаа дахин сонгоно уу.',
                            type: 'info',
                            sticker: false, 
                            delay: 1000000000, 
                            addclass: 'pnotify-center'
                        });
                        Core.unblockUI();
                    }
                };
            }
        }
    });
}
function mvRowsGetValueFromDataMart(elem, indicatorId, rowId, columnPath) {
    var $this = $(elem);   
    var $header = $this.closest('form').find('.kpi-hdr-table').find('[data-path]');
    var headerData = {};
    
    $header.each(function() {
        var $headerElem = $(this);
        headerData[$headerElem.attr('data-path')] = $headerElem.val();
    });
    
    var postData = {indicatorId: indicatorId, rowId: rowId, headerData: headerData};
    
    $.ajax({
        type: 'post',
        url: 'mdform/rowsGetValueFromDataMart',
        data: postData, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            if (data.status == 'success') {
                
                var cells = data.cells;
                var $tbl = $this.closest('div[data-section-path]');
                var $tbodyRows = $tbl.find('table[data-table-path="'+columnPath+'"] > tbody');
                                    
                for (var cellId in cells) {
                    if (cells[cellId].hasOwnProperty('success')) {
                        
                        var $setField = $tbodyRows.find('[data-field-name="'+columnPath+'.'+cellId+'"]');

                        if ($setField.length) {
                            
                            var setValue = cells[cellId]['success'];
                            
                            if ($setField.hasClass('longInit') 
                                || $setField.hasClass('integerInit') 
                                || $setField.hasClass('amountInit')) {

                                setValue = isNumeric(setValue) ? setValue : setValue.replace(/,/g, '');
                                $setField.autoNumeric('set', setValue).trigger('change');
                                
                            } else if ($setField.hasClass('kpiDecimalInit')) {
                                
                                setValue = isNumeric(setValue) ? setValue : setValue.replace(/,/g, '');
                                $setField.next('input[type=hidden]').val(setNumberToFixed(setValue));
                                $setField.autoNumeric('set', setValue).trigger('change');
                            } else {
                                $setField.val(setValue).trigger('change');
                            }
                        }
                    
                    } else {
                        console.log(cells[cellId]['error']);
                    }
                }
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: 'pnotify-center'
                }); 
            }
            Core.unblockUI();
        }
    });
}
function createMvStructureFromFile(elem, dataViewId, opts) {
    
    PNotify.removeAll();
    
    var postData = {}, indicatorId = '', 
        isContextMenu = opts.isContextMenu, 
        isImportManage = opts.hasOwnProperty('isImportManage') ? opts.isImportManage : false;
    
    if (!isContextMenu && !isImportManage && dataViewId != '') {
        var rows = getRowsDataView(dataViewId);
        if (rows.length > 0 && rows.hasOwnProperty(0) && (rows[0]).hasOwnProperty('id')) {
            indicatorId = rows[0]['id'];
            postData.indicatorId = indicatorId;
        } else {
            new PNotify({
                title: 'Info',
                text: 'Дата олдсонгүй!',
                type: 'info',
                sticker: false, 
                addclass: 'pnotify-center'
            });
            return;
        }
    }
    
    var callbackFunction = opts.hasOwnProperty('callbackFunction') ? opts.callbackFunction : null;
    var devIndicatorId = opts.hasOwnProperty('devIndicatorId') ? opts.devIndicatorId : null;
    var devAppType = opts.hasOwnProperty('devAppType') ? opts.devAppType : null;
    var parentMenuId = opts.hasOwnProperty('parentMenuId') ? opts.parentMenuId : null;
    var isImportManageAI = opts.hasOwnProperty('isImportManageAI') ? opts.isImportManageAI : false;
    
    postData.isImportManage = isImportManage ? 1 : 0;
    
    $.ajax({
        type: 'post',
        url: 'mdform/createMvStructureFromFileForm',
        data: postData, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (data.status == 'success') {

                var dialogName = '#dialog-mvrows-createstructure';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName);
                
                if (data.hasOwnProperty('indicatorId')) {
                    indicatorId = data.indicatorId;
                }

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('Файлаас бүтэц үүсгэх'), 
                    width: 1200,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        delete mvFileRowsData;
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('import_btn'), class: 'btn green-meadow btn-sm', click: function() {
                            PNotify.removeAll();

                            var $form = $dialog.find('form');
                            $form.validate({ errorPlacement: function() {} });

                            if ($form.valid()) {

                                Core.blockUI({message: 'Importing... (<span id="mv-file-import-rows-count">0</span> / <span id="mv-file-import-total-count">0</span>)', boxed: true});
                                
                                setTimeout(function() {

                                    var delimiter = $form.find('select[name="delimiter"]').val(), 
                                        isHeader = $form.find('#headerCheckBox').is(':checked'), 
                                        skipRows = Number($form.find('input[name="skipRows"]').val()), 
                                        skipColumns = Number($form.find('input[name="skipColumns"]').val());

                                    var rowsLength = mvFileRowsData.length;

                                    if (rowsLength > 0) {

                                        if (delimiter == 'tab') {
                                            delimiter = "　";
                                        }  

                                        if (skipRows > 0) {
                                            mvFileRowsData = mvFileRowsData.slice(skipRows);
                                            rowsLength = mvFileRowsData.length;
                                        }

                                        var pageSize = 500;
                                        var total = Number(rowsLength);
                                        var headerData = [];

                                        if (isHeader) {
                                            var pages = Math.ceil(total / pageSize) || 1;

                                            if (mvFileReaderExtention == 'txt') {
                                                var secondRow = (mvFileRowsData[0]).split(delimiter);
                                            } else {
                                                var secondRow = mvFileRowsData[0];
                                                var getTypesRow = mvFileFirstData;
                                            }

                                            for (var f in mvFileFirstRow) {
                                                var cellValue = (secondRow.hasOwnProperty(f)) ? (secondRow[f]).trim() : '';
                                                var showType = 'text';
                                                
                                                if (!isImportManage && mvFileReaderExtention == 'txt' && cellValue != '') {
                                                    if (moment(cellValue, 'YYYY-MM-DD', true).isValid()) {
                                                        showType = 'date';
                                                    } else if (Number(cellValue) >= 0 || Number(cellValue) < 0) {
                                                        showType = 'bigdecimal';
                                                    }
                                                } else if (isContextMenu && (mvFileReaderExtention == 'xls' || mvFileReaderExtention == 'xlsx')) {
                                                    
                                                    if (skipColumns > 0) {
                                                        var getCellType = getTypesRow[Number(f) + skipColumns];
                                                    } else {
                                                        var getCellType = getTypesRow[f];
                                                    }
                                                    
                                                    var t = getCellType.t, w = getCellType.w;
                                                    
                                                    if (t == 'd') {
                                                        showType = 'date';
                                                    } else if (t == 's') {
                                                        showType = 'text';
                                                    } else if (t == 'n') {
                                                        if (w.indexOf('.') !== -1) {
                                                            showType = 'bigdecimal';
                                                        } else {
                                                            showType = 'number';
                                                        }
                                                    }
                                                }

                                                headerData.push({'labelName': (mvFileFirstRow[f]).trim(), 'showType': showType});
                                            }

                                        } else {
                                            var pages = Math.ceil(total / pageSize) || 1;

                                            for (var f in mvFileFirstRow) {

                                                var cellValue = (mvFileFirstRow[f]).trim();
                                                var showType = 'text';

                                                if (!isImportManage && cellValue != '') {
                                                    if (moment(cellValue, 'YYYY-MM-DD', true).isValid()) {
                                                        showType = 'date';
                                                    } else if (Number(cellValue) >= 0 || Number(cellValue) < 0) {
                                                        showType = 'bigdecimal';
                                                    }
                                                }

                                                headerData.push({'labelName': 'Column'+(Number(f) + 1), 'showType': showType});
                                            }
                                        }
                                        
                                        $('#mv-file-import-total-count').text(pureNumberFormat(total));
                                        
                                        var createPostData = {indicatorId: indicatorId, headerData: headerData, isOnlyTableCreate: 1};
                                        
                                        if (isContextMenu) {
                                            createPostData.name = $form.find('input[name="name"]').val();
                                            createPostData.parentId = bpGetVisiblePanelSelectedRowVal('secondList', 'id');
                                            createPostData.categoryId = bpGetVisiblePanelSelectedRowVal('firstList', 'id');
                                        }
                                        
                                        if (isImportManage) {
                                            createPostData.name = $form.find('input[name="name"]').val();
                                            createPostData.isImportManage = 1;
                                            createPostData.mainIndicatorId = opts.mainIndicatorId;
                                        }
                                        
                                        if (devIndicatorId) {
                                            createPostData.name = $form.find('input[name="name"]').val();
                                            createPostData.devIndicatorId = devIndicatorId; 
                                            createPostData.devAppType = devAppType;
                                            createPostData.parentMenuId = parentMenuId;
                                        }
                                        
                                        if (isImportManageAI) {
                                            createPostData.isImportManageAI = 1;
                                        }

                                        $.ajax({
                                            type: 'post',
                                            url: 'mdform/createMvStructureFromFile',
                                            data: createPostData,
                                            dataType: 'json', 
                                            success: function(data) {

                                                if (data.status == 'success') {
                                                    
                                                    var nowTimestamp = Date.now();
                                                    
                                                    function mvInsertDataFromFileLoop(p, nowTimestamp) {
                                                        
                                                        var recordCount = p * pageSize;
                                                        
                                                        $.ajax({
                                                            type: 'post',
                                                            url: 'mdform/createMvStructureFromFile',
                                                            data: {
                                                                indicatorId: indicatorId,
                                                                fileExtention: mvFileReaderExtention, 
                                                                delimiter: delimiter, 
                                                                recordCount: recordCount, 
                                                                nowTimestamp: nowTimestamp, 
                                                                headerData: headerData, 
                                                                rowsData: mvFileRowsData.slice((p - 1) * pageSize, recordCount)
                                                            },
                                                            dataType: 'json',
                                                            success: function(loopResponse) {
                                                                
                                                                if (loopResponse.status == 'success') {
                                                                    
                                                                    if (p == pages) {
                                                                        
                                                                        PNotify.removeAll();
                                                                        new PNotify({
                                                                            title: loopResponse.status,
                                                                            text: plang.get('msg_save_success'),
                                                                            type: loopResponse.status,
                                                                            sticker: false, 
                                                                            delay: 1000000000, 
                                                                            addclass: 'pnotify-center'
                                                                        }); 
                                                                        
                                                                        if (isImportManage) {
                                                                            mvRenderChildDataSets(opts.mainIndicatorId);
                                                                            $dialog.dialog('close');
                                                                        } else if (isContextMenu) {
                                                                            $dialog.dialog('close');
                                                                            bpVisiblePanelDataViewReload('secondList');
                                                                        } else if (callbackFunction != '') {
                                                                            $dialog.dialog('close');
                                                                            if (typeof(window[callbackFunction]) === 'function') {
                                                                                window[callbackFunction]();
                                                                            }
                                                                        } else {
                                                                            $dialog.dialog('close');
                                                                            dataViewReload(dataViewId);
                                                                        }
                                                                        
                                                                        Core.unblockUI();
                                                        
                                                                    } else {
                                                                        $('#mv-file-import-rows-count').text(pureNumberFormat(recordCount));
                                                                        mvInsertDataFromFileLoop(p + 1, nowTimestamp);
                                                                    }
                                                                    
                                                                } else {
                                                                    PNotify.removeAll();
                                                                    new PNotify({
                                                                        title: loopResponse.status,
                                                                        text: loopResponse.message,
                                                                        type: loopResponse.status,
                                                                        sticker: false, 
                                                                        delay: 1000000000, 
                                                                        addclass: 'pnotify-center'
                                                                    }); 
                                                                    Core.unblockUI();
                                                                    
                                                                    if (isContextMenu || isImportManage) {
                                                                        removeTempIndicatorById(indicatorId);
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    }
                                                    
                                                    mvInsertDataFromFileLoop(1, nowTimestamp);

                                                } else {
                                                    PNotify.removeAll();
                                                    new PNotify({
                                                        title: data.status,
                                                        text: data.message,
                                                        type: data.status,
                                                        sticker: false, 
                                                        addclass: 'pnotify-center'
                                                    }); 
                                                    Core.unblockUI();
                                                }
                                            }
                                        });
                                        
                                    } else {
                                        Core.unblockUI();
                                    }

                                }, 100);
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                Core.initUniform($dialog);
                Core.initLongInput($dialog);
                $dialog.dialog('open');

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: 'pnotify-center'
                }); 
            }
            Core.unblockUI();
        }
    });
}
function removeTempIndicatorById(id) {
    $.ajax({
        type: 'post',
        url: 'mdform/removeTempIndicator',
        data: {id: id},
        dataType: 'json',
        success: function(data) {}
    });
}

function createMvStructureFromFileInit(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    createMvStructureFromFile(elem, dataViewId, {isContextMenu: true});
}
function reportTemplateKpiIndicatorValue(elem, indicatorId) {
    var rows = getDataViewSelectedRows(indicatorId);
    if (rows.length) {
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/checkCriteria',
            data: {indicatorId: indicatorId, dataRow: rows},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(response) {
                PNotify.removeAll();
                    
                if (response.hasOwnProperty('status') && response.status != 'success') {
                    Core.unblockUI();
                    new PNotify({
                        title: response.status,
                        text: response.message,
                        type: response.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    return;
                }
                
                var $dialogName = 'dialog-printSettings';
                if (!$($dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(response.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('MET_99990001'),
                    width: 500, 
                    minWidth: 400,
                    height: 'auto',
                    maxHeight: $(window).height() - 25, 
                    modal: true,
                    open: function(){
                        Core.initDVAjax($dialog);
                    },
                    close: function(){
                        PNotify.removeAll();
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('preview_btn'), class: 'btn btn-sm green-meadow bp-btn-preview', click: function() {

                            PNotify.removeAll();
                            
                            var numberOfCopies = $("#numberOfCopies").val(),
                                isPrintNewPage = $("#isPrintNewPage").is(':checked') ? '1' : '0',
                                isSettingsDialog = $("#isSettingsDialog").is(':checked') ? '1' : '0',
                                isShowPreview = $("#isShowPreview").is(':checked') ? '1' : '0',
                                isPrintPageBottom = $("#isPrintPageBottom").is(':checked') ? '1' : '0',
                                isPrintPageRight = $("#isPrintPageRight").is(':checked') ? '1' : '0',
                                isPrintSaveTemplate = $("#isPrintSaveTemplate").is(':checked') ? '1' : '0',
                                pageOrientation = $("#pageOrientation").val(),
                                paperInput = $("#paperInput").val(),
                                pageSize = $("#pageSize").val(),
                                templates = $("#printTemplate").val(),
                                templateIds = $("#rtTemplateIds").val(), 
                                templateMetaIds = $("#templateMetaIds").val(),
                                printType = $("#printType").val();
                        
                            var print_options = {
                                numberOfCopies: numberOfCopies,
                                isPrintNewPage: isPrintNewPage,
                                isSettingsDialog: isSettingsDialog,
                                isShowPreview: isShowPreview,
                                isPrintPageBottom: isPrintPageBottom,
                                isPrintPageRight: isPrintPageRight,
                                pageOrientation: pageOrientation,
                                isPrintSaveTemplate: isPrintSaveTemplate,
                                paperInput: paperInput,
                                pageSize: pageSize,
                                printType: printType,
                                templates: templates, 
                                templateIds: templateIds, 
                                templateMetaIds: templateMetaIds, 
                                isKpiIndicator: 1
                            }; 
                            
                            if (numberOfCopies != '' && numberOfCopies != '0' && templateIds) {
                                
                                if (print_options.templates == '') {
                                    new PNotify({
                                        title: 'Warning',
                                        text: 'Загвараа сонгоно уу!',
                                        type: 'warning',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });  
                                    return;              
                                }
                                $dialog.dialog('close');
                                callTemplate(rows, indicatorId, print_options);
                                
                            } else {
                                new PNotify({
                                    title: 'Warning',
                                    text: plang.getDefault('PRINT_0019', 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү'),
                                    type: 'warning',
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.on('change', '#printTemplate', function(){
                    if ($dialog.find("#printTemplate").val().length === 0) {
                        $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:not(.bp-btn-close)').prop('disabled', true);
                    } else {
                        $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:not(.bp-btn-close)').prop('disabled', false);
                    }
                });
                $dialog.dialog('open');
                
                Core.unblockUI();
            }
        });
            
    } else {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
}
function mvDetailAcLookupToggle(elem) {
    var $this = $(elem), $parent = $this.closest('.bp-add-ac-row'), 
        $parentGroup = $this.closest('.input-group-btn'),     
        $button = $parentGroup.find('button.dropdown-toggle'), 
        $ul = $parentGroup.find('ul.dropdown-menu'), 
        $input = $parent.find('input.lookup-code-hard-autocomplete'), 
        lookupType = $this.attr('data-lookup-type'), 
        lookupId = $this.attr('data-lookup-id');
        
    $ul.find('a[onclick*="mvDetailAcLookupToggle"]').closest('li').show();
    $this.closest('li').hide();

    $button.html($this.text());
    $input.attr({'data-lookupid': lookupId, 'data-lookuptype': lookupType}).focus().select();
    
    return;
}
function mvDetailAcLookupAddRows(elem) {
    var $this = $(elem), $parent = $this.closest('.bp-add-ac-row'), 
        $input = $parent.find('input[data-lookuptype]'), 
        lookupType = $input.attr('data-lookuptype'), 
        lookupId = $input.attr('data-lookupid');
    
    if (lookupType == 'indicator') {
        chooseKpiIndicatorRowsFromBasket(elem, lookupId, 'multi', 'mvRowsDetailFillByIndicator');
    } else {
        dataViewSelectableGrid('nullmeta', '0', lookupId, 'multi', 'nullmeta', elem, 'mvRowsDetailFillByMeta');
    }
}
function mvRowsDetailFillByIndicator(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
    var $this = $(elem), $parent = $this.closest('.input-group'), $input = $parent.find('input'), 
    rowId = $input.attr('data-rowid'), groupPath = $input.attr('data-path'), processId = $input.attr('data-processid');
    
    mvRowsDetailFillRender(elem, rowId, groupPath, processId, indicatorId, rows, true, 'autocomplete');
}
function mvRowsDetailFillByMeta(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    var $this = $(elem), $parent = $this.closest('.input-group'), $input = $parent.find('input'), 
    rowId = $input.attr('data-rowid'), groupPath = $input.attr('data-path'), processId = $input.attr('data-processid');
    
    mvRowsDetailFillRender(elem, rowId, groupPath, processId, lookupMetaDataId, rows, false, 'autocomplete');
}
function mvRowsDetailFillCall(elem, _processId, _paramRealPath, _lookupId, rows, addMode) {
    var $this = $(elem), $parent = $this.closest('.input-group'), $input = $parent.find('input'), 
    rowId = $input.attr('data-rowid'), groupPath = $input.attr('data-path'), processId = $input.attr('data-processid'), 
    lookupType = $input.attr('data-lookuptype');
    
    mvRowsDetailFillRender(elem, rowId, groupPath, processId, _lookupId, rows, (lookupType == 'indicator' ? true : false), addMode);
}
function mvRowsDetailFillRender(elem, rowId, groupPath, processId, lookupId, rows, isIndicator, addMode) {
    $.ajax({
        type: 'post',
        url: 'mdform/mvRowsDetailFillRender',
        data: {rowId: rowId, groupPath: groupPath, processId: processId, lookupId: lookupId, rows: rows, isIndicator: (isIndicator ? 1 : 0)}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            if (data.status == 'success') {
                
                var $this = $(elem);
                var $form = $this.closest('[data-addonform-uniqid]'), 
                    $parent = $this.closest('div[data-section-path]'),    
                    $table = $parent.find('table.table[data-table-path]'), 
                    $tbody = $table.find('> tbody'),
                    uniqId = '';

                if ($form.length) {
                    uniqId = $form.attr('data-addonform-uniqid');
                } else {
                    uniqId = $this.closest('.kpi-ind-tmplt-section[data-bp-uniq-id]').attr('data-bp-uniq-id');
                }

                var $html = $('<div />', {html: data.rows});
                $html.children('.bp-detail-row')
                        .removeClass('saved-bp-row')
                        .removeAttr('data-savedrow')
                        .addClass('added-bp-row display-none multi-added-row');

                $tbody.append($html.html());

                var $rowEl = $tbody.find('> .bp-detail-row.multi-added-row');
                var rowLen = $rowEl.length, rowi = 0;

                mvInitControls($rowEl);

                if (rowLen === 1) {

                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true, true, addMode);

                } else if (rowLen > 1) {

                    var rowLen = rowLen - 1;

                    for (rowi; rowi < rowLen; rowi++) { 
                        window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowi]), groupPath, true, false, addMode);
                    }

                    window['bpFullScriptsWithoutEvent_'+uniqId]($($rowEl[rowLen]), groupPath, true, true, addMode);
                }

                $rowEl.removeClass('multi-added-row display-none');

                setRowNumKpiIndicatorTemplate($tbody);
                kpiSetRowIndex($tbody);
                bpDetailFreeze($table);
                
                window['rowsDtlPathReplacer_'+uniqId](groupPath);
                window['dtlAggregateFunction_'+uniqId]();

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }

            Core.unblockUI();
        }, 
        error: function(data) {
            new PNotify({
                title: data.responseJSON.status,
                text: data.responseJSON.message,
                type: data.responseJSON.status,
                sticker: false
            });
            Core.unblockUI();
        }
    });
}
function mvFlowChartExecute(elem, url, indicatorId) {
    $.ajax({
        type: 'post',
        url: 'mdform/mvFlowChartExecute',
        data: {indicatorId: indicatorId}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.status == 'success') {
                var resultData = data.data;
                
                if (resultData.hasOwnProperty('result') && resultData.hasOwnProperty('type')) {
                    var messageList = resultData.result;
                    
                    if (messageList.length) {
                        
                        var dialogName = '#dialog-mvfnc-execute';
                        if (!$(dialogName).length) {
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }
                        var $dialog = $(dialogName), form = [];
                        
                        //messageList.pop();
                        //messageList.shift();
                        
                        var messageListlength = messageList.length;

                        form.push('<div style="background-color: #eee;margin: -10px -15px -5px -15px;padding: 15px 24px 1px 24px;">');
                            
                            for (var m in messageList) {
                                
                                var messateType = 'Info';
                                var iconName = 'fa-info-circle text-warning-400';
                                var actionIndicatorId = messageList[m]['action'];
                                
                                if (messageList[m]['message'] == 'success') {
                                    messateType = 'Success';
                                    iconName = 'fa-check-circle text-success-400';
                                }
                                
                                form.push('<div class="card card-body p-2">');
                                    form.push('<div class="media">');
                                        form.push('<div class="mt2 ml-1 mr-2">');
                                            form.push('<i class="fad '+iconName+' font-size-40"></i>');
                                        form.push('</div>');
                                        form.push('<div class="media-body">');
                                            form.push('<h6 class="media-title font-weight-bold">'+messateType+'</h6>');
                                            form.push('<span class="">' + messageList[m]['message'] + '</span>');
                                        form.push('</div>');
                                        
                                        if (messageListlength > 1) {
                                            form.push('<div class="ml-3 mr-1 align-self-center">');
                                                form.push('<button type="button" onclick="mvFlowChartExecuteRedirect(this, '+actionIndicatorId+');" class="btn btn-outline-primary btn-icon rounded-round">');
                                                    form.push('<i class="fad fa-play-circle font-size-24 mt2"></i>');
                                                form.push('</button>');
                                            form.push('</div>');
                                        }
                                        
                                    form.push('</div>');
                                form.push('</div>');
                            }

                        form.push('</div>');
                        
                        if (messageListlength > 1) {
                            var buttons = [
                                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
                                }}
                            ];
                        } else {
                            var buttons = [
                                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                    mvFlowChartExecuteRedirect(this, messageList[0]['action']);
                                    $dialog.dialog('close');
                                }}, 
                                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
                                }}
                            ];
                        }

                        $dialog.empty().append(form.join(''));
                        $dialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Сануулах', 
                            width: 600,
                            height: 'auto',
                            modal: true,
                            buttons: buttons
                        });
                        $dialog.dialog('open');
    
                    } else {
                        window.location = url;
                    }
                }
                
            } else {
                window.location = url;
            }
            
            Core.unblockUI();
        }
    });
}
function mvFlowChartExecuteRedirect(elem, indicatorId) {
    if (indicatorId == '1525387854737') {
        window.location = 'appmenu/module/166848750564710?kmid=166848750564710&mmid=1671709456096413';
    } else if (indicatorId == '1525387854737') {
        window.location = 'appmenu/module/166848750564710?kmid=166848750564710&mmid=1671709456096413';
    } else if (indicatorId == '16912758355339') {
        window.location = 'mdlayout/v2/164878015785810&mmid=1632910578101658&mid=1632910578101658';
    } else if (indicatorId == '184184421') {
        window.location = 'appmenu/module/1505271180731694?mmid=1505271180731694';
    } else {
        window.location = 'appmenu/module/'+indicatorId+'?kmid='+indicatorId+'&mmid=1671709456096413';
    }
}
function mvProductRender(elem, url, indicatorId) {
    $.ajax({
        type: 'post',
        url: 'mdform/mvProductRender',
        data: {indicatorId: indicatorId}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.status == 'success') {
                
                if (data.renderType == 'paper_main_window') {
                    window.location.href = URL_APP + 'appmenu/mvmodule/' + indicatorId;
                } else {
                    var $dialogName = 'dialog-valuemap-'+indicatorId;
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName);

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: '',
                        width: 1000,
                        height: 'auto',
                        modal: true,
                        closeOnEscape: false,
                        open: function() {
                            $dialog.append(data.html);
                            $dialog.parent().find(">.ui-dialog-buttonpane").remove();
                            $dialog.parent().find(">.ui-dialog-titlebar").remove();
                            var dh = $dialog.parent().find(">.ui-dialog-content").height() + 110;
                            $dialog.parent().find(">.ui-dialog-content").css("height", dh+"px");
                        },
                        beforeClose: function() {
                            
                            if ($dialog.data('can-close')) {
                                $dialog.removeData('can-close');
                                return true;
                            }
    
                            var dialogNameConfirm = '#dialog-mvproduct-confirm';
                            if (!$(dialogNameConfirm).length) {
                                $('<div id="' + dialogNameConfirm.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $dialogConfirm = $(dialogNameConfirm);

                            $dialogConfirm.html(plang.get('Та гарахдаа итгэлтэй байна уу?'));
                            $dialogConfirm.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('msg_title_confirm'), 
                                width: 300,
                                height: 'auto',
                                modal: true,
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                        $dialogConfirm.dialog('close');
                                        $dialog.data('can-close', true);
                                        $dialog.dialog('close');
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialogConfirm.dialog('close');
                                    }}
                                ]
                            });
                            $dialogConfirm.dialog('open');

                            return false;
                        },
                        close: function() {
                            removeHtmlEditorByElement($dialog);
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                                $dialog.dialog('close');
                            }}
                        ]
                    }).dialogExtend({
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

                    $dialog.dialogExtend('maximize');
                    $dialog.dialog('open');
                }                                   
                
            } else if (data.status == 'info') {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: 'pnotify-center'
                });
                
            } else if (url) {
                window.location = url;
            }
            
            Core.unblockUI();
        }
    });
}
function mvFilterRelationLoadData(elem, indicatorId, filterData) {
    var drillDownCriteria = window['drillDownCriteria_' + indicatorId],
        $elem = $(elem), 
        $parent = $elem.closest('.list-group');
    var postData = {
        indicatorId: indicatorId, 
        filterData: filterData, 
        drillDownCriteria: drillDownCriteria
    };
    
    if ($parent.find('.list-group-item.active').length) {
        if ($elem.hasClass('form-control')) {
            postData.ignoreColName = $elem.closest('[data-kpi-indicator-filter-between-input]').attr('data-kpi-indicator-filter-between-input');
        } else {
            postData.ignoreColName = $elem.attr('data-colname');
        }
    }
    
    if ($elem.hasAttr('onclick')) {
        postData.fncName = $elem.attr('onclick');
    } else if ($elem.hasAttr('data-load-fnc')) {
        postData.fncName = $elem.attr('data-load-fnc') + '(this);';
    }
    
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: postData,
        dataType: 'json',
        success: function(data) {
            
            if (data.status == 'success') {
                
                var $html = $('<div />', {html: data.html}), $filterCheckbox = $html.find('[data-filter-type="checkbox"]');
                
                if ($filterCheckbox.length) {
                    
                    var $openedFilter = $parent.find('.list-group-body:not(.d-none)').closest('[data-filter-type]'), openedFilterCol = {};

                    if ($openedFilter.length) {
                        $openedFilter.each(function() { openedFilterCol[$(this).attr('data-filter-column')] = 1; });
                    }
                    
                    $filterCheckbox.each(function() {
                        
                        var $this = $(this), colName = $this.attr('data-filter-column');
                        
                        if ($this.find('.list-group-item').length) {
                            
                            $parent.find('[data-filter-type="checkbox"][data-filter-column="'+colName+'"]').empty().append($this.html()).promise().done(function() {
                                
                                var $prevFilteredElem = $parent.find('[data-filter-type="checkbox"][data-filter-column="'+colName+'"]');
                                
                                if (openedFilterCol.hasOwnProperty(colName)) {
                                    $prevFilteredElem.find('.kpi-indicator-filter-collapse-btn').addClass('opened');
                                    $prevFilteredElem.find('.list-group-body').removeClass('d-none');
                                }
                                
                                if (filterData.hasOwnProperty(colName)) {
                                    
                                    var prevFilteredData = filterData[colName];
                                        
                                    for (var f in prevFilteredData) {
                                        
                                        var $detectText = $prevFilteredElem.find('span[data-value-mode]').filter(function(){ return ($(this).text() == prevFilteredData[f]); });
                                        
                                        if ($detectText.length) {
                                            var $activeItem = $detectText.closest('.list-group-item');
                                            
                                            $activeItem.addClass('active');
                                            $activeItem.find('i').removeClass('far fa-square').addClass('fas fa-check-square');
                                        }
                                    }
                                }
                            });
                            
                        } else {
                            $parent.find('[data-filter-type="checkbox"][data-filter-column="'+colName+'"]').empty();
                        }
                    });
                }
            }
        }
    });
}
function renderAddModeIndicatorTabInit(uniqId, refStructureId, tabType, elem, selectedDataRow, dmMetadataId) {
    var bpContainer = $('div[data-bp-uniq-id="' + uniqId + '"]');
    if (tabType === 'photo') {
        var indicator_photo_tab_length = $.trim(bpContainer.find("div#indicator_photo_tab_" + uniqId).html()).length;
        if (indicator_photo_tab_length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpPhotoTab',
                data: {
                    uniqId: uniqId,
                    refStructureId: refStructureId,
                    dmMetadataId: dmMetadataId,
                    selectedRow: selectedDataRow,
                },
                beforeSend: function() {
                    if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                        $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                    }
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    bpContainer.find("div#indicator_photo_tab_" + uniqId).empty().append(data);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    } else if (tabType === 'file') {
        var indicator_file_tab_length = $.trim(bpContainer.find("div#indicator_file_tab_" + uniqId).html()).length;
        if (indicator_file_tab_length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpFileTab',
                data: {uniqId: uniqId},
                beforeSend: function() {
                    if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                        $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                    }
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    bpContainer.find("div#indicator_file_tab_" + uniqId).empty().append(data);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    } else if (tabType === 'comment') {
        var indicator_comment_tab_length = $.trim(bpContainer.find("div#indicator_comment_tab_" + uniqId).html()).length;
        if (indicator_comment_tab_length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpCommentTab',
                data: { uniqId: uniqId },
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    bpContainer.find("div#indicator_comment_tab_" + uniqId).empty().append(data);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    } else if (tabType === 'commentbtm') {
        $.ajax({
            type: 'post',
            url: 'mdwebservice/renderAddModeBpCommentTab',
            data: { uniqId: uniqId },
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                $('.indicator_comment_tab_' + uniqId).empty().append(data);
                Core.unblockUI();
            },
            error: function() { alert('Error'); }
        });
    } else if (tabType === 'relation') {
        var indicator_relation_tab_length = $.trim(bpContainer.find("div#indicator_relation_tab_" + uniqId).html()).length;
        if (indicator_relation_tab_length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpRelationTab',
                data: {
                    uniqId: uniqId,
                    refStructureId: refStructureId,
                    processId: bpContainer.parent().data('process-id')
                },
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    bpContainer.find("div#indicator_relation_tab_" + uniqId).empty().append(data);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    } else if (tabType === 'mv_relation') {
        var indicator_relation_tab_length = $.trim(bpContainer.find("div#indicator_mv_relation_tab_" + uniqId).html()).length;
        if (indicator_relation_tab_length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdform/renderMetaProcessRelationTab',
                data: {
                    uniqId: uniqId,
                    refStructureId: refStructureId,
                    processId: bpContainer.parent().data('process-id')
                },
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    bpContainer.find("div#indicator_mv_relation_tab_" + uniqId).empty().append(data);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    } else if (tabType === 'wfmlog') {
        var indicator_wfmlog_tab_length = $.trim(bpContainer.find("div#indicator_wfmlog_tab_" + uniqId).html()).length;
        if (indicator_wfmlog_tab_length == 0) {
            var _metadataId = bpContainer.find("div#indicator_main_tab_" + uniqId).find('input[name="dmMetaDataId"]').val();
            var _selectedRowData = $(elem).attr('data-selectedrow');
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'mdobject/getRowWfmStatusForm',
                data: {
                    uniqId: uniqId,
                    metaDataId: _metadataId,
                    refStructureId: refStructureId,
                    processId: bpContainer.parent().data('process-id'),
                    selectedRowData: _selectedRowData,
                    isSee: true,
                    form: false
                },
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    bpContainer.find("div#indicator_wfmlog_tab_" + uniqId).empty().append(data.Html);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    } else if (tabType === 'wfmlogBtm') {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/getRowWfmStatusForm',
            data: {
                uniqId: uniqId,
                metaDataId: dmMetadataId,
                refStructureId: refStructureId,
                processId: bpContainer.parent().data('process-id'),
                selectedRowData: selectedDataRow,
                isSee: true,
                form: false
            },
            beforeSend: function() {
                Core.blockUI({animate: true});
            },
            success: function(data) {
                $('.indicator_wfmlogs_tab_' + uniqId).empty().append(data.Html);
                Core.unblockUI();
            },
            error: function() { alert('Error'); }
        });
    } else if (tabType === 'mv_addon_info') {
        var $tab = bpContainer.find("div#indicator_mv_addoninfo_tab_" + uniqId);
        if ($tab.children().length == 0) {
            $.ajax({
                type: 'post',
                url: 'mdform/renderProcessAddonInfo',
                data: {uniqId: uniqId, processId: bpContainer.parent().data('process-id')},
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    $tab.append(data);
                    Core.unblockUI();
                },
                error: function() { alert('Error'); }
            });
        }
    }
}

function renderDataViewIndicatorSubGrid(rowElement, srcDataViewId, childDataViewList, row, rowIndex, isExcelExport) {
    if (rowElement.children().length == 0 && typeof childDataViewList[0] !== 'undefined') {
        renderDataViewIndicatorSubGridDraw(rowElement, srcDataViewId, row, rowIndex, 0, childDataViewList, isExcelExport);
    }
}
function renderDataViewIndicatorSubGridDraw(rowElement, srcDataViewId, row, rowIndex, key, childDataViewList, isExcelExport) {
    if (typeof childDataViewList[key] === 'undefined') {
        return false;
    }

    var subGridParams = '';
    var params = childDataViewList[key].params.split('&');

    for (var i = 0; i < params.length; i++) {
        var paramRow = params[i].split('=');
        var trgParam = paramRow[0];
        var srcParam = paramRow[1];
        subGridParams += trgParam + '=' + row[srcParam] + '&';
    }
    
    subGridParams = rtrim(subGridParams, '&');

    $.ajax({
        type: 'post',
        url: 'mdform/indicatorList/'+childDataViewList[key].id,
        data: {
            isSubGrid: 1,
            drillDownCriteria: subGridParams
        },
        success: function(html) {

            rowElement.append(html);
            
            if ((childDataViewList.length - 1) !== key) {
                rowElement.append($("<br />"));
            }

            renderDataViewIndicatorSubGridDraw(rowElement, srcDataViewId, row, rowIndex, ++key, childDataViewList);
        }
    });
}
function mvMultiFileChoose(elem) {
    var $parent = $(elem).closest('.mv-multi-file');
    var $fileInput = $parent.find('input[type="file"]');
    if (!$fileInput.is('[readonly]')) {
        $fileInput.click();
    }
    return;
}
function mvMultiFileAddFileToNewInput(file, newInput) {
    var dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    newInput.files = dataTransfer.files;
}
function mvMultiFileBreakIntoSeparateFiles(input) {

    if (!input.files) { return; }
    
    var $input = $(input), $parent = $input.closest('.mv-multi-file'), $preview = $parent.find('.mv-multi-file-preview');
    var deleteBtn = plang.get('delete_btn');
    
    for (var file of input.files) {
        
        var templateHtml = '<div class="btn-group mt3 mb3">'+
            '<input type="file" multiple="multiple" class="d-none" name="'+$input.attr('name')+'">'+    
            '<button type="button" class="btn btn-outline-primary btn-sm mr0" data-filename="'+file.name+'" data-extension="fileextension" title="'+file.name+'" style="padding: 1px 5px;line-height: normal;">'+file.name+'</button>'+
            '<button type="button" class="btn btn-outline-primary btn-icon btn-sm" title="'+deleteBtn+'" onclick="mvMultiFileRemove(this);" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;" data-actionname="remove"><i class="icon-cross"></i></button>'+
        '</div>';
        var $newFile = $(templateHtml).appendTo($preview);
        
        mvMultiFileAddFileToNewInput(file, $newFile.find('input[type="file"]')[0]);
    }

    $input.val([]);
}
function mvMultiFileRemove(elem) {
    var $this = $(elem), $row = $this.closest('.btn-group');
    $row.remove();
    return;
}
function mvRowsPopupRender(elem) {
    var $this = $(elem), 
        title = $this.attr('title'), 
        $parentCell = $this.parent(), 
        $dialog = $this.next('.param-tree-container');
    
    if ($dialog.length == 0) {
        $dialog = $this.next('.ui-dialog').find('> .param-tree-container:eq(0)');
        $dialog.dialog('open');
        return;
    }
    
    $dialog.dialog({
        appendTo: $parentCell,
        cache: false,
        resizable: true,
        draggable: true,
        bgiframe: true,
        autoOpen: false,
        title: title, 
        width: 1000,
        minWidth: 1000,
        height: 'auto',
        maxHeight: $(window).height() - 200,
        modal: true,
        create: function (event, ui) {
            $(event.target).parent().css('position', 'fixed');
        }, 
        resizeStart: function (event) {
            $(event.target).parent().css('position', 'fixed'); 
        },
        resizeStop: function (event) {
            $(event.target).parent().css('position', 'fixed'); 
        }, 
        buttons: [
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": false,
        "collapsable": false,
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
    mvInitControls($dialog);
    $dialog.dialog('open');
}
function mvRowPopupRender(elem) {
    var $this = $(elem), 
        title = $this.attr('title'), 
        $parentCell = $this.parent(), 
        $dialog = $this.next('.param-tree-container');
    
    if ($dialog.length == 0) {
        $dialog = $this.next('.ui-dialog').find('> .param-tree-container:eq(0)');
        $dialog.dialog('open');
        return;
    }
    
    $dialog.dialog({
        appendTo: $parentCell,
        cache: false,
        resizable: true,
        draggable: true,
        bgiframe: true,
        autoOpen: false,
        title: title,
        width: 650,
        minWidth: 650,
        height: 'auto',
        maxHeight: $(window).height() - 200,
        modal: true,
        create: function (event, ui) {
            $(event.target).parent().css('position', 'fixed');
        }, 
        resizeStart: function (event) {
            $(event.target).parent().css('position', 'fixed'); 
        },
        resizeStop: function (event) {
            $(event.target).parent().css('position', 'fixed'); 
        }, 
        buttons: [
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": false,
        "collapsable": false,
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
    mvInitControls($dialog);
    $dialog.dialog('open');
}

function drawTreeIndicator(elem, colName) {

    var indicatorId = elem.find('.tree-demo').data('indicatorid');
    window['indicatorStructureTreeView_'+indicatorId] = elem.find('.tree-demo');

    window['indicatorStructureTreeView_'+indicatorId].jstree({
        "core": {
            "themes": {
                "responsive": true,
                "icons": false
            },
            "check_callback": true,
            "data": {
                "url": function (node) {
                    return 'mdform/getAjaxTree';
                },
                "data": function (node) {
                    return {'parent': node.id, 'indicatorId' : indicatorId, 'colName' : colName};
                }
            }
        },       
        "types": {
            "default": {
                "icon": "icon-folder2 text-orange-300"
            }
        },
        'search': {
            'case_insensitive': true,
            'show_only_matches' : true
        },        
        "plugins": ["types", "cookies", "search"]
    }).bind("select_node.jstree", function (e, data) {
        var nid = data.node.id === 'null' || data.node.id === 'all' ? '' : data.node.id;
        var $treeFilterLi = elem.find('.tree-demo').find('li#'+nid);
        if ($treeFilterLi.hasClass('active')) {
            $treeFilterLi.removeClass('active');
            $treeFilterLi.find('.mv-tree-filter-icon').removeClass('fas fa-check-square').addClass('far fa-square');
        } else {
            $treeFilterLi.addClass('active');
            $treeFilterLi.find('.mv-tree-filter-icon').removeClass('far fa-square').addClass('fas fa-check-square');
        }        
        filterKpiIndicatorValueGrid($treeFilterLi);
        //elem.find('.tree-demo').closest('.list-group-item-action').find('span[data-value-mode]').parent().trigger('click');
    }).bind('loaded.jstree', function (e, data) {
        setTimeout(function(){
            var $jstreeOpen = window['indicatorStructureTreeView_'+indicatorId].find('.jstree-open');
            var $jstreeClicked = window['indicatorStructureTreeView_'+indicatorId].find('.jstree-clicked');

//            if ($jstreeClicked.length) {
//                $jstreeClicked.focus();
//                $jstreeClicked.trigger('click');
//            }
        }, 1);
    });
}

function mvWidgetRelationRender(elem, kpiTypeId, mainIndicatorId, opt, callback, successCallback, srcIndicatorId) {
    var $this = $(elem);
    var postData = {
        mainIndicatorId: mainIndicatorId, 
        methodIndicatorId: opt.methodIndicatorId, 
        structureIndicatorId: opt.structureIndicatorId
    };
    var mode = '';
    
    if (opt.hasOwnProperty('mode')) {
        mode = opt.mode;
        if (mode == 'update' || mode == 'view') {
            if ($this.closest('.objectdatacustomgrid').length && $this.closest('.objectdatacustomgrid').find('.no-dataview').length) {
                var selectedRows = $this.closest('.objectdatacustomgrid').find('.no-dataview.active').length ? [JSON.parse($this.closest('.objectdatacustomgrid').find('.no-dataview.active').attr('data-rowdata'))] : [];      
            } else {                        
                if ($this.hasClass('no-dataview') && $this.attr('data-rowdata')) {
                    isNoDataview = true;
                    fcSelectedRow = [JSON.parse($this.attr('data-rowdata'))];
                    var selectedRows = fcSelectedRow;
                } else {
                    var selectedRows = getDataViewSelectedRows(mainIndicatorId);
                }
            }

            if (selectedRows.length) {

                var selectedRow = selectedRows[0];
                postData.dynamicRecordId = selectedRow[window['idField_'+mainIndicatorId]];
                postData.idField = window['idField_'+mainIndicatorId];
                postData.selectedRow = selectedRow;
                postData.mode = mode;
                postData.widgetCode = opt.widgetCode;

            } else {
                alert(plang.get('msg_pls_list_select'));
                return;
            }
        }
    }

    $.ajax({
        type: 'post',
        url: 'mdwidget/mvWidgetRelationRender',
        data: postData, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            if (data.status == 'success') {
                
                if (typeof callback !== 'undefined') {
                    Core.unblockUI();
                    window[callback](data);
                    return false;
                }

                var $dialogName = 'dialog-widgetrender-'+mainIndicatorId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: data.Width,
                    height: 'auto',
                    closeOnEscape: false,
                    modal: true,
                    open: function() {
                        if (mode == 'view') {
                            $dialog.find('.bp-add-one-row').parent().remove();
                            $dialog.find('.bp-remove-row, button.red, button.bp-btn-save, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                            $dialog.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                            $dialog.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                            $dialog.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                            
                            var $radioElements = $dialog.find("input[type='radio']");
                            if ($radioElements.length) {
                                $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                $radioElements.closest('.radio').addClass('disabled');
                            }
                            
                            var $checkElements = $dialog.find("input[type='checkbox']");
                            $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                            $checkElements.closest('.checker').addClass('disabled');
                        }
                    },
                    close: function() {
                        if (typeof window['videoTimeToSaveInterval' + data.uniqId] !=='undefined') {
                            clearInterval(window['videoTimeToSaveInterval' + data.uniqId]);
                        }

                        if (typeof window['videoToImageCheckInterval' + data.uniqId] !=='undefined') {
                            clearInterval(window['videoToImageCheckInterval' + data.uniqId]);
                        }

                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm blue-hoki bp-btn-save d-none bp-btn-save' + data.uniqId, click: function () {
                            var $form = $dialog.find('.saveForm');
                            if (window['kpiIndicatorBeforeSave_' + data.uniqId]($this) && bpFormValidate($form)) {
                                $form.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdform/saveKpiDynamicDataByList',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status,
                                            sticker: false, 
                                            addclass: pnotifyPosition
                                        });

                                        if (responseData.status == 'success') {
                                            window['kpiIndicatorAfterSave_' + data.uniqId]($this, responseData.status, responseData);
                                            $dialog.dialog('close');
                                            dataViewReload(mainIndicatorId);
                                        } 

                                        Core.unblockUI();
                                    }
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close bp-btn-close' + data.uniqId, click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": (typeof data.Maximize !== 'undefined') ? data.Maximize : true,
                    "minimizable": (typeof data.Maximize !== 'undefined') ? data.Maximize : true,
                    "collapsable": (typeof data.Maximize !== 'undefined') ? data.Maximize : true,
                    "dblclick": (typeof data.Maximize !== 'undefined') ? "" : "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": (typeof data.Maximize !== 'undefined') ? "" : "ui-icon-extlink",
                        "minimize": (typeof data.Maximize !== 'undefined') ? "" : "ui-icon-minus",
                        "collapse": (typeof data.Maximize !== 'undefined') ? "" : "ui-icon-triangle-1-s",
                        "restore": (typeof data.Maximize !== 'undefined') ? "" :  "ui-icon-newwin"
                    }
                });
                if (typeof data.Maximize !== 'undefined' && data.Maximize) {
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}

function mvDataViewSendMailBySelectionRowsInit(elem, processMetaDataId, dataViewId, postParams, getParams) {
    if (typeof isMailSelectionRowsAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/dataview/mail/selectionRows.js').done(function() {
            mvDataViewSendMailBySelectionRows(elem, processMetaDataId, dataViewId, postParams, getParams);
        });
    } else {
        mvDataViewSendMailBySelectionRows(elem, processMetaDataId, dataViewId, postParams, getParams);
    }
}

function onChangeAttachFIleIndicatorMode(input) {
  if($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx",
    "zip", "rar", "mp3", "mp4", "msg"])){
    var ext=input.value.match(/\.([^\.]+)$/)[1],
        i = 0;
    if(typeof ext !== "undefined"){

//      for(i; i < input.files.length; i++) {
        ext=input.files[0].name.match(/\.([^\.]+)$/)[1];

        var li='',
            fileImgUniqId=Core.getUniqueID('file_img'),
            fileAUniqId=Core.getUniqueID('file_a'),
            extension=ext.toLowerCase();

        if(extension == 'png' ||
                extension == 'gif' ||
                extension == 'jpeg' ||
                extension == 'pjpeg' ||
                extension == 'jpg' ||
                extension == 'x-png' ||
                extension == 'bmp') {
          li='<a href="javascript:;" id="' + fileAUniqId + '" class="" data-rel="" style="height: 112px;width: 112px;">';
          li+='<img style="height: 112px;width: 112px;object-fit: cover;" src="" id="' + fileImgUniqId + '"/>';
          li+='</a>';
        } else {
          li='<a href="javascript:;" title="" style="height: 112px;width: 112px;">';
          li+='<img style="height: 112px;width: 112px;object-fit: cover;" src="assets/core/global/img/filetype/64/' + (extension == 'msg' ? 'zip' : extension) + '.png"/>';
          li+='</a>';
        }
        
        var $listViewFile=$(input).closest('.mv-hdr-label-control-input').find('.mv-file-choose-btn');
        setTimeout(function () {
            $listViewFile.empty().append(li);        
            //Core.initFancybox($listViewFile);

            previewPhotoAddMVMode(input.files[i], $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));
            initFileContentMenuMVAddMode();
        }, 1);
//      }

    }
  } else {
    var $listViewFile=$(input).closest('.mv-hdr-label-control-input').find('.mv-file-choose-btn');
    $listViewFile.empty().append('<i class="icon-plus3 big"></i>');    
    $(input).val('');
  }
}

function previewPhotoAddMVMode(input, $targetImg, $targetAnchor){
  if(input){
    var reader=new FileReader();
    reader.onload=function(e){
      $targetImg.attr('src', e.target.result);
      $targetAnchor.attr('href', e.target.result);
    };
    reader.readAsDataURL(input);
  }
}

function initFileContentMenuMVAddMode(){
  $.contextMenu({
    selector: '.mv-file-choose-btn a',
    callback: function(key, opt){
      if(key === 'delete'){
          mvFileChoosedRemove(opt.$trigger);
      }
    },
    items: {
      "delete": {name: "Устгах", icon: "trash"}
    }
  });
}

function mvFileChoosedRemove(elem) {
    var $parent = $(elem).closest('.uniform-uploader');
    var $fileInput = $parent.find('input[type="file"]');
    if (!$fileInput.is('[readonly]')) {
        var $listViewFile=$(elem).closest('.mv-hdr-label-control-input').find('.mv-file-choose-btn');
        $listViewFile.empty().append('<i class="icon-plus3 big"></i>');        
        
        var $fileName = $parent.find('.filename');
        $fileInput.val('');
        $parent.find('input[type="hidden"]').val('');
        $fileName.text($fileName.attr('data-text')).attr('title', $fileName.attr('data-text'));
        $parent.find('a').remove();
    }
    return;
}
function callMetaVerseIndicator(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    if (selectedRow) {
       delete selectedRow.children; 
    }
    var paramObj = paramDataToObject(paramData);
    paramObj.processMetaDataId = processMetaDataId;
    paramObj.selectedRow = selectedRow;
    
    $.ajax({
        type: 'post',
        url: 'mdform/callMetaVerseIndicator',
        data: paramObj,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            
            if (data.status == 'success') {
                var kpiTypeId = data.kpiTypeId;
                
                if (kpiTypeId == '2008') {
                    var typeCode = data.typeCode;
                    
                    if (typeCode == 'import') {
                        var dialogName = '#dialog-kpiindicator-dataimport';
                        if (!$(dialogName).length) {
                            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                        }
                        var $dialog = $(dialogName);

                        $dialog.empty().append(data.html);
                        $dialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: plang.get('Импорт'), 
                            width: $(window).width(),
                            height: $(window).height(),
                            modal: true,
                            buttons: [
                                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
                                }}
                            ]
                        });
                        $dialog.dialog('open');
                    }
                }
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            
            Core.unblockUI();
        }
    });
    
    /*$.ajax({
        type: 'post',
        url: 'mdform/importManagePopup',
        data: {mainIndicatorId: indicatorId},
        dataType: 'html',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var dialogName = '#dialog-kpiindicator-dataimport';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            var $dialog = $(dialogName);

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('Импорт'), 
                width: $(window).width(),
                height: $(window).height(),
                modal: true,
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });*/
}

$(function() {
    
    $(document.body).on('click', '.kpi-indicator-filter-collapse-btn', function() {
        var $this = $(this), 
            $filterBody = $this.next('.list-group-body');
            
        if (!$this.hasClass('opened')) {
            $this.addClass('opened');
            $filterBody.removeClass('d-none');
            if ($filterBody.find('.tree-demo').length && !$filterBody.find('.tree-demo').html().length) {
                drawTreeIndicator($filterBody, $this.parent().attr('data-filter-column'));
            }
        } else {
            $this.removeClass('opened');
            $filterBody.addClass('d-none');
        }  
    });
    
    $(document.body).on('keyup', '.mv-tree-filter-name-search', function(){
        var indicatorId = $(this).closest('.list-group-body').find('.tree-demo').data('indicatorid');
        console.log(window['indicatorStructureTreeView_'+indicatorId]);
        window['indicatorStructureTreeView_'+indicatorId].jstree('search', $(this).val());
    });    
    
    $(document.body).on('change', '[data-kpi-indicator-filter-between]:not(.dateInit)', function() {
        var _this = this, 
            $this = $(_this), 
            loadFunction = $this.attr('data-load-fnc');
        
        if ($this.hasClass('bigdecimalInit') && typeof $this.attr('data-prevent-change') !== 'undefined'){ return; }
        
        if (loadFunction != '') {
            window[loadFunction](_this);
        }
    });
    
    $(document.body).on('changeDate', '[data-kpi-indicator-filter-between]:not(.dateminuteInit)', function() {
        var _this = this, 
            $this = $(_this), 
            loadFunction = $this.attr('data-load-fnc');
        
        if (loadFunction != '') {
            window[loadFunction](_this);
        }
    });
    
    $(document.body).on('change', 'div[data-named-param] input:not(.dateInit, .dateminuteInit)', function() {
        var _this = this, 
            $this = $(_this), 
            loadFunction = $this.closest('[data-named-param]').attr('data-load-fnc');
        
        if ($this.hasClass('bigdecimalInit') && typeof $this.attr('data-prevent-change') !== 'undefined'){ return; }
        
        if (loadFunction != '') {
            window[loadFunction](_this);
        }
    });
    
    $(document.body).on('changeDate', 'div[data-named-param] input.dateInit', function() {
        var _this = this, 
            $this = $(_this), 
            loadFunction = $this.closest('[data-named-param]').attr('data-load-fnc');
        
        if (loadFunction != '') {
            window[loadFunction](_this);
        }
    });
    
    $(document.body).on('change', 'div[data-named-param] select.kpi-ind-combo', function() {
        var _this = this, 
            $this = $(_this), 
            loadFunction = $this.closest('[data-named-param]').attr('data-load-fnc');
        
        if (loadFunction != '') {
            window[loadFunction](_this);
        }
    });
    
    $(document.body).on('click', 'div[data-named-param] .mv-filter-item-click', function() {
        var _this = this, 
            $this = $(_this), 
            $parent = $this.closest('[data-named-param]'), 
            loadFunction = $parent.attr('data-load-fnc');
        
        $parent.find('.active').removeClass('active');
        $this.addClass('active');
        
        if (loadFunction != '') {
            window[loadFunction](_this);
        }
    });
    
    $(document.body).on('click', '.kpi-dtl-table > .tbody > .bp-detail-row .bp-remove-row', function() {
        var $this = $(this), $row = $this.closest('.bp-detail-row');
        
        setTimeout(function() {
            
            if ($row.hasAttr('data-savedrow') && $row.attr('data-savedrow') == '1') {

                var dialogName = '#dialog-kpiindicatorrow-confirm';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName);

                $dialog.html(plang.get('msg_delete_confirm'));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('msg_title_confirm'), 
                    width: 300,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                            var $table = $this.closest('[data-table-path]'),
                                $tbody = $this.closest('.tbody'),
                                isSetRowIndex = true;
                            
                            $this.trigger('change'); 
                            $row.addClass('d-none removed-tr');
                            
                            setTimeout(function() {
                                if ($table.hasAttr('data-pk-columnpath') && $table.attr('data-pk-columnpath') != '') {
                                    var pkVal = $row.find('[data-path="'+$table.attr('data-pk-columnpath')+'"]');
                                    if (pkVal != '') {
                                        bpRowBeforeRemoveInputsSetValue($row);
                                        isSetRowIndex = false;
                                    } else {
                                        $row.remove();
                                    }
                                } else {
                                    $row.remove();
                                }

                                setRowNumKpiIndicatorTemplate($tbody);

                                if (isSetRowIndex) {
                                    if ($table.hasClass('bprocess-table-subdtl')) {
                                        var rowIndex = $tbody.closest('.bp-detail-row').index();
                                        kpiSetRowIndex($tbody, rowIndex);
                                    } else {
                                        kpiSetRowIndex($tbody);
                                    }
                                }
                            }, 205);
                            
                            $dialog.dialog('close');
                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');

            } else {
                var $table = $this.closest('[data-table-path]'),     
                    $tbody = $this.closest('.tbody');
                
                $this.trigger('change'); 
                $row.addClass('d-none removed-tr');
                
                setTimeout(function() {
                    
                    $row.remove();
                    setRowNumKpiIndicatorTemplate($tbody);

                    if ($table.hasClass('bprocess-table-subdtl')) {
                        var rowIndex = $tbody.closest('.bp-detail-row').index();
                        kpiSetRowIndex($tbody, rowIndex);
                    } else {
                        kpiSetRowIndex($tbody);
                    }
                }, 205);
            }
        }, 205);
    });
    
    $(document.body).on('keyup', '.mv-filter-name-search', function(e) {
        var code = e.keyCode || e.which;
        if (code == '9') return;
        
        var $this = $(this);
        var inputVal = $this.val().toLowerCase(), 
            $body = $this.closest('.list-group-body'), 
            $rows = $body.find('a.list-group-item');
            
        var $filteredRows = $rows.filter(function() {
            var $rowElem = $(this);
            var value = $rowElem.find('span[data-value-mode]').text().toLowerCase();
            return value.indexOf(inputVal) === -1;
        });
        
        $rows.css({display: ''});
        $filteredRows.css({display: 'none'});
    });
    
});
