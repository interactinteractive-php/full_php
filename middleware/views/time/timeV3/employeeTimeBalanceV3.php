<?php
if (!$this->isAjax) {
    ?>
    <div class="col-md-12 employeeTimeBalance_<?php echo $this->uniqId ?>" id="employeeTimeBalance">
      <div class="card light shadow tna-card">
        <div class="card-header card-header-no-padding header-elements-inline">
          <div class="card-title">
            <i class="fa fa-pencil-square"></i> <?php echo $this->title; ?>
          </div>
          <div class="caption buttons ml10">
              <?php echo $this->balanceBtn['all']; ?>              
          </div>
          <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body xs-form row">
<?php
    }
?>    
      <div class="row" id="tnaTimeBalanceWindow<?php echo $this->uniqId ?>">
        <input type="hidden" id="selected-datagrid-<?php echo $this->uniqId ?>" value="0"/>
        <div class="col-md-12 center-sidebar center-sidebar-<?php echo $this->uniqId ?> employeeTimeBalance_<?php echo $this->uniqId ?>" id="employeeTimeBalance">
          <div class="form-body">
            <div class="row">
              <form id="tnaTimeBalanceForm<?php echo $this->uniqId ?>" class="form-horizontal xs-form" method="post">
                <fieldset class="collapsible">
                  <legend>Ерөнхий мэдээлэл</legend>
                  <input type="hidden" id="searchClickedTR" value="">
                  <div class="row">
                    <div class="col-md-10 col-sm-10">
                      <div class="row">
                        <div class="col-md-5 col-sm-5">
                          <div class="form-group row fom-row">
                              <?php echo Form::label(array('text' => 'Алба хэлтэс', 'for' => 'departmentId',
                                  'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                            <div class="col-md-8">
                                <?php
                                echo Form::multiselect(
                                        array(
                                            'name' => 'departmentId[]',
                                            'id' => 'balanceDepartmentId_' . $this->uniqId,
                                            'multiple' => 'multiple',
                                            'class' => 'form-control form-control-sm input-xxlarge balanceDepartmentId_' . $this->uniqId,
                                            'data' => $this->departmentList,
                                            'op_value' => 'ID',
                                            'op_text' => 'NAME',
                                            'required' => 'required',
                                            'value' => $this->sessionDepartmentId
                                        )
                                );
                                ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                          <div class="row">
                            <div class="col-md-6 col-sm-6">
                              <div class="form-group row fom-row">
<?php echo Form::label(array('text' => $this->lang->line('start_date'), 'for' => 'startDate',
    'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                <div class="col-md-8">
                                  <div class="dateElement input-group ml5" data-section-path="bookDate">
<?php echo Form::text(array('name' => 'startDate', 'id' => 'startDate', 'class' => 'form-control form-control-sm dateInit',
    'value' => Date::currentDate('Y-m-d'), 'required' => 'required')); ?>
                                    <span class="input-group-btn">
                                      <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                    </span>
                                  </div>
                                </div> 
                              </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                              <div class="form-group row fom-row">
                                      <?php echo Form::label(array('text' => $this->lang->line('end_date'),
                                          'for' => 'startDate', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
                                <div class="col-md-9">
                                  <div class="dateElement input-group" data-section-path="bookDate">
<?php echo Form::text(array('name' => 'endDate', 'id' => 'endDate', 'class' => 'form-control form-control-sm dateInit',
    'value' => Date::currentDate('Y-m-d'), 'required' => 'required')); ?>
                                    <span class="input-group-btn">
                                      <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row fom-row <?php echo (isset($this->golomtView) && $this->golomtView) ? '' : 'hidden'; ?>">
                                    <?php echo Form::label(array('text' => 'Ажилтны төлөв',
                                        'for' => 'employeeStatus', 'class' => 'col-form-label col-md-3')); ?>
                                <div class="col-md-9">
                                    <?php
                                    echo Form::multiselect(
                                            array(
                                                'name' => 'employeeStatus[]',
                                                'id' => 'employeeStatus_' . $this->uniqId,
                                                'multiple' => 'multiple',
                                                'class' => 'form-control input-xs input-xxlarge employeeStatus_' . $this->uniqId,
                                                'data' => $this->searchTnaEmployeeStatusList,
                                                'op_value' => 'STATUS_ID',
                                                'op_text' => 'STATUS_NAME',
                                            )
                                    );
                                    ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>                                                    
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-2">
                      <div class="form-group row fom-row">
                        <div class="col-md-12">
                          <div class="input-icon dateElement right">
                              <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success',
                                  'onclick' => 'getBalanceList(\'' . $this->uniqId . '\')', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                          </div>
                        </div>
                      </div> 
                      <div class="form-group row fom-row hidden">
                        <div class="col-md-12">
                          <div class="input-icon dateElement right">
<?php echo Form::button(array('class' => 'btn btn-sm btn-circle default balanceClear', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('clear_btn'))); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
            <div class="row mergeCelltnaEmployeeBalance">
              <div class="col-md-12 mt10">
                <a class="btn btn-secondary btn-sm btn-circle value-grid-merge-cell-tnaEmployeeBalance default active" title="Merge cell" href="javascript:;"><i class="fa fa-columns"></i></a>  
                <!--<a class="btn btn-secondary btn-sm btn-circle grid-expand-cell-tnaEmployeeBalance default active" title="Merge cell" href="javascript:;"><i class="fa fa-columns"></i></a>-->  
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mt10 xs-form">
                <div class="jeasyuiTheme3 tna-balance-data-grid-div-<?php echo $this->uniqId ?> <?php echo $this->uniqId ?>" style="width: 100%;">
                  <table id="tna-balance-data-grid-<?php echo $this->uniqId ?>" style="width: 100%;"></table>
                </div>                                    
                <div id="datagridselectedRowsDetail"></div>
              </div> 
            </div>
          </div> 
        </div>
        <div class="right-sidebar right-sidebar-<?php echo $this->uniqId ?>" data-status="opened">
            <div class="stoggler sidebar-right sidebar-right-<?php echo $this->uniqId ?>" onclick="tnaTimeBalanceStoggler(this, '<?php echo $this->uniqId ?>')">
                <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
                <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
            </div>
            <form id="tnaTimeBalanceForm" class="form-horizontal" method="post">
                <div class="right-sidebar-content-<?php echo $this->uniqId ?>"></div>
            </form>
        </div>
            <?php echo Form::hidden(array('id' => 'currentSelectedRowIndex-' . $this->uniqId, 'value' => '')) ?>
            <?php echo Form::hidden(array('id' => 'balanceType-' . $this->uniqId, 'value' => $this->timeBalanceViewType)) ?>
        </div>        
        <div id="loadAccount"></div>
        <div class="form-actions mt15 form-actions-btn">
            <div class="row">
                <div class="col-md-9">
                    <?php echo $this->balanceBtn['all']; ?>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
<?php
if (!$this->isAjax) {
    ?>            
        </div>
      </div>
    </div>
    <?php
}
?> 

<div id="dialog-fillInFor-employee"></div>
<div id="dialogDescription"></div>

<style type='text/css'>

  #employeeTimeBalance .form-horizontal .col-form-label {
      padding-top: 0px;
  }

  .ui-multiselect-checkboxes label input {
      top: 3px; 
      margin-right: 5px;
  }

  .ui-state-active, .ui-state-focus, .ui-state-hover, .ui-widget-content .ui-state-active, .ui-widget-content .ui-state-focus, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-active, .ui-widget-header .ui-state-focus, .ui-widget-header .ui-state-hover {
      font-weight: 600;
      color: #FFFFFF;
  }

  .ui-multiselect-menu label {
      margin: 0;
  }

  .ui-multiselect-checkboxes label {
      padding-top:1px;
      padding-bottom:5px;
  }

  #tnaTimeBalanceWindow<?php echo $this->uniqId ?> .datagrid-body .datagrid-cell a.btn-secondary {
      float: right;
      border-bottom-style: none;
      border-right-style: none;
      border-top-style: none;
      border-radius: 50%;
      border-left-color: #333;
      margin-top: 1px;
  }

  .background-color-grey {
      background-color: #D3D3D3 !important;
  } 
</style>

<script type="text/javascript">

    var currentDate='<?php echo Date::currentDate("Y-m-d"); ?>';
    var currentUserId='<?php echo Ue::sessionUserId(); ?>';
    var golomtView='<?php echo (isset($this->golomtView) && $this->golomtView) ? '1' : '0' ?>';
    var _employeeCodeBalanceWindow='<?php echo (isset($this->golomtView) && $this->golomtView) ? 'Домайн' : 'Ажилтны код' ?>';
    var _isAdminApproved='<?php echo (isset($this->isAdmin) && $this->isAdmin) ? '1' : '0' ?>';
    windowId="#tnaTimeBalanceWindow<?php echo $this->uniqId ?>";
    var isMod = true;

    $(function() {            
        
        $('.mergeCelltnaEmployeeBalance').hide();
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');

        $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js").done(function(script, textStatus){
          $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function(script, textStatus){
            $('.balanceDepartmentId_<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
            $('#ui-multiselect-balanceDepartmentId_<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

            $('.causeTypeId_<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
            $('#ui-multiselect-causeTypeId_<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

            $('.employeeStatus_<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
            $('#ui-multiselect-employeeStatus_<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

            $('.ui-multiselect-menu').attr('style', 'width: 360px');
          });
        });
        $('.radio').find('span').addClass('mt0');

        var balanceDepartmentVa_<?php echo $this->uniqId ?>=$('.balanceDepartmentId_<?php echo $this->uniqId ?>').val();
        if(balanceDepartmentVa_<?php echo $this->uniqId ?> != '') {
            $.ajax({
                type: 'post',
                url: 'mdtime/getDepartmentGroupList',
                data: {departmentId: balanceDepartmentVa_<?php echo $this->uniqId ?>},
                dataType: "json",
                beforeSend: function() {},
                success: function(detail) {
                    Core.unblockUI();

                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');

                    $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js").done(function(script, textStatus) {
                        $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function(script, textStatus) {
                            $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>lanceC').empty();
                            $('.ui-multiselect', '.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').addClass('ui-state-disabled').attr('aria-disabled','true').attr('diabled', 'diabled');
                            
                            var ticketGroup=true;
                            var html = '<select name="groupId[]" multiple="multiple" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title="">';
                            
                            if (detail.length > 0) {
                                $.each(detail, function(key, value){
                                  html+='<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                                });
                                ticketGroup=false;
                            }
                            
                            html+='</select>';

                            if (ticketGroup) {
                                html = '<select disabled = "disabled"  name="groupId[]" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title=""></select>';
                            }
                            
                            $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').html(html);
                            $('.groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                            $('#ui-multiselect-groupIdTimeEmployeeBalance-option-0').parent().parent().remove();
                        });
                    });

                },
                error: function() {
                    Core.unblockUI();
                    new PNotify({
                        title: 'Error',
                        text: 'error',
                        type: 'error',
                        sticker: false
                    });
                }
            });
        }
    });

    $('.balanceDepartmentId_<?php echo $this->uniqId ?>').on('change', function(){
      var thisval=$(this).val();
      $.ajax({
        type: 'post',
        url: 'mdtime/getDepartmentGroupList',
        data: {departmentId: thisval},
        dataType: "json",
        beforeSend: function(){
            },
        success: function(detail){
          Core.unblockUI();
          $("head").append(
                        '<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
          $("head").append(
                        '<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');
          $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js").done(function(script, textStatus){
            $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function(script, textStatus){
              $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').empty();
              $('.ui-multiselect', '.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').addClass('ui-state-disabled').attr('aria-disabled', 'true').
                                attr('diabled', 'diabled');
              var ticketGroup=true;

              var html=
                                '<select name="groupId[]" multiple="multiple" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title="">';
              if(detail.length > 0){
                $.each(detail, function(key, value){
                  html+='<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                });
                ticketGroup=false;
              }
              html+='</select>';

              if(ticketGroup){
                html=
                                    '<select disabled = "disabled" name="groupId[]" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title=""></select>';
              }

              $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').html(html);
              $('.groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).
                                multiselectfilter();
              $('#ui-multiselect-groupIdTimeEmployeeBalance-option-0').parent().parent().remove();
            });
          });
        },
        error: function(){
          Core.unblockUI();
          new PNotify({
            title: 'Error',
            text: 'error',
            type: 'error',
            sticker: false
          });
        }
      })
    });

    function onUserImageError(source){
      source.src="assets/core/global/img/user.png";
      source.onerror="";
      return true;
    }
    
    function groupSelectableGrid(metaDataCode, chooseType, elem, rows){
      var _selectedRowId='';
      var _selectedRowName='';
      var _selectedRowCode='';
      if(rows.length > 0){
        $.each(rows, function(key, row){
          if(key == 0){
            _selectedRowId=row.id;
            _selectedRowCode=row.code;
            _selectedRowName=row.name;
          }
          else {
            _selectedRowId=_selectedRowId + ',' + row.id;
            _selectedRowCode=_selectedRowCode + ',' + row.code;
            _selectedRowName=_selectedRowName + ',' + row.name;
          }
        });
      }
      $("#groupId", windowId).val(_selectedRowId);
      $("#groupCode_displayField", windowId).val(_selectedRowCode);
      $("#groupName_nameField", windowId).val(_selectedRowName);
    }

    $('.balanceClear', windowId).on('click', function(){
      $("#groupId", windowId).val('');
      $("#groupCode_displayField", windowId).val('');
      $("#groupName_nameField", windowId).val('');
    });

    $('.value-grid-merge-cell-tnaEmployeeBalance', windowId).on('click', function(){
      var mergeBtn=$(this);
      if(mergeBtn.hasClass("active")){
        mergeBtn.removeClass("active").addClass("init-merge-cell");
        $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('reload');
      } else {
        var isMergeColumn=JSON.parse('["BALANCE_DATE1", "EMPLOYEE_NAME"]');
        $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid("autoMergeCells", isMergeColumn);
        mergeBtn.addClass("active").removeClass("init-merge-cell");
      }
    });

    $('.grid-expand-cell-tnaEmployeeBalance', windowId).on('click', function(){
      var mergeBtn=$(this);
      var index=1;

      if(mergeBtn.hasClass("active")){
        mergeBtn.removeClass("active").addClass("init-merge-cell");
        var count=$('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('getRows').length;

        for(var i=0; i < count; i++){
          $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('expandRow', i);
          index++;
        }

      } else {
        mergeBtn.addClass("active").removeClass("init-merge-cell");
        var count=$('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('getRows').length;

        for(var i=0; i < count; i++){
          $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('collapseRow', i);
          index++;
        }
      }
    });

    $("body").on("click", "#tnaTimeBalanceForm" + " .isLock", function(){
      var rows=$('body #tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('getSelections');

      if(rows.length == 0){
        new PNotify({
          title: 'Анхааруулга',
          text: 'Мөр сонгоно уу?',
          type: 'warning'
        });
        return;
      }
      var isDiffUser=false;
      $.each(rows, function(key, row){
        if(row.LOCK_USER_ID != null){
          if(row.LOCK_USER_ID != '<?php echo Ue::sessionUserId(); ?>'){
            isDiffUser=true;
          }
        }
      });

      if(isDiffUser){
        new PNotify({
          title: 'Анхааруулга',
          text: 'Өөр хэрэглэгч түгжсэн эсвэл ямар нэг нүд нүд сонгоогүй байна',
          type: 'warning'
        });
      } else {
        if(userSessionIsFull()){
          new PNotify({
            title: 'Анхааруулга',
            text: 'Өөрчлөлт хийх хязгаар хэтэрсэн байна.',
            type: 'warning'
          });
        } else {
          callIsLockBalanceDialog(rows);
        }
      }
    });

    function getLogTimeAttendanceData_<?php echo $this->uniqId ?>(element, timeBalanceHdrId){
      var dialogName='#dialog-timebalanceHdrLogData';
      if(!$(dialogName).length){
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
      }

      $.ajax({
        type: 'post',
        url: 'mdtime/timebalanceHdrLogData',
        dataType: "json",
        data: {timeBalanceHdrId: timeBalanceHdrId},
        beforeSend: function(){
          Core.blockUI({
            animate: true
          });
        },
        success: function(data){
          var html='';
          html='<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1">';
          html+='</thead>';
          html+='<tr>';
          html+='<td>№</td>';
          html+='<td>Өөрчлөсөн өдөр</td>';
          html+='<td>Ирсэн цаг</td>';
          html+='<td>Явсан цаг</td>';
          html+='<td>Өөрчлөсөн хэрэглэгч</td>';
          html+='</tr>';
          html+='</thead>';
          html+='<tbody>';
          var _index=1;
          $.each(data, function(index, row){
            html+='<tr>';
            html+='<td>' + _index + '</td>';
            html+='<td>' + row.CREATED_DATE + '</td>';
            html+='<td>' + ((row.IN_TIME) ? row.IN_TIME : '') + '</td>';
            html+='<td>' + ((row.OUT_TIME) ? row.OUT_TIME : '') + '</td>';
            html+='<td>' + row.CREATED_USER + '</td>';
            html+='</tr>';
            _index++;
          });

          html+='</tbody>';
          html+='</table>';

          $(dialogName).empty().html(html);
          $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: data.Title,
            width: '430',
            height: 'auto',
            modal: true,
            close: function(){
              $(dialogName).empty().dialog('destroy').remove();
            },
            buttons: [
              {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function(){
                  $(dialogName).empty().dialog('destroy').remove();
                }
              }]
          });
          $(dialogName).dialog('open');
          Core.unblockUI();
        },
        error: function(){
          Core.unblockUI();
          PNotify.removeAll();
          new PNotify({
            title: 'Error',
            text: 'error',
            type: 'error',
            sticker: false
          });
        }
      }).done(function(){
        Core.initInputType();
      });
    }

</script>