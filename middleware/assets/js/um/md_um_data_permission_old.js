/* global go, Core, msg_saving_block, PNotify */

var MdUmDataPermission=function(){

  var actionInterval;

  var initEvent=function(roleId, userId){
    $("#userPermissionTableId").change(function(){
      if($("#userPermissionTableId").val().length > 0){
        Core.blockUI({
          message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
          boxed: true
        });
        $.ajax({
          type: 'post',
          url: 'mdobject/dataValueViewer',
          data: {
            metaDataId: $("#userPermissionTableId").val(),
            ignorePermission: 1, 
            dvDefaultCriteria: {roleid: roleId, userid: userId}
          },
          success: function(html){
            $("#div-user-permission-map-datagrid").html(html);
          },
          error: function(jqXHR, exception){
            Core.unblockUI();
          }
        }).complete(function(){
          Core.unblockUI();
        });
      } else {
        $("#div-user-permission-map-datagrid").html("");
      }
    });

    $("#addUserDataPermissionBn").click(function(){
      if($("#userPermissionTableId").val().length === 0){
        PNotify.removeAll();
        new PNotify({
          title: 'Info',
          text: 'Та эрх тохируулах хүснэгтээ сонгоно уу.',
          type: 'info',
          sticker: false
        });
      } else {
        getDataPermissionModal();
      }
    });

    $("#removeUserDataPermissionBn").click(function(){
      var selectedRow=$("#objectdatagrid-" + $("#userPermissionTableId").val()).datagrid(
              'getSelections');
      if(selectedRow.length === 0){
        PNotify.removeAll();
        new PNotify({
          title: 'Info',
          text: 'Та хасах мөрөө сонгоно уу.',
          type: 'info',
          sticker: false
        });
      } else {
        var data=[];
        $.each(selectedRow, function(){
          data.push(this.id);
        });
        Core.blockUI({
          message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
          boxed: true
        });
        $.ajax({
          type: 'post',
          url: 'mdum/removeUserDataPermission',
          dataType: "json",
          data: {
            data: data
          },
          success: function(response){
            if(response.status === 'success'){
              PNotify.removeAll();
              new PNotify({
                title: response.status,
                text: 'Үйлдэл амжилттай боллоо',
                type: 'success',
                sticker: false
              });
              dataViewReload($("#userPermissionTableId").val());
            } else {
              PNotify.removeAll();
              new PNotify({
                title: response.status,
                text: 'Үйлдэл амжилтгүй боллоо.',
                type: 'error',
                sticker: false
              });
            }
          },
          error: function(jqXHR, exception){
            Core.unblockUI();
          }
        }).complete(function(){
          Core.unblockUI();
        });
      }
    });
  };

  var getDataPermissionModal=function(){
    $.ajax({
      type: 'post',
      url: 'mdum/getDataPermissionModal',
      success: function(html){
        renderDataPermissionMdoal(html);
      },
      error: function(jqXHR, exception){
        Core.unblockUI();
      }
    }).complete(function(){
      Core.unblockUI();
    });
  };

  var renderDataPermissionMdoal=function(html){
    if(!$("#dialog-user-data-permission-selection").length){
      $('<div id="dialog-user-data-permission-selection"></div>').appendTo('body');
    }

    var $dialog=$("#dialog-user-data-permission-selection");

    $dialog.empty().html(html);
    Core.initSelect2($dialog);
    Core.initUniform($dialog);

    if($("#userPermissionTableId option:selected").attr("ishierarchy") == 1){
      $dialog.find("#isHierarchy").parents(".form-group").show();
      $dialog.find("#actionId").parents(".form-group").show();
      $dialog.find("#actionCheckBoxesAllDiv").hide();
    } else {
      $dialog.find("#isHierarchy").parents(".form-group").hide();
      $dialog.find("#actionId").parents(".form-group").hide();
      $dialog.find("#actionCheckBoxesAllDiv").show();
    }
    var grid=$("#div-user-permission-selection-datagrid");

    $dialog.dialog({
      appendTo: "body",
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      title: "Тохиргоо",
      width: '80%',
      position: {my: 'center', at: 'top+100'},
      height: 'auto',
      modal: true,
      close: function(){
        $dialog.empty().dialog('destroy');
      },
      open: function(){
        Core.blockUI({
          message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
          boxed: true
        });
        $.ajax({
          type: 'post',
          url: 'mdobject/dataValueViewer',
          data: {
            metaDataId: $("#userPermissionTableId option:selected").attr("id"), 
            ignorePermission: 1, 
            dvDefaultCriteria: {roleid: roleId, userid: userId}
          },
          success: function(html){
            grid.html(html);
            initActionCheckBoxesEventInterval();
            initActionAllFireEvent();
          },
          error: function(jqXHR, exception){
            Core.unblockUI();
          }
        }).complete(function(){
          Core.unblockUI();
        });
      },
      buttons: [
        {text: 'Хадгалах', class: 'btn btn-sm blue', click: function(){
            saveUserDataPermission(roleId, userId, $dialog);
          }},
        {text: 'Хаах', class: 'btn btn-sm blue-hoki', click: function(){
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
    ;
    $dialog.dialog('open');
    $dialog.dialogExtend("maximize");
  };

  var initActionCheckBoxesEventInterval=function(){
    actionInterval=setInterval(initActionCheckBoxesEvent, 1000);
  };

  var initActionCheckBoxesEvent=function(){
    if($('.actionCheckBoxes').length > 0){
      var dvId=$("#userPermissionTableId option:selected").attr("id"),
              $dataGrid=$('.div-objectdatagrid-' + dvId),
              options=$("#objectdatagrid-" + dvId).datagrid('options');

      options.onLoadSuccess=function(data){
        initActionFireEvent();
        Core.initInputType($dataGrid);
      };

      initActionFireEvent();
      clearInterval(actionInterval);
    }
  };

  var initActionFireEvent=function(){
    $('.actionCheckBoxes').click(function(){
      var $this=$(this);
      $this.toggleClass('btn-success');
      $this.find('i').toggleClass('fa-square-o');
      $this.find('i').toggleClass('fa-check-square-o');
      return false;
    });
  };

  var initActionAllFireEvent=function(){
    var $actionCheckBoxesAll=$('.actionCheckBoxesAll');
    $actionCheckBoxesAll.removeClass('btn-success');
    $actionCheckBoxesAll.find('i').removeClass('fa-check-square-o');
    //$actionCheckBoxesAll.off().on('click', function(){
    $actionCheckBoxesAll.on('click', function(){
      var $this=$(this);
      $this.toggleClass('btn-success');
      $this.find('i').toggleClass('fa-square-o');
      $this.find('i').toggleClass('fa-check-square-o');
      var $childAction=$('button[actionId=' + $this.attr('childActionid') + ']');
      if($this.hasClass('btn-success')){
        $childAction.addClass('btn-success');
        $childAction.find('i').addClass('fa-check-square-o');
        $childAction.find('i').removeClass('fa-square-o');
      } else {
        $childAction.removeClass('btn-success');
        $childAction.find('i').removeClass('fa-check-square-o');
        $childAction.find('i').addClass('fa-square-o');
      }
    });
  };

  var saveUserDataPermission=function(roleId, userId, $dialog){
    var dvId=$("#userPermissionTableId option:selected").attr("id"),
            dataGrid=window['objectdatagrid_' + dvId],
            $dataGridBody=$('.div-objectdatagrid-' + dvId).find('.datagrid-view2 .datagrid-body'),
            rows = dataGrid.datagrid('getSelections');

    if(rows.length > 0){
      $.each(rows, function(k, v){
        if(typeof v.actionname !== "undefined"){
          var index=getRowIndexDataViewByElement(dataGrid, v);
          rows[k]['actionname']=$dataGridBody.find('tr[datagrid-row-index="' + index + '"] td[field="actionname"] .datagrid-cell').html();
        }
      });

      Core.blockUI({
        message: 'Боловсруулж байна түр хүлээнэ үү...',
        boxed: true
      });

      var isHierarchy=0;
      if($("#userPermissionTableId option:selected").attr("ishierarchy") == 1){
        isHierarchy=$dialog.find("#isHierarchy").is(':checked') ? 1 : 0;
      }
      $.ajax({
        type: 'post',
        url: 'mdum/saveUserDataPermission',
        dataType: "json",
        data: {roleId: roleId,
          userId: userId,
          dvId: dvId,
          strId: $("#userPermissionTableId option:selected").attr("param"),
          dvCode: $("#userPermissionTableId").val(),
          actionId: $dialog.find("#actionId").val(),
          isHierarchy: isHierarchy,
          rows: rows},
        success: function(data){
          if(data.status === 'success'){
            PNotify.removeAll();
            new PNotify({
              title: data.status,
              text: 'Үйлдэл амжилттай боллоо',
              type: 'success',
              sticker: false
            });
            dataViewReload($("#userPermissionTableId").val());
            $dialog.dialog('close');
          } else {
            PNotify.removeAll();
            new PNotify({
              title: data.status,
              text: 'Үйлдэл амжилтгүй боллоо.',
              type: 'error',
              sticker: false
            });
          }
        },
        error: function(jqXHR, exception){
          Core.unblockUI();
        }
      }).complete(function(){
        Core.unblockUI();
      });
    } else {
      PNotify.removeAll();
      new PNotify({
        title: '',
        text: 'Мөр сонгоно уу.',
        type: 'error',
        sticker: false
      });
    }
  };

  return {
    init: function(pRoleId, pUserId){
      initEvent(pRoleId, pUserId);
    }
  };
}();