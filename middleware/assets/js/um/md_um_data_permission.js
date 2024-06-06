/* global go, Core, msg_saving_block, PNotify, selectedTableRow */

var MdUmDataPermission=function(){

  var $userPermissionTable, roleId, userId;

  var initEvent=function(){

    var $dialogDataPermission=$('#dialog-data-permission');
    var dvId = selectedTableRow['userdatapermissiondvid'];
    
    Core.initSelect2($dialogDataPermission);
    Core.initUniform($dialogDataPermission);

    if(selectedTableRow['ishierarchy'] == 1){
      $dialogDataPermission.find("#isHierarchy").parents(".form-group").show();
    } else {
      $dialogDataPermission.find("#isHierarchy").parents(".form-group").hide();
    }

    $userPermissionTable=$('#userpermission-table');

    Core.blockUI({
      message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
      boxed: true
    });
    $.ajax({
      type: 'post',
      url: 'mdobject/dataValueViewer',
      data: {
        metaDataId: dvId, 
        viewType: 'detail', 
        ignorePermission: 1, 
        uriParams: '{"roleid": "'+roleId+'","userid": "'+userId+'"}', 
        dataGridDefaultHeight: $(window).height() - 160
      },
      success: function(dataHtml){
        var $permissionGrid = $("#div-user-permission-map-datagrid");  
        $permissionGrid.empty().append('<div id="object-value-list-'+dvId+'">'+dataHtml+'</div>');
        initActionAllFireEvent();
        initActionFireEvent($permissionGrid);
      },
      error: function(jqXHR, exception){
        Core.unblockUI();
      }
    }).complete(function(){
      Core.unblockUI();
    });

    $("#removeUserDataPermissionBn").click(function(){
      if(typeof selectedTableRow !== "undefined"){
        var selectedRow=$("#objectdatagrid-" + selectedTableRow['userdatapermissiondvid']).datagrid('getSelections');
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
              data: data,
              roleId: roleId,
              userId: userId,
              strId: selectedTableRow['id']
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
                dataViewReload(selectedTableRow['userdatapermissiondvid']);
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
        }
      }
    });

    $("#enableUserDataPermissionBn").click(function(){
      if(typeof selectedTableRow !== "undefined"){
        var selectedRow=$("#objectdatagrid-" + selectedTableRow['userdatapermissiondvid']).datagrid('getSelections');
        if(selectedRow.length === 0){
          PNotify.removeAll();
          new PNotify({
            title: 'Info',
            text: 'Та идэвхжүүлэх мөрөө сонгоно уу.',
            type: 'info',
            sticker: false
          });
        } else {
          var data=[];
          $.each(selectedRow, function(){
            if (this.hasOwnProperty('isactive') && this.isactive == '0') {
              data.push(this.id);
            }
          });
          if(data.length === 0){
            PNotify.removeAll();
            new PNotify({
              title: 'Info',
              text: 'Та идэвхгүй мөр сонгоно уу.',
              type: 'info',
              sticker: false
            });            
          }
          Core.blockUI({
            message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
            boxed: true
          });
          $.ajax({
            type: 'post',
            url: 'mdum/removeUserDataPermission',
            dataType: "json",
            data: {
              data: data,
              roleId: roleId,
              userId: userId,
              strId: selectedTableRow['id']
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
                dataViewReload(selectedTableRow['userdatapermissiondvid']);
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
        }
      }
    });

    $("#isActivePermission").click(function(){
      Core.blockUI({
        message: "Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү.",
        boxed: true
      });
      var checkValue = $(this).is(':checked') ? '1' : '';
      $.ajax({
        type: 'post',
        url: 'mdobject/dataValueViewer',
        data: {
          metaDataId: dvId, 
          viewType: 'detail', 
          ignorePermission: 1, 
          uriParams: '{"roleid": "'+roleId+'","userid": "'+userId+'","isActive": "'+checkValue+'"}', 
          dataGridDefaultHeight: $(window).height() - 160
        },
        success: function(dataHtml){
          var $permissionGrid = $("#div-user-permission-map-datagrid");  
          $permissionGrid.empty().append('<div id="object-value-list-'+dvId+'">'+dataHtml+'</div>');
          initActionAllFireEvent();
          initActionFireEvent($permissionGrid);
        },
        error: function(jqXHR, exception){
          Core.unblockUI();
        }
      }).complete(function(){
        Core.unblockUI();
      });      
    });
  };

  var initActionFireEvent=function($body){
    //$body.find('.actionCheckBoxes').off();
    $body.on('click', '.actionCheckBoxes', function(){
      var $this=$(this), $parentTr=$this.closest('tr');
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

  var saveUserDataPermission=function(roleId, userId, $dialog){
    var dvId=selectedTableRow['userdatapermissiondvid'],
            dataGrid=window['objectdatagrid_' + dvId],
            $dataGridBody=$('.div-objectdatagrid-' + dvId).find('.datagrid-view2 .datagrid-body'),
            rows = dataGrid.datagrid('getSelections');

    if(rows.length > 0){
      $.each(rows, function(k, v){
        if (typeof v.actionname !== "undefined") {
            
            var index = getRowIndexDataViewByElement(dataGrid, v);
            var $rowIndex = $dataGridBody.find('tr[datagrid-row-index="' + index + '"]');
            
            if ($rowIndex.length) {
                var actionCellHtml = $rowIndex.find('td[field="actionname"] .datagrid-cell').html();
            } else {
                var actionCellHtml = $dataGridBody.find('tr[node-id="' + rows[k]['id'] + '"] td[field="actionname"] .datagrid-cell').html();
            }
          
            rows[k]['actionname'] = actionCellHtml;
        }
      });

      Core.blockUI({
        message: 'Боловсруулж байна түр хүлээнэ үү...',
        boxed: true
      });

      var isHierarchy=0;
      if(selectedTableRow['ishierarchy'] == 1){
        isHierarchy=$dialog.find("#isHierarchy").is(':checked') ? 1 : 0;
      }
      $.ajax({
        type: 'post',
        url: 'mdum/saveUserDataPermission',
        dataType: "json",
        data: {
          roleId: roleId,
          userId: userId,
          dvId: dvId,
          strId: selectedTableRow['id'],
          dvCode: selectedTableRow['userdatapermissiondvid'],
          actionId: $dialog.find("#actionId").val(),
          isHierarchy: isHierarchy,
          rows: rows
        },
        success: function(data){
          PNotify.removeAll();
          if(data.status === 'success'){
            new PNotify({
              title: data.status,
              text: 'Үйлдэл амжилттай боллоо',
              type: 'success',
              sticker: false
            });
            dataViewReload(selectedTableRow['userdatapermissiondvid']);
//            $dialog.dialog('close');
          } else {
            new PNotify({
              title: data.status,
              text: data.message,
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
    saveUserDataPermission(roleId, userId, $('#dialog-data-permission'));
  };


  var saveUserDataPermissionFromBasket=function(rows){
      
    Core.blockUI({
      message: 'Боловсруулж байна түр хүлээнэ үү...',
      boxed: true
    });
      
    var dvId=selectedTableRow['userdatapermissiondvid'];

    $.ajax({
      type: 'post',
      url: 'mdum/saveUserDataPermission',
      dataType: "json",
      data: {
        roleId: roleId,
        userId: userId,
        dvId: dvId,
        strId: selectedTableRow['id'],
        dvCode: selectedTableRow['userdatapermissiondvid'],
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
          dataViewReload(selectedTableRow['userdatapermissiondvid']);
        } else {
          PNotify.removeAll();
          new PNotify({
            title: data.status,
            text: data.message,
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
    init: function(pRoleId, pUserId){
      roleId=pRoleId;
      userId=pUserId;
      initEvent();
    },
    saveDataPermission: function(){
      saveDataPermission();
    },
    saveUserDataPermissionFromBasket: function(rows){
      saveUserDataPermissionFromBasket(rows);
    }
  };
}();

function saveUserDataPermissionFromBasket(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup){
  MdUmDataPermission.saveUserDataPermissionFromBasket(rows);
}