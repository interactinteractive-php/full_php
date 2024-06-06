<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title"><i class="fa fa-clock-o"></i> <?php echo $this->title; ?></div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
<?php
}
?>
            <div class="row">
                <div class="col-md-12">
                    <div id="tnaTimeEmployeePlanWindow" class="col-md-12 tnaTimeEmployeePlan-<?php echo $this->uniqId ?>" timeplan-uniqId="<?php echo $this->uniqId ?>">
                        <div class="col-md-12 center-sidebar">
                            <div class="form-body xs-form pl0">
                                <form id="tnaTimeEmployeePlanForm" class="form-horizontal" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset class="collapsible">
                                                <legend>Ерөнхий мэдээлэл</legend>
                                                <input type="hidden" id="searchClickedTR" value="">
                                                <input type="hidden" id="onlyWorkingDay" name="onlyWorkingDay"  value="0">
                                                <input type="hidden" name="golomtView"  value="<?php echo ($this->golomtView) ? '1' : '0' ?>">
                                                <input type="hidden" id="onlyPositionWorkingDays" name="onlyPositionWorkingDays"  value="0">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group row fom-row">
                                                            <?php echo Form::label(array('text' => 'Алба хэлтэс', 'for' => 'departmentId', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                                            <div class="col-md-8">
                                                                <?php
                                                                echo Form::multiselect(
                                                                  array(
                                                                    'name'      => 'departmentId[]',
                                                                    'id'        => 'departmentId',
                                                                    'multiple'  => 'multiple',
                                                                    'class'     => 'form-control form-control-sm input-xxlarge',
                                                                    'data'      => $this->departmentList,
                                                                    'op_value'  => 'ID',
                                                                    'op_text'   => 'NAME',
                                                                    'required'  => 'required',
                                                                    'value'     => $this->sessionDepartmentId
                                                                  )
                                                                );
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row fom-row">
                                                            <?php 
                                                            $labelname = 'Ээлжийн бүлэг';
                                                            if ($this->golomtView)
                                                                $labelname = 'Ирц бүртгэл /Бусад/';

                                                            echo Form::label(array('text' => $labelname, 'for' => 'startDate', 'class' => 'col-form-label col-md-4')); ?>
                                                            <div class="col-md-8 groupIdTimeEmployeePlanC">
                                                                <?php
                                                                echo Form::multiselect(
                                                                        array(
                                                                            'name' => 'groupId[]',
                                                                            'id' => 'groupIdTimeEmployeePlan-'.$this->uniqId,
                                                                            'multiple' => 'multiple',
                                                                            'disabled' => 'disabled',
                                                                            'class' => 'form-control input-xs input-xxlarge',
                                                                            'data' => $this->searchTnaGroupList,
                                                                            'op_value' => 'ID',
                                                                            'op_text' => 'NAME',
                                                                        )
                                                                );
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group row fom-row">
                                                            <?php echo Form::label(array('text' => 'Огноо', 'for' => 'startDate', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
                                                            <div class="col-md-4">
                                                                <div class="input-icon right">
                                                                    <?php
                                                                    echo Form::select(
                                                                      array(
                                                                        'name'      => 'planYear',
                                                                        'id'        => 'planYear',
                                                                        'class'     => 'form-control select2 form-control-sm input-xxlarge',
                                                                        'data'      => Info::getRefYearList(),
                                                                        'op_value'  => 'YEAR_CODE',
                                                                        'op_text'   => 'YEAR_NAME',
                                                                        'required'  => 'required',
                                                                        'value'     => Date::currentDate('Y')
                                                                      )
                                                                    );
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="input-icon right">
                                                                    <?php
                                                                    echo Form::select(
                                                                        array(
                                                                            'name'      => 'planMonth',
                                                                            'id'        => 'planMonth',
                                                                            'class'     => 'form-control select2 form-control-sm input-xxlarge',
                                                                            'data'      => Info::getRefMonthList(),
                                                                            'op_value'  => 'MONTH_CODE',
                                                                            'op_text'   => 'MONTH_NAME',
                                                                            'required'  => 'required',
                                                                            'value'     => Date::currentDate('m')
                                                                        )
                                                                    );
                                                                    ?>
                                                                </div>
                                                            </div> 

                                                        </div> 
                                                        <div class="form-group row fom-row">
                                                            <?php echo Form::label(array('text' => 'Албан тушаал', 'for' => 'Албан тушаал', 'class' => 'col-form-label col-md-3')); ?>
                                                            <div class="col-md-4">
                                                                <div class="input-icon right">
                                                                    <?php
                                                                    echo Form::select(
                                                                      array(
                                                                        'name'      => 'positionId',
                                                                        'id'        => 'positionId',
                                                                        'class'     => 'form-control select2 form-control-sm input-xxlarge',
                                                                        'data'      => $this->positionList,
                                                                        'op_value'  => 'POSITION_ID',
                                                                        'op_text'   => 'POSITION_NAME'
                                                                      )
                                                                    );
                                                                    ?>
                                                                </div>
                                                                <div class="input-icon right mt5 positionGroup">
                                                                    <?php
                                                                    echo Form::select(
                                                                      array(
                                                                        'name'      => 'positionGroupId',
                                                                        'id'        => 'positionGroupId',
                                                                        'class'     => 'form-control select2 form-control-sm input-xxlarge',
                                                                        'data'      => '',
                                                                        'op_value'  => '',
                                                                        'op_text'   => ''
                                                                      )
                                                                    );
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="input-icon right">
                                                                    <?php
                                                                    echo Form::text(
                                                                        array(
                                                                            'name'      => 'stringValue',
                                                                            'id'        => 'stringValue',
                                                                            'class'     => 'form-control form-control-sm input-xxlarge',
                                                                            'placeholder' => 'Утгын хайлт (овог, нэр)'
                                                                        )
                                                                    );
                                                                    ?>
                                                                </div>
                                                            </div> 
                                                        </div> 
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group row fom-row">
                                                            <div class="col-md-12">
                                                                <div class="input-icon dateElement right">
                                                                    <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success balanceReload', 'data-view-id' => $this->uniqId, 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group row fom-row hidden">
                                                            <div class="col-md-12">
                                                                <div class="input-icon dateElement right">
                                                                    <?php echo Form::button(array('class' => 'btn btn-sm btn-circle default timePlanClear', 'value' => $this->lang->line('clear_btn'))); ?>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group row fom-row <?php echo (isset($this->golomtView) && $this->golomtView) ? '' : 'hidden'; ?>">
                                                            <?php echo Form::label(array('text' => 'Ажилтны төлөв', 'for' => 'employeeStatus', 'class' => 'col-form-label col-md-4')); ?>
                                                            <div class="col-md-8">
                                                                <?php
                                                                    echo Form::multiselect(
                                                                            array(
                                                                                'name' => 'employeeStatus[]',
                                                                                'id' => 'employeeStatusPlan-'.$this->uniqId,
                                                                                'multiple' => 'multiple',
                                                                                'class' => 'form-control input-xs input-xxlarge',
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
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-12 mt10">
                                        <form id="tnaBalancePlanGridForm">
                                            <input type="hidden" name="planIdSet" id="planIdSet">
                                            <input type="hidden" name="wfmStatusCodeSet" id="wfmStatusCodeSet">
                                            <input type="hidden" name="wfmStatusIdSet" id="wfmStatusIdSet">

                                            <div id="tnaBalanceGrid" style="min-height: inherit !important; max-height: inherit !important;"> <!-- class="table-scrollable"> -->
                                            </div>
                                        </form>
                                    </div> 
                                </div>      
                            </div> 
                        </div>
                        <div class="right-sidebar" data-status="closed">
                            <div class="stoggler sidebar-right">
                                <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
                                <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
                            </div>
                            <div class="right-sidebar-content">
                                <div id="setSideBarDefaultContent"></div>
                                <div class="grid-row-content isVerifyBtn" id="setSideBarAddTimePlan"></div>

                                <div class="panel panel-default bg-inverse additional-panel hidden">
                                    <table class="table sheetTable">
                                        <tbody>
                                            <tr class="isVerifyBtn">
                                                <td class="left-padding"><?php echo Form::label(array('text' => 'Хэлтсийн нийт энэ сарын цаг', 'for' => 'departmentCurrentMonthTime'));?></td>
                                                <td>
                                                    <?php
                                                    echo Form::text(
                                                        array(
                                                            'name'      => 'departmentCurrentMonthTime',
                                                            'id'        => 'departmentCurrentMonthTime',
                                                            'class'     => 'form-control longInit',
                                                            'disabled'  => 'disabled',
                                                            'value'     => ''
                                                        )
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="isVerifyBtn">
                                                <td class="left-padding"><?php echo Form::label(array('text' => 'Хэлтсийн үлдэгдэл цаг', 'for' => 'departmentCurrentTime'));?></td>
                                                <td>
                                                    <?php
                                                    echo Form::text(
                                                        array(
                                                            'name'      => 'departmentCurrentTime',
                                                            'id'        => 'departmentCurrentTime',
                                                            'class'     => 'form-control longInit',
                                                            'disabled'  => 'disabled',
                                                            'value'     => ''
                                                        )
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="isVerifyBtn">
                                                <td class="left-padding"><?php echo Form::label(array('text' => 'Ажилтны нийт энэ сарын цаг', 'for' => 'employeeCurrentMonthTime'));?></td>
                                                <td>
                                                    <?php
                                                    echo Form::text(
                                                        array(
                                                            'name'      => 'employeeCurrentMonthTime',
                                                            'id'        => 'employeeCurrentMonthTime',
                                                            'class'     => 'form-control longInit',
                                                            'value'     => ''
                                                        )
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="isVerifyBtn">
                                                <td class="left-padding"><?php echo Form::label(array('text' => 'Ажилтны нийт цаг', 'for' => 'employeeCurrentTime'));?></td>
                                                <td>
                                                    <?php
                                                    echo Form::text(
                                                        array(
                                                            'name'      => 'employeeCurrentTime',
                                                            'id'        => 'employeeCurrentTime',
                                                            'class'     => 'form-control longInit',
                                                            'disabled'  => 'disabled',
                                                            'value'     => ''
                                                        )
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>        
            </div>        
            <div id="loadAccount"></div>
<?php if (!$this->isAjax) { ?>      
        </div>
    </div>
</div>
<?php
}
?>
<div id="dialogDescription"></div>
<style type='text/css'>
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
    body {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
</style>
<script type="text/javascript">
    
    var verifEmployee = '<?php echo $this->sessionVerifEmployee; ?>';
    var currentDate = '<?php echo Date::currentDate("Y-m-d");?> 00:00:00';
    var elementPosition = $('.right-sidebar-content').offset();
    var depreciationWindowId = "#depreciation";
    
    var _golomtViewEmployeePlan = <?php echo (defined('CONFIG_TNA_GOLOMT') ? json_encode(CONFIG_TNA_GOLOMT) : 'false'); ?>;
    var _tempedSelectedPlanRows = [];
    
    $(window).scroll(function() {
        if($(window).scrollTop() > elementPosition.top) {
            $('.right-sidebar-content').addClass("fixedRightSideBar");
        } else {
            $('.right-sidebar-content').removeClass("fixedRightSideBar");
        }    
    });
    
    $(window).resize(function(){
        timePlanResizeDtlTable();
    });
    
    $(function () {
        $('.positionGroup').hide();

        $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js" ).done(function( script, textStatus ) {
            $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function( script, textStatus ) {
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');
                $('#departmentId').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-departmentId-option-0').parent().parent().remove(); 

                $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove(); 
               
                $('#employeeStatusPlan-<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-employeeStatusPlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove(); 
                
            });
        });


        Core.initNumberInput();
        if (parseInt(verifEmployee) == 1) {
            $('.isVerif').removeClass('hidden');
            $('.isVerifyBtn').addClass('hidden');
        }
        else  {
            $('.isVerif').addClass('hidden');
            $('.isVerifyBtn').removeClass('hidden');
        }

        $('.additional-panel').addClass('hidden');
          ///renderSidebar(tnaTimeEmployeePlanWindowId, "");
        $(".cancelTimeBalance").on("click", function () {
            var dialogName = '#cancelDialog';
            if (!$(dialogName).length) {
              $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            $(dialogName).html('Та итгэлтэй байна уу').dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулга',
                width: 'auto',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: '<?php echo $this->lang->line('yes_btn'); ?>', class: 'btn blue btn-sm', click: function () {
                      $(dialogName).dialog('close');
                      window.location = URL_APP + 'mdtime/timeBalance';
                    }},
                    {text: '<?php echo $this->lang->line('no_btn'); ?>', class: 'btn grey-cascade btn-sm', click: function () {
                      $(dialogName).dialog('close');
                    }}
                ]
            }).dialog('open');
        });
        
        var departmentId = $('#departmentId').val();
        
        if (departmentId != '') {
            $.ajax({
                type: 'post',
                url: 'mdtime/getDepartmentGroupList',
                data: {departmentId: departmentId},
                dataType: "json",
                beforeSend: function() {},
                success: function(detail) {
                    Core.unblockUI();
                    
                    $('.groupIdTimeEmployeePlanC').empty();

                    var ticketDepGroup = true;

                    var html = '<select id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" multiple="multiple" class="form-control input-xs input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
                    if (detail.length > 0) {
                        $.each(detail, function (key, value) {
                            html += '<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                        });
                        ticketDepGroup = false;
                    }
                    html += '</select>'; 

                    if (ticketDepGroup) {
                        html = '<select disabled = "disabled" id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" class="form-control input-xs input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option></select>'; 
                    }

                    $('.groupIdTimeEmployeePlanC').html(html);
                    $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                    $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove(); 
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
    
    
    $('#departmentId').on('change', function () {
        var thisval = $(this).val();
        $.ajax({
            type: 'post',
            url: 'mdtime/getDepartmentGroupList',
            data: {departmentId: thisval},
            dataType: "json",
            beforeSend: function() {},
            success: function(detail) {
                Core.unblockUI();
                $('.groupIdTimeEmployeePlanC').empty();

                var ticketDepGroup = true;
                var html = '<select id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" class="form-control input-xs input-xxlarge" multiple="multiple" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
                if (detail.length > 0) {
                    $.each(detail, function (key, value) {
                        html += '<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                    });
                    ticketDepGroup = false;
                }
                html += '</select>'; 

                if (ticketDepGroup) {
                    html = '<select disabled = "disabled" id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" class="form-control input-xs input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option></select>'; 
                }

                $('.groupIdTimeEmployeePlanC').html(html);

                $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();
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
    });
    
    $(document).keydown(function(e) {
        if (e.keyCode == 65 && e.ctrlKey) {
            $("#tnaBalanceGrid table tbody").find('td.tbl-cell').addClass('ui-selected');
        }
        if (e.keyCode == 90 && e.ctrlKey) {
            $("#tnaBalanceGrid table tbody").find('.ui-selected').removeClass('ui-selected');
        }
        if (e.keyCode == 67 && e.ctrlKey) { // ctrl + c
            _tempedSelectedPlanRows = [];
            var tbl = $('#tnaBalanceGrid', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').find('table tbody');
            tbl.find('.ui-selected').each(function () {
                var _this = $(this);
                _tempedSelectedPlanRows.push({
                    index : _this.index(),
                    planId: _this.find('input[data-name="planId"]').val(),
                    planDate: _this.find('input[data-name="planDate"]').val(),
                    day: _this.find('input[data-name="day"]').val(),
                    planTime: _this.find('input[data-name="planTime"]').val(),
                });
            });
            
        }
        if (e.keyCode == 86 && e.ctrlKey) { // ctrl + v
            var pasteSaveParams = [];
            var tbl = $('#tnaBalanceGrid', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').find('table tbody');
            var _pasteRowLength = tbl.find('.ui-selected').length;
            var _copyRowLength = _tempedSelectedPlanRows.length;
            var index = 0;
            
            if (_pasteRowLength <= _copyRowLength) {
                tbl.find('.ui-selected').each(function (key, row) {
                    var _this = $(this);
                    var _planRows = _tempedSelectedPlanRows.length;
                    pasteSaveParams.push({
                        date : _this.find('input[data-name="planDate"]').val(), 
                        wfmStatusId : _this.find('input[data-name="wfmStatusId"]').val(),
                        wfmStatusCode : _this.find('input[data-name="wfmStatusCode"]').val(),
                        tnaEmployeeTimePlanId : _this.find('input[data-name="tnaEmployeeTimePlanId"]').val(),
                        id : _this.closest('tr').find('input[data-name="employeeId"]').val(), 
                        employeeKeyId : _this.closest('tr').find('input[data-name="employeeKeyId"]').val(),
                        planId: _tempedSelectedPlanRows[index]['planId'],
                        isLock: _this.closest('tr').find('input[data-name="isLock"]').val(),
                        lockEndDate: _this.closest('tr').find('input[data-name="lockEndDate"]').val(),
                        lockUserId : _this.closest('tr').find('input[data-name="lockUserId"]').val(),
                    });
                    
                    if ((_planRows-1) == index ) {
                        index = -1
                    }
                    index++;
                });
            }
            else {
                tbl.find('.ui-selected').each(function (key, row) {
                    var _this = $(this);
                    var _planRows = _tempedSelectedPlanRows.length;
                    
                    pasteSaveParams.push({
                        date : _this.find('input[data-name="planDate"]').val(), 
                        wfmStatusId : _this.find('input[data-name="wfmStatusId"]').val(),
                        wfmStatusCode : _this.find('input[data-name="wfmStatusCode"]').val(),
                        tnaEmployeeTimePlanId : _this.find('input[data-name="tnaEmployeeTimePlanId"]').val(),
                        id : _this.closest('tr').find('input[data-name="employeeId"]').val(), 
                        employeeKeyId : _this.closest('tr').find('input[data-name="employeeKeyId"]').val(),
                        planId: _tempedSelectedPlanRows[index]['planId'],
                        isLock: _this.closest('tr').find('input[data-name="isLock"]').val(),
                        lockEndDate: _this.closest('tr').find('input[data-name="lockEndDate"]').val(),
                        lockUserId : _this.closest('tr').find('input[data-name="lockUserId"]').val(),
                    });
                    
                    if ((_planRows-1) == index ) {
                        index = -1
                    }
                    index++;
                });
            }
            
            var postParams = {"data": pasteSaveParams};
            $.ajax({
                type: 'post',
                url: 'mdtime/saveEmployeePlanPaste',
                data: postParams,
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        message: "Түр хүлээнэ үү!!!",
                        boxed: true
                    });
                },
                success: function (data) {
                    Core.unblockUI();
                    PNotify.removeAll(); 
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    getEmployeePlanList();
                },
                error: function () {
                  $.unblockUI();
                  PNotify.removeAll(); 
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
    
    function onUserImageError(source) {
      source.src = "assets/core/global/img/user.png";
      source.onerror = "";
      return true;
    }
    
    function checkFullTime(elem) {
        var _this = $(elem);
        var _realFullTime = parseInt(_this.val());
        
        var row = _this.closest('tr');
        var _planTime = row.find('input[data-name="planTime"]');
        var _tempFullTime = 0;
        for(var i = 0; i < _planTime.length; i++){
            var _time = $(_planTime[i]).val();
            if (_time.length > 0) {
                _tempFullTime = _tempFullTime + parseInt(_time);
            }
        }
        if (parseInt(_realFullTime) < parseInt(_tempFullTime)) {
            _this.addClass('error');
            PNotify.removeAll(); 
            new PNotify({
                title: 'Тайлбар',
                text: 'Хүн цаг хэтэрсэн байна',
                type: 'warning',
                sticker: false
            });
        } else {
            _this.removeClass('error');
        }
    }
    
    function groupSelectableGrid (metaDataCode, chooseType, elem, rows) {
        var _selectedRowId = '';
        var _selectedRowName = '';
        var _selectedRowCode = '';
        if (rows.length > 0) {
            $.each(rows, function(key, row){
                if (key == 0) {
                    _selectedRowId   = row.id;
                    _selectedRowCode = row.code;
                    _selectedRowName = row.name;
                }
                else {
                    _selectedRowId   = _selectedRowId+','+row.id;
                    _selectedRowCode = _selectedRowCode+','+row.code;
                    _selectedRowName = _selectedRowName+','+row.name;
                }
            });
        }
        
        $("#groupId", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(_selectedRowId);
        $("#groupCode_displayField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(_selectedRowCode);
        $("#groupName_nameField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(_selectedRowName);
    }
    
    $('.timePlanClear', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', function () {
        $("#groupId", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val('');
        $("#groupCode_displayField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val('');
        $("#groupName_nameField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val('');
    });
    
    function timePlanResizeDtlTable() {
        var freezeParent = $('#fz-parent', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>'));
        
        if (freezeParent.length) {
            var dynamicHeight = $(window).height() - freezeParent.offset().top - 65;
            freezeParent.css('height', dynamicHeight);
            $('table#assetDtls', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')).tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9}); 
            $('#fz-parent', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')).trigger('scroll');
        }
    }
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-prev:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        timePlanGotoPage(currentPageNumber - 1);
    });
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-last-prev:not(.pf-custom-pager-disabled)', function () {
        timePlanGotoPage(1);
    });
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        timePlanGotoPage(currentPageNumber + 1);
    });
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-last-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var totalPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());
        
        timePlanGotoPage(totalPageNumber);
    });
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-refresh:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        timePlanGotoPage(currentPageNumber);
    });
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('keydown', '#assetDtls > thead > tr > th > input[data-fieldname]', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code === 13) {
            timePlanGotoPage(1);
        }
    });
    
    function timePlanGotoPage(pageNumber) {
        
        Core.blockUI({
            boxed : true,
            message: 'Уншиж байна...'
        });  
        
        var filterRules = '';
        $('#tnatimePlanPage', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(pageNumber)
        $('#assetDtls > thead > tr > th > input[data-fieldname]', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').each(function(){
            var _this = $(this);
            var _value = _this.val();

            if (_value != '') {
                var fieldName = _this.attr('data-fieldname');
                var condition = _this.attr('data-condition');

                filterRules += '{"field":"'+fieldName+'","op":"'+condition+'","value":"'+_value+'"},';
            }
        });

        if (filterRules) {
            filterRules = rtrim(filterRules, ',');
            filterRules = '['+filterRules+']';
        }

        $.ajax({
            type: 'POST',
            url: 'mdtime/empPlanListMainDataGrid',
            data: {
                "params": $("#tnaTimeEmployeePlanForm").serialize(),
                uniqId: '<?php echo $this->uniqId; ?>', 
                metaDataId: '<?php echo $this->uniqId; ?>', 
                page: pageNumber, 
                rows: 50, 
                filterRules: filterRules,
                srch_yearCode: $('#srch_yearCode').val(),
                srch_monthCode: $('#srch_monthCode').val(),
            },
            dataType: 'json',
            beforeSend: function() {}, 
            success: function (data) {
                if (data.hasOwnProperty('status') && data.status == 'success') {
                    $("table#assetDtls > tbody", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').empty();
                    var depreciationContent = $('table#assetDtls > tbody', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')[0];
                    depreciationContent.innerHTML = data.Html;
                    $('table#assetDtls > tbody', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').promise().done(function() {
                        
                        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
                        var totalRowNumber = data.total;
                        var pageNumbers = Math.ceil(totalRowNumber / 50) || 1;
                        var currentPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());

                        pagerElement.find('.pf-custom-pager-total > span').text(totalRowNumber);
                        pagerElement.find('input[data-gotopage]').val(pageNumber);
                        pagerElement.find('span[data-pagenumber]').text(pageNumbers);

                        if (currentPageNumber == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else {
                            if (pageNumber == currentPageNumber) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            } 
                            else if (pageNumber == 1 && pageNumbers == 1) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            } 
                            else if (pageNumber == 1) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev').addClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            } 
                            else {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            }
                        }

                        if ($().tableHeadFixer) {
                            $('table#assetDtls', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9}); 
                            $('#fz-parent', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').trigger('scroll');
                        }
                        var tbl = $("#tnaBalanceGrid").find("table");
                        var tblHeader = tbl.find('tr.tablesorter-headerRow th');
                        var tblFilterHeader = tbl.find('tr.tablesorter-ignoreRow td');
                        var _tblColSpan = tbl.find('tbody.tablesorter-no-sort tr').find('td.departmentTitle');
                        var $bcolspan = _tblColSpan.attr('colspan');
                        if (isWorkingDays) {
                            $('#isWorkingDays').attr('checked', 'checked');
                            tbl.find('td.weekday').removeClass('tbl-cell').hide();
                            tbl.find('th.weekday').removeClass('tbl-cell').hide();
                            var $colspan = 1;
                            for( var i = 0; i <= tblHeader.length; i++) {
                                if ($(tblHeader[i]).attr('data-isworking') == '7' || $(tblHeader[i]).attr('data-isworking') == '6') {
                                    $(tblFilterHeader[i]).hide();
                                    $colspan++;
                                }
                            }
                            _tblColSpan.attr('colspan', parseInt($bcolspan) - parseInt($colspan));
                        }
                        
                        Core.unblockUI();
                    });
                }
                else if (data.hasOwnProperty('status') && data.status == 'error') {

                    new PNotify({
                        title: 'Error',
                        text: 'Өгөгдөл олдсонгүй',
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                }

                
            }
        });
    }
</script>