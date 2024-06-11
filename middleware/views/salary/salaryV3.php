<?php
if (!$this->isAjax) {
    ?>
    <div class="col-md-12">
        <div class="card light shadow">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title">
                    <i class="fa fa-pencil-square"></i> <?php echo $this->title; ?>
                </div>
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
            <div id="calculateSalarySheetDiv_<?php echo $this->uniqId; ?>">     
                <div id="calcInfoWindow_<?php echo $this->uniqId; ?>">
                <div class="tabbable-line">
                    <div class="salary-filter-header-info" style="position: absolute;margin-left: 220px;"></div>                    
                    <ul class="nav nav-tabs bp-addon-tab" id="prl_salary_tname_<?php echo $this->uniqId; ?>">
                        <li class="nav-item" data-fetch>
                            <a href="#prl_salary_tab_<?php echo $this->uniqId; ?>" class="nav-link active" data-toggle="tab">Цалин бодолтын мэдээлэл</a>
                        </li>            
                    </ul>
                    <div class="tab-content" style="padding-top: 10px;" id="prl_salary_tcontent_<?php echo $this->uniqId; ?>">
                        <div class="tab-pane active" id="prl_salary_tab_<?php echo $this->uniqId; ?>">                    
                            <div class="row">
                        <div class="col-md-12 center-sidebar" style="overflow: hidden;">
                            <form id="calcInfoForm_<?php echo $this->uniqId; ?>" class="form-horizontal xs-form" method="post">
                                <div class="form-body">
                                    <div class="card-header card-header-no-padding header-elements-inline hidden" style="min-height: 0px;">
                                        <div class="caption p-0 card-collapse _collapse"><i class="fa fa-search"></i> Шүүлтүүр</div>
                                        <div class="tools p-0"> 
                                            <a href="javascript:;" class="tool-collapse collapse"></a>
                                        </div>
                                    </div>
                                    <?php
                                        $configCriteriaTemplate = Config::getFromCache('prlCalculateTemplateCretria');
                                        $configCriteriaTemplate2 = Config::getFromCache('prlCalculateTemplateCretria2');
                                        
                                        if ($configCriteriaTemplate == '1') {
                                            $dNoneClass = '';
                                        } else {
                                            $dNoneClass = ' d-none';
                                        }
                                        
                                        $drillDownEmployeeId = (isset($this->salaryBookInfo['EMPLOYEE_ID']) && $this->salaryBookInfo['EMPLOYEE_ID'] != '') ? $this->salaryBookInfo['EMPLOYEE_ID'] : '';
                                        $drillDownEmployeeName = (isset($this->salaryBookInfo['FIRST_NAME']) && $this->salaryBookInfo['FIRST_NAME'] != '') ? $this->salaryBookInfo['FIRST_NAME'] : '';
                                        $drillDownEmployeeCode = (isset($this->salaryBookInfo['EMPLOYEE_CODE']) && $this->salaryBookInfo['EMPLOYEE_CODE'] != '') ? $this->salaryBookInfo['EMPLOYEE_CODE'] : '';                                        
                                    ?>
                                    <div class="card-body form xs-form display-none top-sidebar-content mb10 pl0 pr0" style="display: block;">
                                        <div class="row">
                                            <div class="col calculate-department-cls<?php echo empty($drillDownEmployeeId) ? '' : ' hidden'; ?>">
                                                <div class="next-generation-input-wrap next-generation-input-wrap-1">
                                                    <div class="next-generation-input-label green">                                                                
                                                        <select name="prlCalculateType" class="prlCalculateType" style="background-color: transparent; border: 0">
                                                            <option <?php echo empty($drillDownEmployeeId) ? 'selected="selected"' : ''; ?> value="department"><?php echo $this->lang->line('work_department_name_salary'); ?></option>
                                                            <option <?php echo empty($drillDownEmployeeId) ? '' : 'selected="selected"'; ?> value="employee">Ажилтнаар</option>
                                                        </select>                                                                
                                                        <div class="next-generation-input-group" style="width: 84%">
                                                            <?php
                                                                $drillDownDepartmentId = (isset($this->salaryBookInfo['DEPARTMENT_ID']) && empty($drillDownEmployeeId) && $this->salaryBookInfo['DEPARTMENT_ID'] != '') ? $this->salaryBookInfo['DEPARTMENT_ID'] : '';
                                                                $drillDownDepartmentName = (isset($this->salaryBookInfo['DEPARTMENT_NAME']) && $this->salaryBookInfo['DEPARTMENT_NAME'] != '') ? $this->salaryBookInfo['DEPARTMENT_NAME'] : '';
                                                                $drillDownDepartmentSelect = (isset($this->salaryBookInfo['DEPARTMENT_ID']) && $this->salaryBookInfo['DEPARTMENT_ID'] != '') ? $this->selectedDepsCount . ' сонгогдсон' : '';
                                                            ?>
                                                            <label class="ml10" style="position: absolute; right: -32px; top: 4px;">
                                                                <input type="checkbox" name="isChild" value="1" title="Дэд салбар"/>Дэд
                                                            </label>
                                                            <div class="input-icon right groupDepartmentId_<?php echo $this->uniqId ?>" data-toggle="dropdown" aria-expanded="true">
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
                                                    <div class="next-generation-input-body green">
                                                        <div class="selectedDepartmentNamesWrap hidden">
                                                            <div id="selectedDepartmentNamesContainer_<?php echo $this->uniqId; ?>" class="selectedDepartmentNamesContainer"></div>
                                                            <div class="selectedDepartmentNamesContainerBtn" data-depnames="close">Дэлгэрэнгүй харах</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col<?php echo empty($drillDownEmployeeId) ? ' hidden' : ''; ?> calculate-employee-cls">
                                                <div class="next-generation-input-wrap">
                                                    <div class="next-generation-input-label green">
                                                        <select style="background-color: transparent; border: 0" class="prlCalculateType">
                                                            <option <?php echo empty($drillDownEmployeeId) ? 'selected="selected"' : ''; ?> value="department"><?php echo $this->lang->line('work_department_name_salary'); ?></option>
                                                            <option <?php echo empty($drillDownEmployeeId) ? '' : 'selected="selected"'; ?> value="employee">Ажилтнаар</option>
                                                        </select>
                                                        <div class="next-generation-input-group" style="width:84%">
                                                            <div class="meta-autocomplete-wrap">
                                                                <div class="input-group">
                                                                    <input type="hidden" id="employee_valueField" class="employee_valueField" name="employeeIds" value="<?php echo $drillDownEmployeeId; ?>">
                                                                    <input type="text" id="employeeCode_displayField" name="employeeCode" class="form-control form-control-sm meta-autocomplete-salary lookup-code-autocomplete-salary calcTypeCode_displayField" value="<?php echo $drillDownEmployeeCode; ?>" title="" placeholder="Код" data-metadataid="0" data-processid="0" data-lookupid="<?php echo $this->lookUpEmployee['META_DATA_ID']; ?>" data-lookuptypeid="<?php echo $this->lookUpEmployee['META_TYPE_ID']; ?>">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" style="background-color: #fff; height: 25px;" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('PAYROLL_EMPLOYEE_LIST', 'multiple', 'calcEmployeeSelectabledGrid_<?php echo $this->uniqId; ?>', '', this);"><i class="fa fa-search"></i></button>
                                                                    </span>
                                                                </div>                                                                
                                                            </div>                                                                
                                                        </div>    
                                                    </div>
                                                    <div class="next-generation-input-body green tms-departmentname-<?php echo $this->uniqId; ?>">
                                                        <div class="selectedDepartmentNamesWrap hidden">
                                                            <div id="selectedEmployeeNamesContainer_<?php echo $this->uniqId; ?>" class="selectedEmployeeNamesContainer"></div>
                                                            <!--<div class="selectedDepartmentNamesContainerBtn" data-depnames="close">Дэлгэрэнгүй харах</div>-->
                                                            <?php echo $drillDownEmployeeName; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     
                                            <div class="col-md-auto">
                                                <div class="next-generation-input-wrap" style="width: 210px">
                                                    <div class="next-generation-input-label blue" style="width: 100%">
                                                        Бодолтын загвар
                                                        <div>
                                                            <?php
                                                            echo Form::select(
                                                                array(
                                                                    'name' => 'calcTypeId',
                                                                    'id' => 'calcTypeId_valueField',
                                                                    'text' => $this->lang->line('choose'),
                                                                    'class' => 'form-control form-control-sm select2 mt2 calcTypeId_valueField',
                                                                    'data' => $this->lookUpCalcType,
                                                                    'op_value' => 'id',
                                                                    'op_custom_attr' => array(
                                                                        array('attr' => 'data-usebooknumber', 'key' => 'usebooknumber')
                                                                    ),
                                                                    'value' => isset($this->salaryBookInfo) ? $this->salaryBookInfo['CALC_TYPE_ID'] : '',
                                                                    'op_text' => 'calctypecode|-|calctypename'
                                                                )
                                                            );
                                                            ?>                                          
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto<?php echo $dNoneClass ?>">
                                                <div class="next-generation-input-wrap" style="width: 210px">
                                                    <div class="next-generation-input-label blue" style="width: 100%">
                                                        <span class="required">*</span> <?php echo $this->lang->line('prlsavedcriteriatemplate'); ?>
                                                        <div>
                                                            <?php
                                                            $param = array(
                                                                'data' => array(
                                                                    array(
                                                                        'id' => issetParam($this->salaryBookInfo['CRITERIA_TEMPLATE_ID']),
                                                                        'name' => issetParam($this->salaryBookInfo['CRITERIA_TEMPLATE_NAME'])
                                                                    )
                                                                ),
                                                                'op_value' => 'id',
                                                                'op_text' => 'name',                                                                
                                                                'name' => 'criteriaTemplateId',
                                                                'id' => 'criteriaTemplateId',
                                                                'text' => $this->lang->line('choose'),
                                                                'class' => 'form-control form-control-sm dropdownInput select2 mt2',
                                                                'value' => issetParam($this->salaryBookInfo['CRITERIA_TEMPLATE_ID']),
                                                            );
                                                            if (!isset($this->salaryBookInfo)) {
                                                                $param['disabled'] = '';
                                                            }
                                                            echo Form::select($param);
                                                            ?>                                                       
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>                                                          
                                            <div class="col-md-auto">
                                                <div class="next-generation-input-wrap" style="width: 210px">
                                                    <div class="next-generation-input-label blue" style="width:60%">
                                                        <?php echo $this->lang->line('PL_0056'); ?>
                                                        <div class="next-generation-input-group">
                                                            <div class="meta-autocomplete-wrap">
                                                                <div class="input-group">
                                                                    <input type="hidden" id="calcId_valueField" class="calcId_valueField" name="calcId" value="<?php echo isset($this->salaryBookInfo['CALC_ID']) ? $this->salaryBookInfo['CALC_ID'] : issetParam($this->getSuggestionInfo['id']); ?>" data-startdate="<?php echo isset($this->salaryBookInfo) ? issetParam($this->salaryBookInfo['START_DATE']) : issetParam($this->getSuggestionInfo['startdate']); ?>" data-enddate="<?php echo isset($this->salaryBookInfo) ? issetParam($this->salaryBookInfo['END_DATE']) : issetParam($this->getSuggestionInfo['enddate']); ?>" required="required">
                                                                    <input type="text" id="calcCode_displayField" name="calcCode"<?php echo !isset($this->salaryBookInfo) && $configCriteriaTemplate == '1' ? ' disabled' : ''; ?> class="form-control form-control-sm meta-autocomplete-salary lookup-code-autocomplete-salary calcCode_displayField" value="<?php echo isset($this->salaryBookInfo['CALC_CODE']) ? $this->salaryBookInfo['CALC_CODE'] : issetParam($this->getSuggestionInfo['calccode']); ?>" required="required" title="" placeholder="Код" data-metadataid="0" data-processid="0" data-lookupid="<?php echo $this->lookUpCalc['META_DATA_ID']; ?>" data-lookuptypeid="<?php echo $this->lookUpCalc['META_TYPE_ID']; ?>">                                                                        
                                                                    <span class="input-group-btn">
                                                                        <?php if ($configCriteriaTemplate == '1') { ?>
                                                                            <button type="button"<?php echo isset($this->salaryBookInfo) ? '' : ' disabled'; ?> style="background-color: #fff; height: 25px" id="searchCalcButton" class="btn default btn-bordered form-control-sm mr0 searchCalcButton" onclick="calcSelectabledGridClick_<?php echo $this->uniqId; ?>(this);"><i class="fa fa-search" style="color: #616161;"></i></button>
                                                                        <?php } elseif ($configCriteriaTemplate2 == '1') { ?>
                                                                            <button type="button"<?php echo isset($this->salaryBookInfo) ? '' : ' disabled'; ?> style="background-color: #fff; height: 25px" id="searchCalcButton" class="btn default btn-bordered form-control-sm mr0 searchCalcButton" onclick="calcSelectabledGridClick_<?php echo $this->uniqId; ?>(this);"><i class="fa fa-search" style="color: #616161;"></i></button>
                                                                        <?php } else { ?>
                                                                            <button type="button" style="background-color: #fff; height: 25px" id="searchCalcButton" class="btn default btn-bordered form-control-sm mr0 searchCalcButton" onclick="dataViewCustomSelectableGrid('PRL_CALC_DV', 'single', 'calcSelectabledGrid_<?php echo $this->uniqId; ?>', '', this);"><i class="fa fa-search" style="color: #616161;"></i></button>
                                                                        <?php } ?>
                                                                    </span>
                                                                </div>                                                                
                                                            </div>                                                                
                                                        </div>    
                                                    </div>
                                                    <div class="next-generation-input-body blue" style="width:40%">
                                                        <?php echo isset($this->salaryBookInfo['CALC_NAME']) ? $this->salaryBookInfo['CALC_NAME'] : issetParam($this->getSuggestionInfo['calcname']); ?>
                                                    </div>
                                                </div>
                                            </div>                                          
                                            <div class="col-md-auto">
                                                <div style="width: 100px">
                                                    <?php
                                                    echo Form::button(array('class' => 'btn btn-circle btn-success bg-navi-blue searchCalcInfo btn-sm', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('view_btn')));
                                                    echo Form::button(array('class' => 'btn btn-circle btn-success bg-navi-blue reSearchCalcInfo btn-sm', 'value' => '<i class="fa fa-edit"></i> ' . $this->lang->line('MET_99990769'), 'style' => 'display: none; margin-top: 0;'));
                                                    ?>               
                                                    <div class="clearfix"></div>                                                            
                                                    <input type="hidden" name="javaCacheId" value="">
                                                    <input type="hidden" name="fromCache" value="0">
                                                    <input type="hidden" name="isBatchNumber" value="<?php echo isset($this->batchNumber) ? '1' : '0' ?>">
                                                    <input type="hidden" name="salaryBookId" value="<?php echo $this->salaryBookId ?>">
                                                    <input type="hidden" name="singleEditMode" value="<?php echo $this->singleEditMode ?>">
                                                    <input type="hidden" name="batchNumber" value="">
                                                    <input type="hidden" name="isChange" value="<?php echo $this->isChange; ?>">
                                                    <input type="hidden" name="isAllEmployeeSelected" value="">
                                                    <input type="hidden" name="calcTypeName" value="<?php echo isset($this->salaryBookInfo['CALC_TYPE_NAME']) ? $this->salaryBookInfo['CALC_TYPE_NAME'] : ''; ?>">
                                                    <input type="hidden" name="bookNumber" value="<?php echo isset($this->salaryBookInfo['BOOK_NUMBER']) ? $this->salaryBookInfo['BOOK_NUMBER'] : ''; ?>">                                                            
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt10 addon-toolbar-part" style="display: none">
                                                <div class="float-left pt2 pr5"><?php echo $this->lang->line('calc_type_book_type'); ?>:</div>
                                                <div class="meta-autocomplete-wrap float-left" data-section-path="bookTypeId" style="width: 243px;">
                                                    <div class="input-group double-between-input">
                                                        <input type="hidden" name="bookTypeId" id="bookTypeId_valueField" data-path="bookTypeId" class="popupInit" data-criteria-param="calcTypeId@calcTypeId">
                                                        <input type="text" name="bookTypeId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="bookTypeId" data-isclear="0" id="bookTypeId_displayField" data-processid="1454315883636" data-lookupid="1515139763791651" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('bookTypeId', '1454315883636', '1515139763791651', 'single', 'bookTypeId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                        </span>     
                                                        <span class="input-group-btn">
                                                            <input type="text" name="bookTypeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="bookTypeId" data-isclear="0" id="bookTypeId_nameField" data-processid="1454315883636" data-lookupid="1515139763791651" placeholder="<?php echo $this->lang->line('name_search'); ?>" autocomplete="off" tabindex="-1">      
                                                        </span>     
                                                    </div>
                                                </div>
                                                <div class="float-left pt2 pl10">
                                                    <?php echo $this->lang->line('book_number'); ?>: <span class="badge label-sm badge-primary" data-path="bookNumber"></span>
                                                </div>
                                            </div>    
                                        </div> 
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <span style="float: left;" class="customLabel text-right existSalaryBook"><?php echo $this->lang->line('MET_99990774'); ?></span>
                                    <select id="filterDepartment_<?php echo $this->uniqId; ?>" multiple="multiple" name="" style="max-width: 350px !important; height: 28px !important;" class="hidden form-control select2 form-control-sm float-left" data-placeholder="- <?php echo $this->lang->line('MET_99990862'); ?> -">
                                        <option value="">- <?php echo $this->lang->line('MET_99990862'); ?> -</option>
                                    </select>                              
                                    <button id="filterDepartmentBtn_<?php echo $this->uniqId; ?>" class="btn hidden btn-sm ml5 btn-secondary"><?php echo $this->lang->line('search_btn'); ?></button>
                                    <!--<label style="white-space: nowrap;"><input type="checkbox" <?php echo empty($drillDownEmployeeId) ? '' : 'checked'; ?> name="isEmployee" value="1"> Ажилтнаар</label>-->
                                    <div class="form-actions row hidden salarySheetActions">
                                        <div class="col-md-12">
                                        <div class="col-md-12">
                                            <?php
                                            echo Form::button(
                                                array(
                                                    'class' => 'btn btn-circle btn-sm btn-success bg-navi-blue float-right ml5 saveSalarySheet hidden',
                                                    'value' => '<i class="fa fa-save"></i> ' . $this->lang->line('save_btn')
                                                )
                                            );
                                            echo $this->salaryBookId != '' ? Form::button(array('class' => 'btn btn-circle btn-sm bg-light-blue float-right salaryBackBtn', 'value' => '<i class="fa fa-reply"></i> ' . $this->lang->line('back_btn'))) : '';
                                            echo Html::anchor(
                                                'javascript:;',
                                                '<i class="fa fa-expand"></i>',
                                                array(
                                                    'onclick' => "javascript:;",
                                                    'title' => "Fullscreen",
                                                    'class' => 'btn float-right btn-sm btn-circle bg-light-blue ml5 salary-datarid-fullscreen-btn'
                                                ),
                                                true 
                                            );
                                            echo Html::anchor(
                                                'javascript:;',
                                                '<i class="fa fa-chevron-up"></i>',
                                                array(
                                                    'onclick' => "javascript:;",
                                                    'title' => "Хураах",
                                                    'class' => 'btn float-right btn-sm btn-circle bg-light-blue ml5 salary-datarid-collapsed-btn'
                                                ),
                                                true 
                                            );
                                            echo Html::anchor(
                                                'javascript:;',
                                                '<i class="fa fa-info"></i>',
                                                array(
                                                    'onclick' => "getHelpContent(1, '12345678910', '')",
                                                    'title' => $this->lang->line('PL_0243'),
                                                    'class' => 'btn float-right btn-sm btn-circle bg-light-blue ml5'
                                                ),
                                                Config::getFromCache('isHideDocumentationSalary') == '1' ? false : true 
                                            );       
                                            echo Html::anchor(
                                                'javascript:;',
                                                '<i class="fa fa-cog"></i>',
                                                array(
                                                    'onclick' => 'window[\'salaryObj' . $this->uniqId . '\'].salaryColumnConfigPosition()',
                                                    'title' => $this->lang->line('PL_0243'),
                                                    'class' => 'btn float-right btn-sm btn-circle bg-light-blue ml5'
                                                )
                                            );                        
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-shopping-cart"></i> <span class="save-database-'. $this->uniqId .'">0</span>', array(
                                                    'class' => 'btn float-right btn-sm btn-circle bg-light-blue ml5',
                                                    'onclick' => 'prlUseBasketView_' . $this->uniqId . '(this);',
                                                    'title' => $this->lang->line('META_00113'),
                                                ), $configCriteriaTemplate == '1' ? true : false 
                                            );                                    
                                            ?>                        
                                            <div class="btn-group float-right ml5">
                                                <button type="button" class="btn btn-sm bg-light-blue btn-circle dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="fa fa-table"></i> <?php echo $this->lang->line('excel'); ?>
                                                </button>
                                                <ul class="dropdown-menu float-right">
                                                    <li>
                                                        <a class="exportExcelSalary" href="javascript:;"><?php echo $this->lang->line('excel_btn'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a class="importTemplateExcelSalary" href="javascript:;"><?php echo $this->lang->line('template_excel_output'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a class="importExcelSalary" href="javascript:;"><?php echo $this->lang->line('MET_99990861'); ?></a>
                                                    </li>
                                                </ul>
                                                <form action="mdsalary/export_excel1" id="xlform" method="post">
                                                    <input type="hidden" name="p" id="p">
                                                </form>
                                            </div>                           
                                            <div class="btn-group float-right ml5">
                                                <button type="button" class="btn btn-sm bg-light-blue btn-circle dropdown-toggle" onClick="window['salaryObj<?php echo $this->uniqId; ?>'].actionBtnListener(this)" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="fa fa-bars"></i> Үйлдлүүд
                                                </button>
                                                <ul class="dropdown-menu float-right">
                                                    <li>
                                                        <?php echo Html::anchor('javascript:;', $this->lang->line('MET_99990772'), array('class' => 'addEmployeeButton', 'onclick' => 'selectedEmployeeSalary()')); ?>
                                                    </li>
                                                    <li>
                                                        <a class="setColumnSameValue" href="javascript:;"><?php echo $this->lang->line('PL_0130'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a class="copyColumn" href="javascript:;"><?php echo $this->lang->line('MET_99990771'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a class="getProcessRunSalary" href="javascript:;"><?php echo $this->lang->line('prl_salary_getprocess_btn'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a class="duplicateColumn" href="javascript:;"><?php echo $this->lang->line('prl_salary_duplicatecolvalue_btn'); ?></a>
                                                    </li>
    <!--                                                <li>
                                                        <a class="saveChange" href="javascript:;">Өөрчлөлт хадгалах</a>
                                                    </li>-->
                                                    <li>
                                                    </li>
                                                </ul>
                                                <form action="mdsalary/export_excel1" id="xlform" method="post">
                                                    <input type="hidden" name="p" id="p">
                                                </form>
                                            </div>         
                                            <?php
                                            echo Form::button(array('class' => 'btn btn-sm btn-circle btn-success bg-navi-blue float-right calculateSalarySheet ml5','value' => '<i class="fa fa-calculator"></i> ' . $this->lang->line('MET_99990770')));
                                            ?>    
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="w-100">
                                <div class="jeasyuiTheme3 mt5">
                                    <table class="no-border" id="salaryDatagrid_<?php echo $this->uniqId; ?>" style="width: 100%;"></table>
                                </div>
                            </div>
                            <div class="clearfix w-100"></div>
                            <div class="form-actions mt5" id="action_<?php echo $this->uniqId; ?>">
                                <div class="justify-content-end">
                                    <div class="col-md-12 row">
                                        <div class="col-md-12" style="padding-left: 0;">
                                            <div class="float-left sheetExpression"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="right-sidebar" data-status="closed">
                        <div class="stoggler sidebar-right hidden">
                            <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span>
                            <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
                        </div>
                        <div class="right-sidebar-content">
                            <div class="card light bg-blue-hoki">
                                <div class="card-body">
                                    <div class="clearfix w-100">
                                        <a href="javascript:;" class="float-left thumb avatar border m-r">
                                            <img src="assets/core/global/img/user.png" class="rounded-circle" id="sidebar-user-logo">
                                        </a>
                                        <div class="clear">
                                            <div class="h4 mt5 mb5 text-color-white" style="font-size: 12px !important">
                                                <div id="sidebar-employee-name" class="sidebar-employee-name"></div>
                                                <div id="sidebar-department-name" class="sidebar-department-name"></div>
                                                <div id="sidebar-position-name" class="sidebar-position-name"></div>
                                                <input type="hidden" id="salarySheetId" class="salarySheetId" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default bg-inverse">
                                <table class="table sheetTable calc-sidebar" id="calc-sidebar">
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div id="dialog-copy-field_<?php echo $this->uniqId; ?>"></div>
                    <div id="dialog-append-employee_<?php echo $this->uniqId; ?>"></div>
                    <div id="dialog-confirm-template-excel_<?php echo $this->uniqId; ?>"></div>
                    <div id="dialog-delete-confirm-employee_<?php echo $this->uniqId; ?>"></div>
                    <div id="dialog-prl-call-process_<?php echo $this->uniqId; ?>"></div>
                    <div id="dialog-expression-error_<?php echo $this->uniqId; ?>"></div>
                    <div id="dialog-batchNumber-confirm-employee_<?php echo $this->uniqId; ?>"></div>
                </div>        
                </div>        
                </div>        
                </div>        
            </div>    
            <form id="salary_fileupload_<?php echo $this->uniqId; ?>" class="hidden" method="post" enctype="multipart/form-data">
                <input type="file" class="selectedExcelFile" name="selectedExcelFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
            </form>
<?php
if (!$this->isAjax) {
?>       
            </div>
        </div>
    </div>
<?php
}
?>

<style type="text/css">
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
    
    #calculateSalarySheetDiv .customLabel {
        padding-top: 3px;
        font-size: 14px;
        
        font-weight: 400;
        color: #444;
        text-align: right;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> #fieldExpressionSpan_<?php echo $this->uniqId; ?>, #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> #fieldSpan_<?php echo $this->uniqId; ?>{
        font-size: 14px !important;
        
        text-align: justify;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .form-control-sm {
        border-radius: 0px !important        
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell{
        padding: 0px !important;
        display: -ms-flexbox!important;
        display: flex!important;        
        /*padding-left: 5px !important;*/
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body td input[type="text"]:focus{
        border: 1px solid #999 !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .salary-cart-title {
        margin-bottom: 10px;
        color: #333;
        text-align: center; 
        font-weight: 600;
        font-family: 'Roboto Light';
    }   
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-tile:hover {
        background-color: #ff6f55;
    }   
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-descr {
        font-size: 12px;
    }     
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {
        font-size: 18px;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body td{
        /*border-width: 0px 0px 0px 0px !important;*/
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body .form-control {
        border: 0px !important;
        font-size: 12px !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-footer .form-control {
        font-size: 12px !important;
    }

    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-footer input{
        border: 0px !important;
        font-weight: bold !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-footer .form-control[readonly]{
        background-color: #fff;
    }
    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-body{
        height: auto !important;
    }

    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-wrap{
        height: 240px !important;
    }
    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-view{
        height: 200px !important;
    }
    #dialog_for_employee_<?php echo $this->uniqId; ?>{
        height: 200px !important;
    }
    .datagrid-row-over td {
        background: #fff;
    }  
    .datagrid-row-selected td {
        background: #98ccff !important;
        border-bottom-color: #98ccff;
        color: #000;
    }  
    .datagrid-row-selected input {
        background: #98ccff !important;
        color: #000;
    }  
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell input.saved-log-data-cell {
        width: 60%;
        float: left;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell a.btn-secondary {
        border-bottom-style: none;
        border-right-style: none;
        border-top-style: none;
        border-radius: 50%;
        border-left-color: #333;
        margin-top: 1px;    
        padding: 0px 5px;
        height: 20px;        
    }
    .jeasyuiTheme3 .datagrid-header .datagrid-cell span {
        font-size: 11px;
        color: #333;
    }
    .multipleAddFilterBtn {
        position: absolute;
        margin-top: -22px;
        right: 0;
        margin-right: 30px;        
    }        
    .context-menu-list {
        box-shadow: 5px 5px 5px -3px rgba(0,0,0,0.6);
    }
    .selectedDepartmentNamesContainer {
        max-height: 58px;
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
    
    /* Custom Card CSS Start */
    .next-generation-input-wrap {
        width: 100%;
        height: 60px;
        -moz-box-shadow: 0px 0px 10px 0px #dadada;
        -webkit-box-shadow: 0px 0px 10px 0px #dadada;
        box-shadow: 0px 0px 10px 0px #dadada;        
    }
    @media screen and (min-width: 1210px) {
        .next-generation-input-label {
            width: 40%;
        }
        .next-generation-input-body {
            width: 60%;
        }
    }
    @media screen and (max-width: 1210px) {
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
        padding-top: 5px;
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
        overflow: hidden;
        line-height: 15px;
    }
    .next-generation-input-label.green {
        background-color: #e1ebcb;
        border-left: 5px solid #abc66a;
    }    
    .next-generation-input-label.green label {
        color: #666;
        font-size: 11px;
    }
    .next-generation-input-label.green .checker {
        margin-top: 0px !important;
        margin-left: 0px !important;
    }
    .next-generation-input-body.green {
        background-color: #f3f7e8;
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
        bottom: 8px;
    }       
    .next-generation-input-label > .next-generation-input-group {
        position: absolute;
        bottom: 8px;
    }       
    .groupDepartmentId_<?php echo $this->uniqId; ?> {
        padding-right: 8px;
    }       
    .next-generation-input-label .input-group-btn {
        padding-right: 8px;
    }       
    .salarySheetActions {
        margin-top: -2px;
    }
    @media screen and (min-width: 1050px) {
        .input-icon.right .form-control {
            padding-right: 0px;
            margin-right: 85px;
        }
    }
    /* Custom Card CSS End */
    .select2-container-multi .select2-choices {
        padding-top: 0;
    }
    .select2-container-multi .select2-choices .select2-search-choice {
        margin: 1px 0 3px 1px;
    }
    .existSalaryBook {
        font-style: italic;
        color: #e80505; 
        text-align: center; 
        display: none; 
        position: relative; 
        top: 8px;        
        font-size: 12px;
    }
    .select2-container-multi .select2-choices .select2-search-field input {    
        padding: 2px;
    }
    .page-footer {
        height: 0px !important;
    }
    .jeasyuiTheme3 .datagrid-row {
      height: 25px;	
    }    
    #selectedDepartmentNamesContainer_<?php echo $this->uniqId; ?> span:hover {
        background-color: #c9dca3;
    }
    #selectedDepartmentNamesContainer_<?php echo $this->uniqId; ?> span:hover i {
        display: inline-block !important;
        cursor: pointer;
        color: #505050;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .right-sidebar > .stoggler {
        height: 40px;
        box-shadow: -3px -2px 5px rgba(0, 0, 0, 0.17);
        right: 0;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .right-sidebar > .stoggler > span {
        margin: 15px 2px;
        font-size: 12px;        
    }    
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .right-sidebar > .stoggler.sidebar-opened {
        left: -10px;
    }    
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-view1 .datagrid-body input:not(.datagrid-filter) {
        background-color: #ffddd3;
    }    
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .right-sidebar > .right-sidebar-content, #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .right-sidebar {
        margin-top: 0;
    }    
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .fa-filter {
        color: #a0a0a0;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .bg-navi-blue {
    background-color: #1ed6c3 !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .bg-light-blue {
    background-color: #41c3f1 !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .form-control {
        height: <?php echo Config::getFromCacheDefault('PayrollWindowRowPX', null, '24px') ?> !important;
        min-height: <?php echo Config::getFromCacheDefault('PayrollWindowRowPX', null, '24px') ?> !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-row {
        height: <?php echo Config::getFromCacheDefault('PayrollWindowRowPX', null, '25px') ?> !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-cell-check {
        height: <?php echo Config::getFromCacheDefault('PayrollWindowRowPX', null, '22px') ?> !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 td div, 
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body .form-control, 
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 td span {
        font-size: <?php echo Config::getFromCacheDefault('PayrollWindowRowFont', null, '12px') ?> !important;
    }
</style>

<script type="text/javascript">
    /**
     * Init Globe Code To JS
     */
    var PL_0130 = "<?php echo $this->lang->line('PL_0130'); ?>",
        MET_99990771 = "<?php echo $this->lang->line('MET_99990771'); ?>",
        MET_999990992 = "<?php echo $this->lang->line('MET_999990992'); ?>",
        MET_99990770 = "<?php echo $this->lang->line('MET_99990770'); ?>",
        PL_0239 = "<?php echo $this->lang->line('PL_0239'); ?>",
        lname_globecode = "<?php echo $this->lang->line('lname'); ?>",
        fname_globecode = "<?php echo $this->lang->line('fname'); ?>",
        code_globecode = "<?php echo $this->lang->line('code'); ?>",
        template_excel_output = "<?php echo $this->lang->line('template_excel_output'); ?>",
        configKeyUpdate = '<?php echo Config::getFromCache('isHideKeyUpdateSalary') == '1' ? '0' : Config::getFromCache('CONFIG_TNA_HISHIGARVIN'); ?>',
        configNumDec = '<?php echo Config::getFromCacheDefault('CONFIG_TNA_NUMBER_DEC', null, '2'); ?>',
        configCalculation = '<?php echo Config::getFromCache('PRL_CALCULATION') == '1' ? '1' : '0'; ?>',
        configPager = '<?php echo Config::getFromCache('PRL_PAGER') == '1' ? '1' : '0'; ?>',
        configCalculateTemplateCretria = '<?php echo Config::getFromCache('prlCalculateTemplateCretria') == '1' ? '1' : '0'; ?>',
        configSelectedPage = '<?php echo Config::getFromCache('PRL_SELECTED_PAGE') ? Config::getFromCache('PRL_SELECTED_PAGE') : '50'; ?>',
        configAddEmployee = '<?php echo Config::getFromCache('PRL_COPYCALCULATECONFIG') ? Config::getFromCache('PRL_COPYCALCULATECONFIG') : '0'; ?>',
        configRowLockColor = '<?php echo Config::getFromCache('PRL_CALCULATION_COLOR_CODE1') ? Config::getFromCache('PRL_CALCULATION_COLOR_CODE1') : '#ddffda'; ?>',
        configRowGLColor = '<?php echo Config::getFromCache('PRL_CALCULATION_COLOR_CODE2_GL') ? Config::getFromCache('PRL_CALCULATION_COLOR_CODE2_GL') : '#b0eeaa'; ?>',
        varWindowId = "<?php echo $this->uniqId; ?>",
        configCriteriaTemplateJS = "<?php echo $configCriteriaTemplate; ?>",
        configCriteriaTemplateJS2 = "<?php echo $configCriteriaTemplate2; ?>",
        _selectedRows<?php echo $this->uniqId; ?> = [];
    
    /**
     * Binding Class
     */
    
    $.ajax({
        url: "middleware/assets/js/salary/salaryV3.js",
        dataType: "script",
        cache: false,
        async: false
    }).done(function(){
        window['salaryObj' + varWindowId] = new SalaryV3('<?php echo $this->uniqId; ?>');
        window['salaryObj' + varWindowId].initEventListener();
    });
    
    function selectedEmployeeSalary() {
        dataViewCustomSelectableGrid('PAYROLL_EMPLOYEE_LIST', 'multi', 'appendSelectedEmployeeSalary', 'param[startDate]='+$("#calcInfoForm_<?php echo $this->uniqId; ?>").find('.calcId_valueField').attr('data-startdate')+'&param[endDate]='+$("#calcInfoForm_<?php echo $this->uniqId; ?>").find('.calcId_valueField').attr('data-enddate'), this);
    }
    
    function appendSelectedEmployeeSalary(metaDataCode, chooseType, elem, rows) {
        window['salaryObj' + varWindowId].selectedEmployeeSalary(rows);
    }
    
    function selectedKeyEmployeeSalary(metaDataCode, chooseType, elem, rows) {
        window['salaryObj' + varWindowId].selectedKeyEmployeeSalary(rows);
    }
    
    $("body").on("keydown", 'input.lookup-code-autocomplete-salary:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            }
            return false;
        } else {
            if (!_this.data("ui-autocomplete")) {
                lookupAutoCompleteSalary(_this, 'code');
            }
        }
    });  
    
    $("body").on("keydown", 'input.meta-autocomplete-salary:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)', function (e) {
        if (e.which === 13) {
            var _this = $(this);
            var _value = _this.val();
            var _metaDataId = _this.attr("data-metadataid");
            var _processId = _this.attr("data-processid");
            var _lookupId = _this.attr("data-lookupid");
            var _lookupTypeId = _this.attr("data-lookuptypeid");
            var _metaDataCode = _this.attr("data-field-name");
            var bpElem = _this.parent().find("input[type='hidden']");
            var _paramRealPath = bpElem.attr("data-path");
            var _parent = _this.closest("div.meta-autocomplete-wrap");
            var _isName = false;
            var params = '';
            
            if (configCriteriaTemplateJS === '1') {
                params = "booktypeid=15001&calctypeid=" + $("#calcInfoForm_<?php echo $this->uniqId; ?>").find('select[name="calcTypeId"]').val();
                _lookupId = '21573124090418';
            }
            
            if (typeof _this.attr('data-ac-id') !== 'undefined') {
                _isName = 'idselect';
                _value = _this.attr('data-ac-id');
            }
            
            $.ajax({
                type: 'post',
                url: 'mdobject/autoCompleteById',
                data: {
                    processMetaDataId: _processId,
                    metaDataId: _metaDataId,
                    lookupId: _lookupId, 
                    lookupMetaTypeId: _lookupTypeId, 
                    paramRealPath: _paramRealPath,
                    code: _value,
                    isName: _isName, 
                    params: encodeURIComponent(params) 
                },
                dataType: 'json',
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    
                    _this.removeAttr('data-ac-id');
                    
                    var controlsData;
                    var rowData;
                    
                    if (typeof (data.controlsData) !== 'undefined') {
                        controlsData = data.controlsData;
                    }
                    if (typeof (data.rowData) !== 'undefined') {
                        rowData = data.rowData;
                    }
                    
                    if (_parent.closest("div.bp-param-cell").length > 0) {
                        var parentCell = _parent.closest("div.bp-param-cell");
                        var parentTable = _parent.closest("div.xs-form");
                    } else if (_parent.closest("div.form-md-line-input").length > 0) {
                        var parentCell = _parent.closest("div.form-md-line-input");
                        var parentTable = _parent.closest("div.xs-form");
                    } else {
                        if (_parent.closest("div.meta-autocomplete-wrap").length > 0) {
                            var parentCell = _parent.closest("div.meta-autocomplete-wrap");
                        } else {
                            var parentCell = _parent.closest("td");
                        }
                        
                        if (_parent.closest("table.bprocess-table-dtl").length > 0) {
                            var parentTable = _parent.closest("tr");
                        } else {
                            var parentTable = _parent.closest("form");
                        }
                    }
                    
                    if (controlsData !== undefined) {
                        $.each(controlsData, function (i, v) {
                            if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                                var getPathElement = parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");
                                if (getPathElement.length > 0) {
                                    if (getPathElement.prop("tagName").toLowerCase() == 'select') {
                                        if (getPathElement.hasClass('select2')) {
                                            getPathElement.trigger("select2-opening", 'notdisabled');
                                            getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                        } else {                                                
                                            getPathElement.trigger("focus");
                                            getPathElement.val(rowData[v.FIELD_NAME]);
                                        }
                                    } else if (getPathElement.hasClass('dateInit')) {
                                        getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                                    } else if (getPathElement.hasClass('bigdecimalInit')) {
                                        getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                        getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                    } else {
                                        $.getScript('assets/custom/addon/plugins/phpjs/strings/get_html_translation_table.js', function() {
                                            $.getScript('assets/custom/addon/plugins/phpjs/strings/html_entity_decode.js', function() {
                                                getPathElement.val(html_entity_decode(rowData[v.FIELD_NAME])).trigger('change');
                                            });
                                        });
                                    }
                                }
                            }
                        });
                    }

                    if (data.META_VALUE_ID !== '') {
                        if (_parent.find("input[id*='_displayField']").hasClass('calcCode_displayField')) {
                            _parent.find("input[id*='_valueField']").attr('data-startdate', rowData.startdate);
                            _parent.find("input[id*='_valueField']").attr('data-enddate', rowData.enddate);
                        }
                        _parent.find("input[id*='_valueField']").attr('data-row-data', JSON.stringify(rowData));
                        _parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).trigger("change");
                        _parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                        _parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(data.META_VALUE_NAME);
                    } else {
                        _parent.find("input[id*='_valueField']").val('').trigger("change");
                        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text('');
                    }

                    /**
                     * 
                     * @description Sidebar үед ашиглаж байгаа
                     * @author  Ulaankhuu Ts
                     */
                    var selectedTR = $('table.bprocess-table-dtl tbody').find('tr.currentTarget');
                    var fieldPath = _parent.attr('data-section-path');
                    if (selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").length > 0) {
                        _parent.find("input").removeClass("spinner2");
                        selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").empty().append(_parent.html());
                    }
                    _this.removeClass("spinner2");
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    });    
    
    function lookupAutoCompleteSalary(elem, type) {
        var _this = elem;
        var _lookupId = _this.attr("data-lookupid");
        var _metaDataId = _this.attr("data-metadataid");
        var _processId = _this.attr("data-processid");
        var bpElem = _this.parent().parent().find("input[type='hidden']");
        var _paramRealPath = bpElem.attr("data-path");
        var _parent = _this.closest("div.meta-autocomplete-wrap");
        var mainSelector = $("#bp-window-"+_processId+":visible");
        var params = '';
        var isHoverSelect = false;
        
        if (configCriteriaTemplateJS === '1') {
            params = "booktypeid=15001&calctypeid=" + $("#calcInfoForm_<?php echo $this->uniqId; ?>").find('select[name="calcTypeId"]').val();
            _lookupId = '21573124090418';
        }       

        _this.autocomplete({
            minLength: 1,
            maxShowItems: 30,
            delay: 500,
            highlightClass: "lookup-ac-highlight", 
            appendTo: "body",
            position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
            autoSelect: false,
            source: function(request, response) {

                if (lookupAutoCompleteRequest != null) {
                    lookupAutoCompleteRequest.abort();
                    lookupAutoCompleteRequest = null;
                }

                lookupAutoCompleteRequest = $.ajax({
                    type: 'post',
                    url: 'mdwebservice/lookupAutoComplete',
                    dataType: 'json',
                    data: {
                        lookupId: _lookupId, 
                        metaDataId: _metaDataId, 
                        processId: _processId, 
                        paramRealPath: _paramRealPath, 
                        q: request.term, 
                        type: type, 
                        criteriaParams: encodeURIComponent(params) 
                    },
                    success: function(data) {
                        if (type == 'code') {
                            response($.map(data, function(item) {
                                var code = item.split("|");
                                return {
                                    value: code[1], 
                                    label: code[1],
                                    name: code[2], 
                                    id: code[0]
                                };
                            }));
                        } else {
                            response($.map(data, function(item) {
                                var code = item.split("|");
                                return {
                                    value: code[2], 
                                    label: code[1],
                                    name: code[2], 
                                    id: code[0]
                                };
                            }));
                        }
                    }
                });
            },
            focus: function(event, ui) {
                if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                    isHoverSelect = false;
                } else {
                    if (event.keyCode == 38 || event.keyCode == 40) {
                        isHoverSelect = true;
                    }
                }
                return false;
            },
            open: function() {
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function() {
                $(this).autocomplete("option","appendTo","body"); 
            }, 
            select: function(event, ui) {
                var origEvent = event;	

                if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                    if (type === 'code') {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_displayField']").attr('data-ac-id', ui.item.id);
                    } else {
                        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                    }
                } else {
                    if (type === 'code') {
                        if (ui.item.label === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                        } else {
                            _parent.find("input[id*='_displayField']").val(_this.val());
                            event.preventDefault();
                        }
                    } else {
                        if (ui.item.name === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                        } else {
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(_this.val());
                            event.preventDefault();
                        }
                    }
                }

                while (origEvent.originalEvent !== undefined){
                    origEvent = origEvent.originalEvent;
                }

                if (origEvent.type === 'click') {
                    var e = jQuery.Event("keydown");
                    e.keyCode = e.which = 13;
                    _this.trigger(e);
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            ul.addClass('lookup-ac-render');

            if (type === 'code') {
                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            } else {
                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    name = item.name.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+item.label+'</div><div class="lookup-ac-render-name">'+name+'</div>').appendTo(ul);
            }
        };
    }
    
    function calcTypeSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest("div.meta-autocomplete-wrap");
        _parent.find("input[id*='_valueField']").val(row.id);
        _parent.find("input[id*='_valueField']").attr('data-row-data', JSON.stringify(row));
        _parent.find("input[id*='_displayField']").val(row.calctypecode);
        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(row.calctypename);
    }    
    
    function calcEmployeeSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var _parent = $(elem).closest("div.meta-autocomplete-wrap"), eid = '', ename = '', ecode = '';
        for (var i = 0; i < rows.length; i++) {
            eid += rows[i].employeekeyid + ',';
            ecode += rows[i].code + ',';
            ename += rows[i].firstname + ',';
        }
        _parent.find("input[id*='_valueField']").val(rtrim(eid, ','));
        _parent.find("input[id*='_displayField']").val(rtrim(ecode, ','));
        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(rtrim(ename, ','));
    }    
    
    function calcSelectabledGridClick_<?php echo $this->uniqId; ?>(elem) {
        dataViewCustomSelectableGrid('PRL_CALC_DV2', 'single', 'calcSelectabledGrid_<?php echo $this->uniqId; ?>', 'param[booktypeid]=15001&param[calctypeid]='+$("#calcInfoForm_<?php echo $this->uniqId; ?>").find('select[name="calcTypeId"]').val(), elem);
    }
    
    function calcSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest("div.meta-autocomplete-wrap");
        _parent.find("input[id*='_valueField']").val(row.id);
        _parent.find("input[id*='_valueField']").attr('data-startdate', row.startdate);
        _parent.find("input[id*='_valueField']").attr('data-enddate', row.enddate);
        _parent.find("input[id*='_displayField']").val(row.calccode);
        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(row.calcname);        
    }    
    
    function prlUseBasketView_<?php echo $this->uniqId; ?>(elem) {
        var $dialogName = 'prlBasket-datagrid-dialog-<?php echo $this->uniqId; ?>';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);

        var dynamicHeight = $(window).height() - 200;
        var data = '<div class="row"><div class="col-md-12">'+
                '<div><a class="btn blue btn-circle btn-sm mr6 mb6" onclick="prlBasketLock_<?php echo $this->uniqId; ?>()" href="javascript:;"><i class="fa fa-lock"></i> Түгжих</a>'+
                '<a class="btn blue btn-circle btn-sm mr6 mb6" onclick="prlBasketUnLock_<?php echo $this->uniqId; ?>()" href="javascript:;"><i class="fa fa-unlock"></i> Түгжээг тайлах</a>'+
                '<a class="btn btn-danger btn-circle btn-sm mr6 mb6" onclick="prlBasketRemove_<?php echo $this->uniqId; ?>()" href="javascript:;"><i class="fa fa-trash"></i> Сагс цэвэрлэх</a>'+
                '</div><div class="bp-overflow-xy-auto" style="max-height: '+dynamicHeight+'px; overflow: auto;">'+
                    '<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" id="prlSalaryBasketCardDatagrid_<?php echo $this->uniqId; ?>">';
            data += '<thead><tr>';
            data += '<th class="rowNumber" style="width:30px">№</th>';
            data += '<th style="padding: 7px !important">'+code_globecode+'</th>';
            data += '<th style="padding: 7px !important">'+lname_globecode+'</th>';
            data += '<th style="padding: 7px !important">'+fname_globecode+'</th>';
            data += '</tr></thead><tbody>';
            var ii = 1;
            for (var i = 0; i < _selectedRows<?php echo $this->uniqId; ?>.length; i++) {                
                data += '<tr>';
                data += '<td style="height:24px; padding: 5px !important; widht: 30px">'+ii+'</td>';
                data += '<td style="height:24px; padding: 5px !important">'+_selectedRows<?php echo $this->uniqId; ?>[i]['employeecode']+'</td>';
                data += '<td style="height:24px; padding: 5px !important">'+_selectedRows<?php echo $this->uniqId; ?>[i]['lastname']+'</td>';
                data += '<td style="height:24px; padding: 5px !important">'+_selectedRows<?php echo $this->uniqId; ?>[i]['firstname']+'</td>';
                data += '</tr>';
                ii++;
            }
            data += '</tbody></table></div>'+
            '</div></div>';
        $dialog.empty().append(data);
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сагсанд',
            width: '800',
            height: 'auto',
            modal: true,
            close: function() {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function() {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');     
        $.contextMenu({
            selector: "#prlSalaryBasketCardDatagrid_<?php echo $this->uniqId; ?> > tbody > tr > td",
            items: {
                "sheetLock": {
                    name: 'Түгжих', 
                    icon: "lock", 
                    callback: function(key, options) {
                        prlBasketLock_<?php echo $this->uniqId; ?>();
                    }
                },
                "sheetUnLock": {
                    name: 'Түгжээг тайлах', 
                    icon: "unlock", 
                    callback: function(key, options) {
                        prlBasketUnLock_<?php echo $this->uniqId; ?>();
                    }
                },
                "sheetRemove": {
                    name: 'Сагс цэвэрлэх', 
                    icon: "trash", 
                    callback: function(key, options) {
                        prlBasketRemove_<?php echo $this->uniqId; ?>();
                    }
                }
            }
        });        
        
    }
    
    function prlBasketRemove_<?php echo $this->uniqId; ?>() {
        $("#prlSalaryBasketCardDatagrid_<?php echo $this->uniqId; ?>").find('tbody').empty();
        _selectedRows<?php echo $this->uniqId; ?> = [];
    }
    
    function prlBasketLock_<?php echo $this->uniqId; ?>() {
        if (!_selectedRows<?php echo $this->uniqId; ?>.length) {
            return;
        }
        $.ajax({
            type: 'post',
            url: 'Mdsalary/lockFieldRowSheetWebservice',
            data: {
                sheet: _selectedRows<?php echo $this->uniqId; ?>,
                javaCacheId: $('input[name="javaCacheId"]', "#calcInfoForm_<?php echo $this->uniqId; ?>").val(),
                isAllEmployee: '0',
                params: '',
                isLock: '1',
                filterParams: ''
            },
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (resp) {
                if(resp.status === 'success') {
                    $("#salaryDatagrid_<?php echo $this->uniqId; ?>").datagrid('reload');
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: resp.text,
                        type: 'warning',
                        sticker: false
                    });           
                }
                Core.unblockUI();
            }
        });        
    }
    
    function prlBasketUnLock_<?php echo $this->uniqId; ?>() {
        if (!_selectedRows<?php echo $this->uniqId; ?>.length) {
            return;
        }    
        $.ajax({
            type: 'post',
            url: 'Mdsalary/lockFieldRowSheetWebservice',
            data: {
                sheet: _selectedRows<?php echo $this->uniqId; ?>,
                javaCacheId: $('input[name="javaCacheId"]', "#calcInfoForm_<?php echo $this->uniqId; ?>").val(),
                isAllEmployee: '0',
                params: '',
                isLock: '0',
                filterParams: ''
            },
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (resp) {
                if(resp.status === 'success') {
                    $("#salaryDatagrid_<?php echo $this->uniqId; ?>").datagrid('reload');
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхааруулга',
                        text: resp.text,
                        type: 'warning',
                        sticker: false
                    });           
                }
                Core.unblockUI();
            }
        });    
    }
    
    function sheetNumberFormatter_<?php echo $this->uniqId; ?>(val, row, index) {
        if(typeof val === 'undefined')
            return;
        
        var cellStyle = '';
        if (row.isgl === '1' && row.islock === '1') {
            cellStyle = ' readonly style="background-color: '+configRowGLColor+'"';
        } else if (row.islock === '1') {
            cellStyle = ' readonly style="background-color: '+configRowLockColor+'"';
        }         

        var value = 0;
        if (val !== null && val !== '') {
            value = val;
        }
        value = value.toString();
        var html = '<input type="text"'+cellStyle+' class="form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat w-100" onChange="window[\'salaryObj' + <?php echo $this->uniqId; ?> + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + <?php echo $this->uniqId; ?> + '\'].selectInput(this)" data-oldValue="' + value + '" value="' + value + '" title="' + pureNumberFormat(parseFloat(value).toFixed(2)) + '" />';

        if(typeof row.loggedvalues !== 'undefined' && row.loggedvalues) {
            if (row.loggedvalues.search(new RegExp(this.field+'\]', 'g')) !== -1) {
                html = '<input type="text"'+cellStyle+' class="saved-log-data-cell form-control text-right form-control-inline m-wrap form-control-sm salaryNumberFormat w-100" onChange="window[\'salaryObj' + <?php echo $this->uniqId; ?> + '\'].setSheetValue(this)" onClick="window[\'salaryObj' + <?php echo $this->uniqId; ?> + '\'].selectInput(this)" data-oldValue="' + value + '" value="' + value + '" title="' + pureNumberFormat(parseFloat(value).toFixed(2)) + '" />'+
                        '<a class="btn btn-xs btn-secondary" title="Өөрчлөлтийн түүх харах" href="javascript:;" onclick="window[\'salaryObj' + <?php echo $this->uniqId; ?> + '\'].getLogData(this)"><i style="color:#ff2929;" class="fa fa-history"></i></a>';
            }
        }

        return html;
    };    
    
</script>