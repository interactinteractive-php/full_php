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

    function tnaRenderSidebar(row, index, $uniqId){
      selectedDataRow=row;
      selectedDataRowIndex=index;
      index=(typeof index === 'undefined') ? '' : index;
      var selectedRowUniqueId=row.TIME_BALANCE_HDR_ID;
      var rightSidebarContent=$('.right-sidebar-content-' + $uniqId);

      Core.blockUI({animate: true});
      $.ajax({
        type: 'post',
        url: 'mdtime/getBalanceDetailList',
        data: {timeBalanceId: row.TIME_BALANCE_HDR_ID, balanceDate: row.BALANCE_DATE, employeeKeyId: row.EMPLOYEE_ID, uniqId: '<?php echo $this->uniqId ?>'
                },
        dataType: "json",
        beforeSend: function(){
            },
        success: function(detail){
          var detail=detail['causeType'];
          selectedDataRowDetail=detail['causeType'];
          var EMPLOYEE_NAME=row.LAST_NAME.substring(0, 1) + "." + row.FIRST_NAME;
          var _defferenceTime=(row.DEFFERENCE_TIME!=null ? row.DEFFERENCE_TIME : '0');
          var _originalDefferenceTime=(row.ORIGINAL_DEFFERENCE_TIME!=null ? row.ORIGINAL_DEFFERENCE_TIME : '0');

          var _originalDTimeS=(_originalDefferenceTime).substring(0, 2);
          var _originalDTimeF=(_originalDefferenceTime).substring(0, 1);
          var _originalDTimeFr=(_originalDefferenceTime).substring(1);

          var _defferenceTimeS=(_defferenceTime).substring(0, 2);
          var _defferenceTimeF=(_defferenceTime).substring(0, 1);
          var _defferenceTimeFr=(_defferenceTime).substring(1);

          _originalDefferenceTime=(_originalDTimeF == '.') ? '0' + _originalDefferenceTime : ((_originalDTimeS == '-.') ? _originalDTimeF +
                        '0' + _originalDTimeFr : _originalDefferenceTime);
          _defferenceTime=(_defferenceTimeF == '.') ? '0' + _defferenceTime : ((_defferenceTimeS == '-.') ? _defferenceTimeF + '0' +
                        _defferenceTimeFr : _defferenceTime);

          var sideBarHtml='<div id="' + selectedRowUniqueId + '" class="selectedRowDetail">';
          sideBarHtml+='<input name="timeBalanceHdrId[' + selectedRowUniqueId + ']" data-name="timeBalanceHdrId" type="hidden" value="' +
                        row.TIME_BALANCE_HDR_ID + '">';
          sideBarHtml+='<input name="employeeName[' + selectedRowUniqueId + ']" data-name="employeeName" type="hidden" value="' +
                        EMPLOYEE_NAME + '">';
          sideBarHtml+='<input name="timeBalanceId[' + selectedRowUniqueId + ']" data-name="timeBalanceId" type="hidden" value="' +
                        row.TIME_BALANCE_HDR_ID + '">';
          sideBarHtml+='<input name="employeeId[' + selectedRowUniqueId + ']" data-name="timeBalanceId" type="hidden" value="' +
                        row.EMPLOYEE_ID + '">';
          sideBarHtml+='<input name="employeeKeyId[' + selectedRowUniqueId + ']" data-name="employeeKeyId" type="hidden" value="' +
                        row.EMPLOYEE_KEY_ID + '">';
          sideBarHtml+='<input name="inTime[' + selectedRowUniqueId + ']" data-name="inTime" type="hidden" value="' + row.IN_TIME + '">';
          sideBarHtml+='<input name="outTime[' + selectedRowUniqueId + ']" data-name="outTime" type="hidden" value="' + row.OUT_TIME +
                        '">';
          sideBarHtml+='<input name="balanceDate[' + selectedRowUniqueId + ']" data-name="balanceDate" type="hidden" value="' +
                        row.BALANCE_DATE + '">';
          sideBarHtml+='<input name="clearTime[' + selectedRowUniqueId + ']" data-name="clearTime" type="hidden" value="' +
                        row.CLEAR_TIME + '">';
          sideBarHtml+='<input name="unclearTime[' + selectedRowUniqueId + ']" data-name="unclearTime" type="hidden" value="' +
                        row.UNCLEAR_TIME + '">';
          sideBarHtml+='<input name="defferenceTime[' + selectedRowUniqueId + ']" data-name="defferenceTime" type="hidden" value="' +
                        _defferenceTime + '">';
          sideBarHtml+='<input name="originalDefferenceTime[' + selectedRowUniqueId +
                        ']" data-name="originalDefferenceTime" type="hidden" value="' + _originalDefferenceTime + '">';
          sideBarHtml+='<input name="faultType[' + selectedRowUniqueId + ']" data-name="faultType" type="hidden" value="' +
                        row.FAULT_TYPE + '">';
          sideBarHtml+='<input name="nightTime[' + selectedRowUniqueId + ']" data-name="nightTime" type="hidden" value="' +
                        row.NIGHT_TIME + '">';
          sideBarHtml+='<input name="chBalanceDate[' + selectedRowUniqueId + ']" data-name="chBalanceDate" type="hidden" value="' +
                        row.CH_BALANCE_DATE + '">';
          sideBarHtml+='<input name="activetrIndex" type="hidden" value="' + index + '">';
          sideBarHtml+='<input name="timeBalanceHdrId" type="hidden" value="' + row.TIME_BALANCE_HDR_ID + '">';
          sideBarHtml+='<input name="isMod" type="hidden" value="1">';

          sideBarHtml+='<div class="card light bg-blue-hoki">';
          sideBarHtml+='<div class="card-body">';
          sideBarHtml+='<div class="clearfix w-100">';
          sideBarHtml+='<a href="javascript:;" class="float-left thumb avatar border m-r">';
          sideBarHtml+='<img src="' + row.PICTURE +
                        '" class="rounded-circle" id="sidebar-user-logo" onerror="onUserImageError(this);" style="width: 58px; height:58px;">';
          sideBarHtml+='</a>';
          sideBarHtml+='<div class="clear">';
          sideBarHtml+='<div class="h4 mt5 mb5 text-color-white" style="font-size: 12px !important">';
          sideBarHtml+='<div id="sidebar-user-name">' + EMPLOYEE_NAME + ' (' + row.EMPLOYEE_CODE + ')' + '</div>';
          sideBarHtml+='<div id="sidebar-user-status">' + row.STATUS_NAME + ' - ' + row.POSITION_NAME + '</div>';
          sideBarHtml+='<div id="sidebar-user-type-name">' + (row.TYPE_NAME === null ? '' : row.TYPE_NAME) + '</div>';
          var employeeIntime='';
          if(row.STARTTIME != null && row.ENDTIME != null && row.BALANCE_DATE != null){
            employeeIntime+=row.STARTTIME + ' - ' + row.ENDTIME;
            sideBarHtml+='<div id="sidebar-user-date">' + row.BALANCE_DATE + ' ' + employeeIntime + '</div>';
          }

          sideBarHtml+='</div>';
          sideBarHtml+='</div>';
          sideBarHtml+='</div>';
          sideBarHtml+='</div>';
          sideBarHtml+='</div>';
          var _timeInit='timeInit';
          sideBarHtml+='<div class="panel panel-default bg-inverse grid-row-content">';
          sideBarHtml+='<table class="table sheetTable">';
          sideBarHtml+='<tbody>';
          if(golomtView == '1'){
            _timeInit='secountInit';
          }

          for(var i=0; i < detail.length; i++){
            var _btn='<div class="btn-group">';
            var color="#000";
            var disabled=(detail[i].IS_EDIT === '0') ? 'disabled="disabled"' : '';

            if((detail[i].NAME).toUpperCase() === 'ГАДУУР АЖИЛЛАСАН' ||
                    (detail[i].NAME).toUpperCase() === 'ТОМИЛОЛТ' ||
                    (detail[i].NAME).toUpperCase() === 'ЧӨЛӨӨТЭЙ' ||
                    (detail[i].NAME).toUpperCase() === 'ЭЭЛЖИЙН АМРАЛТ' ||
                    (detail[i].NAME).toUpperCase() === 'БАЯРААР АЖИЛЛАСАН' ||
                    (detail[i].NAME).toUpperCase() === 'ТАСАГ ШИЛЖСЭН' ||
                    (detail[i].NAME).toUpperCase() === 'ИЛҮҮ ЦАГ' ||
                    (detail[i].NAME).toUpperCase() === 'ХУВИЙН ШАЛТГААН' ||
                    (detail[i].NAME).toUpperCase() === 'ӨВЧТЭЙ'
                    ){
              var descriptionBtnClassName='btn btn-sm btn-success';
              var descriptionBtnClickEvent='onclick="clickCallDialogOpen(this)"';
              if(detail[i].DESCRIPTION_CAUSE_DTL == 0){
                descriptionBtnClassName='btn btn-sm grey-cascade';
                descriptionBtnClickEvent='';
              }
              _btn+='<button type="button" class="' + descriptionBtnClassName + ' employeeOutWorkBtn ml0 mr0" ' +
                                descriptionBtnClickEvent + ' title="' + detail[i].NAME + '"><i class="fa fa-list"></i> </button>';
            }
            if((detail[i].NAME).toUpperCase() === 'ОРЛОН ХАВСАРСАН'){
              if(parseFloat(detail[i].COUNTT) > 0){
                _btn+='<button type="button" ' + disabled +
                                    ' class="btn btn-sm red employeeFillInForBtn ml0 mr0" title="' + detail[i].NAME +
                                    '"><i class="fa fa-list"></i> </button>';
              }
              color=(parseFloat(detail[i].COUNTT) > 0) ? '#F00' : "#000";
            }
            if((detail[i].NAME).toUpperCase() === 'ОРЛОН ХАВСАРСАН'){
              TEMPED_FILLINFORDATA={
                EMPLOYEE_NAME: EMPLOYEE_NAME,
                EMPLOYEE_ID: row.EMPLOYEE_ID,
                EMPLOYEE_KEY_ID: row.EMPLOYEE_KEY_ID,
                BALANCE_DATE: row.BALANCE_DATE,
                BALANCE_DTL_NAME: detail[i].NAME,
                BALANCE_TYPE_ID: detail[i].CAUSE_TYPE_ID
              };
            }

            _btn+='<button type="button" ' + disabled + ' id=' + i +
                            ' class="btn btn-sm employeeBalanceDescription ml0 mr0" title="Тайлбар"><i class="fa fa-font"></i> </button>';
            _btn+='<input type="hidden" name="description[]" data-description="description"  class="causeDescriptionClassName_' +
                            detail[i].CAUSE_TYPE_ID + '" value="' + (detail[i].DESCRIPTION === undefined ? '' : detail[i].DESCRIPTION) +
                            '">';
            _btn+='</div>';

            var ctypeDisable='';
            //row.WFM_STATUS_CODE === 'confirmedbyceo'

            if((detail[i].CODE === '1014' || detail[i].CODE === '1004') && row.DEFFERENCE_TIME < 0)
              ctypeDisable=' disabled';
          
            sideBarHtml+='<tr class="causeClassName_' + detail[i].CAUSE_TYPE_ID + '">';
            sideBarHtml+='<td class="left-padding hide"></td>';
            sideBarHtml+='<td class="left-padding hide"></td>';
            sideBarHtml+='<td style="width: 200px; color:' + color + ' !important;" class="left-padding">' + detail[i].NAME + '</td>';
            sideBarHtml+='<td>';
            sideBarHtml+='<input name="cause_type_id[' + selectedRowUniqueId + '][]" data-name="cause_type_id" type="hidden" value="' +
                            detail[i].CAUSE_TYPE_ID + '">';
            sideBarHtml+='<input name="cause_type_value[' + selectedRowUniqueId +
                            '][]" data-name="cause_type_value" type="hidden" value="' + detail[i].V_TIME + '">';
            sideBarHtml+='<input ' + disabled + ' class="cause_type_value_display ' + _timeInit + ' "' + ctypeDisable +
                            ' id="cause_type_code_' + detail[i].CODE +
                            '" data-name="cause_type_value_display" name="cause_type_value_display[' + selectedRowUniqueId +
                            '][]" placeholder="hh:mm" type="text" value="' + minutToTime(detail[i].V_TIME) +
                            '" onchange="setMinut(this);">';
            sideBarHtml+='</td>';
            sideBarHtml+='<td>' + _btn + '</td>';
            sideBarHtml+='</tr>';
          }

          sideBarHtml+='<tr class="hidden">';
          sideBarHtml+='<td style="!important; width: 200px;" class="left-padding">Экспорт</td>';
          sideBarHtml+='<td style="!important; padding-right:2px;"></td>';
          sideBarHtml+=
                        '<td><button style="border-radius:0 !important;" type="button" class="btn btn-sm employeeBalanceExportListExcel ml0 mr0" title="Экспорт"><i class="fa fa-file-excel-o"></i> </button></td>';
          sideBarHtml+='</tr>';
          if(!golomtView){
            sideBarHtml+='<tr>';
            sideBarHtml+=
                            '<td style="background-color:#95A5A6 !important; width: 200px; color:#FFF !important;" class="left-padding">Дэлгэрэнгүй</td>';
            sideBarHtml+='<td style="background-color:#95A5A6 !important; padding-right:2px;"></td>';
            sideBarHtml+='<td>';
            sideBarHtml+='<div class="btn-group">';
            sideBarHtml+=
                            '<button style="border-radius:0 !important;" type="button" class="btn btn-sm grey-cascade employeeBalanceDetail ml0 mr0" title="Дэлгэрэнгүй"><i class="fa fa-list-alt"></i> </button>';
            sideBarHtml+=
                            '<button type="button" class="btn btn-sm blue-madison ml0 mr0 isLock" title="Түгжих"><i class="fa fa-lock"></i> / <i class="fa fa-unlock-alt"></i></button>';
            sideBarHtml+='</div>';
            sideBarHtml+='</td>';
            sideBarHtml+='</tr>';
          }

          sideBarHtml+='<tr>';
          sideBarHtml+=
                        '<td style="background-color:#3b9c96 !important; width: 200px; color:#FFF !important;" class="left-padding">Батлах</td>';
          sideBarHtml+='<td style="background-color:#3b9c96 !important; padding-right:2px;"></td>';
          sideBarHtml+='<td>';
          sideBarHtml+='<div class="btn-group">';

          sideBarHtml+='<?php echo $this->balanceBtn['item']; ?>';
          /*sideBarHtml += '<button';
           sideBarHtml += ' style="border-radius:0 !important; border-left: 3px solid #3598dc;" '
           sideBarHtml += ' type="button" ';
           sideBarHtml += ' class="btn btn-sm green  confirmTimeBalance ml0 mr0" '
           sideBarHtml += ' title="Хадгалах"><i class="fa fa fa-save"></i> </button>';
           sideBarHtml += '<button style="border-radius:0 !important; border-left: 3px solid #3598dc;" type="button" class="btn btn-sm green-meadow employeeConfirmBtn ml0 mr0" title="Батлах"><i class="fa fa-check-square-o"></i> </button>';*/
          sideBarHtml+='</div>';
          sideBarHtml+='</td>';
          sideBarHtml+='</tr>';

          sideBarHtml+='<tr id ="EMPLOYEE_DESCRIPTION_' + row.EMPLOYEE_KEY_ID + '_' + row.CH_BALANCE_DATE + '" class="hidden">';
          sideBarHtml+=
                        '<td style="background-color:#f36a5a !important; width: 200px; color:#FFF !important;" class="left-padding">CASE Дэлгэрэнгүй</td>';
          sideBarHtml+='<td style="background-color:#f36a5a !important; padding-right:2px;"></td>';
          sideBarHtml+='<td>';
          sideBarHtml+=
                        '<button style="border-radius:0 !important; border-left: 3px solid #26a69a;" type="button" class="btn btn-sm red employeeBalanceDetailDescription ml0 mr0" title="Дэлгэрэнгүй"><i class="fa fa-list-alt"></i></button>';
          sideBarHtml+='</td>';
          sideBarHtml+='</tr>';
          sideBarHtml+='</tbody>';
          sideBarHtml+='</table>';
          sideBarHtml+='</div>';
          sideBarHtml+='<div class="panel panel-default bg-inverse grid-plan-time-more"></div>';
          sideBarHtml+='</div>';

          rightSidebarContent.find(".selectedRowDetail").hide();
          rightSidebarContent.html(sideBarHtml);
          
          showRightSidebarContent($uniqId);

          Core.unblockUI();
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
      }).done(function(){
          Core.unblockUI();
          Core.initTimeInput(rightSidebarContent);
          Core.initDateTimeInput(rightSidebarContent)
        }
      });
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