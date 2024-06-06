function changeWFStatus(workflowId, wfStatusId, objectCode, sourceId, objectName, columnName, statusName, statusColor, logTable, element, rowType) {
    var $dialogName = 'dialog-wfmstatus';
    var datas = {
                WORKFLOW_ID:workflowId,
                STATUS_ID:wfStatusId,
                OBJECT_CODE:objectCode,
                SOURCE_ID:sourceId,
                OBJECT_NAME:objectName,
                COLUMN_NAME:columnName,
                STATUS_NAME:statusName,
                STATUS_COLOR:statusColor,
                LOG_TABLE:logTable,
                ROW_TYPE:rowType
    };
    $.ajax({
        type: 'post',
        url: 'mdworkflow/changeStatus',
        data: datas,
        beforeSend:function(){
            App.blockUI(element);
        },
        success:function(data){
            $("#"+$dialogName).empty().html(data);  
            $("#"+$dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('wf_change_status'),
                width: 650,
                height: "auto",
                modal: true,     
                close:function(){
                    $("#"+$dialogName).empty().dialog('close');
                }, 
                buttons: [
                    {text: plang.get('save_btn'), class:'btn green', click: function(){
                        $("#wfChangeStatus-form").validate({ errorPlacement: function(){} });
                        if ($("#wfChangeStatus-form").valid()) {
                            $.ajax({
                                type: 'post',
                                url: 'mdworkflow/saveChangeStatus',
                                data: $("#wfChangeStatus-form").serialize(),
                                dataType: "json",
                                beforeSend: function(){
                                    show_msg_saving_block();
                                },
                                success: function(data) {
                                    $.unblockUI();
                                    if (data.status === 'success') {
                                        $.pnotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $(element).css("background-color", data.wfStatusColor);
                                        if ($.trim(data.isWriteStatus) == '1') {
                                            $(element).empty().html('<i class="icon-link"></i> ' + data.wfStatusName);
                                            $(element).attr("onclick", "changeWFStatus("+data.workflowId+", "+data.wfStatusId+", '"+data.objectCode+"', "+data.sourceId+", '"+data.objectName+"', '"+data.columnName+"', '"+data.wfStatusName+"', '"+data.wfStatusColor+"', '"+data.logTable+"', this,'"+data.row_type+"');");
                                        } else {
                                            $(element).empty().html(data.wfStatusName);
                                            $(element).removeAttr("onclick").css('cursor', 'text');
                                        }
                                        $("#"+$dialogName).dialog('close');
                                    } else {
                                        $.pnotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: plang.get('close_btn'), class:'btn', click: function(){
                        $("#"+$dialogName).dialog('close');
                    }}
                ]        
            });
            $("#"+$dialogName).dialog('open');
            App.unblockUI(element);
        },
        error:function(){
            alert("Error");
        }
    });
}
function changeWFStatusV3(workflowId, wfStatusId, objectCode, sourceId, objectName, columnName, statusName, statusColor, logTable, element) {
    Core.blockUI({target: element});
    var $dialogName = 'dialog_wfmstatus';
    if (!$("div#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdworkflow/changeStatusV3',
        data: {
            WORKFLOW_ID: workflowId,
            STATUS_ID: wfStatusId,
            OBJECT_CODE: objectCode,
            SOURCE_ID: sourceId,
            OBJECT_NAME: objectName,
            COLUMN_NAME: columnName,
            STATUS_NAME: statusName,
            STATUS_COLOR: statusColor,
            LOG_TABLE: logTable
        },
        dataType: 'json',
        success: function(data) {
            $("#" + $dialogName).html(data.Html).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                title: data.wf_change_status,
                autoOpen: false,
                width: 650,
                height: 'auto',
                modal: true,
                close: function() {
                    $(this).empty().dialog('close');
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green', click: function() {
                            $("#wfChangeStatus-form").validate({
                                errorPlacement: function(){
                                },
                                highlight: function(element){
                                    $(element).closest('.form-group').addClass('has-error');
                                }
                            });
                            if ($("#wfChangeStatus-form").valid()) {
                                Core.blockUI();
                                $.ajax({
                                    type: 'post',
                                    url: 'mdworkflow/saveChangeStatus',
                                    data: $("#wfChangeStatus-form").serialize(),
                                    dataType: "json",
                                    success: function(data) {
                                        Core.unblockUI();
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                            $(element).css("background-color", data.wfStatusColor);
                                            if ($.trim(data.isWriteStatus) === '1') {
                                                $(element).html('<i class="fa fa-link"></i> ' + data.wfStatusName);
                                                $(element).attr("onclick", "changeWFStatusV3(" + workflowId + ", " + data.wfStatusId + ", '" + objectCode + "', " +
                                                        sourceId + ", '" + objectName + "', '" + columnName + "', '" + data.wfStatusName + "', '" + data.wfStatusColor +
                                                        "', '" + logTable + "', this);");
                                            } else {
                                                $(element).html(data.wfStatusName);
                                                $(element).removeAttr("onclick").css('cursor', 'text');
                                            }
                                            $("#" + $dialogName).dialog('close');
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    },
                                    error: function() {
                                        alert("Error");
                                    }
                                });
                            }
                        }
                    },
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $(this).dialog('close');
                        }
                    }]
            }).dialog('open');
            Core.unblockUI(element);
        },
        error: function() {
            alert("Error");
        }
    });
}
function changeWFStatusMiniV3(workflowId, wfStatusId, objectCode, sourceId, objectName, columnName, statusName, statusColor, logTable, element, url) {
    Core.blockUI({target: element});
    var $dialogName = 'dialog_wfmstatus';
    if (!$("div#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var postdata =  {
            WORKFLOW_ID: workflowId,
            STATUS_ID: wfStatusId,
            OBJECT_CODE: objectCode,
            SOURCE_ID: sourceId,
            OBJECT_NAME: objectName,
            COLUMN_NAME: columnName,
            STATUS_NAME: statusName,
            STATUS_COLOR: statusColor,
            LOG_TABLE: logTable
        };
    $.ajax({
        type: 'post',
        url: 'mdworkflow/changeStatusMiniV3',
        data: postdata,
        dataType: 'json',
        success: function(data) {
            $("#" + $dialogName).html(data.Html).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                title: data.wf_change_status,
                autoOpen: false,
                width: 650,
                height: 'auto',
                modal: true,
                close: function() {
                    $(this).empty().dialog('close');
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-xs green', click: function() {
                        $("#wfChangeStatus-form").validate({
                          errorPlacement: function(){
                          },
                          highlight: function(element){
                            $(element).closest('.form-group').addClass('has-error');
                          }
                        });
                        if($("#wfChangeStatus-form").valid()) {
                          Core.blockUI();
                          if(url.length != '') {
                            $.ajax({
                              type  : 'post',
                              url   : url,
                              data  : $("#wfChangeStatus-form").serialize() ,
                              dataType: "json",
                              success: function(data) { 
                                if(data.status === 'success') {
                                  $.ajax({
                                    type: 'post',
                                    url: 'mdworkflow/saveChangeStatus',
                                    data: $("#wfChangeStatus-form").serialize(),
                                    dataType: "json",
                                    success: function(data) {
                                      if(data.status === 'success'){
                                        new PNotify({
                                          title: 'Success',
                                          text: data.message,
                                          type: 'success',
                                          sticker: false
                                        });
                                        $(element).css("background-color", data.wfStatusColor);
                                        if($.trim(data.isWriteStatus) === '1') {
                                          $(element).html('<i class="fa fa-link"></i> ' + data.wfStatusName);
                                          $(element).attr("onclick", "changeWFStatusMiniV3(" + workflowId + ", " +
                                                  data.wfStatusId + ", '" + objectCode + "', " +
                                                  sourceId + ", '" + objectName + "', '" + columnName + "', '" +
                                                  data.wfStatusName + "', '" + data.wfStatusColor +
                                                  "', '" + logTable + "', this, '"+url+"');");
                                        } 
                                        else {
                                          $(element).html(data.wfStatusName);
                                          $(element).removeAttr("onclick").css('cursor', 'text');
                                        }


                                        Core.unblockUI();
                                        $("#" + $dialogName).dialog('close');
                                      } else {
                                        new PNotify({
                                          title: 'Error',
                                          text: data.message,
                                          type: 'error',
                                          sticker: false
                                        });
                                      }
                                    },
                                    error: function(){
                                      alert("Error");
                                    }
                                  });
                                }
                                else {
                                  PNotify.removeAll(); 
                                  new PNotify({
                                    title   :  data.title,
                                    text    :  data.message,
                                    type    :  data.status,
                                    sticker : false
                                  });
                                  $.unblockUI();
                                }
                              },
                              error: function() {
                                alert("Error");
                              }
                            });
                          }
                          else {
                            $.ajax({
                              type: 'post',
                              url: 'mdworkflow/saveChangeStatus',
                              data: $("#wfChangeStatus-form").serialize(),
                              dataType: "json",
                              success: function(data) {
                                if(data.status === 'success'){
                                  new PNotify({
                                    title: 'Success',
                                    text: data.message,
                                    type: 'success',
                                    sticker: false
                                  });
                                  $(element).css("background-color", data.wfStatusColor);
                                  if($.trim(data.isWriteStatus) === '1') {
                                    $(element).html('<i class="fa fa-link"></i> ' + data.wfStatusName);
                                    $(element).attr("onclick", "changeWFStatusMiniV3(" + workflowId + ", " +
                                            data.wfStatusId + ", '" + objectCode + "', " +
                                            sourceId + ", '" + objectName + "', '" + columnName + "', '" +
                                            data.wfStatusName + "', '" + data.wfStatusColor +
                                            "', '" + logTable + "', this, '"+url+"');");
                                  } 
                                  else {
                                    $(element).html(data.wfStatusName);
                                    $(element).removeAttr("onclick").css('cursor', 'text');
                                  }


                                  Core.unblockUI();
                                  $("#" + $dialogName).dialog('close');
                                } else {
                                  new PNotify({
                                    title: 'Error',
                                    text: data.message,
                                    type: 'error',
                                    sticker: false
                                  });
                                }
                              },
                              error: function(){
                                alert("Error");
                              }
                            });
                          }
                        }
                      }
                    },
                    {text: data.close_btn, class: 'btn btn-xs grey-cascade', click: function() {
                            $(this).dialog('close');
                        }
                    }]
            }).dialog('open');
            Core.unblockUI(element);
        },
        error: function() {
            alert("Error");
        }
    });
}