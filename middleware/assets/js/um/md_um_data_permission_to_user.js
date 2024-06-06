/* global go, Core, msg_saving_block, PNotify, selectedTableRow */

var MdUmDataPermissionToUser=function(){

  var $userPermissionTable,
          dbStructureId,
          recordId;
  
  var initEvent=function(){
    
    var $dialogDataPermission=$('#dialog-data-permission');
      
    Core.initSelect2($dialogDataPermission);
    Core.initUniform($dialogDataPermission);

    initActionFireEvent();

    $userPermissionTable=$('#userpermission-table');

    Core.blockUI({
      message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
      boxed: true
    });
    $.ajax({
      type: 'post',
      url: 'mdobject/dataValueViewer',
      data: {
        metaDataId: 1488526977648690, 
        ignorePermission: 1, 
        dvDefaultCriteria: {dbStructureId: dbStructureId, filterRecordId: recordId}
      },
      success: function(html){
        $("#div-user-permission-map-datagrid").html(html);
        initActionAllFireEvent();
      },
      error: function(jqXHR, exception){
        Core.unblockUI();
      }
    }).complete(function(){
      Core.unblockUI();
    });

    $dialogDataPermission.find('.savePermissionToUserBtn').click(function(){
      saveUserDataPermission();
    });
    
    $("#removeUserDataPermissionBnFinance").click(function(){
        var dvId = 1488526977648690,
            dataGrid = window['objectdatagrid_' + dvId];
        var selectedRow = dataGrid.datagrid('getSelections');

        if(selectedRow.length === 0){
          PNotify.removeAll();
          new PNotify({
            title: 'Info',
            text: 'Та хасах мөрөө сонгоно уу.',
            type: 'info',
            sticker: false
          });
        } else {
            
        var $dialogName = 'dialog-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }            
        $("#" + $dialogName).empty().append('Та устгахдаа итгэлтэй байна уу?');
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 350,
            height: "auto",
            modal: true,
            close: function () {
                $("#" + $dialogName).empty().dialog('close');
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $("#" + $dialogName).dialog('close');
                    Core.blockUI({
                      message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
                      boxed: true
                    });
                    $.ajax({
                      type: 'post',
                      url: 'mdum/removeUserDataPermissionFin',
                      dataType: "json",
                      data: {
                        data: selectedRow,
                        strId: dbStructureId,
                        recordId: recordId
                      },
                      success: function(response){
                        PNotify.removeAll();  
                        if(response.status === 'success'){
                          new PNotify({
                            title: response.status,
                            text: 'Үйлдэл амжилттай боллоо',
                            type: 'success',
                            sticker: false
                          });
                          dataViewReload(1488526977648690);
                        } else {
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
                    }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');                    
        }
    });    
  };

  var initActionFireEvent=function(){
    var $body = $('body');  
    //$body.find('.actionCheckBoxes').off();
    $body.on('click', '.actionCheckBoxes', function(){
      var $this = $(this), $parentTr = $this.closest('tr');
      if(!$parentTr.hasClass('datagrid-row-selected')){
        $parentTr.trigger('click');
      }
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
      $.each($childAction, function(){
        var $thisAction=$(this),
                $parentTr=$thisAction.closest('tr');
        if(!$parentTr.hasClass('datagrid-row-selected')){
          $parentTr.trigger('click');
        }
      });
    });
  };

  var saveUserDataPermission=function(){
    var dvId=1488526977648690,
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

      $.ajax({
        type: 'post',
        url: 'mdum/saveUserDataPermissionToUser',
        dataType: "json",
        data: {
          dbStructureId: dbStructureId,
          recordId: recordId,
          rows: rows
        },
        success: function(data){
          if(data.status === 'success'){
            PNotify.removeAll();
            new PNotify({
              title: data.status,
              text: 'Үйлдэл амжилттай боллоо',
              type: 'success',
              sticker: false
            });
            dataViewReload(1488526977648690);
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

  var saveDataPermission=function(){
    saveUserDataPermission(dbStructureId, recordId, $('#dialog-data-permission'));
  };


  var saveUserDataPermissionToUserFromBasket=function(rows, isUser){
    $.ajax({
      type: 'post',
      url: 'mdum/saveUserDataPermissionToUser',
      dataType: "json",
      data: {
        dbStructureId: dbStructureId,
        recordId: recordId,
        rows: rows,
        isUser: isUser
      },
      success: function(data){
        if(data.status === 'success'){
          PNotify.removeAll();
          new PNotify({
            title: data.status,
            text: 'Үйлдэл амжилттай боллоо',
            type: 'success',
            sticker: false
          });

          dataViewReload(1488526977648690);
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
  };

  return {
    init: function(pdbStructureId, precordIdd){
      dbStructureId=pdbStructureId;
      recordId=precordIdd;
      initEvent();
    },
    saveDataPermission: function(){
      saveDataPermission();
    },
    saveUserDataPermissionToUserFromBasket: function(rows, isUser){
      saveUserDataPermissionToUserFromBasket(rows, isUser);
    }
  };
}();

function saveUserDataPermissionToUserFromBasket(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup){
  MdUmDataPermissionToUser.saveUserDataPermissionToUserFromBasket(rows, true);
}
function saveUserDataPermissionToRoleFromBasket(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup){
  MdUmDataPermissionToUser.saveUserDataPermissionToUserFromBasket(rows);
}