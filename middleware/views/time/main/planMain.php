<div class="row">
        <div id="tnaTimeEmployeePlanWindow" class="col-md-12 row tnaTimeEmployeePlan-<?php echo $this->uniqId ?>" timeplan-uniqId="<?php echo $this->uniqId ?>">
            <div class="col-md-12 center-sidebar">
                <div class="form-body xs-form pl0">
                    <form id="tnaTimeEmployeePlanForm" class="form-horizontal" method="post">
                        <input type="hidden" value="" id="listfromdv">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="collapsible">
                                    <legend>Ерөнхий мэдээлэл</legend>
                                    <input type="hidden" id="searchClickedTR" value="">
                                    <input type="hidden" id="onlyWorkingDay" name="onlyWorkingDay"  value="0">
                                    <input type="hidden" name="golomtView"  value="<?php echo ($this->golomtView) ? '1' : '0' ?>">
                                    <input type="hidden" id="onlyPositionWorkingDays" name="onlyPositionWorkingDays"  value="0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row fom-row">
                                                <?php if (Config::getFromCache('tmsCustomerCode') == 'gov') { ?>
                                                <div class="next-generation-input-wrap next-generation-input-wrap-1 ml12">
                                                    <div class="next-generation-input-label grey">
                                                            <?php echo $this->lang->line('work_department_name_salary'); ?><br />
                                                            <label><input type="checkbox" name="isChild" <?php echo $this->isParentDep == '1' ? 'checked' : ''; ?> value="1"> Дэд салбар</label>
                                                            <div class="next-generation-input-group">
                                                                <?php
                                                                    $drillDownDepartmentId = '';
                                                                    $drillDownDepartmentName = '';
                                                                    $drillDownDepartmentSelect = '';
                                                                ?>
                                                                <div class="input-icon right groupDepartmentId_<?php echo $this->uniqId ?>" data-toggle="dropdown" data-delay="1000" data-close-others="true">
                                                                        <i class="fa fa-angle-down selectedDepartmentIco_<?php echo $this->uniqId ?>"></i>
                                                                    <input type="text" style="border:none;" name="departmentList_search" placeholder="Хайх..." autocomplete="off" class="departmentList_search_<?php echo $this->uniqId; ?> form-control form-control-sm selectedDepartment_<?php echo $this->uniqId ?>" />
                                                                    <?php
                                                                    echo Form::hidden(array('name' => 'newDepartmentId', 'class' => 'departmentId_' . $this->uniqId, 'id' => 'departmentId', 'value' => $this->departmentList['id']));
                                                                    echo Form::hidden(array('name' => '', 'class' => 'departmentIdName_' . $this->uniqId, 'id' => 'departmentIdName', 'value' => $drillDownDepartmentName));
                                                                    ?>
                                                                </div>
                                                                <div class="hidden departmentlist-jtree-<?php echo $this->uniqId; ?>">
                                                                    <div class="search-tree-<?php echo $this->uniqId; ?>">                                                                        
                                                                        <a class="department-multiselect-all-<?php echo $this->uniqId ?>" href="javascript:;"><span>Бүгдийг сонгох</span></a>
                                                                        <a class="department-multiselect-none-<?php echo $this->uniqId ?>" style="margin-left:10px;" href="javascript:;"><span>Буцаах</span></a>
                                                                    </div>
                                                                    <div class="list-jtree-<?php echo $this->uniqId; ?>"></div>
                                                                </div>                                                                   
                                                        </div>
                                                        </div>
                                                        <div class="next-generation-input-body grey">
                                                            <div class="selectedDepartmentNamesWrap">
                                                                <div id="selectedDepartmentNamesContainer_<?php echo $this->uniqId; ?>" class="selectedDepartmentNamesContainer">
                                                                    <?php echo $this->departmentList['departmentname']; ?>
                                                                </div>
                                                                <div class="selectedDepartmentNamesContainerBtn" data-depnames="close">Дэлгэрэнгүй харах</div>
                                                            </div>
                                                        </div>
                                                    </div>                                                 
                                                <?php } else { ?>
                                                <?php echo Form::label(array('text' => 'Салбар нэгж', 'for' => '', 'class' => 'col-form-label col-md-4 p-0 margin-0', 'required' => 'required')); ?>
                                                    <div class="col-md-8">        
                                                        <div class="meta-autocomplete-wrap" data-section-path="">
                                                            <div class="input-group double-between-input">
                                                                <input type="hidden" data-criteria="" data-criteria-param="" name="newDepartmentId[]" id="newDepartmentId_valueField" data-path="newDepartmentId" class="popupInit" data-out-param="" data-in-param="" value="<?php echo $this->departmentList['id']; ?>" data-in-lookup-param="">
                                                                <input type="text" name="newDepartmentId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" data-field-name="" id="newDepartmentId_displayField" data-processid="1528858041095420" data-lookupid="1526027254412" value="<?php echo $this->departmentList['code']; ?>" placeholder="кодоор хайх" autocomplete="off" required="required">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('newDepartmentId', '1528858041095420', '1526027254412', 'multi', 'newDepartmentId', this);" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                                                </span>     
                                                                <span class="input-group-btn w-100">
                                                                    <input type="text" name="newDepartmentId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete ui-autocomplete-input" data-field-name="newDepartmentId" id="newDepartmentId_nameField" data-processid="1528858041095420" data-lookupid="1526027254412" placeholder="нэрээр хайх" autocomplete="off" required="required" value="<?php echo $this->departmentList['departmentname']; ?>" style="border-radius:0 !important;">
                                                                </span>
                                                                <span class="tmsDepChild"><input type="checkbox" class="mt10" name="isChild" <?php echo $this->isParentDep == '1' ? 'checked' : ''; ?> value="1" title="ДЭД САЛБАР"></span>
                                                            </div>
                                                        </div>
                                                    </div>        
                                                <?php } ?>
                                            </div>
                                            <?php if (Config::getFromCache('tmsCustomerCode') == 'khaanbank') { ?>
                                                <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => '', 'class' => 'col-form-label col-md-4')); ?>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">
                                                            <?php
                                                            echo Form::text(
                                                                    array(
                                                                        'name' => 'stringValue',
                                                                        'id' => 'stringValue',
                                                                        'class' => 'form-control form-control-sm input-xxlarge',
                                                                        'placeholder' => 'Овог, Нэр, Код'
                                                                    )
                                                            );
                                                            ?>
                                                        </div>
                                                    </div>      
                                                </div>
                                            <?php } elseif (Config::getFromCache('tmsCustomerCode') != 'gov') { ?>
                                                <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => '', 'class' => 'col-form-label col-md-4')); ?>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">
                                                            <?php
                                                            echo Form::text(
                                                                    array(
                                                                        'name' => 'stringValue',
                                                                        'id' => 'stringValue',
                                                                        'class' => 'form-control form-control-sm input-xxlarge',
                                                                        'placeholder' => 'Овог, Нэр, Код, РД'
                                                                    )
                                                            );
                                                            ?>
                                                        </div>
                                                    </div>      
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCalcIdCode') == '1' ? ' hidden' : '' ?>">
                                                <?php echo Form::label(array('text' => 'Огноо', 'for' => 'startDate', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                                <div class="col-md-4">
                                                    <div class="input-icon right">
                                                        <?php
                                                        $yearDataSort = array();
                                                        $yearData = Info::getRefYearList();
                                                        $dateCurrent = Date::currentDate('Y');
                                                        $dateCurrent2 = Date::currentDate('Y') - 1;
                                                        
                                                        foreach ($yearData as $yearIndex => $year) {
                                                            if ($year['YEAR_CODE'] > $dateCurrent) {
                                                                array_push($yearDataSort, $year);
                                                            }
                                                        }
                                                        
                                                        foreach ($yearData as $yearIndex => $year) {
                                                            if ($year['YEAR_CODE'] < $dateCurrent2) {
                                                                array_push($yearDataSort, $year);
                                                            }
                                                        }
                                                        
                                                        array_unshift($yearDataSort , array('YEAR_CODE' => $dateCurrent, 'YEAR_NAME' => $dateCurrent.' он'));
                                                        array_unshift($yearDataSort , array('YEAR_CODE' => $dateCurrent2, 'YEAR_NAME' => $dateCurrent2.' он'));
                                                        
                                                        echo Form::select(
                                                                array(
                                                                    'name' => 'planYear',
                                                                    'id' => 'planYear',
                                                                    'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                                    'data' => $yearDataSort,
                                                                    'op_value' => 'YEAR_CODE',
                                                                    'op_text' => 'YEAR_NAME',
                                                                    'required' => 'required',
                                                                    'value' => Date::currentDate('Y')
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 pl0">
                                                    <div class="input-icon right">
                                                        <?php
                                                        echo Form::select(
                                                                array(
                                                                    'name' => 'planMonth',
                                                                    'id' => 'planMonth',
                                                                    'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                                    'data' => Info::getRefMonthList(),
                                                                    'op_value' => 'MONTH_CODE',
                                                                    'op_text' => 'MONTH_NAME',
                                                                    'required' => 'required',
                                                                    'value' => Date::currentDate('m')
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div> 
                                            </div> 
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCalcIdCode') == '1' ? '' : ' hidden' ?>">
                                                <?php echo Form::label(array('text' => 'Цагийн цикл', 'for' => 'calcId', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                                <div class="col-md-8">
                                                    <?php
                                                    echo Form::select(
                                                            array(
                                                                'name' => 'calcId',
                                                                'id' => 'calcId',
                                                                'class' => 'form-control select2 form-control-sm',
                                                                'data' => $this->calcList,
                                                                'op_value' => 'id',
                                                                'op_text' => 'calcname',
                                                                'required' => 'required',
                                                                'op_custom_attr' => array(
                                                                    array(
                                                                        'attr' => 'startdate',
                                                                        'key' => 'startdate'
                                                                    ),
                                                                    array(
                                                                        'attr' => 'enddate',
                                                                        'key' => 'enddate'
                                                                    )
                                                                )
                                                            )
                                                    );
                                                    ?>
                                                </div> 
                                            </div> 
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCalcIdCode') == '1' ? '' : ' hidden' ?>">
                                                <label for="startDate" class="col-form-label col-4 p-0 margin-0"> <span class="required">*</span>Огноо<span class="label-colon">:</span></label>                                                <div class="col-8 d-flex flex-row">
                                                    <div class="dateElement input-group mr-2">
                                                        <input type="text" name="startDate" id="startDate" class="form-control form-control-sm dateInit" value="<?php echo Date::currentDate('Y-m') . '-01' ?>" required="required">                                                        <span class="input-group-btn">
                                                            <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                    <div class="dateElement input-group">
                                                        <input type="text" name="endDate" id="endDate" class="form-control form-control-sm dateInit" value="<?php echo Date::currentDate('Y-m-d') ?>" required="required">                                                        <span class="input-group-btn">
                                                            <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>                                                        
                                                </div>                                    
                                            </div>     
                                            <?php if (Config::getFromCache('tmsCustomerCode') == 'gov') { ?>
                                                <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => '', 'class' => 'col-form-label col-md-4')); ?>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">
                                                            <?php
                                                            echo Form::text(
                                                                    array(
                                                                        'name' => 'stringValue',
                                                                        'id' => 'stringValue',
                                                                        'class' => 'form-control form-control-sm input-xxlarge',
                                                                        'placeholder' => 'Овог, Нэр, Код, РД'
                                                                    )
                                                            );
                                                            ?>
                                                        </div>
                                                    </div>      
                                                </div>
                                            <?php } ?>                                            
                                        </div>
                                        <div class="col-md-3">
                                                <!--<div class="form-group row fom-row">    
                                                <?php echo Form::label(array('text' => 'Албан тушаал', 'for' => 'Албан тушаал', 'class' => 'col-form-label col-md-4')); ?>
                                                <div class="col-md-8">
                                                    <div class="input-icon right">
                                                        <?php
                                                        echo Form::multiselect(
                                                                array(
                                                                    'name' => 'positionId[]',
                                                                    'id' => 'positionId-' . $this->uniqId,
                                                                    'multiple' => 'multiple',
                                                                    'class' => 'form-control form-control-sm input-xxlarge',
                                                                    'data' => $this->positionList,
                                                                    'op_value' => 'POSITION_ID',
                                                                    'op_text' => 'POSITION_NAME'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                    <div class="input-icon right mt5 positionGroup">
                                                        <?php
                                                        echo Form::select(
                                                                array(
                                                                    'name' => 'positionGroupId',
                                                                    'id' => 'positionGroupId',
                                                                    'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                                    'data' => '',
                                                                    'op_value' => '',
                                                                    'op_text' => ''
                                                                )
                                                        );
                                                        ?>                                                        
                                                    </div>
                                                </div>
                                            </div>-->
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCustomerCode') == 'gov' ? ' hidden' : '' ?>">    
                                            <?php
                                                $labelname = 'Ээлжийн бүлэг';
                                                if ($this->golomtView)
                                                    $labelname = 'Ирц бүртгэл /Бусад/';

                                                echo Form::label(array('text' => $labelname, 'for' => 'startDate', 'class' => 'col-form-label col-md-4'));
                                                ?>
                                                <div class="col-md-8 groupIdTimeEmployeePlanC">
                                                    <?php
                                                    echo Form::multiselect(
                                                            array(
                                                                'name' => 'groupId[]',
                                                                'id' => 'groupIdTimeEmployeePlan-' . $this->uniqId,
                                                                'multiple' => 'multiple',
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
                                        <div class="col-md-1">
                                            <div class="form-group row fom-row">
                                                <div class="col-md-12">
                                                    <div class="input-icon dateElement right">
                                                        <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success balanceReload search-tms-plan-btn', 'data-view-id' => $this->uniqId, 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
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
                                                                'id' => 'employeeStatusPlan-' . $this->uniqId,
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
                    <div class="grid-row-content isVerifyBtn" id="setSideBarAddTimePlan"></div>
                    <hr class="mt6 mb15 d-none plan-seperator">
                    <div id="setSideBarDefaultContent"></div>

                    <div class="panel panel-default bg-inverse additional-panel hidden">
                        <table class="table sheetTable">
                            <tbody>
                                <tr class="isVerifyBtn">
                                    <td class="left-padding"><?php echo Form::label(array('text' => 'Хэлтсийн нийт энэ сарын цаг', 'for' => 'departmentCurrentMonthTime')); ?></td>
                                    <td>
                                        <?php
                                        echo Form::text(
                                                array(
                                                    'name' => 'departmentCurrentMonthTime',
                                                    'id' => 'departmentCurrentMonthTime',
                                                    'class' => 'form-control longInit',
                                                    'disabled' => 'disabled',
                                                    'value' => ''
                                                )
                                        );
                                        ?>
                                    </td>
                                </tr>
                                <tr class="isVerifyBtn">
                                    <td class="left-padding"><?php echo Form::label(array('text' => 'Хэлтсийн үлдэгдэл цаг', 'for' => 'departmentCurrentTime')); ?></td>
                                    <td>
                                        <?php
                                        echo Form::text(
                                                array(
                                                    'name' => 'departmentCurrentTime',
                                                    'id' => 'departmentCurrentTime',
                                                    'class' => 'form-control longInit',
                                                    'disabled' => 'disabled',
                                                    'value' => ''
                                                )
                                        );
                                        ?>
                                    </td>
                                </tr>
                                <tr class="isVerifyBtn">
                                    <td class="left-padding"><?php echo Form::label(array('text' => 'Ажилтны нийт энэ сарын цаг', 'for' => 'employeeCurrentMonthTime')); ?></td>
                                    <td>
                                        <?php
                                        echo Form::text(
                                                array(
                                                    'name' => 'employeeCurrentMonthTime',
                                                    'id' => 'employeeCurrentMonthTime',
                                                    'class' => 'form-control longInit',
                                                    'value' => ''
                                                )
                                        );
                                        ?>
                                    </td>
                                </tr>
                                <tr class="isVerifyBtn">
                                    <td class="left-padding"><?php echo Form::label(array('text' => 'Ажилтны нийт цаг', 'for' => 'employeeCurrentTime')); ?></td>
                                    <td>
                                        <?php
                                        echo Form::text(
                                                array(
                                                    'name' => 'employeeCurrentTime',
                                                    'id' => 'employeeCurrentTime',
                                                    'class' => 'form-control longInit',
                                                    'disabled' => 'disabled',
                                                    'value' => ''
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

<!-- <script src="assets/custom/addon/plugins/datatables/all.min.js" type="text/javascript"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/> -->

<style type="text/css">
    .ui-state-hover, .ui-widget-content .ui-state-hover {
        color: #333 !important;
    }       

   /* Custom Card CSS Start */
   .next-generation-input-wrap {
        width: 100%;
        height: 66px;
        -moz-box-shadow: 0px 0px 10px 0px #dadada;
        -webkit-box-shadow: 0px 0px 10px 0px #dadada;
        box-shadow: 0px 0px 10px 0px #dadada;        
    }
    @media screen and (min-width: 1220px) {
        .next-generation-input-label {
            width: 40%;
        }
        .next-generation-input-body {
            width: 60%;
        }
    }
    @media screen and (max-width: 1220px) {
        .next-generation-input-label {
            width: 60%;
        }
        .next-generation-input-body {
            width: 40%;
        }        
    }
    .next-generation-input-label {
        float: left;
        height: inherit;
        padding: 8px;
        padding-top: 0px;
        position: relative;
    }    
    .next-generation-input-label > .next-generation-input-group > .meta-autocomplete-wrap > .input-group > input {
        border: none;
    }
    .next-generation-input-body {
        background-color: #f3f7e8;
        float: left;
        height: inherit;
        padding: 8px;
        padding-top: 5px;
    }
    .next-generation-input-label.grey {
        background-color: #dcdcdc;
        border-left: 5px solid #b7b7b7;
    }    
    .next-generation-input-label.grey label {
        color: #666;
        font-size: 11px;
    }
    .next-generation-input-label.grey .checker {
        margin-top: 0px !important;
        margin-left: 0px !important;
    }
    .next-generation-input-body.grey {
        background-color: #f5f5f5;
    }
    .next-generation-input-label.blue {
        background-color: #c5e7f0;
        border-left: 5px solid #57bcd3;
    }    
    .next-generation-input-body.blue {
        background-color: #e4f5fa;
    }
    .next-generation-input-label.orange {
        background-color: #fbdfc4;
        border-left: 5px solid #f79b37;
    }    
    .next-generation-input-body.orange {
        background-color: #fef1e3;
    }
    .next-generation-input-label > .next-generation-input-group {
        position: absolute;
        bottom: 4px;
    }       
    .groupDepartmentId_<?php echo $this->uniqId; ?> {
        padding-right: 8px;
    }       
    .next-generation-input-label .input-group-btn {
        width: 0 !important;
        padding-right: 8px;
    }       
    .salarySheetActions {
        margin-top: -8px;
    }
    @media screen and (min-width: 1050px) {
        .input-icon.right .form-control {
            padding-right: 0px;
            margin-right: 85px;
        }
    }
    /* Custom Card CSS End */    

    .selectedDepartment_<?php echo $this->uniqId; ?> {
        background-color: #FFF;
        cursor: default !important;
    }    
    .departmentlist-jtree-<?php echo $this->uniqId; ?> {
        overflow: auto;
        z-index: 101;
        position:absolute;
        border-right: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
        max-height: 350px;
        padding-bottom: 10px;
        background: #FFF;
    }    
    .departmentlist-jtree-<?php echo $this->uniqId; ?> .jstree-container-ul {
        background: #FFF !important;
    }
    .search-tree-<?php echo $this->uniqId; ?> > span {
        float: left;
        margin-right: 5px;
        margin-left: 5px;
        padding-top: 5px;
        font-size: 12px;
    }
    .search-tree-<?php echo $this->uniqId; ?> {
        background: #FFF !important; 
        padding-top: 6px;
        padding-bottom: 2px;
        border-bottom: 1px solid #ccc;
        padding-left: 8px;
        padding-right: 8px;        
        font-size: 12px;
    }
    .search-tree-<?php echo $this->uniqId; ?> > input {
        border-radius: 0 !important;
        width: 100% !important;
    }   
    .selectedDepartmentNamesContainer {
        max-height: 44px;
        overflow: hidden;
        color: #505050;
    }
    .selectedDepartmentNamesContainerBtn {
        text-align: center;
        font-size: 11px;
        text-transform: lowercase;
        color: #789e26;
        border-top: 1px solid #c2da8e;
    }
    .selectedDepartmentNamesContainerBtn:hover {
        color: #0057c7;
        cursor: pointer;
    }
    .tnaTimeEmployeePlan-<?php echo $this->uniqId ?> .ui-multiselect {
        background-color: #fff !important;
        color: #656565 !important;
        border: 1px solid #d0d0d0;
        border-radius: 3px;
    }    
    .tnaTimeEmployeePlan-<?php echo $this->uniqId ?> .btn:not(.search-tms-plan-btn) {
        padding: 1px 5px 1px 5px;
    }    
</style>