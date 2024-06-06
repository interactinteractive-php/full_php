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
                                                                'name' => 'departmentId[]',
                                                                'id' => 'departmentId-'.$this->uniqId,
                                                                'multiple' => 'multiple',
                                                                'class' => 'form-control form-control-sm input-xxlarge',
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

                                                echo Form::label(array('text' => $labelname, 'for' => 'startDate', 'class' => 'col-form-label col-md-4'));
                                                ?>
                                                <div class="col-md-8 groupIdTimeEmployeePlanC">
                                                    <?php
                                                    echo Form::multiselect(
                                                            array(
                                                                'name' => 'groupId[]',
                                                                'id' => 'groupIdTimeEmployeePlan-' . $this->uniqId,
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
                                                                    'name' => 'planYear',
                                                                    'id' => 'planYear',
                                                                    'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                                    'data' => Info::getRefYearList(),
                                                                    'op_value' => 'YEAR_CODE',
                                                                    'op_text' => 'YEAR_NAME',
                                                                    'required' => 'required',
                                                                    'value' => Date::currentDate('Y')
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
                                            <div class="form-group row fom-row">
                                                <?php echo Form::label(array('text' => 'Албан тушаал', 'for' => 'Албан тушаал', 'class' => 'col-form-label col-md-3')); ?>
                                                <div class="col-md-4">
                                                    <div class="input-icon right">
                                                        <?php
                                                        echo Form::select(
                                                                array(
                                                                    'name' => 'positionId',
                                                                    'id' => 'positionId',
                                                                    'class' => 'form-control select2 form-control-sm input-xxlarge',
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
                                                <div class="col-md-5">
                                                    <div class="input-icon right">
                                                        <?php
                                                        echo Form::text(
                                                                array(
                                                                    'name' => 'stringValue',
                                                                    'id' => 'stringValue',
                                                                    'class' => 'form-control form-control-sm input-xxlarge',
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
                    <div id="setSideBarDefaultContent"></div>
                    <div class="grid-row-content isVerifyBtn" id="setSideBarAddTimePlan"></div>

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
</div>
