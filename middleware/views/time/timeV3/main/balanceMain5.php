<div class="row" id="tnaTimeBalanceWindow<?php echo $this->uniqId ?>">
    <input type="hidden" id="selected-datagrid-<?php echo $this->uniqId ?>" value="0"/>
    <div class="w-100 center-sidebar-<?php echo $this->uniqId  ?> employeeTimeBalance_<?php echo $this->uniqId ?>" id="employeeTimeBalance">
        <div class="form-body">
            <form id="tnaTimeBalanceForm<?php echo $this->uniqId ?>" class="form-horizontal xs-form" method="post">
                <fieldset class="collapsible">
                    <legend>Ерөнхий мэдээлэл</legend>
                    <div class="d-flex">
                        <div class="col-10">
                            <div class="row">
                                <?php if (Config::getFromCache('tmsCustomerCode') == 'gov') { ?>
                                <div class="col-5">
                                    <div class="form-group row fom-row">
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
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCustomerCode') == 'gov' ? ' hidden' : '' ?>">
                                                <?php echo Form::label(array('text' => 'Загвар', 'for' => 'tmsTemplate_'.$this->uniqId, 'class' => 'col-form-label col-4 p-0 margin-0', 'required' => 'required')); ?>
                                                <div class="col-8">        
                                                <?php
                                                echo Form::select(
                                                    array(
                                                        'name' => 'tmsTemplate',
                                                        'id' => 'tmsTemplate_'.$this->uniqId,
                                                        'class' => 'form-control form-control-sm select2',
                                                        'data' => $this->tmsTemplateList,
                                                        'op_value' => 'ID',
                                                        'text' => 'notext',
                                                        'op_text' => 'NAME',
                                                        'required' => 'required'
                                                    )
                                                );
                                                ?>                                                        
                                                </div>                                    
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="col-5">
                                    <div class="">
                                        <div class="form-group row fom-row">
                                            <?php echo Form::label(array('text' => 'Салбар нэгж', 'for' => '', 'class' => 'col-form-label col-4 p-0 margin-0', 'required' => 'required')); ?>
                                            <div class="col-8">
                                                <div class="meta-autocomplete-wrap" data-section-path="">
                                                    <div class="input-group double-between-input">
                                                        <input type="hidden" data-criteria="" data-criteria-param="" name="newDepartmentId[]" id="newDepartmentId_valueField" data-path="newDepartmentId" class="popupInit" data-out-param="" data-in-param="" data-out-group="" data-in-lookup-param="" value="<?php echo $this->departmentList['id']; ?>">
                                                        <input type="text" name="newDepartmentId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" data-field-name="" id="newDepartmentId_displayField" data-processid="1528858041095420" data-lookupid="1526027254412" placeholder="кодоор хайх" autocomplete="off" value="<?php echo $this->departmentList['code']; ?>">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('newDepartmentId', '1528858041095420', '1526027254412', 'multi', 'newDepartmentId', this);" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                                        </span>     
                                                        <span class="input-group-btn w-100">
                                                            <input type="text" name="newDepartmentId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete ui-autocomplete-input" data-field-name="newDepartmentId" id="newDepartmentId_nameField" data-processid="1528858041095420" data-lookupid="1526027254412" placeholder="нэрээр хайх" autocomplete="off" value="<?php echo $this->departmentList['departmentname']; ?>" style="border-radius:0 !important;">
                                                        </span>
                                                        <span class="tmsDepChild"><input type="checkbox" class="mt10" name="isChild" <?php echo $this->isParentDep == '1' ? 'checked' : ''; ?> value="1" title="ДЭД САЛБАР"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCustomerCode') == 'gov' ? ' hidden' : '' ?>">
                                            <?php echo Form::label(array('text' => 'Загвар', 'for' => 'tmsTemplate_'.$this->uniqId, 'class' => 'col-form-label col-4 p-0 margin-0', 'required' => 'required')); ?>
                                            <div class="col-8">        
                                            <?php
                                            echo Form::select(
                                                array(
                                                    'name' => 'tmsTemplate',
                                                    'id' => 'tmsTemplate_'.$this->uniqId,
                                                    'class' => 'form-control form-control-sm select2',
                                                    'data' => $this->tmsTemplateList,
                                                    'op_value' => 'ID',
                                                    'text' => 'notext',
                                                    'op_text' => 'NAME',
                                                    'required' => 'required'
                                                )
                                            );
                                            ?>                                                        
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-7">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCalcIdCode') == '1' ? '' : ' hidden' ?>">
                                                <?php echo Form::label(array('text' => 'Цагийн цикл', 'for' => 'calcIdBalance', 'class' => 'col-form-label col-md-3')); ?>
                                                <div class="col-md-9">
                                                    <?php
                                                    echo Form::select(
                                                            array(
                                                                'name' => 'calcIdBalance',
                                                                'id' => 'calcIdBalance',
                                                                'class' => 'form-control select2 form-control-sm',
                                                                'data' => $this->calcList,
                                                                'op_value' => 'id',
                                                                'op_text' => 'calcname',
                                                                'style' => 'max-width: 272px;',
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
                                            <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Огноо', 'for' => 'startDate', 'class' => 'col-form-label col-3 p-0 margin-0', 'required' => 'required')); ?>
                                                <div class="col-9 d-flex flex-row">
                                                    <div class="dateElement input-group mr-2">
                                                        <?php echo Form::text(array('name' => 'startDate', 'id' => 'startDate', 'class' => 'form-control form-control-sm dateInit', 'value'=>  Date::currentDate('Y-m') . '-01', 'required' => 'required'));?>
                                                        <span class="input-group-btn">
                                                            <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                    <div class="dateElement input-group">
                                                        <?php echo Form::text(array('name' => 'endDate', 'id' => 'endDate', 'class' => 'form-control form-control-sm dateInit', 'value'=>  Date::currentDate('Y-m-d'), 'required' => 'required'));?>
                                                        <span class="input-group-btn">
                                                            <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>                                                        
                                                </div>                                    
                                            </div>                                           
                                            <?php if (Config::getFromCache('tmsCalcIdCode') != '1') { ?>
                                                <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => 'stringValue', 'class' => 'col-form-label col-3 p-0 margin-0')); ?>
                                                    <div class="col-9">
                                                        <div class="">
                                                            <input type="text" id="stringValue" name="stringValue" class="form-control form-control-sm input-xxlarge" placeholder="Овог, Нэр, Код" style="max-width: 272px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group row fom-row hidden">
                                                <?php echo Form::label(array('text' => 'Утгаар хайх', 'for' => 'departmentId', 'class' => 'col-form-label col-md-3')); ?>
                                                <div class="col-md-9">
                                                    <?php echo Form::text(array('name' => 'filterString', 'id' => 'filterString', 'class' => 'form-control', 'placeholder' => 'Код, овог, нэр'));?>
                                                </div>
                                            </div>
                                            <div class="form-group row fom-row<?php echo Config::getFromCache('tmsCustomerCode') == 'gov' ? ' hidden' : '' ?>">
                                            <?php 
                                                    $labelname = 'Ээлжийн бүлэг';
                                                    echo Form::label(array('text' => $labelname, 'for' => '', 'class' => 'col-form-label col-4 p-0 margin-0')); ?>
                                                <div class="col-8 groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>">
                                                    <?php
                                                    echo Form::multiselect(
                                                            array(
                                                                'name' => 'groupId[]',
                                                                'id' => 'groupId_'.$this->uniqId,
                                                                'multiple' => 'multiple',
                                                                'class' => 'form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_'.$this->uniqId,
                                                                'data' => $this->searchTnaGroupList,
                                                                'op_value' => 'ID',
                                                                'style' => 'background-color: #fff',
                                                                'op_text' => 'NAME',
                                                            )
                                                    );
                                                    ?>
                                                </div>
                                            </div>                  
                                            <?php if (Config::getFromCache('tmsCustomerCode') == 'khaanbank') { ?>
                                                <div class="form-group row fom-row">                              
                                                    <?php echo Form::label(array('text' => 'Албан тушаал', 'for' => 'Албан тушаал', 'class' => 'col-form-label col-4')); ?>
                                                    <div class="col-8">
                                                        <?php
                                                        echo Form::multiselect(
                                                                array(
                                                                    'name' => 'positionId[]',
                                                                    'id' => 'positionId-' . $this->uniqId,
                                                                    'multiple' => 'multiple',
                                                                    'class' => 'form-control input-xs input-xxlarge',
                                                                    'data' => $this->positionList,
                                                                    'op_value' => 'POSITION_ID',
                                                                    'op_text' => 'POSITION_NAME'
                                                                )
                                                        );
                                                        ?>
                                                    </div>                                                
                                                </div>           
                                            <?php } else { ?>
                                                <div class="form-group row fom-row <?php echo Config::getFromCache('hideReason') == '1' ? 'd-none' : ''; ?>">                              
                                                    <?php echo Form::label(array('text' => 'Шалтгаан', 'for' => 'positionId', 'class' => 'col-form-label col-4 p-0 margin-0')); ?>
                                                    <div class="col-8">
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
                                                <?php if (Config::getFromCache('hideReason') == '1' && Config::getFromCache('tmsCalcIdCode') == '1') { ?>
                                                <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => 'stringValue', 'class' => 'col-form-label col-4 p-0 margin-0')); ?>
                                                    <div class="col-8">
                                                        <div class="">
                                                            <input type="text" id="stringValue" name="stringValue" class="form-control form-control-sm input-xxlarge" placeholder="Овог, Нэр, Код" style="max-width: 272px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>                                                       
                                            <?php } ?>
                                            <div class="form-group row fom-row hidden">
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
                            <div class="row <?php echo 'hidden'; ?>">
                                <div class="col-5">
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Өөрчлөлт орсон эсэх', 'for' => 'viewEmployee', 'class' => 'col-form-label col-4')); ?>
                                        <div class="col-8">
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
                                <div class="col-7">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <?php if (Config::getFromCache('tmsBalanceIsMovementEmployee') == '1') { ?>
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Шилжилт хөдөлгөөн тооцох орсон эсэх', 'for' => 'isMovementEmployee_' . $this->uniqId, 'class' => 'col-form-label col-4')); ?>
                                        <div class="col-8">
                                            <?php
                                            echo Form::checkbox(
                                                array(
                                                    'name' => 'isMovementEmployee',
                                                    'id' => 'isMovementEmployee_'.$this->uniqId,
                                                    'class' => 'form-control form-control-sm input-xxlarge isMovementEmployee_'.$this->uniqId,
                                                    'value' => '1',
                                                )
                                            );
                                            ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="col-7">
                                    <div class="row">
                                        <div class="col-6">
                                <?php if (Config::getFromCache('tmsCalcIdCode') == '1' && Config::getFromCache('hideReason') != '1') { ?>
                                        <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => 'Утгын хайлт', 'for' => 'stringValue', 'class' => 'col-form-label col-3 p-0 margin-0')); ?>
                                            <div class="col-9">
                                                <div class="">
                                                    <input type="text" id="stringValue" name="stringValue" class="form-control form-control-sm input-xxlarge" placeholder="Овог, Нэр, Код" style="max-width: 272px;">
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>                                
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row main-balance-btn">
                                <div class="col-6">
                                    <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success float-left search-tms-btn', 'onclick' => 'getBalanceList(\''. $this->uniqId .'\')', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                                    <button type="button" data-uniqid='<?php echo $this->uniqId ?>' class="btn btn-sm blue btn-circle float-left downloadDataIO" title="Бодолт хийх"><i class="fa fa-calculator"></i> Бодолт хийх</button>
                                </div>
                                <div class="col-6 pl-0 pr-0">
                                    <?php if ($this->getMetaDataIdWorkflow) { ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle balance-workflow-btn" data-toggle="dropdown" aria-expanded="false"><i class="icon-cog5 mr-2"></i> Төлөв өөрчлөх</button>
                                            <div class="dropdown-menu dropdown-menu-right workflow-dropdown-balance" x-placement="bottom-end">
                                                <a href="javascript:;" class="dropdown-item" onclick="seeWfmStatusForm(this, '1546957164905462');"><i class="icon-menu7"></i> Өөрчлөлтийн түүх харах</a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php echo Form::button(array('class' => 'btn btn-sm btn-circle default balanceClear hidden', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('clear_btn'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
            <!--<div class="row mergeCelltnaEmployeeBalance">
                <div class="col-12 mt10">
                    <a class="btn btn-secondary btn-sm btn-circle value-grid-merge-cell-tnaEmployeeBalance default active" title="Merge cell" href="javascript:;"><i class="fa fa-columns"></i></a>  
                </div>
            </div>-->
            <div class="row">
                <div class="col-12 mt10 xs-form">
                    <div class="jeasyuiTheme3 tna-balance-data-grid-div-<?php echo $this->uniqId ?> <?php echo $this->uniqId ?>" style="width: 100%">
                        <table id="tna-balance-data-grid-<?php echo $this->uniqId ?>" class="w-100"></table>
                    </div>                                    
                    <div id="datagridselectedRowsDetail"></div>
                </div> 
                <div class='clearfix w-100'></div>
                <div class="col-12 xs-form color-description-<?php echo $this->uniqId; ?> hide">
                <div class='col-12 mt10 row' style="background-color: #fff;padding-top: 8px;padding-bottom: 5px;">
                    <div class="col-md-3" style="text-align: center;">
                        <span style="background-color: #dff0d8; height: 15px; width: 30px; position: absolute;"></span><span style="margin-left: 35px">Зөрчилгүй</span>
                    </div>
                    <div class="col-md-3" style="text-align: center;">
                        <span style="background-color: #fbeac5; height: 15px; width: 30px; position: absolute;"></span><span style="margin-left: 35px">Хоцролттой</span>
                    </div>
                    <div class="col-md-3" style="text-align: center;">
                        <span style="background-color: #f2dede; height: 15px; width: 30px; position: absolute;"></span><span style="margin-left: 35px">Зөрчилтэй</span>
                    </div>
                    <div class="col-md-3" style="text-align: center;">
                        <span style="background-color: #B4D8E7; height: 15px; width: 30px; position: absolute;"></span><span style="margin-left: 35px">Илүү Ажилласан</span>
                    </div>
                </div>
                </div>
            </div>
        </div> 
    </div>
    
    <!--
    <div class="right-sidebar tna-sidebar-container right-sidebar-<?php echo $this->uniqId  ?>" data-status="opened" style="margin-top: 9px;">
        <div class="stoggler sidebar-right sidebar-right-<?php echo $this->uniqId ?>" onclick="tnaTimeBalanceStoggler(this, '<?php echo $this->uniqId ?>')">
            <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
            <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
        </div>
        <form id="tnaTimeBalanceForm" class="form-horizontal" method="post">
            <div class="right-sidebar-content-<?php echo $this->uniqId ?>"></div>
        </form>
    </div>-->
    
    <?php 
    echo Form::hidden(array('id' => 'currentSelectedRowIndex-'. $this->uniqId, 'value' => '')); 
    echo Form::hidden(array('id' => 'balanceType-'. $this->uniqId, 'value' => $this->mergeView ? 'merge' : '')); 
    ?>
    
</div>
<!--<div class="form-actions mt15 form-actions-btn">
    <div class="row">
        <div class="col-md-9">
            <?php //echo $this->balanceBtn['all']; ?>            
        </div>
        <div class="col-md-3"></div>
    </div>
</div>-->

<script type="text/javascript">
    var tmsCustomerCode = '<?php echo Config::getFromCache('tmsCustomerCode'); ?>';
    var isbalanceTableAutoHeight = '<?php echo Config::getFromCache('isbalanceTableAutoHeight'); ?>';
    
    if (typeof TIMEBALANCEV5 === 'undefined') {
        $.ajax({
            url: "middleware/assets/js/time/timeV5/timeBalanceV5.v<?php echo getUID(); ?>.js",
            dataType: "script",
            cache: true,
            async: false,
            beforeSend: function() {
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
            }
        }).done(function() {
        });
    }
</script>

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
    #dialog-tna-subgrid .io-time {
        color: #000;
        font-weight: bold;
    }
    #dialog-tna-subgrid .datagrid-row-over .io-time {
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
    #tnaTimeBalanceWindow<?php echo $this->uniqId ?> .ui-multiselect {
        background-color: #fff !important;
        color: #656565 !important;
        border: 1px solid #d0d0d0;
        border-radius: 3px;
    }
    .editLog-<?php echo $this->uniqId ?> {
        font-size: 12px;
        color: #333;
    }
</style>