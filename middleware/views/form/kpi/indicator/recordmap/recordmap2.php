<div class="kpi-component-wrap" id="kpi-component-<?php echo $this->componentUniqId; ?>">
    <div class="col p-0">
        <div class="d-flex justify-content-between">
            <div class="dv-process-buttons">
              <div class="btn-group btn-group-devided">
                <a class="btn btn-success btn-circle btn-sm" title="Нэмэх шинэ" onclick="runAddKpiRelatonBp(this);" data-actiontype="update" data-dvbtn-processcode="data_IndicatorMapDV_006" data-ismain="0" href="javascript:;"><i class="far fa-plus"></i> Нэмэх /шинэ/</a>
                <a class="btn btn-success btn-circle btn-sm" title="Нэмэх хуучин" onclick="runKpiRelatonBp(this, '16425125580661', false);" data-actiontype="update" data-dvbtn-processcode="data_IndicatorMapDV_006" data-ismain="0" href="javascript:;"><i class="far fa-plus"></i></a>
                <a class="btn btn-warning btn-circle btn-sm" title="Засах" onclick="runKpiRelatonBp(this, '16660589496259', true);" data-actiontype="update" data-dvbtn-processcode="data_IndicatorMapDV_006" data-ismain="0" href="javascript:;"><i class="far fa-edit"></i></a>
                <a class="btn btn-danger btn-circle btn-sm" title="Устгах" onclick="deleteKpiRelatonBp(this);" data-actiontype="update" data-dvbtn-processcode="data_IndicatorMapDV_006" data-ismain="0" href="javascript:;"><i class="far fa-trash"></i></a>
              </div>                                    
            </div>
            <div>
                <select class="form-control relation-list-view-type">
                    <option value="LIST">Жагсаалтаар</option>
                    <option value="VISUAL">Визуал</option>
                    <option value="SEMANTIC_TYPE_NAME">Холбоосын төрөл</option>
                    <option value="CRITERIA">Шалгуур</option>
                    <option value="TAB_NAME">Таб нэр</option>
                    <option value="GROUP_NAME">Бүлгийн нэр</option>
                    <option value="LOOKUP_META_DATA_ID">Үзүүлэлт</option>
                </select>
            </div>
        </div>
    </div>
    <div class="back-action" style="display: none;">
        <div class="col p-0 mt-2 d-flex back-action" style="display: none;">
            <a href="javascript:;" class="back-item-btn d-block"><i class="icon-arrow-left8" style="color:#000"></i></a>
            <div class="item-card-toptitle"></div>
        </div>
    </div>
    <div class="row recordmap2content-container">    
        <?php include_once "recordmap2content.php"; ?>
    </div>
</div>

<style type="text/css">
#kpi-component-<?php echo $this->componentUniqId; ?> .reldetail {
    margin-bottom: 8px;
    /*height: 83px;*/
    background-color: #f1f8e9; 
    border: 1px solid #e0e0e0;
}
#kpi-component-<?php echo $this->componentUniqId; ?> .reldetail.active {
    background-color: #afe4fb !important;
}
#kpi-component-<?php echo $this->componentUniqId; ?> .item-card-toptitle {
    font-size: 15px;
    padding-top: 10px;
    padding-left: 15px;
    text-transform: uppercase;
}
#kpi-component-<?php echo $this->componentUniqId; ?> .back-item-btn {
    background: #FFFFFF;
    border: 1px solid #E6E6E6;
    box-sizing: border-box;
    border-radius: 10px;
    width: 40px;
    height: 40px;
    text-align: center;
    padding-top: 12px;
}
#kpi-component-<?php echo $this->componentUniqId; ?> .joint-tools {
    display: none;
}
</style>
<script type="text/javascript">
$(function() {
    
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').on('change', 'input[data-listen-path="1"]', function() {
        var $this = $(this);
        if ($this.val() != '') {
            var attributes = $this.attr('data-attributes');
            var rowData = $this.attr('data-row-data');
            var $parent = $this.closest('.quick-item-process');
            
            $parent.find('input[id*="_nameField"], input[id*="_valueField"]').val('').attr({'data-attributes': '', 'data-row-data': ''});
            
            if (typeof attributes !== 'object') {
                var attributesObj = JSON.parse(html_entity_decode(attributes, 'ENT_QUOTES'));
            } else {
                var attributesObj = attributes;
            }
            
            if (typeof rowData !== 'object') {
                var rowDataObj = JSON.parse(html_entity_decode(rowData, 'ENT_QUOTES'));
            } else {
                var rowDataObj = rowData;
            }
            var rows = [rowDataObj];
            
            kpiIndicatorRelationFillRows($this, $this.attr('data-lookupid'), rows, attributesObj.id, attributesObj.code, attributesObj.name, 'single');
        }
    });
    
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').on('click', '.reldetail', function() {
        var $this = $(this);
        $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.reldetail').removeClass('active');
        $this.addClass('active');
    });
    
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').on('click', '.back-item-btn', function() {
        var $this = $(this);
        $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.view-type-all').addClass('d-none');
        $("#kpi-component-<?php echo $this->componentUniqId; ?> .back-action").hide();
        $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.view-type').removeClass('d-none');
    });
    
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').on('change', '.relation-list-view-type', function() {
        var $this = $(this);
        $.ajax({
          type: "post",
          url: "mdform/renderRelationKpiViewType",
          data: {
              viewType: $this.val(),
              indicatorId: '<?php echo $this->indicatorId; ?>'
          },
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true,
          });
        },          
          dataType: "json",                 
          success: function (data) {              
                $("#kpi-component-<?php echo $this->componentUniqId; ?> .back-action").hide();
                $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").html(data.html).promise().done(function () {
                    if ($this.val() == 'VISUAL') {
                        var dynamicHeight = $(window).height() - $("#app").offset().top - 20;
                        var conWidth = $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").width();
                        $("#app").css({height:dynamicHeight - 20,width:conWidth});
                        $(".canvas").css({height:dynamicHeight - 20,width:conWidth});

//                        $.cachedScript('http://localhost:8080/bundle.js').done(function() {
                        $.cachedScript('<?php echo autoVersion('assets/rappidjs/relationconfig/bundle.js'); ?>').done(function() {
                        });            
                    }               
                    Core.unblockUI();
                });                            
          }
        });  
    });
    
    $.contextMenu({
        selector: "#kpi-component-<?php echo $this->componentUniqId; ?> .reldetail",
        events: {
            show: function(opt) {
                var $this = $(opt.$trigger);
                $this.trigger('click');
            }
        },
        callback: function(key, opt) {
            if (key == 'edit') {
                var $this = $(opt.$trigger);
                runKpiRelatonBp($this, '16660589496259');
            }
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: 'edit'}
        }
    });
    
    $(window).resize(function () {
        var dynamicHeight = $(window).height() - $("#app").offset().top - 20;
        var conWidth = $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").width();
        $("#app").css({height:dynamicHeight - 20,width:conWidth});
        $(".canvas").css({height:dynamicHeight - 20,width:conWidth});
    });    
    
}); 

function relationListViewTypeMore(viewType, viewType2) {
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.view-type').addClass('d-none');
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.view-type-'+viewType).removeClass('d-none');
    $("#kpi-component-<?php echo $this->componentUniqId; ?> .back-item-btn").attr('data-view-type', viewType);
    $("#kpi-component-<?php echo $this->componentUniqId; ?> .item-card-toptitle").text(viewType2);
    $("#kpi-component-<?php echo $this->componentUniqId; ?> .back-action").show();
}
function kpiIndicatorMainRelationFillRows(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
    
    var html = [], $tbody = elem.closest('.reldetail').find('table.mv-record-map-tbl > tbody:eq(0)');
    var delete_btn = plang.get('delete_btn');
    var view_btn = plang.get('view_btn');
    var isAddonForm = false;
    var indicatorRecordMaps = [];
    
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
            
            indicatorRecordMaps.push(rowId);
        }
    }    

    $.ajax({
      type: "post",
      url: "mdform/kpiSaveMetaDmRecordMap2",
      data: {
        mainIndicatorId: '<?php echo $this->indicatorId; ?>',
        indicatorId: indicatorId,
        indicatorRecordMaps: indicatorRecordMaps
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
            //$tbody.append(html.join(''));        
            $.ajax({
              type: "post",
              url: "mdform/renderRelationKpiReload",
              data: {
                indicatorId: '<?php echo $this->indicatorId; ?>'
              },
              dataType: "json",                 
              success: function (data) {
                $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").append(data.html)
                $("#kpi-component-<?php echo $this->componentUniqId; ?> .relation-list-view-type").val('LIST').trigger('change');
                Core.unblockUI();
              },
            });             
            new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });            
        } else {
          Core.unblockUI();
        }
      },
    });    
}
function kpiIndicatorMainRelationMetaFillRows(metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup) {
    
    var html = [], $tbody = $(elem).closest('.reldetail').find('table.mv-record-map-tbl > tbody:eq(0)');
    var delete_btn = plang.get('delete_btn');
    var view_btn = plang.get('view_btn');
    var isAddonForm = false;
    var indicatorRecordMaps = [];
    
    if ($(elem).hasAttr('data-config')) {
        var configObj = $(elem).attr('data-config');
        if (typeof configObj !== 'object') {
            configObj = JSON.parse(html_entity_decode(configObj, "ENT_QUOTES"));
        } 
        if (Number(configObj.isAddonForm) > 0 && configObj.metaInfoIndicatorId != '') {
            var metaInfoIndicatorId = configObj.metaInfoIndicatorId;
            isAddonForm = true;
        }
    }

    for (var i in rows) {
        
        var row = rows[i], rowId = row['id'];
        var $checkRow = $tbody.find('> tr[data-basketrowid="'+rowId+'"]');
        var childRowData = '';
        
        if ($checkRow.length == 0) {
            
            if (isAddonForm) {
                childRowData = JSON.stringify(row);
            }
            
            indicatorRecordMaps.push(rowId);
        }
    }    

    $.ajax({
      type: "post",
      url: "mdform/kpiSaveMetaDmRecordMap2",
      data: {
        mainIndicatorId: '<?php echo $this->indicatorId; ?>',
        indicatorId: lookupMetaDataId,
        indicatorRecordMaps: indicatorRecordMaps
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
            $.ajax({
              type: "post",
              url: "mdform/renderRelationKpiReload",
              data: {
                indicatorId: '<?php echo $this->indicatorId; ?>'
              },
              dataType: "json",                 
              success: function (data) {
                $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").append(data.html)
                $("#kpi-component-<?php echo $this->componentUniqId; ?> .relation-list-view-type").val('LIST').trigger('change');
                Core.unblockUI();
              },
            });             
            new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });            
        } else {
          Core.unblockUI();
        }
      },
    });    
}
function runAddKpiRelatonBp(elem) {
  var $this = $(elem);
  var $dialogName = "dialog-kpi-add-relation-bp";
  
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdform/addRelationHtmlForm",
    data: {
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({message: "Loading...", boxed: true});
    },
    success: function (data) {
      $dialog.empty().append(data.Html);
      
      $dialog.find('input[name="srcIndicator"]').val($('input[name="param[code]"]').val()+' - '+$('textarea[name="param[name]"]').val());

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");
      var runModeButton = '';
        if (data.run_mode === '') {
            runModeButton = ' hide';
        }      

      var buttons = [{
            text: data.save_btn,
            class: 'btn green-meadow btn-sm bp-run-btn bp-btn-saveadd ' + runModeButton,
            click: function(e) {

                var processForm = $dialog.find('form');
                
                processForm.validate({ errorPlacement: function () { } });

                if (processForm.valid()) {
                    var getLastReadDateOrder = $.ajax({
                      type: "post",
                      url: "api/callProcess",
                      data: {
                        processCode: "data_IndicatorMapDV_006",
                        paramData: { 
                            srcIndicatorId: '<?php echo $this->indicatorId; ?>', 
                            trgIndicatorId: processForm.find('input[name="trgIndicatorId"]').val(), 
                            semanticTypeId: 10000010 
                        }
                      },
                      dataType: "json",
                      async: false,
                    });
                    getLastReadDateOrder = getLastReadDateOrder.responseJSON;
                    if (getLastReadDateOrder.status == "success") {
                        $dialog.dialog("close");
                        new PNotify({
                          title: "Success",
                          text: plang.get('msg_save_success'),
                          type: "success",
                          sticker: false
                        });   
                        $.ajax({
                          type: "post",
                          url: "mdform/renderRelationKpiReload",
                          data: {indicatorId: '<?php echo $this->indicatorId; ?>'},
                          dataType: "json",                 
                          success: function (data) {
                            $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").append(data.html)
                            $("#kpi-component-<?php echo $this->componentUniqId; ?> .relation-list-view-type").val('LIST').trigger('change');
                            Core.unblockUI();
                          }
                        });                         
                    } else {
                        new PNotify({
                          title: "Warning",
                          text: 'Алдаа гарлаа',
                          type: "warning",
                          sticker: false
                        });                        
                    }
                }
            }
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          }
        }
      ];

      var dialogWidth = 770,
        dialogHeight = 'auto';

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          position: { my: "top", at: "top+50" },
          closeOnEscape: typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin"
          }
        });
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}
function runKpiRelatonBp(elem, metaDataId, isEdit) {
  var $this = $(elem);
  var workSpaceId = '', workSpaceParams = '';    
  var $dialogName = "dialog-kpi-relation-bp";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName);
  var recordId = $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.reldetail.active').data('rowid');
  
    if (isEdit && !recordId) {
        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: 'Мөр сонгоно уу!',
          type: "warning",
          sticker: false
        });        
        return;
    }  

  if ($this.closest("div.ws-area").length > 0) {
      var wsArea = $this.closest("div.ws-area");
      var workSpaceIdAttr = wsArea.attr("id").split("-");
      workSpaceId = workSpaceIdAttr[2];
      workSpaceParams = $("div.ws-hidden-params", wsArea).find("input[type=hidden]").serialize();
  }  

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: metaDataId,
      dmMetaDataId: 16425125540641,
      isDialog: true,
      isSystemMeta: false,
      oneSelectedRow: {id: recordId},
      responseType: "",
      workSpaceId: workSpaceId,
      workSpaceParams: workSpaceParams,
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({message: "Loading...", boxed: true});
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");
      var runModeButton = '';
        if (data.run_mode === '') {
            runModeButton = ' hide';
        }      

      var buttons = [{
            text: data.run_mode,
            class: 'btn green-meadow btn-sm bp-run-btn bp-btn-saveadd ' + runModeButton,
            click: function(e) {

                var processForm = $dialog.find('form');

                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                    if (bpFormValidate(processForm)) {

                        callWebServiceByMetaRunMode(processForm, $dialogName, processUniqId, e.target, function(){
                            $.ajax({
                              type: "post",
                              url: "mdform/renderRelationKpiReload",
                              data: {indicatorId: '<?php echo $this->indicatorId; ?>'},
                              dataType: "json",                 
                              success: function (data) {
                                $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").append(data.html)
                                $("#kpi-component-<?php echo $this->componentUniqId; ?> .relation-list-view-type").val('LIST').trigger('change');
                                Core.unblockUI();
                              }
                            });                            
                        });

                    } else {
                        bpIgnoreGroupRemove(processForm);
                    }

                } else {
                    bpIgnoreGroupRemove(processForm);
                }
            }
        },
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            var $saveBtn = $(e.target);
            if (window['processBeforeSave_' + processUniqId]($saveBtn) && bpFormValidate(processForm)) {  

                processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: "Loading..."
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {

                      PNotify.removeAll();
                      new PNotify({
                        title: responseData.status,
                        text: responseData.message,
                        type: responseData.status,
                        sticker: false,
                        addclass: "pnotify-center"
                      });
                      window['processAfterSave_' + processUniqId]($saveBtn, responseData.status, responseData);
                      
                      $dialog.dialog("close");

                      $.ajax({
                        type: "post",
                        url: "mdform/renderRelationKpiReload",
                        data: {indicatorId: '<?php echo $this->indicatorId; ?>'},
                        dataType: "json",                 
                        success: function (data) {
                          $("#kpi-component-<?php echo $this->componentUniqId; ?> .recordmap2content-container").append(data.html)
                          $("#kpi-component-<?php echo $this->componentUniqId; ?> .relation-list-view-type").val('LIST').trigger('change');
                          Core.unblockUI();
                        }
                      });                                            
                    }                    
                  },
                  error: function () {
                    alert("Error");
                  }
                });
            }
          }
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          }
        }
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape: typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          }
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}
function deleteKpiRelatonBp(elem, metaDataId) {
    var $this = $(elem);
    var recordId = $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.reldetail.active').data('rowid');
    var userId = $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.reldetail.active').data('userid');
    
    if (!recordId) {
        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: 'Мөр сонгоно уу!',
          type: "warning",
          sticker: false
        });        
        return;
    }
    
    if (userId != '' && userId != '<?php echo Ue::sessionUserKeyId() ?>') {
        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: 'Үүсгэсэн хэрэглэгч устгах боломжтой!',
          type: "warning",
          sticker: false
        });        
        return;
    }
    
    var $dialogName = "dialog-kpi-relation-confirm";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog.empty().append(plang.get("msg_delete_confirm"));
    $dialog.dialog({
      cache: false,
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      title: plang.get('msg_title_confirm'), 
      width: 320,
      height: "auto",
      modal: true,
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
            text: plang.get('yes_btn'),
            class: "btn green-meadow btn-sm",
            click: function () {
                $.ajax({
                    type: "post",
                    url: "api/callProcess",
                    data: {
                      processCode: "data_IndicatorMapDV_005",
                      paramData: { 
                          id: recordId
                      }
                    },
                    beforeSend: function () {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true
                      });
                    },            
                    dataType: "json",
                    success: function (data) {
                        PNotify.removeAll();
                        if (data.status === "success") {
                            new PNotify({
                              title: "Success",
                              text: "Амжилттай устгагдлаа",
                              type: "success",
                              sticker: false
                            });                               
                            $('#kpi-component-<?php echo $this->componentUniqId; ?>').find('.reldetail.active').remove();
                        } else {
                            new PNotify({
                              title: "Warning",
                              text: data.text,
                              type: "warning",
                              sticker: false
                            });                    
                        }
                        Core.unblockUI();
                        $dialog.dialog("close");
                    }
                }); 
            }
        },
        {
            text: plang.get('no_btn'),
            class: "btn blue-madison btn-sm",
            click: function () {
              $dialog.dialog("close");
            }
        }
      ]
    });

    $dialog.dialog("open");
}
function kpiIndicatorRelation2RemoveRows(elem, mapId) {
  var $dialogName = "dialog-relation-config-confirm";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog.empty().append(plang.get('msg_delete_confirm'));
    $dialog.dialog({
      cache: false,
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      title: plang.get('msg_title_confirm'),
      width: 300,
      height: "auto",
      modal: true,
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: plang.get('yes_btn'),
          class: "btn green-meadow btn-sm",
          click: function () {
            $.ajax({
              type: "post",
              url: "mdform/deleteRelationpKpi",
              data: {
                mapId: mapId
              },
              dataType: "json",                 
              success: function (data) {
                $(elem).closest('tr').remove();
                new PNotify({
                    title: 'Success',
                    text: plang.get('msg_save_success'),
                    type: 'success',
                    sticker: false, 
                    addclass: 'pnotify-center'
                });                         
                Core.unblockUI();
              },
            }); 
            $dialog.dialog("close");
          },
        },
        {
          text: plang.get('no_btn'),
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ],
    });

    $dialog.dialog("open");  
}
</script>