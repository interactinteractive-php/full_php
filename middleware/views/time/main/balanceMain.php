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
                                            <?php echo Form::label(array('text' => 'Алба хэлтэс', 'for' => 'departmentId', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo Form::multiselect(
                                                    array(
                                                        'name' => 'departmentId[]',
                                                        'id' => 'balanceDepartmentId_'.$this->uniqId,
                                                        'multiple' => 'multiple',
                                                        'class' => 'form-control form-control-sm input-xxlarge balanceDepartmentId_'.$this->uniqId,
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
                                        <div class="form-group row fom-row">
                                            <?php 
                                                $labelname = 'Ээлжийн бүлэг';
                                                if ($this->golomtView)
                                                    $labelname = 'Ирц бүртгэл /Бусад/';

                                                echo Form::label(array('text' => $labelname, 'for' => 'startDate', 'class' => 'col-form-label col-md-4')); ?>
                                            <div class="col-md-8 groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>">
                                                <?php
                                                echo Form::multiselect(
                                                        array(
                                                            'name' => 'groupId[]',
                                                            'id' => 'groupId_'.$this->uniqId,
                                                            'multiple' => 'multiple',
                                                            'disabled' => 'disabled',
                                                            'class' => 'form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_'.$this->uniqId,
                                                            'data' => $this->searchTnaGroupList,
                                                            'op_value' => 'ID',
                                                            'op_text' => 'NAME',
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
                                                    <?php echo Form::label(array('text' => $this->lang->line('start_date'), 'for' => 'startDate', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                                    <div class="col-md-8">
                                                        <div class="dateElement input-group ml5" data-section-path="bookDate">
                                                            <?php echo Form::text(array('name' => 'startDate', 'id' => 'startDate', 'class' => 'form-control form-control-sm dateInit', 'value'=>  Date::currentDate('Y-m') . '-01', 'required' => 'required'));?>
                                                            <span class="input-group-btn">
                                                                <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <?php echo Form::label(array('text' => 'Шалтгаан', 'for' => 'positionId', 'class' => 'col-form-label col-md-4')); ?>
                                                <div class="col-md-8">
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
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group row fom-row">
                                                    <?php echo Form::label(array('text' => $this->lang->line('end_date'), 'for' => 'startDate', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
                                                    <div class="col-md-8">
                                                        <div class="dateElement input-group" data-section-path="bookDate">
                                                            <?php echo Form::text(array('name' => 'endDate', 'id' => 'endDate', 'class' => 'form-control form-control-sm dateInit', 'value'=>  Date::currentDate('Y-m-d'), 'required' => 'required'));?>
                                                            <span class="input-group-btn">
                                                                <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row fom-row hidden">
                                                    <?php echo Form::label(array('text' => 'Утгаар хайх', 'for' => 'departmentId', 'class' => 'col-form-label col-md-3')); ?>
                                                    <div class="col-md-9">
                                                        <?php echo Form::text(array('name' => 'filterString', 'id' => 'filterString', 'class' => 'form-control', 'placeholder' => 'Код, овог, нэр'));?>
                                                    </div>
                                                </div>
                                                <div class="form-group row fom-row <?php echo (isset($this->golomtView) && $this->golomtView) ? 'hidden' : ''; ?>">
                                                    <div class="col-md-4"></div>
                                                    <div class="col-md-8">
                                                        <div class="input-icon right">
                                                            <input type="text" id="stringValue" name="stringValue" class="form-control form-control-sm input-xxlarge" placeholder="Утгын хайлт (овог, нэр)">
                                                        </div>
                                                    </div>
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
                                <div class="row">
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
    #uniform-isAddonDate > span {
        margin-top: 7px;
    }
    .uniform-isAddonDate > span {
        margin-top: 0px !important;
    }
    .tnasidbar-viewer-class {
        z-index: 100;
        position: fixed;
        top: 45px;
        right: 15px;
        overflow-y: auto;
    } 
</style>