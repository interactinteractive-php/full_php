<div class="row" id="tnaTimeBalanceWindow<?php echo $this->uniqId ?>">
    <input type="hidden" id="selected-datagrid-<?php echo $this->uniqId ?>" value="0"/>
    <div class="col-md-12 center-sidebar center-sidebar-<?php echo $this->uniqId  ?> employeeTimeBalance_<?php echo $this->uniqId ?>" id="employeeTimeBalance">
        <div class="form-body">
            <div class="row">
                <form id="tnaTimeBalanceForm<?php echo $this->uniqId ?>" class="form-horizontal xs-form" method="post">
                    <fieldset class="collapsible">
                        <legend>Ерөнхий мэдээлэл</legend>
                        <div class="row">
                            <div class="col-md-10 col-sm-12">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5">
                                        <div class="form-group row fom-row">
                                            <div class="next-generation-input-wrap next-generation-input-wrap-1">
                                                <div class="next-generation-input-label grey">
                                                        <?php echo $this->lang->line('work_department_name_salary'); ?><br />
                                                        <label><input type="checkbox" name="isChild" value="1"> Дэд салбар</label>
                                                        <div class="next-generation-input-group">
                                                            <?php
                                                                $drillDownDepartmentId = '';
                                                                $drillDownDepartmentName = '';
                                                                $drillDownDepartmentSelect = '';
                                                            ?>
                                                            <div class="input-icon right groupDepartmentId_<?php echo $this->uniqId ?>" data-toggle="dropdown" data-delay="1000" data-close-others="true">
                                                                <i class="fa fa-angle-down selectedDepartmentIco_<?php echo $this->uniqId ?>"></i>
                                                                <!--<input type="text" style="border:none;" class="form-control form-control-sm selectedDepartment_<?php echo $this->uniqId ?>" id="selectedDepartment_<?php echo $this->uniqId ?>" value="<?php echo $drillDownDepartmentSelect ?>" placeholder="- Сонгох -">-->
                                                                <input type="text" style="border:none;" name="departmentList_search" placeholder="Хайх..." autocomplete="off" class="departmentList_search_<?php echo $this->uniqId; ?> form-control form-control-sm selectedDepartment_<?php echo $this->uniqId ?>" />
                                                                <?php
                                                                echo Form::hidden(array('name' => 'departmentId', 'class' => 'departmentId_' . $this->uniqId, 'id' => 'departmentId', 'value' => $drillDownDepartmentId));
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
                                                        <div class="selectedDepartmentNamesWrap hidden">
                                                            <div id="selectedDepartmentNamesContainer_<?php echo $this->uniqId; ?>" class="selectedDepartmentNamesContainer"></div>
                                                            <div class="selectedDepartmentNamesContainerBtn" data-depnames="close">Дэлгэрэнгүй харах</div>
                                                        </div>
                                                    </div>
                                                </div>                                        
                                            </div>
                                    </div>
                                    <div class="col-md-7 col-sm-7">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group row fom-row">
                                                    <?php echo Form::label(array('text' => 'Огноо', 'for' => 'startDate', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                                    <div class="col-md-8">
                                                        <div class="dateElement input-group" style="max-width: 96px !important;">
                                                            <?php echo Form::text(array('name' => 'startDate', 'id' => 'startDate', 'class' => 'form-control form-control-sm dateInit', 'value'=>  Date::currentDate('Y-m') . '-01', 'required' => 'required'));?>
                                                            <span class="input-group-btn">
                                                                <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>
                                                        <div class="dateElement input-group" style="position: absolute;margin-top: -24px;margin-left: 98px;max-width: 96px !important;">
                                                            <?php echo Form::text(array('name' => 'endDate', 'id' => 'endDate', 'class' => 'form-control form-control-sm dateInit', 'value'=>  Date::currentDate('Y-m-d'), 'required' => 'required'));?>
                                                            <span class="input-group-btn">
                                                                <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>                                                        
                                                    </div>                                    
                                                </div>
                                                <div class="form-group row fom-row <?php echo (isset($this->golomtView) && $this->golomtView) ? 'hidden' : ''; ?>">
                                                <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => 'stringValue', 'class' => 'col-form-label col-md-4')); ?>
                                                    <div class="col-md-8">
                                                        <div class="">
                                                            <input type="text" id="stringValue" name="stringValue" class="form-control form-control-sm input-xxlarge" placeholder="Овог, Нэр" style="max-width: 195px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group row fom-row hidden">
                                                    <?php echo Form::label(array('text' => 'Утгаар хайх', 'for' => 'departmentId', 'class' => 'col-form-label col-md-3')); ?>
                                                    <div class="col-md-9">
                                                        <?php echo Form::text(array('name' => 'filterString', 'id' => 'filterString', 'class' => 'form-control', 'placeholder' => 'Код, овог, нэр'));?>
                                                    </div>
                                                </div>
                                                <div class="form-group row fom-row">
                                                <?php 
                                                        $labelname = 'Ээлжийн бүлэг';
                                                        if ($this->golomtView)
                                                            $labelname = 'Ирц бүртгэл /Бусад/';

                                                        echo Form::label(array('text' => $labelname, 'for' => '', 'class' => 'col-form-label col-md-4')); ?>
                                                    <div class="col-md-8 groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>">
                                                        <?php
                                                        echo Form::multiselect(
                                                                array(
                                                                    'name' => 'groupId[]',
                                                                    'id' => 'groupId_'.$this->uniqId,
                                                                    'multiple' => 'multiple',
                                                                    'class' => 'form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_'.$this->uniqId,
                                                                    'data' => $this->searchTnaGroupList,
                                                                    'op_value' => 'ID',
                                                                    'op_text' => 'NAME',
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>                                                
                                                <?php echo Form::label(array('text' => 'Шалтгаан', 'for' => 'positionId', 'class' => 'col-form-label col-md-4')); ?>
                                                <div class="col-md-8" style="padding-right: 0;padding-left: 10px;">
                                                    <?php
                                                    echo Form::multiselect(
                                                            array(
                                                                'name' => 'causeTypeId[]',
                                                                'id' => 'causeTypeId_'.$this->uniqId,
                                                                'multiple' => 'multiple',
                                                                'class' => 'form-control input-xs input-xxlarge causeTypeId_'.$this->uniqId,
                                                                'data' => $this->searchTnaCauseTypeList,
                                                                'op_value' => 'CAUSE_TYPE_ID',
                                                                'op_text' => 'NAME',
                                                            )
                                                    );
                                                    ?>
                                                </div>                                                
                                                <div class="form-group row fom-row <?php echo (isset($this->golomtView) && $this->golomtView) ? '' : 'hidden'; ?>">
                                                    <?php echo Form::label(array('text' => 'Ажилтны төлөв', 'for' => 'employeeStatus', 'class' => 'col-form-label col-md-3')); ?>
                                                    <div class="col-md-9">
                                                        <?php
                                                            echo Form::multiselect(
                                                                array(
                                                                    'name' => 'employeeStatus[]',
                                                                    'id' => 'employeeStatus_'.$this->uniqId,
                                                                    'multiple' => 'multiple',
                                                                    'class' => 'form-control input-xs input-xxlarge employeeStatus_'.$this->uniqId,
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
                                <div class="row hidden">
                                    <div class="col-md-5 col-sm-5">
                                        <div class="form-group row fom-row">
                                            <?php echo Form::label(array('text' => 'Өөрчлөлт орсон эсэх', 'for' => 'viewEmployee', 'class' => 'col-form-label col-md-4')); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo Form::checkbox(
                                                    array(
                                                        'name' => 'viewEmployee',
                                                        'id' => 'viewEmployee_'.$this->uniqId,
                                                        'class' => 'form-control form-control-sm input-xxlarge viewEmployee_'.$this->uniqId,
                                                        'value' => '1'
                                                    )
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-sm-7">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <div class="form-group row fom-row">
                                    <div class="col-md-12">
                                        <div class="input-icon dateElement right">
                                            <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success float-left', 'onclick' => 'getBalanceList(\''. $this->uniqId .'\')', 'style' => 'width: 110px', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group row fom-row">
                                    <div class="col-md-12">
                                        <div class="input-icon dateElement right">
                                            <button type="button" style="padding:0px 7px 1px 7px; width: 110px" data-uniqid='<?php echo $this->uniqId ?>' class="float-right btn btn-sm blue-hoki btn-circle mr10 float-left downloadData" title="Өгөгдөл татах"><i class="fa fa-download"></i> Өгөгдөл татах</button>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group row fom-row hidden">
                                    <div class="col-md-12">
                                        <div class="input-icon dateElement right">
                                            <button type="button" style="padding:0px 7px 1px 7px; width: 110px" data-uniqid='<?php echo $this->uniqId ?>' class="float-right btn btn-sm blue btn-circle mr10 float-left downloadDataIO" title="Бодолт хийх"><i class="fa fa-download"></i> Бодолт хийх</button>
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
            <!--<div class="row mergeCelltnaEmployeeBalance">
                <div class="col-md-12 mt10">
                    <a class="btn btn-secondary btn-sm btn-circle value-grid-merge-cell-tnaEmployeeBalance default active" title="Merge cell" href="javascript:;"><i class="fa fa-columns"></i></a>  
                </div>
            </div>-->
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
    <div class="right-sidebar tna-sidebar-container right-sidebar-<?php echo $this->uniqId  ?>" data-status="opened" style="margin-top: 9px;">
        <div class="stoggler sidebar-right sidebar-right-<?php echo $this->uniqId ?>" onclick="tnaTimeBalanceStoggler(this, '<?php echo $this->uniqId ?>')">
            <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
            <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
        </div>
        <form id="tnaTimeBalanceForm" class="form-horizontal" method="post">
            <div class="right-sidebar-content-<?php echo $this->uniqId ?>"></div>
        </form>
    </div>
    
    <?php echo Form::hidden(array('id' => 'currentSelectedRowIndex-'. $this->uniqId, 'value' => '')) ?>
    <?php echo Form::hidden(array('id' => 'balanceType-'. $this->uniqId, 'value' => $this->mergeView ? 'merge' : '')) ?>
    
</div>
<!--<div class="form-actions mt15 form-actions-btn">
    <div class="row">
        <div class="col-md-9">
            <?php //echo $this->balanceBtn['all']; ?>            
        </div>
        <div class="col-md-3"></div>
    </div>
</div>-->

<style type="text/css">
    .tnasidbar-viewer-class {
        z-index: 100;
        position: fixed;
        top: 45px;
        right: 13px;
        overflow-y: auto;
    }
    table.sheetTableTms tbody td {
        min-width: 60px;
    }
    .ui-state-hover, .ui-widget-content .ui-state-hover {
        color: #333;
    }       
    .tna-balance-data-grid-div-<?php echo $this->uniqId ?> .datagrid-btable {
        color: #000;
    }
    .tna-balance-data-grid-div-<?php echo $this->uniqId ?> .io-time {
        color: #000;
        font-weight: bold;
    }
    .tna-balance-data-grid-div-<?php echo $this->uniqId ?> .datagrid-row-over .io-time {
        color: #fff;
        font-weight: bold;
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
</style>