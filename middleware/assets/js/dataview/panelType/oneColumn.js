var isDataViewPanelOneColumn = true;

var buildOneColSecondPart = function(uniqId, dvId, $this) {
    
    var metaDataId = $this.data('listmetadataid');

    var rowData = $this.data('rowdata');
    if (rowData.hasOwnProperty('weblink') && rowData.weblink) {
        var urlLower = (rowData.weblink).toLowerCase();
        switch (urlLower) {
            case 'contentpoll':
                if (typeof isProjecPollAddonScript === 'undefined') {
                    $.getScript(URL_APP + 'projects/assets/custom/projects/poll.js').done(function() {
                        contentPoll($this, rowData, window['viewProcess_' + uniqId]);
                    });
                } else {
                    contentPoll($this, rowData, window['viewProcess_' + uniqId]);
                }
                break;
        
            default:
                break;
        }
    }

    if (!metaDataId) {
        return;
    }
    
    var metaTypeId = $this.data('metatypeid');
    
    if (metaTypeId == '200101010000016') { //Dataview
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/dataview/' + metaDataId + '/0/json',
            data: {drillDownDefaultCriteria: window['firstList_'+uniqId].find('a.dv-twocol-f-selected').data('listmetadatacriteria')}, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {

                window['viewProcess_'+uniqId].empty().append(data.Html).promise().done(function () {
                    window['viewProcess_'+uniqId].find('> .row > .col-md-12:eq(0)').remove();
                    Core.unblockUI();
                });
            },
            error: function(){ alert('Error'); }
        });
        
    } else if (metaTypeId == '200101010000011') { //Process
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: metaDataId,
                dmMetaDataId: dvId,
                isDialog: false,
                isHeaderName: false,
                isBackBtnIgnore: 1, 
                isIgnoreSetRowId: 1, 
                oneSelectedRow: rowData, 
                callerType: 'dv', 
                openParams: '{"callerType":"dv","afterSaveNoAction":true}'
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data){
                window['viewProcess_'+uniqId].empty().append(data.Html).promise().done(function () {
                    window['viewProcess_'+uniqId].find('.bp-btn-back, .bpTestCaseSaveButton').remove();
                    Core.initBPAjax(window['viewProcess_'+uniqId]);
                    Core.unblockUI();
                });
            },
            error: function(){
                alert('Error');
            }
        });

    } else if (metaTypeId == '200101010000010') { //Bookmark
        
        var obj = qryStrToObj($this.data('listmetadatacriteria').toLowerCase());
        
        if (metaDataId == '1476633896425334') { //htmlContentOpener
    
            $.ajax({
                type: 'post',
                url: 'mdcontentui/contentHtmlRender',
                data: {
                    id: obj.id,
                    dataViewId: dvId,
                    srcRecordId: $this.data('id')
                },
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    window['viewProcess_'+uniqId].empty().append(data.html).promise().done(function () {
                        Core.initAjax(window['viewProcess_'+uniqId]);
                        Core.unblockUI();
                    });
                }
            });
            
        } else if (metaDataId == '1636438397801188') { //erdConfig
            
            window['viewProcess_'+uniqId].empty();
            var $appendElement = window['viewProcess_'+uniqId];
            var isReadOnly = obj.hasOwnProperty('isreadonly') ? obj.isreadonly : 0;

            erdConfigInit($this, metaDataId, metaDataId, {id: obj.id, isreadonly: isReadOnly}, $appendElement);
        }
        
    } else if (metaTypeId == '200101010000029') {
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/getTemplateByRowData',
            data: {metaDataId: metaDataId, dmMetaDataId: dvId, rowData: rowData},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {

                if (data.status == 'success') {

                    var renderHtml = (data.Html).replace('report-preview-container', 'report-preview-container rt-set-autoheight');

                    window['viewProcess_'+uniqId].empty().append(renderHtml).promise().done(function () {
                        Core.initAjax(window['viewProcess_'+uniqId]);
                        Core.unblockUI();
                        
                        if (typeof rowData.windowtype !== 'undefined' && rowData.windowtype === '2') {
                            if (window['viewProcess_'+uniqId].find('.addonwindowType').length > 0) {
                                window['viewProcess_'+uniqId].find('.addonwindowType').empty();
                            } else {
                                window['viewProcess_'+uniqId].append('<div class="w-100 pull-left addonwindowType"></div>');
                            }
                        }
                    });

                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            },
            error: function() { alert('Error'); Core.unblockUI(); }
        });
        
    } else if (metaTypeId == '200101010000032') {
        
        var rowData = $this.data('rowdata'), isAddonMsg = false,
            setHeight = window['viewProcess_'+uniqId].height() - 90;

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        if (rowData.hasOwnProperty('metadatadescription') && rowData.metadatadescription) {
            
            window['viewProcess_'+uniqId].prepend('<div id="addon-footer-msg" style="position:absolute;visibility:hidden;display:block">'+html_entity_decode(rowData.metadatadescription)+'</div>');
            
            isAddonMsg = true;
            setHeight = setHeight - window['viewProcess_'+uniqId].find('#addon-footer-msg').height() - 5;
        }
        
        $.ajax({
            type: 'post',
            url: 'mddashboard/diagramRenderByPost',
            data: {
                metaDataId: metaDataId, 
                rowId: rowData.id, 
                defaultCriteriaData: window['panelDv_'+uniqId].find('.dv-paneltype-filter-form').serialize(), 
                setHeight: setHeight
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                var renderHtml = data.Html;
                
                if (isAddonMsg) {
                    renderHtml += html_entity_decode(rowData.metadatadescription);
                }
                
                window['viewProcess_'+uniqId].empty().append(renderHtml).promise().done(function () {
                    Core.initBPAjax(window['viewProcess_'+uniqId]);
                    Core.unblockUI();
                });
            }
        });
        
    } else if (metaTypeId == '200101010000033') { 
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdobject/package/' + metaDataId,
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                var renderHtml = dataHtml;
                
                if (rowData.hasOwnProperty('metadatadescription') && rowData.metadatadescription) {
                    renderHtml += html_entity_decode(rowData.metadatadescription);
                }
                
                window['viewProcess_'+uniqId].empty().append(renderHtml).promise().done(function() {
                    Core.unblockUI();
                });
            }
        });
            
    } else if (metaTypeId == '200101010000034') { //Workspace
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
                
        $.ajax({
            type: 'post',
            url: 'mdworkspace/renderWorkSpace',
            data: {metaDataId: metaDataId, dmMetaDataId: dvId, selectedRow: rowData},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if ($("link[href='middleware/assets/theme/" + data.theme + "/css/main.v5.css']").length == 0) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.v5.css"/>');
                }

                if (data.theme == 'theme10') {
                    $.cachedScript("assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js");
                    $.cachedScript("assets/custom/addon/plugins/jquery.sparkline.min.js");
                }

                window['viewProcess_'+uniqId].empty().append(data.html).promise().done(function () {
                    window['viewProcess_'+uniqId].find('.close-btn').remove();
                    Core.initAjax(window['viewProcess_'+uniqId]);
                    Core.unblockUI();
                });
            }
        });
        
    } else if (metaTypeId == '200101010000035') {
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdstatement/index/' + metaDataId,
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {

                var renderHtml = dataHtml;
                
                if (rowData.hasOwnProperty('metadatadescription') && rowData.metadatadescription) {
                    renderHtml = html_entity_decode(rowData.metadatadescription) + renderHtml;
                }
                
                window['viewProcess_'+uniqId].empty().append(renderHtml).promise().done(function() {
                    Core.unblockUI();
                });
            }
        });
    } else if (metaTypeId == '200101010000036') {
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdlayoutrender/index/' + metaDataId,
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {

                var renderHtml = data.Html;
                
                if (rowData.hasOwnProperty('metadatadescription') && rowData.metadatadescription) {
                    renderHtml = html_entity_decode(rowData.metadatadescription) + renderHtml;
                }
                
                window['viewProcess_'+uniqId].empty().append(renderHtml).promise().done(function() {
                    Core.unblockUI();
                });
            }
        });
    } else if (metaTypeId == 'indicator') {
        
        var rowData = $this.data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdform/indicatorDataList/' + metaDataId,
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                window['viewProcess_'+uniqId].empty().append(data).promise().done(function() {});
            }
        }).done(function() {
            Core.unblockUI();
        });
    } else if (metaTypeId == 'method') {
        
        var rowData = $this.data('rowdata');
        var getIds = metaDataId.split('_');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        var postData = {param: {indicatorId: getIds[1], crudIndicatorId: getIds[0]}};
        
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
                    window['viewProcess_'+uniqId].empty().append('<form method="post" enctype="multipart/form-data" class="dv-process-buttons"><button type="button" class="btn btn-sm btn-circle btn-success" style="position: absolute;right: 0;top: 45px;" onclick="runMetaverseCrud(this);"><i class="icon-checkmark-circle2"></i> Хадгалах</button>' + data.html + '</form>').promise().done(function() {});                
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
};

function runMetaverseCrud (elem) {
    var $form = $(elem).closest('form');    
    $form.validate({errorPlacement: function () {}});

    if ($form.valid()) {
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
                } 

                Core.unblockUI();
            }
        });
    }    
}