<script type="text/javascript">
var $checkList_<?php echo $this->uniqId2; ?> = $('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>');
var $checkListMenu_<?php echo $this->uniqId2; ?> = $checkList_<?php echo $this->uniqId2; ?>.find('.mv_checklist_card_html');
var viewMode_<?php echo $this->uniqId2; ?> = '<?php echo $this->mode; ?>';
var strIndicatorId_<?php echo $this->uniqId2; ?> = '<?php echo $this->strIndicatorId; ?>';
    
$(function() { 
    
    $checkList_<?php echo $this->uniqId2; ?>.on('click', '.back-menu-card-list', function() {
        $checkList_<?php echo $this->uniqId2; ?>.find('.prnt-content-wrapper').hide();
        $checkList_<?php echo $this->uniqId2; ?>.find('.sidebar').show();        
    });
    
    $checkListMenu_<?php echo $this->uniqId2; ?>.on('click', 'a.nav-link:not(.disabled)', function() {
        var $this = $(this);
        
        $checkListMenu_<?php echo $this->uniqId2; ?>.find('a.nav-link.active').removeClass('active');
        $this.addClass('active');
        
        var rowJson = $this.attr('data-json'), uniqId = $this.attr('data-uniqid'), indicatorId = $this.attr('data-indicatorid'), 
            isComment = $this.hasAttr('data-iscomment') ? $this.attr('data-iscomment') : 0;
        
        if (typeof rowJson === 'undefined') {
            if ($this.parent().hasClass('nav-group-sub-mv-opened')) {
                $this.parent().removeClass('nav-group-sub-mv-opened');
            } else {
                $this.parent().addClass('nav-group-sub-mv-opened');
            }
            return;
        }
        
//        $checkList_<?php //echo $this->uniqId; ?>.find('.prnt-content-wrapper').show();
//        $checkList_<?php //echo $this->uniqId; ?>.find('.sidebar').hide();              
        $checkList_<?php echo $this->uniqId2; ?>.find('.prnt-content-wrapper').empty().append('<div>'+
                                        '<div class="content-wrapper pt-2 pl-3 pr-3 mv-checklist-render-card">'+        
                                        '</div>'+                
                                    '</div>'+                
                                    '<div class="mv-checklist-render-card-comment pl-3 pr-3">'+
                                    '</div>');              
        
        var viewProcess_<?php echo $this->uniqId2; ?> = $checkList_<?php echo $this->uniqId2; ?>.find('.mv-checklist-render-card:visible');
        var viewProcessComment_<?php echo $this->uniqId2; ?> = $checkList_<?php echo $this->uniqId2; ?>.find('.mv-checklist-render-card-comment:visible');
        
        if (typeof rowJson !== 'object') {
            var jsonObj = JSON.parse(html_entity_decode(rowJson, 'ENT_QUOTES'));
        } else {
            var jsonObj = rowJson;
        }
        
        jsonObj.mainIndicatorId = indicatorId;
        
        var metaDataId = jsonObj.metaDataId, metaTypeId = jsonObj.metaTypeId, 
            indicatorId = jsonObj.indicatorId, kpiTypeId = jsonObj.kpiTypeId;
    
        if (false && (metaDataId == null || metaDataId == '')) {
              $.ajax({
                type: "post",
                url: "api/callDataview",
                data: {
                  dataviewId: "17085010097299",
                  criteriaData: {
                    id: [{
                        operator: "=",
                        operand: indicatorId,
                    }]
                  }
                },
                dataType: "json",
                success: function (data) {            
                    if (data.status === "success" && data.result[0]) {
                        $.ajax({
                            type: 'post',
                            url: 'mdworkspace/renderWorkSpace',
                            data: {metaDataId: "17085010237759", dmMetaDataId: "16424766176721", selectedRow: data.result[0]},
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },                
                            dataType: 'json',
                            success: function(data) {

                                if ($("link[href='middleware/assets/theme/" + data.theme + "/css/main.css']").length == 0) {
                                    $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.css"/>');
                                }

                                if (data.theme == 'theme10') {
                                    $.getScript("assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js");
                                    $.getScript("assets/custom/addon/plugins/jquery.sparkline.min.js");
                                }
                                
//                                var $dialogName = "dialog-script-mv-config-show";
//                                if (!$("#" + $dialogName).length) {
//                                  $('<div id="' + $dialogName + '"></div>').appendTo("body");
//                                }
//                                var $dialog = $("#" + $dialogName);
//
//                                $dialog.empty().append(data.html);
//                                
//                                $dialog.dialog({
//                                  cache: false,
//                                  resizable: true,
//                                  bgiframe: true,
//                                  autoOpen: false,
//                                  title: "Тохиргоо",
//                                  width: 400,
//                                  height: "auto",
//                                  modal: true,
//                                  open: function () {
//                                  },
//                                  close: function () {
//                                    $dialog.empty().dialog("destroy").remove();
//                                  },
//                                  buttons: [{
//                                      text: plang.get("close_btn"),
//                                      class: "btn btn-sm blue-madison",
//                                      click: function () {
//                                        $dialog.dialog("close");
//                                      }
//                                    }
//                                  ]
//                                }).dialogExtend({
//                                closable: true,
//                                maximizable: true,
//                                minimizable: true,
//                                collapsable: true,
//                                dblclick: "maximize",
//                                minimizeLocation: "left",
//                                icons: {
//                                  close: "ui-icon-circle-close",
//                                  maximize: "ui-icon-extlink",
//                                  minimize: "ui-icon-minus",
//                                  collapse: "ui-icon-triangle-1-s",
//                                  restore: "ui-icon-newwin",
//                                };
//                                });
//                                $dialog.dialog("open");                 
//                                $dialog.dialogExtend("maximize");
//
//                                return;
                                viewProcess_<?php echo $this->uniqId2; ?>.empty().append(data.html).promise().done(function () {
                                    Core.unblockUI();
                                    viewProcess_<?php echo $this->uniqId2; ?>.find('.close-btn').remove();
                                    viewProcess_<?php echo $this->uniqId2; ?>.find('ul.workspace-menu > li > a').css({"color":"#737373", "font-weight":"normal"});
                                    Core.initAjax(viewProcess_<?php echo $this->uniqId2; ?>);                        
                                });
                            }
                        });  
                    }
                },
              });                    
            return;
        }
        
        if (metaDataId != '' && metaDataId != null) {
            
            if (metaTypeId == '200101010000011') { //Process

                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/callMethodByMeta',
                    data: {
                        metaDataId: metaDataId,
                        isDialog: false, 
                        isHeaderName: true, 
                        isBackBtnIgnore: 1, 
                        isIgnoreSetRowId: 1, 
                        kpiIndicatorMapConfig: jsonObj, 
                        callerType: 'dv', 
                        openParams: '{"callerType":"dv","afterSaveNoAction":true}'
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(data.Html).promise().done(function () {
                            viewProcess_<?php echo $this->uniqId2; ?>.find('.bp-btn-back, .bpTestCaseSaveButton').remove();
                            viewProcess_<?php echo $this->uniqId2; ?>.find('.meta-toolbar').addClass('not-sticky');
                            
                            var $saveAddBtn = viewProcess_<?php echo $this->uniqId2; ?>.find('.bp-btn-saveadd:visible');
                            if ($saveAddBtn.length) {
                                $saveAddBtn.text(plang.get('save_btn'));
                                viewProcess_<?php echo $this->uniqId2; ?>.find('.bp-btn-save').remove();
                            }
                            
                            Core.initBPAjax(viewProcess_<?php echo $this->uniqId2; ?>);
                            Core.unblockUI();
                        });
                    },
                    error: function() { alert('Error'); Core.unblockUI(); }
                });

            } else if (metaTypeId == '200101010000016') { //Dataview
        
                $.ajax({
                    type: 'post',
                    url: 'mdobject/dataview/' + metaDataId + '/0/json',
                    data: {kpiIndicatorMapConfig: jsonObj},
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (data) {
                        if (data.hasOwnProperty('Html')) {
                            viewProcess_<?php echo $this->uniqId2; ?>.empty().append(data.Html).promise().done(function () {
                                viewProcess_<?php echo $this->uniqId2; ?>.find('> .row > .col-md-12:eq(0)').remove();
                                Core.unblockUI();
                            });
                        } else {
                            viewProcess_<?php echo $this->uniqId2; ?>.removeClass('pl-3 pr-3').addClass('pl5 pr5');
                            viewProcess_<?php echo $this->uniqId2; ?>.empty().append(data.html).promise().done(function () {
                                Core.unblockUI();
                            });
                        }
                    },
                    error: function(){ alert('Error'); Core.unblockUI(); }
                });
                
            } else if (metaTypeId == '200101010000032') { //Chart
            
                $.ajax({
                    type: 'post',
                    url: 'mddashboard/diagramRenderByPost',
                    data: {metaDataId: metaDataId},
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (data) {
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(data.Html).promise().done(function () {
                            Core.unblockUI();
                        });
                    },
                    error: function(){ alert('Error'); Core.unblockUI(); }
                });
            } else if (metaTypeId == '200101010000035') { //Statement
            
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/index/' + metaDataId,
                    data: {kpiIndicatorMapConfig: jsonObj},
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (dataHtml) {
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(dataHtml).promise().done(function () {
                            Core.unblockUI();
                        });
                    },
                    error: function(){ alert('Error'); Core.unblockUI(); }
                });
            } else if (metaDataId == '1522652361821242') { //Pos menu meta id
            
                $.ajax({
                    type: 'post',
                    url: 'mdpos',
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (data) {
                        $.ajax({
                            url: "assets/custom/addon/plugins/jquery-fixedheadertable/jquery.fixedheadertable.min.js",
                            dataType: "script",
                            cache: true,
                            async: false,
                            beforeSend: function() {
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/css/pos/style.css?v=1"/>');
                            }
                        }).done(function() {
                            $.ajax({
                                url: "assets/custom/addon/plugins/scannerdetection/jquery.scannerdetection.js",
                                dataType: "script",
                                cache: true,
                                async: false
                            });
                            viewProcess_<?php echo $this->uniqId2; ?>.empty().append(data.html).promise().done(function () {
                                Core.unblockUI();                        
                                if (typeof data.chooseCashier === 'undefined') {
                                    viewProcess_<?php echo $this->uniqId2; ?>.find('.pos-wrap').css({"margin-left":"-15px", "margin-right":"-16px", "margin-top":"-9px"});
                                    viewProcess_<?php echo $this->uniqId2; ?>.find('.pos-left').css({"position":"inherit","overflow-y":"auto","overflow-x":"hidden","height":viewProcess_<?php echo $this->uniqId2; ?>.find('.pos-center-inside-height').height()+130+'px'});
                                    viewProcess_<?php echo $this->uniqId2; ?>.find('.pos-left-inside-help').css("position","inherit");
                                    if (typeof checkInitPosJS === 'undefined') {
                                        $.ajax({
                                            url: "middleware/assets/js/pos/pos.js",
                                            dataType: "script",
                                            cache: false,
                                            async: false
                                        });
                                    } else {
                                        setTimeout(function() {
                                            Core.initDecimalPlacesInput();
                                            posConfigVisibler($('body'));
                                            posPageLoadEndVisibler();
                                            posItemCombogridList('');
                                            $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();

                                            var $tbody = $('#posTable').find('> tbody');                

                                            if ($tbody.find('> tr').length) {

                                                Core.initLongInput($tbody);
                                                Core.initUniform($tbody);

                                                posGiftRowsSetDelivery($tbody);

                                                var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                                                $firstRow.click();

                                                posCalcTotal();
                                            }                  

                                            /*if (posUseIpTerminal === '1') {
                                                posConnectBankTerminal();
                                            }*/

                                            if (isConfirmSaleDate === '1' && !isBasketOnly) {
                                                askDateTransaction();
                                            }                    

                                        }, 300);
                                    }
                                    setTimeout(function() {
                                        posTableSetHeight();
                                        posFixedHeaderTable();
                                    }, 300);
                                }                    
                            });
                        });                        
                    },
                    error: function(){ alert('Error'); Core.unblockUI(); }
                });
                
            } else if (metaDataId == '1710231625314794') { //FA_DEPRECTION_WEBLINK
            
                $.ajax({
                    type: 'post',
                    url: 'mdasset/deprecation',
                    dataType: 'html',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (dataHtml) {
                        var getRenderWidth = viewProcess_<?php echo $this->uniqId2; ?>.width();
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(dataHtml).promise().done(function () {
                            viewProcess_<?php echo $this->uniqId2; ?>.find('.pf-custom-pager > .freeze-overflow-xy-auto').css('width', getRenderWidth);
                            Core.initAjax(viewProcess_<?php echo $this->uniqId2; ?>);
                            Core.unblockUI();
                        });
                    }
                });
                
            } else if (metaDataId == '1710746826924995') { //Create GL
            
                $.ajax({
                    type: 'post',
                    url: 'mdgl/entry',
                    dataType: 'html',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (dataHtml) {
                        var getRenderWidth = viewProcess_<?php echo $this->uniqId2; ?>.width();
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(dataHtml).promise().done(function () {
                            viewProcess_<?php echo $this->uniqId2; ?>.find('.freeze-overflow-xy-auto').removeClass('w-100').css('width', '1160px');
                            Core.initAjax(viewProcess_<?php echo $this->uniqId2; ?>);
                            Core.unblockUI();
                        });
                    }
                });
                
            } else if (metaDataId == '1710748364382042') { //Create Cashrate
                
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
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/cashrate',
                    dataType: 'html',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (dataHtml) {
                        var getRenderWidth = viewProcess_<?php echo $this->uniqId2; ?>.width();
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(dataHtml).promise().done(function () {
                            viewProcess_<?php echo $this->uniqId2; ?>.find('.freeze-overflow-xy-auto').removeClass('w-100').css('width', getRenderWidth);
                            Core.initAjax(viewProcess_<?php echo $this->uniqId2; ?>);
                            Core.unblockUI();
                        });
                    }
                });
                
            } else if (metaDataId == '1710748420762728') { //Create Clearingtrans
                
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
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/clearingtrans',
                    dataType: 'html',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (dataHtml) {
                        var getRenderWidth = viewProcess_<?php echo $this->uniqId2; ?>.width();
                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(dataHtml).promise().done(function () {
                            viewProcess_<?php echo $this->uniqId2; ?>.find('.freeze-overflow-xy-auto').removeClass('w-100').css('width', getRenderWidth);
                            Core.initAjax(viewProcess_<?php echo $this->uniqId2; ?>);
                            Core.unblockUI();
                        });
                    }
                });
            }
            
        } else {
            
            var strIndicatorId = jsonObj.strIndicatorId;
            var mapId = jsonObj.mapId;
            var isMartRender = Number(jsonObj.isMartRender);
            
            var $parent = $this.closest('.mv-checklist-render-parent');
            var $headerParams = $parent.find('input[data-path="headerParams"]');
            var headerRecordId = '';
            
            if ($headerParams.length) {
                var rowParse = JSON.parse(html_entity_decode($headerParams.val(), "ENT_QUOTES"));
                headerRecordId = $parent.find('input[data-path="headerRecordId"]').val();
            }
            
            if (kpiTypeId == '2008' || isMartRender > 0) { 
        
                var postData = {
                    mainIndicatorId: jsonObj.mainIndicatorId, 
                    structureIndicatorId: strIndicatorId, 
                    trgIndicatorId: indicatorId, 
                    trgIndicatorKpiTypeId: kpiTypeId, 
                    typeCode: '', 
                    endSessionLogSavedStatusId: $this.find(".text-three-line.position-4").children().attr("data-statusid"), 
                    recordId: '', 
                    srcMapId: mapId, 
                    selectedRow: ''
                };

                if ($headerParams.length) {
                    postData.selectedRow = rowParse;
                    postData.recordId = headerRecordId;
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/renderValueMapStructure',
                    data: postData,
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(dataHtml) {
                        var html = [];

                        var renderHeader = '<div class="meta-toolbar is-bp-open-">'+
                            '<div class="main-process-text">\n\
                                <div>'+$this.text()+'</div>\n\
                                <div class="main-process-text-description">'+(dataHtml.indicatorInfo && dataHtml.indicatorInfo.DESCRIPTION ? dataHtml.indicatorInfo.DESCRIPTION : '')+'</div>\n\
                            </div>';
                        
                        if (viewMode_<?php echo $this->uniqId2; ?> != 'view' && false) {
                            renderHeader += '<div class="ml-auto">'+
                                    '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="checkListSaveKpiIndicatorForm(this, \'\', \''+strIndicatorId+'\');"><i class="icon-checkmark-circle2"></i> '+plang.get('save_btn')+'</button>'+
                                '</div>';
                        }
                        
                        renderHeader += '</div>';
                
                        html.push('<form method="post" enctype="multipart/form-data">');
                            html.push(renderHeader);
                            html.push(dataHtml.html);
                        html.push('</form>');

                        viewProcess_<?php echo $this->uniqId2; ?>.empty().append(html.join('')).promise().done(function() {
                            
                            if (viewMode_<?php echo $this->uniqId2; ?> == 'view') {
                                
                                var $render = viewProcess_<?php echo $this->uniqId2; ?>;
                                
                                $render.find('.bp-add-one-row').parent().remove();
                                $render.find('.bp-remove-row, button.red, button.bp-btn-save, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                                $render.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                                $render.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                                $render.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                                $render.find('[data-action-name="exportexcel"]').removeClass('d-none');

                                var $radioElements = $render.find("input[type='radio']");
                                if ($radioElements.length) {
                                    $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                    $radioElements.closest('.radio').addClass('disabled');
                                }

                                var $checkElements = $render.find("input[type='checkbox']");
                                $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                $checkElements.closest('.checker').addClass('disabled');
                            }
                            
                            if (isComment == '1' && postData.hasOwnProperty('recordId')) {
                                
                                viewProcessComment_<?php echo $this->uniqId2; ?>.empty().append('<div style="font-weight: bold;padding: 10px 0 7px 0;">Сэтгэгдэл</div>');
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdwebservice/renderEditModeBpCommentTab',
                                    data: {
                                        uniqId: uniqId, 
                                        refStructureId: jsonObj.mainIndicatorId, 
                                        sourceId: postData.recordId, 
                                        listMetaDataId: indicatorId
                                    },
                                    success: function(data) {
                                        viewProcessComment_<?php echo $this->uniqId2; ?>.append(data);
                                        Core.unblockUI();
                                    }
                                });
                            } else {
                                Core.unblockUI();
                            }
                            
                            var $dialog = $checkList_<?php echo $this->uniqId2; ?>.find(".mv-checklist-render-card:visible");
                            var $parentDialog = $checkList_<?php echo $this->uniqId2; ?>.find('.ui-dialog-content');                    
                            if ($parentDialog.length) {  
                                $parentDialog.css({'position': 'static', 'overflow': 'inherit'});         
                                $parentDialog.parent().css('overflow', 'inherit');
                            }

                            $dialog.dialog({
                              cache: false,
                              resizable: false,
                              bgiframe: true,
                              appendTo: $checkList_<?php echo $this->uniqId2; ?>.find(".prnt-content-wrapper"),
                              autoOpen: false,
                              title: "",
                              width: 1100,
                              height: "auto",
                              position: { my: "top", at: "top+70" },
                              modal: true,
                              close: function () {
                                $dialog.empty().dialog("destroy").remove();
                              },
                              buttons: [{
                                    text: plang.get('save_btn'),
                                    class: "btn btn-sm green-meadow",
                                    click: function () {
                                      checkListSaveKpiIndicatorForm($dialog.find('.kpi-ind-tmplt-section'));
                                      $dialog.dialog("close");
                                    },
                                  },{
                                  text: "Хаах",
                                  class: "btn blue-madison btn-sm",
                                  click: function () {
                                    $dialog.dialog("close");
                                  },
                                }],
                            });
                            $dialog.dialog("open");                
                            Core.initBPAjax(viewProcess_<?php echo $this->uniqId2; ?>);
                        });
                    }
                });
                
            } else {
                
                var recordId = headerRecordId;
                var postData = {
                    mapSrcMapId: mapId, 
                    mapSelectedRow: $headerParams.val(), 
                    srcMapId: mapId, 
                    /*isIgnoreFilter: 1,*/
                    isHideCheckBox: 0, 
                    isIgnoreTitle: 1
                };
                
                if (isComment == '1' && recordId != '') {
                    postData.isComment = 1;
                    postData.dynamicHeight = ($(window).height() / 2) - 40;
                }
                    
                $.ajax({
                    type: 'post',
                    url: 'mdform/indicatorList/' + indicatorId,
                    data: postData, 
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(dataHtml) {
                        $.ajax({
                            type: 'post',
                            url: 'mdform/getIndicatorDescription',
                            data: {
                                indicatorId: indicatorId
                            }, 
                            dataType: 'json',
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(dataJson) {
                                var html = [];

                                var renderHeader = '<div class="meta-toolbar is-bp-open-">'+
                                    '<div class="main-process-text">\n\
                                        <div>'+$this.text()+'</div>\n\
                                        <div style="" class="main-process-text-description">'+(dataJson && dataJson.DESCRIPTION ? dataJson.DESCRIPTION : '')+'</div>\n\
                                    </div>'+
                                '</div>';

                                html.push(renderHeader);
                                html.push(dataHtml);

                                viewProcess_<?php echo $this->uniqId2; ?>.empty().append(html.join('')).promise().done(function() {
                                    if (postData.hasOwnProperty('isComment') && postData.isComment == '1') {

                                        viewProcessComment_<?php echo $this->uniqId2; ?>.empty().append('<div style="font-weight: bold;padding: 10px 0 7px 0;">Сэтгэгдэл</div>');

                                        $.ajax({
                                            type: 'post',
                                            url: 'mdwebservice/renderEditModeBpCommentTab',
                                            data: {
                                                uniqId: uniqId, 
                                                refStructureId: jsonObj.mainIndicatorId, 
                                                sourceId: recordId, 
                                                listMetaDataId: indicatorId
                                            },
                                            success: function(data) {
                                                viewProcessComment_<?php echo $this->uniqId2; ?>.append(data);
                                                Core.unblockUI();
                                            }
                                        });
                                    } else {
                                        Core.unblockUI();
                                    }
                                    
                                    var $dialog = $checkList_<?php echo $this->uniqId2; ?>.find(".mv-checklist-render-card:visible");
                                    var $parentDialog = $checkList_<?php echo $this->uniqId2; ?>.find('.ui-dialog-content');                    
                                    if ($parentDialog.length) {  
                                        $parentDialog.css({'position': 'static', 'overflow': 'inherit'});         
                                        $parentDialog.parent().css('overflow', 'inherit');
                                    }

                                    $dialog.dialog({
                                      cache: false,
                                      resizable: false,
                                      bgiframe: true,
                                      appendTo: $checkList_<?php echo $this->uniqId2; ?>.find(".prnt-content-wrapper"),
                                      autoOpen: false,
                                      title: "",
                                      width: 1100,
                                      height: "auto",
                                      position: { my: "top", at: "top+70" },
                                      modal: true,
                                      close: function () {
                                        $dialog.empty().dialog("destroy").remove();
                                      },
                                      buttons: [{
                                          text: "Хаах",
                                          class: "btn blue-madison btn-sm",
                                          click: function () {
                                            $dialog.dialog("close");
                                          },
                                        }],
                                    });
                                    $dialog.dialog("open");                                              
                                    
                                });
                            }
                        });      
                    }
                });
            }
        }
    });
    
    <?php
    if (isset($this->rowData) && $this->rowData) {
        echo Mdform::checkListRelationCriteriaScript($this->rowData, $this->relationList, $this->uniqId);
    }
    ?>
    
});
</script>