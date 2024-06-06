<div class="row" id="depreciation">
    <?php if (!$this->isAjax) { ?> 
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption buttons"> 
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo $this->title; ?>
                </span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body xs-form">
    <?php } ?> 
            <div class="col-md-12">
                <div class="meta-toolbar">
                    <span class="text-uppercase"><?php echo Lang::lineDefault('PL_031777', 'Үндсэн хөрөнгийн элэгдэл тооцох'); ?></span>
                    <div class="ml-auto">
                        <?php 
                        echo Mdcommon::redirectHelpContentButton('FIN_ASSET_DEPRECATION_HELP_CONTENT');
                        echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success saveDepreciationBook', 'value' => $this->lang->line('save_btn'))); 
                        echo Form::button(array('class' => 'btn btn-circle btn-sm default cancelDepreciationBook', 'value' => $this->lang->line('cancel_btn'))); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <form class="form-horizontal xs-form" role="form" method="post" id="saveDepreciation-form">
                    <fieldset class="collapsible mb10">
                        <legend><?php echo $this->lang->line('filter'); ?></legend>
                        <div class="row" id="headerFilterParam">
                            <div class="col-md-4">
                                <?php
                                $filterConfig = Config::getFromCache('IS_HIDE_FILTER_CLOUD_DEPR');
                                if ($filterConfig != 1) {
                                ?>                                
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_0317', 'Салбар нэгж'), 'for' => 'departmentCode_displayField', 'class' => 'col-form-label col-md-3 custom-label', 'required' => 'required', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="input-group double-between-input" data-section-path="ORG_DEPARTMENT_COST_CENTER_SUB">
                                            <?php 
                                            echo Form::hidden(array('name' => 'departmentId', 'id' => 'departmentId_valueField', 'value' => Arr::get($this->selectDefaultDepartment, 'id'))); 
                                            echo Form::text(array('name' => 'departmentCode', 'id' => 'departmentCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete assetdepr-code-ac', 'required' => 'required', 'placeholder' => 'кодоор хайх', 'value' => Arr::get($this->selectDefaultDepartment, 'departmentcode'))); 
                                            ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('ORG_DEPARTMENT_COST_CENTER_SUB', 'single', 'departmentSelectableGrid', '', this);"><i class="fa fa-search"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'departmentName', 'id' => 'departmentName_nameField', 'class' => 'form-control form-control-sm meta-name-autocomplete glName-autocomplete assetdepr-name-ac', 'required' => 'required', 'placeholder' => 'нэрээр хайх', 'value' => Arr::get($this->selectDefaultDepartment, 'departmentname'))); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }    
                                if (!$this->IS_NOT_SHOW_ACC_IN_DEPR) {
                                ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('prl_008', 'Данс'), 'for' => 'filterAccountId', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="meta-autocomplete-wrap" data-section-path="filterAccountId">
                                            <div class="input-group double-between-input">
                                                <input type="hidden" name="filterAccountId[]" id="filterAccountId_valueField" data-path="filterAccountId" class="popupInit">
                                                <input type="text" name="filterAccountId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="filterAccountId" id="filterAccountId_displayField" data-processid="1454315883636" data-lookupid="1454379109682" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('filterAccountId', '1454315883636', '1454379109682', 'multi', 'filterAccountId', this);" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                                </span>  
                                                <span class="input-group-btn">
                                                    <input type="text" name="filterAccountId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="filterAccountId" id="filterAccountId_nameField" data-processid="1454315883636" data-lookupid="1454379109682" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                </span>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                if (!$this->IS_NOT_SHOW_SK_IN_DEPR) {
                                ?>
                                <div class="form-group row fom-row<?php echo Config::getFromCache('isHideDeprFilterAssetKeeperId') == '1' ? ' d-none' : ''; ?>">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('FIN_1019', 'Нярав'), 'for' => 'filterAssetKeeperId', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="meta-autocomplete-wrap" data-section-path="filterAssetKeeperId">
                                            <div class="input-group double-between-input">
                                                <input type="hidden" name="filterAssetKeeperId" id="filterAssetKeeperId_valueField" data-path="filterAssetKeeperId" class="popupInit">
                                                <input type="text" name="filterAssetKeeperId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="filterAssetKeeperId" id="filterAccountId_displayField" data-processid="1454315883636" data-lookupid="1528100121759" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('filterAssetKeeperId', '1454315883636', '1528100121759', 'single', 'filterAssetKeeperId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                </span>  
                                                <span class="input-group-btn">
                                                    <input type="text" name="filterAssetKeeperId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="filterAssetKeeperId" id="filterAssetKeeperId_nameField" data-processid="1454315883636" data-lookupid="1528100121759" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                </span>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                if (!$this->IS_NOT_SHOW_SK_IN_DEPR) {
                                ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_2067', 'Нярав/данс'), 'for' => 'cashierKeeper', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="input-group double-between-input" data-section-path="FA_ASSET_KEEPER_KEY_LIST">
                                            <?php 
                                            echo Form::hidden(array('name' => 'cashierKeeperId[]', 'id' => 'cashierKeeperId_valueField')); 
                                            echo Form::text(array('name' => 'cashierKeeperCode', 'id' => 'cashierKeeperCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete assetdepr-code-ac', 'placeholder' => 'кодоор хайх', 'readonly' => 'readonly')); 
                                            ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('FA_ASSET_KEEPER_KEY_LIST', 'multi', 'cashierKeeperSelectableGrid', 'param[filterDepartmentId]='+$('#departmentId_valueField', depreciationWindowId).val(), this);" disabled="disabled"><i class="fa fa-list-ul"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'cashierKeeperName', 'id' => 'cashierKeeperName_nameField', 'class' => 'form-control form-control-sm  meta-name-autocomplete glName-autocomplete assetdepr-name-ac', 'placeholder' => 'нэрээр хайх', 'readonly' => 'readonly')); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group row fom-row mb0">
                                    <div class="col-md-9 ml-md-auto">
                                        <div class="radio-list">
                                            <?php
                                            if ($this->calcMethod != '1' && $this->calcMethod != '2' && $this->calcMethod != '3') {
                                            ?>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="calcMethod" id="calcMethod1" value="0" checked> <?php echo Lang::lineDefault('PL_0317771', 'Огноогоор'); ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="calcMethod" id="calcMethod2" value="1"> <?php echo Lang::lineDefault('PL_0317772', 'Хоногоор'); ?>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" name="calcMethod" id="calcMethod3" value="2"> <?php echo Lang::lineDefault('PL_0317773', 'Сараар'); ?>
                                                </label>
                                            </div>
                                            <?php
                                            } else {
                                                if ($this->calcMethod == '1') {
                                                    echo '<div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" name="calcMethod" id="calcMethod1" value="0" checked> '.Lang::lineDefault('PL_0317771', 'Огноогоор').'
                                                        </label>
                                                    </div>';
                                                } elseif ($this->calcMethod == '2') {
                                                    echo '<div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" name="calcMethod" id="calcMethod2" value="1" checked> '.Lang::lineDefault('PL_0317772', 'Хоногоор').'
                                                        </label>
                                                    </div>';
                                                } elseif ($this->calcMethod == '3') {
                                                    echo '<div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" name="calcMethod" id="calcMethod3" value="2" checked> '.Lang::lineDefault('PL_0317773', 'Сараар').'
                                                        </label>
                                                    </div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row fom-row row-dep-type" style="display:none">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <?php echo Form::label(array('text' => 'Элэгдэл тооцох <span class="dep-type">хоног</span>', 'for' => 'depMonth', 'class' => 'col-form-label col-md-5 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-7" style="width: 100px !important; max-width: 100px !important;">
                                                <?php echo Form::text(array('name' => 'depMonth', 'id' => 'depMonth', 'class' => 'form-control form-control-sm bigdecimalInit',  'readonly' => 'readonly', 'value'=>0)); ?>
                                            </div>
                                        </div>    
                                    </div>    
                                </div>  
                            </div>
                            <div class="col-md-4<?php echo $filterConfig != 1 && !$this->IS_NOT_SHOW_EMPLOYEE_IN_DEPR 
                                    && !$this->IS_NOT_SHOW_CUSTOMER_IN_DEPR && !$this->IS_NOT_SHOW_CAT_IN_DEPR ? ' hidden' : '' ?>">
                                <?php if ($filterConfig != 1) { ?>
                                    <div class="form-group row fom-row">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('PL_2030', 'Байршил'), 'for' => 'location', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                        <div class="col-md-9">
                                            <div class="input-group double-between-input" data-section-path="fa_asset_location_list">
                                                <?php 
                                                echo Form::hidden(array('name' => 'locationId', 'id' => 'locationId_valueField')); 
                                                echo Form::text(array('name' => 'locationCode', 'id' => 'locationCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete assetdepr-code-ac', 'placeholder' => 'кодоор хайх')); 
                                                ?>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('fa_asset_location_list', 'multi', 'locationSelectableGrid', '', this);"><i class="fa fa-list-ul"></i></button>
                                                </span>     
                                                <span class="input-group-btn">
                                                    <?php echo Form::text(array('name' => 'locationName', 'id' => 'locationName_nameField', 'class' => 'form-control form-control-sm meta-name-autocomplete glName-autocomplete assetdepr-name-ac', 'placeholder' => 'нэрээр хайх')); ?>    
                                                </span>     
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if (!$this->IS_NOT_SHOW_EMPLOYEE_IN_DEPR) {
                                ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_2033', 'Эд хариуцагч'), 'for' => 'assetOwner', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="input-group double-between-input" data-section-path="hrm_emp_base_person">
                                            <?php 
                                            echo Form::hidden(array('name' => 'assetOwnerId', 'id' => 'assetOwnerId_valueField')); 
                                            echo Form::text(array('name' => 'assetOwnerCode', 'id' => 'assetOwnerCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete assetdepr-code-ac', 'placeholder' => 'кодоор хайх')); 
                                            ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('hrm_emp_base_person', 'multi', 'ownerSelectableGrid', '', this);"><i class="fa fa-list-ul"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'assetOwnerName', 'id' => 'assetOwnerName_nameField', 'class' => 'form-control form-control-sm meta-name-autocomplete glName-autocomplete assetdepr-name-ac', 'placeholder' => 'нэрээр хайх')); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                if (!$this->IS_NOT_SHOW_CUSTOMER_IN_DEPR) {
                                ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_203333333', 'Харилцагч'), 'for' => 'customerId', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="input-group double-between-input" data-section-path="hrm_emp_base_person">
                                            <?php 
                                            echo Form::hidden(array('name' => 'customerId', 'id' => 'customerId_valueField')); 
                                            echo Form::text(array('name' => 'customerCode', 'id' => 'customerCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete assetdepr-code-ac', 'placeholder' => 'кодоор хайх')); 
                                            ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('FIN_CUSTOMER_DVLIST', 'multi', 'customerSelectableGrid', '', this);"><i class="fa fa-list-ul"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'customerName', 'id' => 'customerName_nameField', 'class' => 'form-control form-control-sm meta-name-autocomplete glName-autocomplete assetdepr-name-ac', 'placeholder' => 'нэрээр хайх')); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                if (!$this->IS_NOT_SHOW_CAT_IN_DEPR) {
                                ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_2054', 'Бүлэг'), 'for' => 'assetGroup', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="input-group double-between-input" data-section-path="FA_ASSET_GROUP_DVLIST">
                                            <?php 
                                            echo Form::hidden(array('name' => 'assetGroupId', 'id' => 'assetGroupId_valueField')); 
                                            echo Form::text(array('name' => 'assetGroupCode', 'id' => 'assetGroupCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete assetdepr-code-ac', 'placeholder' => 'кодоор хайх')); 
                                            ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('FA_ASSET_GROUP_DVLIST', 'multi', 'groupSelectableGrid', '', this);"><i class="fa fa-list-ul"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'assetGroupName', 'id' => 'assetGroupName_nameField', 'class' => 'form-control form-control-sm meta-name-autocomplete glName-autocomplete assetdepr-name-ac', 'placeholder' => 'нэрээр хайх')); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <?php if ($filterConfig != 1) { ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_2034', 'Хөрөнгө'), 'for' => 'filterAssetId', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="meta-autocomplete-wrap" data-section-path="filterAssetId">
                                            <div class="input-group double-between-input">
                                                <input type="hidden" name="filterAssetId[]" id="filterAssetId_valueField" data-path="filterAssetId" class="popupInit">
                                                <input type="text" name="filterAssetId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="filterAssetId" id="filterAssetId_displayField" data-processid="1454315883636" data-lookupid="1484729890678" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('filterAssetId', '1454315883636', '1484729890678', 'multi', 'filterAssetId', this);" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                                </span>  
                                                <span class="input-group-btn">
                                                    <input type="text" name="filterAssetId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="filterAssetId" id="filterAssetId_nameField" data-processid="1454315883636" data-lookupid="1484729890678" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                </span>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('1456303596744_1633047737215343', 'Элэгдэл тооцох арга'), 'for' => 'deprMethodId', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <div class="meta-autocomplete-wrap" data-section-path="deprMethodId">
                                            <div class="input-group double-between-input">
                                                <input type="hidden" name="deprMethodId[]" id="deprMethodId_valueField" data-path="deprMethodId" class="popupInit">
                                                <input type="text" name="deprMethodId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="deprMethodId" id="deprMethodId_displayField" data-processid="1454315883636" data-lookupid="1447037905559" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('deprMethodId', '1454315883636', '1447037905559', 'multi', 'deprMethodId', this);" tabindex="-1"><i class="fa fa-list-ul"></i></button>
                                                </span>  
                                                <span class="input-group-btn">
                                                    <input type="text" name="deprMethodId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="deprMethodId" id="deprMethodId_nameField" data-processid="1454315883636" data-lookupid="1447037905559" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                </span>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>   
                            <div class="col-md-4">
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('MET_330312', 'Баримтын огноо'), 'for' => 'bookDate', 'class' => 'col-form-label col-md-3 custom-label', 'required' => 'required', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-7">
                                        <div class="dateElement input-group">
                                            <?php 
                                            $bookDateAttr = array('name' => 'bookDate', 'id' => 'bookDate', 'class' => 'form-control form-control-sm dateInit', 'value' => Date::currentDate('Y-m-d'), 'required' => 'required');
                                            if ($this->isBookDateDisable) {
                                                $bookDateAttr['readonly'] = 'readonly';
                                            }
                                            echo Form::text($bookDateAttr); 
                                            ?>
                                            <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($filterConfig != 1) { ?>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('FIN_01340', 'Баркод'), 'for' => 'bookDate', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <?php echo Form::text(array('name' => 'filterAssetNumber', 'id' => 'filterAssetNumber', 'class' => 'form-control form-control-sm')); ?>
                                    </div>
                                </div>
                                <div class="form-group row fom-row">
                                    <?php echo Form::label(array('text' => Lang::lineDefault('PL_2056', 'Сериал'), 'for' => 'filterSerialNumber', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-9">
                                        <?php echo Form::text(array('name' => 'filterSerialNumber', 'id' => 'filterSerialNumber', 'class' => 'form-control form-control-sm')); ?>
                                        <?php echo Form::hidden(array('name' => 'filterSystemTypeId', 'id' => 'filterSystemTypeId', 'value' => issetParam($this->filterSystemTypeId))); ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-12">
                                <div class="form-group row fom-row mt10 header-filter-param-btns">
                                    <div class="col text-right">
                                        <button type="button" class="btn btn-sm btn-circle green" onclick="getAssetDv();"><?php echo Lang::lineDefault('FIN_1020', 'Шүүх / Бодох'); ?></button>                        
                                        <button type="button" class="btn btn-sm btn-circle default" onclick="resetHeader();"><?php echo Lang::lineDefault('FIN_00879', 'Цэвэрлэх'); ?></button>                    
                                    </div>
                                </div>                                
                            </div>                       
                        </div>    
                    </fieldset>    
                    <div class="clearfix w-100"></div>
                    <div class="row" id="headerCalcParam">
                        <div class="col-md-5 ml-md-auto">   
                            <div class="form-group row fom-row mb0">
                                <div class="col-md-9 ml-md-auto">
                                    <label>
                                        <input type="checkbox" name="cMethod" id="cMethod" checked="checked"> <?php echo Lang::lineDefault('FIN_1021', 'Татварын жилээр бодох'); ?>
                                    </label>
                                </div>
                            </div>                                                 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row fom-row mb0">
                                <?php 
                                if (!$this->IS_NOT_SHOW_DIFF_IN_DEPR) {
                                    echo Form::label(array('text' => Lang::lineDefault('FIN_1022', 'Зөрүүгийн бичилт'), 'for' => 'isReconciliation', 'class' => 'col-form-label col-md-3 custom-label', 'style' => 'font-size: 12px !important')); 
                                ?>
                                    <div class="col-md-1">
                                        <?php echo Form::checkbox(array('name' => 'isReconciliation', 'id' => 'isReconciliation', 'value' => '1', 'class' => 'form-control form-control-sm booleanInit')); ?>
                                    </div>
                                <?php
                                }
                                if (!$this->isNotUseGLAsset) {
                                ?>
                                <div class="col-md-8">
                                    <div class="form-group row fom-row mb0">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('PL_01563', 'Журналд холбох'), 'for' => 'isUseGl', 'class' => 'col-form-label col-md-4 custom-label', 'style' => 'font-size: 12px !important')); ?>
                                        <div class="col-md-6">
                                            <?php echo Form::checkbox(array('name' => 'isUseGl', 'id' => 'isUseGl', 'class' => 'form-control form-control-sm booleanInit')); ?>
                                        </div>
                                    </div> 
                                </div>
                                <?php
                                }
                                ?>
                            </div>                                                
                        </div>
                        <div class="clearfix w-100"></div>
                    </div>   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line" id="assetField">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a href="#asset" class="nav-link active" data-toggle="tab"><?php echo Lang::lineDefault('1461563549096_1632291822285712', 'Үндсэн хөрөнгө'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#additional" data-toggle="tab" class="nav-link"><?php echo Lang::lineDefault('PL_ST087', 'Бусад'); ?></a>
                                    </li>
                                    <li class="nav-item hidden" id="glli">
                                        <a href="#gl" data-toggle="tab" class="nav-link"><?php echo Lang::lineDefault('FIN_1000', 'Журнал бичилт'); ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active in" id="asset">
                                        <div class="pf-custom-pager">
                                            <div id="fz-parent" class="freeze-overflow-xy-auto">
                                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" id="assetDtls">
                                                    <thead>
                                                        <?php echo $this->header; ?>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td style="width:30px;"></td>
                                                            <td style="width:30px;"></td>
                                                            <td style="width:30px;"></td>
                                                            <td style="width:100px;"></td>
                                                            <td style="width:100px;"></td>
                                                            <td style="width:150px;"></td>
                                                            <td style="width:80px;"></td>
                                                            <td style="width:30px;"></td>
                                                            <td style="width:80px;"></td>
                                                            <td style="min-width: 30px !important; max-width: 40px !important;"></td>
                                                            <td style="width:50px;"></td>
                                                            <td class="bigdecimalInit text-right font-weight-bold inqty_sum">0.00</td>
                                                            <td style="width:50px;"></td>
                                                            <td class="bigdecimalInit text-right font-weight-bold incostamt_sum" style="width:100px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold originalcost_sum" style="width:100px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold calculatecost_sum" style="width:100px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold salvageamt_sum" style="width:100px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold indepramt_sum"  style="width:80px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold standartindepramt_sum"  style="width:80px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold actualcost_sum" style="width:80px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold outdepramt_sum" style="width:150px;">0.00</td>
                                                            <td class="bigdecimalInit text-right font-weight-bold intaxdepramt_sum" style="width:50px;">0.00</td>
                                                            <td style="width:50px;"></td>
                                                            <td style="width:50px;"></td>
                                                            <td style="width:50px;"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="pf-custom-pager-tool">
                                                <div class="pf-custom-pager-buttons">
                                                    <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="0" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="1">0</span></div>	
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-next pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <a href="javascript:;" class="pf-custom-pager-last-next pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <a href="javascript:;" class="pf-custom-pager-refresh pf-custom-pager-disabled">
                                                        <span></span>
                                                    </a>
                                                    <div class="pf-custom-pager-separator"></div>
                                                    <select class="pagination-page-list depreciation-pagination-list"><option value="50">50</option><option value="100">100</option><option value="200">200</option><option value="300">300</option><option value="500">500</option><option value="1000">1000</option><option value="2000">2000</option></select>                                                                                                                                                            
                                                </div>
                                                <div class="pf-custom-pager-total">Нийт <span>0</span> байна.</div>
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="tab-pane in" id="additional">
                                        <?php echo $this->additionalTabContent; ?>
                                    </div>
                                    <div class="tab-pane in hidden" id="gl">
                                    </div>
                                </div>   
                            </div>
                        </div>    
                    </div>       
                </form>
            </div>     
           <?php if (!$this->isAjax) { ?>
        </div> 
    </div>
    <?php } ?>
</div>

<style type="text/css">
    #depreciation #fz-parent {
        min-height: 200px;
    }
    #uniform-isUseGl {
        padding-top: 0;
    }
</style>

<script type="text/javascript">
var isChecked_stn = true;
var depreciationWindowId = "#depreciation";
var assetDVMetadataId = '<?php echo $this->assetDVMetadataId; ?>';
var assetDVRowsSize = 50;
var offset = 0;
var calcMethod = 0;
var attached = false;
var rowCheckChanged = {}, rowValueChanged = [], rowRemovedChanged = {};
var IS_SHOW_CUSTOMER_DEPR = '<?php echo Config::getFromCacheDefault('IS_SHOW_CUSTOMER_DEPR', null, ''); ?>';

$(function() {
    
    depreciationResizeDtlTable();
    
    $(depreciationWindowId).find('legend').on('click', function() {
        setTimeout(function() {
            $(window).trigger('resize');
        }, 500);
    });
    
    $(window).resize(function(){
        depreciationResizeDtlTable();
    });
    
    $("input[name='calcMethod']").click(function(){
       calcMethod = $(this).val();
    });
    
    $(depreciationWindowId).on('change', '#bookDate', function(){
        setDescriptionValue();
    });
    
    $(depreciationWindowId).on('change', '.depreciation-pagination-list', function(){
        assetDVRowsSize = $(this).val();
        assetsGotoPage(1);
    });
    
    $(depreciationWindowId).on('click', '#cMethod', function(){
        var isChecked = $(this).attr('checked');
        
        if (isChecked === undefined) {
            isChecked_stn = false;
            $('thead th.stnHead').hide();
            $('tbody td.stnDepr').hide();
            $('tfoot td.standartindepramt_sum').hide().text('0.00');
            $('tbody td.stnDepr').find('input').val('0');
            
            $(depreciationWindowId).find("#isReconciliation").attr("disabled", true);
        } else {
            isChecked_stn = true;
            $('thead th.stnHead').show();
            $('tbody td.stnDepr').show();
            $('tfoot td.standartindepramt_sum').show();
            
            $(depreciationWindowId).find("#isReconciliation").removeAttr("disabled");
        }
        
        $.uniform.update($(depreciationWindowId).find("#isReconciliation"));
    });
    
    $(depreciationWindowId).on('click', 'input[name="isUseGl"]', function(e) {
        if ($(this).is(':checked')) {
            $("#saveDepreciation-form", depreciationWindowId).validate({ 
                ignore: "", 
                highlight: function(element) {
                    $(element).addClass('error');
                    $(element).parent().addClass('error');
                    if ($("#saveDepreciation-form", depreciationWindowId).find("div.tab-pane.active:has(.error)").length == 0) {
                        $("#saveDepreciation-form", depreciationWindowId).find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                            var tabId = $(tab).attr("id");
                            $("#saveDepreciation-form", depreciationWindowId).find('a[href="#'+tabId+'"]').tab('show');
                        });
                    }
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                },
                errorPlacement: function(){}
            });
            if ($("#saveDepreciation-form", depreciationWindowId).valid() && $("table#assetDtls > tbody > tr", depreciationWindowId).length > 0){
                
                $("#headerParam", depreciationWindowId).find('input, select').attr("readonly", true);
                $("#headerParam", depreciationWindowId).find('button, input[type="radio"]').attr("disabled", true);
                
                $("input[name='cMethod']", depreciationWindowId).attr("disabled", true);
                
                $("#headerFilterParam", depreciationWindowId).find('input, select').attr("readonly", true);
                $("#headerFilterParam", depreciationWindowId).find('button, input[type="radio"]').attr("disabled", true);
                
                $('#assetDtls > tbody > tr', depreciationWindowId).each(function(){
                    $(this).find('input[name="isChecked[]"]').attr("readonly", true);
                    $(this).find('input[name="FA_ASSET_DTL_DV.inDeprAmt[]"]').attr("readonly", true);
                });
                depreciationToGl();
            }
            
        } else {
            
            $("#headerParam", depreciationWindowId).find('input, select').attr("readonly", false);
            $("#headerParam", depreciationWindowId).find('button, input[type="radio"]').attr("disabled", false);
            
            $("input[name='cMethod']", depreciationWindowId).attr("disabled", false);
                
            $("#headerFilterParam", depreciationWindowId).find('input, select').attr("readonly", false);
            $("#headerFilterParam", depreciationWindowId).find('button, input[type="radio"]').attr("disabled", false);
                
            $('#assetDtls > tbody > tr', depreciationWindowId).each(function(){
                $(this).find('input[name="isChecked[]"]').attr("readonly", false);
                $(this).find('input[name="FA_ASSET_DTL_DV.inDeprAmt[]"]').attr("readonly", false);
            });
            if ($(depreciationWindowId).find('div#gl').find("#glTemplateSectionStatic").length > 0) {
                $(depreciationWindowId).find('div#gl').find("#glTemplateSectionStatic").remove();

                $(depreciationWindowId).find('li#glli').addClass('hidden');
                $(depreciationWindowId).find('div#gl').addClass('hidden');
                $(depreciationWindowId).find('li').removeClass('active');
                $(depreciationWindowId).find('div').removeClass('active');
                $(depreciationWindowId).find('li:first-child').addClass('active');
                $(depreciationWindowId).find('.tab-content').find('div:first-child').addClass('active');
            }
        }    
    });
    
    $("body").on('change', depreciationWindowId + ' input[name="FA_ASSET_DTL_DV.inDeprAmt[]"]', function(e) {
        var row = $(this).closest('tr');
        
        rowValueChanged.push(
            {
                "id": row.find("input[name='keyId[]']").val(), 
                "inDeprAmt": row.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric('get')
            }
        );
    });
    
    $("body").on('change', depreciationWindowId + ' input[name="FA_ASSET_DTL_DV.stnDepr[]"]', function(e) {
        var row = $(this).closest('tr');
        
        rowValueChanged.push(
            {
                "id": row.find("input[name='keyId[]']").val(), 
                "stInDeprAmt": row.find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric('get')
            }
        );
    });
    
    $("body").on('keydown', depreciationWindowId + ' input[name="FA_ASSET_DTL_DV.inDeprAmt[]"]', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var parent = _this.parent();
        var getIndex = parent.index();

        if (code === 13) {
            if (parent.parent().next().index() != '-1') {
                if (parent.parent().next()) {
                    parent.parent().next()
                          .children("td:eq(" + getIndex + ")")
                          .children("input[type=text]")
                          .select();
                    return e.preventDefault();
                }
            } else {
                return e.preventDefault();
            }
        }
    });
    
    // elegdliin check darah ved tuhain mornii bodolt 
    $(depreciationWindowId).on('click', 'input[name="isChecked[]"]', function(e) {
        var _this = $(this);
        var tr = _this.closest("tr");
        var equalAll = true;
        if (!_this.hasAttr('readonly')) {
            if (_this.is(':checked')) {
                _this.attr('checked', true);
                _this.val(1);
                _this.closest('td').find('.this_check').val("1");
                /*
                var inCostAmt = $(tr).find("input[name='incostamt[]']").autoNumeric("get");
                var salvageAmt = $(tr).find("input[name='salvageamt[]']").autoNumeric("get");
                var inQty = $(tr).find("input[name='inqty[]']").autoNumeric("get");
                var deprPercent = $(tr).find("input[name='deprPercent[]']").val();
                var countQty = $(tr).find("input[name='countqty[]']").val();
                var inDeprAmt = (inCostAmt - (salvageAmt * inQty))* deprPercent / 12 / 30 * countQty / 100;
                */
                var loanMonth =  $("#depMonth", depreciationWindowId).autoNumeric('get');
                // new check darah
                var diff_stn = 0;
                var countQty = $(tr).find("input[name='count[]']").val();
                var inCostAmt = $(tr).find("input[name='incostamt[]']").autoNumeric("get");
                var actualCost = $(tr).find("input[name='actualcost[]']").autoNumeric("get");
                var salvageAmt = $(tr).find("input[name='salvageamt[]']").autoNumeric("get");
                var inQty = $(tr).find("input[name='inqty[]']").autoNumeric("get");
                var usageYear = $(tr).find("input[name='usageYear[]']").val();
                var stusageYear = $(tr).find("input[name='stusageYear[]']").val();
                var oldindepramt = $(tr).find("input[name='indepramt[]']").autoNumeric("get");
                var intaxdepramt = $(tr).find("input[name='intaxdepramt[]']").autoNumeric("get");
                var clDeprDate = $(tr).find("input[name='theClosestDeprDate[]']").val();
                var diff_stn = 0;
                var bookdate = $(depreciationWindowId+" #bookDate").val();
                var split_bookDate = bookdate.split("-");
                var split_clDeprDate = clDeprDate.split("-");
                var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                var firstDate = new Date(split_clDeprDate[0], split_clDeprDate[1],split_clDeprDate[2]);
                var secondDate = new Date(split_bookDate[0], split_bookDate[1],split_bookDate[2]);
               /*
                var uldehCost = salvageAmt * inQty;
                var q1 = inCostAmt - uldehCost;
                var countqty = q1 / (usageYear * 365);
                var stnDepr = q1 /(stusageYear*365);
                var inDeprAmt = stnDepr * countQty;
                diff_stn =  inDeprAmt-stnDepr;
                */
               // үлдэх өртөг * тоо ширхэг
                var split_date = bookdate.split('-');
                var n = parseInt(split_date[0]);
                var dayOfYear = (n%4==0 ? 366:365);
                var uldehCost = salvageAmt * inQty;
                //Элэгдэл = Нийт өртөг - үлдэх өртөг
                var q1 = inCostAmt - uldehCost;
                // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                var countqty = q1 / (usageYear * dayOfYear);
                // q1 / (Татварын жил *365 )
                var countqtys = q1 /(stusageYear * dayOfYear);
                // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                if (calcMethod == 1) {
                     if (loanMonth != 0 && loanMonth != '') {
                        var split_date = bookdate.split('-');
                        var n = parseInt(split_date[0]);
                        var dayOfYear = (n%4==0 ? 366:365);
                        // үлдэх өртөг * тоо ширхэг
                        var uldehCost = salvageAmt * inQty;
                        //Элэгдэл = Нийт өртөг - үлдэх өртөг
                        var q1 = inCostAmt - uldehCost;
                        // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                        var countqty = q1 / (usageYear * dayOfYear);
                        // q1 / (Татварын жил *365 )
                        var countqtys = q1 /(stusageYear*dayOfYear);
                        // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                        var inDeprAmt = countqty * loanMonth;
                        // Татварын элэгдэл 
                        var stnDepr =  countqtys * loanMonth;
                        
                        if (inDeprAmt > (actualCost - (salvageAmt * inQty))) {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", (actualCost - (salvageAmt * inQty)));
                        } else {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                        }
                        
                        if (inCostAmt-oldindepramt-(salvageAmt*inQty) > 0) {
                            if (isChecked_stn == true) {
                                diff_stn = inDeprAmt-stnDepr;
                                $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                                $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                            }
                        } else {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                            if (isChecked_stn == true) {
                                diff_stn = inDeprAmt-stnDepr;
                                $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                                $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                            }
                        }
                    } else {
                        $(tr).find("input[name='countqty[]']").val(countQty);
                        $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                        if (isChecked_stn == true) {
                            diff_stn = inDeprAmt-stnDepr;
                            $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                            $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                        }
                    }     
                }
                
                if (calcMethod == 0) {
                    var split_date = bookdate.split('-');
                    var n = parseInt(split_date[0]);
                    var dayOfYear = (n%4==0 ? 366:365);
                    var diffDays = countQty;
                    // үлдэх өртөг * тоо ширхэг
                    var uldehCost = salvageAmt * inQty;
                    //Элэгдэл = Нийт өртөг - үлдэх өртөг
                    var q1 = inCostAmt - uldehCost;
                    // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                    var countqty = q1 / (usageYear * dayOfYear);
                    // q1 / (Татварын жил *365 )
                    var countqtys = q1 /(stusageYear * dayOfYear);
                    // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                    var inDeprAmt = countqty * diffDays;
                    //console.log("inDeprAmt = countqty * diffDays: " +inDeprAmt+"="+countqty+"*"+diffDays);
                    // Татварын элэгдэл 
                    var stnDepr =  countqtys * diffDays;
                    
                    if (inDeprAmt > (actualCost - (salvageAmt * inQty))) {
                        $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", (actualCost - (salvageAmt * inQty)));
                    } else {
                        if (inDeprAmt > actualCost) {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", actualCost);
                        } else {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                        }
                    }
                    if (inCostAmt-oldindepramt-(salvageAmt*inQty) > 0) {
                        if (isChecked_stn == true) {
                            diff_stn = inDeprAmt - stnDepr;
                            $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                            $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                        }
                    } else {
                        $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                        if (isChecked_stn == true) {
                            diff_stn = inDeprAmt - stnDepr;
                            $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                            $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                        }
                    }
                }
                
                if (calcMethod == 2) {
                     if (loanMonth != 0 && loanMonth != '') {
                        var split_date = bookdate.split('-');
                        var n = parseInt(split_date[0]);
                        var dayOfYear = 12;
                        // үлдэх өртөг * тоо ширхэг
                        var uldehCost = salvageAmt * inQty;
                        //Элэгдэл = Нийт өртөг - үлдэх өртөг
                        var q1 = inCostAmt - uldehCost;
                        // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                        var countqty = q1 / (usageYear * dayOfYear);
                        // q1 / (Татварын жил *365 )
                        var countqtys = q1 /(stusageYear*dayOfYear);
                        // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                        var inDeprAmt = countqty * loanMonth;
                        // Татварын элэгдэл 
                        var stnDepr =  countqtys * loanMonth;
                        
                        if (inDeprAmt > (actualCost - (salvageAmt * inQty))) {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", (actualCost - (salvageAmt * inQty)));
                        } else {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                        }
                        
                        if (inCostAmt-oldindepramt-(salvageAmt*inQty) > 0) {
                            if (isChecked_stn == true) {
                                diff_stn = inDeprAmt-stnDepr;
                                $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                                $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                            }
                        } else {
                            $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                            if (isChecked_stn == true) {
                                diff_stn = inDeprAmt-stnDepr;
                                $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                                $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                            }
                        }
                    } else {
                        $(tr).find("input[name='countqty[]']").val(countQty);
                        $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                        if (isChecked_stn == true) {
                            diff_stn = inDeprAmt-stnDepr;
                            $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                            $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                        }
                    }     
                }
                
                diff_stn = inDeprAmt - stnDepr;
                $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                $(tr).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                
                if (inCostAmt == intaxdepramt || inCostAmt < intaxdepramt) {
                    $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                }
                if (inCostAmt == oldindepramt) {
                    $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                }
                /*if (inCostAmt < oldindepramt) {
                    $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", actualCost);
                }*/
                
                depreciationCalcFooter(tr, 'add');
                
                delete rowCheckChanged[$(tr).find("input[name='keyId[]']").val()];
                rowCheckChanged[$(tr).find("input[name='keyId[]']").val()] = true;
                
            } else {    
                
                _this.attr('checked', false);
                _this.val(0);
                _this.closest('td').find('.this_check').val("0");
                
                depreciationCalcFooter(tr, 'minus');
                
                delete rowCheckChanged[$(tr).find("input[name='keyId[]']").val()];
                rowCheckChanged[$(tr).find("input[name='keyId[]']").val()] = false;
            }   
            
        } else {
            if (_this.is(':checked')) {
                _this.attr('checked', false);
                _this.val("0");
                
                depreciationCalcFooter(tr, 'minus');
                
                delete rowCheckChanged[$(tr).find("input[name='keyId[]']").val()];
                rowCheckChanged[$(tr).find("input[name='keyId[]']").val()] = true;
                
            } else {         
                
                _this.attr('checked', true);
                _this.val("1");
                
                delete rowCheckChanged[$(tr).find("input[name='keyId[]']").val()];
                rowCheckChanged[$(tr).find("input[name='keyId[]']").val()] = false;
                
                /*var inCostAmt = $(tr).find("input[name='incostamt[]']").autoNumeric("get");
                var salvageAmt = $(tr).find("input[name='salvageamt[]']").autoNumeric("get");
                var inQty = $(tr).find("input[name='inqty[]']").autoNumeric("get");
                var deprPercent = $(tr).find("input[name='deprPercent[]']").val();
                var countQty = $(tr).find("input[name='countqty[]']").val();
                var inDeprAmt = (inCostAmt - (salvageAmt * inQty))* deprPercent / 12 / 30 * countQty / 100;
       
                $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);*/
            }    
        }
        
        $('input[name="isChecked[]"]').each(function () {
            if ($(this).is(':checked') === false) {
                equalAll = false;
            }
        });
        if (equalAll) {
            $("#assetDtls > thead").find("input[name=isCheckedAll]").attr('checked', true);
            $("#assetDtls > thead").find("input[name=isCheckedAll]").parent().addClass('checked');
        } else {
            $("#assetDtls > thead").find("input[name=isCheckedAll]").attr('checked', false);
            $("#assetDtls > thead").find("input[name=isCheckedAll]").parent().removeClass('checked');
        }
        /*calculateFooter();*/
    });
    
    //all check end
    $(depreciationWindowId).on('click', 'input[name="calcMethod"]', function(e) {
        var _thisVal = $(this).val();

        if (_thisVal == '1') {
           $("#depMonth", depreciationWindowId).removeAttr('readonly');  
           $(".dep-type", depreciationWindowId).text('хоног');  
           $('.row-dep-type', depreciationWindowId).show();
        } else if (_thisVal == '2') {
           $("#depMonth", depreciationWindowId).removeAttr('readonly');  
           $(".dep-type", depreciationWindowId).text('сар');  
           $('.row-dep-type', depreciationWindowId).show();
        } else {
           $("#depMonth", depreciationWindowId).attr('readonly', 'readonly');   
           $("#depMonth", depreciationWindowId).autoNumeric('set', 0);   
           $('.row-dep-type', depreciationWindowId).hide();
        }
    });
    
    $(".saveDepreciationBook", depreciationWindowId).on("click", function () {
        PNotify.removeAll();
        if ('1' == '<?php echo $this->isNotUseGLAsset; ?>' || $(depreciationWindowId).find('div#gl').find("#glTemplateSectionStatic").length > 0) {
            saveDepreciationBook();
        } else {
            new PNotify({
                title: 'Анхааруулга',
                text: 'Журналд холбоно уу!',
                type: 'error',
                sticker: false
            });
        }
    });
    
    $('.cancelDepreciationBook', depreciationWindowId).on('click', function () {
        clearForm();
    });  
    
    $(depreciationWindowId).on('change', '#departmentId_valueField', function (e) {
        depreciationcashierKeeperEnable();        
    });
    
    $(depreciationWindowId).on('click', '.pf-custom-pager-prev:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        assetsGotoPage(currentPageNumber - 1);
    });
    
    $(depreciationWindowId).on('click', '.pf-custom-pager-last-prev:not(.pf-custom-pager-disabled)', function () {
        assetsGotoPage(1);
    });
    
    $(depreciationWindowId).on('click', '.pf-custom-pager-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        assetsGotoPage(currentPageNumber + 1);
    });
    
    $(depreciationWindowId).on('click', '.pf-custom-pager-last-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
        var totalPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());
        
        assetsGotoPage(totalPageNumber);
    });
    
    $(depreciationWindowId).on('click', '.pf-custom-pager-refresh:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        assetsGotoPage(currentPageNumber);
    });
    
    $(depreciationWindowId).on('keydown', '#assetDtls > thead > tr > th > input[data-fieldname]', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code === 13) {
            assetsGotoPage(1);
        }
    });
    
    $(depreciationWindowId).on('keydown', '.pf-custom-pager-page-info input[type=text]', function (e) {
        
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code === 13) {
            var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
            var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
            var totalPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());
            
            if (currentPageNumber === 0) {
                currentPageNumber = 1;
            } else if (currentPageNumber > totalPageNumber) {
                currentPageNumber = totalPageNumber;
            }
            
            assetsGotoPage(currentPageNumber);
        }
    });
    
    depreciationAutoComplete();
    
    $(depreciationWindowId).find('input[name="calcMethod"]:checked').trigger('click');
    
    if ($(depreciationWindowId).find('#departmentCode_displayField').val()) {
        depreciationcashierKeeperEnable();
    }
});
function assetsGotoPage(pageNumber) {
    
    var filterRules = '';
            
    $('#assetDtls > thead > tr > th > input[data-fieldname]', depreciationWindowId).each(function(){
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
        url: 'mdasset/getDepreciationAssetsNavigation',
        data: {
            uniqId: '<?php echo $this->uniqId; ?>', 
            metaDataId: assetDVMetadataId, 
            page: pageNumber, 
            rows: assetDVRowsSize, 
            filterRules: filterRules, 
            deprmethod: $("input[name='calcMethod']:checked", depreciationWindowId).val(), 
            deprvalue: $("#depMonth", depreciationWindowId).val(), 
            calcstandardamt: isChecked_stn ? 1 : 0,  
            rowCheckChanged: rowCheckChanged, 
            rowValueChanged: rowValueChanged, 
            rowRemovedChanged: rowRemovedChanged 
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                boxed : true,
                message: 'Уншиж байна...'
            });  
        }, 
        success: function (data) {

            var isChecked_stn = false;
            var isChecked = $('#cMethod').attr('checked');
            if (isChecked === undefined) {
                isChecked_stn = false;
            }

            if (data.hasOwnProperty('status') && data.status == 'success' && data.hasOwnProperty('rows')) {

                $("table#assetDtls > tbody", depreciationWindowId).empty();

                var dataRows = data.rows;
                var dataHtml = convertToTr(dataRows, pageNumber, assetDVRowsSize);

                var depreciationContent = $('table#assetDtls > tbody', depreciationWindowId)[0];
                depreciationContent.innerHTML = dataHtml;

                if (dataRows.length > 0) {
                    $('#assetDtls > thead').find("input[name=isCheckedAll]").attr('checked', true);
                    $('#assetDtls > thead').find("input[name=isCheckedAll]").parent().addClass('checked');
                    $('#calcAmount').removeClass("disabled");
                } else {
                    $('#calcAmount').addClass("disabled");
                }

                $('table#assetDtls > tbody', depreciationWindowId).promise().done(function() {

                    var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
                    var totalRowNumber = data.total;
                    var pageNumbers = Math.ceil(totalRowNumber / assetDVRowsSize) || 1;
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
                        } else if (pageNumber == 1 && pageNumbers == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else if (pageNumber == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        }
                    }

                    if ($().tableHeadFixer) {
                        $('table#assetDtls', depreciationWindowId).tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9}); 
                        $('#fz-parent', depreciationWindowId).trigger('scroll');
                    }
                    
                    if (data.hasOwnProperty('footer')) {
                        for (var key in data.footer) {
                            $("#assetDtls > tfoot", depreciationWindowId).find("td."+key+"_sum").autoNumeric('set', data.footer[key]);    
                        }
                    }
                });

                var tableElement = $('table#assetDtls', depreciationWindowId);
                $('.bigdecimalInit', tableElement).autoNumeric('init', {mDec: 2, aPad: true, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});
                
                rowCheckChanged = {}, rowValueChanged = [], rowRemovedChanged = {};
                
            } else if (data.hasOwnProperty('status') && data.status == 'error') {

                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }

            /*calculateFooter();*/
            Core.unblockUI();
        }
    });
}
function getAssetDv(){
    
    PNotify.removeAll();
    
    if ($("#departmentId_valueField", depreciationWindowId).val() != '' && $("#bookDate", depreciationWindowId).val() != '') {    
        
        var filterAssetKeeperKeyId = $("#cashierKeeperId_valueField", depreciationWindowId).val();
        var filterDepartmentId = $("#departmentId_valueField", depreciationWindowId).val();
        var filterAssetLocationId = $("#locationId_valueField", depreciationWindowId).val();
        var employeeId = $("#assetOwnerId_valueField", depreciationWindowId).val();
        var customerId = $("#customerId_valueField", depreciationWindowId).val();
        var assetGroupId = $("#assetGroupId_valueField", depreciationWindowId).val();
        var filterAssetKeeperId = $("#filterAssetKeeperId_valueField", depreciationWindowId).val();
        var filterSerialNumber = $("#filterSerialNumber", depreciationWindowId).val();
        var filterAssetNumber = $("#filterAssetNumber", depreciationWindowId).val();
        var filterSystemTypeId = $("#filterSystemTypeId", depreciationWindowId).val();
        
        if (!filterAssetKeeperKeyId) {
            filterAssetKeeperKeyId = '';
        }
        if (!filterDepartmentId) {
            filterDepartmentId = '';
        }
        if (!filterAssetLocationId) {
            filterAssetLocationId = '';
        }
        if (!employeeId) {
            employeeId = '';
        }
        if (!customerId) {
            customerId = '';
        }
        if (!assetGroupId) {
            assetGroupId = '';
        }
        if (!filterAssetKeeperId) {
            filterAssetKeeperId = '';
        }
        if (!filterSerialNumber) {
            filterSerialNumber = '';
        }
        if (!filterAssetNumber) {
            filterAssetNumber = '';
        }
        if (!filterSystemTypeId) {
            filterSystemTypeId = '';
        }
        
        $.ajax({
            type: 'POST',
            url: 'mdasset/getDepreciationAssetsCache',
            data: {
                uniqId: '<?php echo $this->uniqId; ?>', 
                metaDataId: assetDVMetadataId, 
                page: 1, 
                rows: assetDVRowsSize, 
                defaultCriteriaData: 'param[filterAssetKeeperKeyId][]=' + filterAssetKeeperKeyId 
                    + '&param[filterDepartmentId]=' + filterDepartmentId 
                    + '&param[filterAssetLocationId][]=' + filterAssetLocationId 
                    + '&param[employeeId][]=' + employeeId 
                    + '&param[customerId][]=' + customerId 
                    + '&param[assetGroupId][]=' + assetGroupId 
                    + '&param[filterAssetKeeperId]=' + filterAssetKeeperId 
                    + '&param[filterAccountId][]=' + $("input[name='filterAccountId[]']", depreciationWindowId).map(function(){return this.value;}).get().join(',')
                    + '&param[filterAssetId][]=' + $("input[name='filterAssetId[]']", depreciationWindowId).map(function(){return this.value;}).get().join(',')
                    + '&param[filterdeprMethodId][]=' + $("input[name='deprMethodId[]']", depreciationWindowId).map(function(){return this.value;}).get().join(',')
                    + '&param[filterSerialNumber]=' + filterSerialNumber 
                    + '&param[filterAssetNumber]=' + filterAssetNumber 
                    + '&param[filterSystemTypeId]=' + filterSystemTypeId 
                    + '&param[bookDate]=' + $("#bookDate", depreciationWindowId).val() 
                    + '&param[getValueData]=' + (isChecked_stn ? 1 : 0), 
                deprmethod: $("input[name='calcMethod']:checked", depreciationWindowId).val(), 
                deprvalue: $("#depMonth", depreciationWindowId).val(), 
                calcstandardamt: isChecked_stn ? 1 : 0 
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    boxed : true,
                    message: 'Loading...'
                });  
            }, 
            success: function (data) {
                
                var isChecked_stn = false;
                var isChecked = $('#cMethod', depreciationWindowId).attr('checked');
                if (isChecked === undefined) {
                    isChecked_stn = false;
                }
                
                if (data.hasOwnProperty('status') && data.status == 'success' && data.hasOwnProperty('rows')) {
                    
                    $("table#assetDtls > tbody", depreciationWindowId).empty();
                    
                    var dataRows = data.rows;
                    var dataHtml = convertToTr(dataRows, 1, assetDVRowsSize);
                    
                    var depreciationContent = $('table#assetDtls > tbody', depreciationWindowId)[0];
                    depreciationContent.innerHTML = dataHtml;
                    
                    if (dataRows.length > 0) {
                        $('#assetDtls > thead').find("input[name=isCheckedAll]").attr('checked', true);
                        $('#assetDtls > thead').find("input[name=isCheckedAll]").parent().addClass('checked');
                        $('#calcAmount').removeClass("disabled");
                    } else {
                        $('#calcAmount').addClass("disabled");
                    }
                    
                    $('table#assetDtls > tbody', depreciationWindowId).promise().done(function() {
                        
                        var pagerElement = $('.pf-custom-pager-tool', depreciationWindowId);
                        var totalRowNumber = data.total;
                        var pageNumber = Math.ceil(totalRowNumber / assetDVRowsSize) || 1;
                        
                        pagerElement.find('.pf-custom-pager-total > span').text(totalRowNumber);
                        pagerElement.find('input[data-gotopage]').val('1');
                        pagerElement.find('span[data-pagenumber]').text(pageNumber);
                        
                        if (totalRowNumber > assetDVRowsSize) {
                            pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').addClass('pf-custom-pager-disabled');
                        }
                        
                        if (data.hasOwnProperty('footer')) {
                            for (var key in data.footer) {
                                $("#assetDtls > tfoot", depreciationWindowId).find("td."+key+"_sum").autoNumeric('set', data.footer[key]);    
                            }
                        }
                    
                        if ($().tableHeadFixer) {
                            $('table#assetDtls', depreciationWindowId).tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9}); 
                            $('#fz-parent', depreciationWindowId).trigger('scroll');
                        }
                    });
                    
                    var tableElement = $('table#assetDtls', depreciationWindowId);
                    $('.bigdecimalInit', tableElement).autoNumeric('init', {mDec: 2, aPad: true, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});
                    
                    rowCheckChanged = {}, rowValueChanged = [], rowRemovedChanged = {};
                    
                } else if (data.hasOwnProperty('status') && data.status == 'error') {
                    
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }
                
                Core.unblockUI();
            }
        });
    } else {
        new PNotify({
            title: 'Анхааруулга',
            text: 'Шүүлтийн талбарууд дутуу байна',
            type: 'error',
            sticker: false
        });
    }
}
function convertToTr(data, pageNumber, assetDVRowsSize) {
    var isHide = '';

    if (isChecked_stn == false) {
        isHide = 'style="display:none;"';
        
        $('thead th.stnHead').hide();
        $('tfoot td.standartindepramt_sum').hide();
    }
    
    var k = 0, html = [], rowChecked = '';
    
    for (var i in data) {
        
        if (isNumeric(i)) {
            i = parseInt(i);
            var value = data[i];
            var k = i + 1;
            k += (pageNumber - 1) * assetDVRowsSize;

            rowChecked = '<input type="checkbox" name="isChecked[]" value="1"><input type="hidden" name="this_check[]" class="this_check" value="0">';

            if (value.ischecked == '1') {
                rowChecked = '<input type="checkbox" name="isChecked[]" value="1" checked="checked"><input type="hidden" name="this_check[]" class="this_check" value="1">';
            }

            html.push("<tr>"+
                        "<td class='text-center middle'>"+k+"<input type='hidden' name='usageYear[]' value='"+value.usageyear+"'>"+
                            "<input type='hidden' name='dtlAccountId[]' value='"+value.id+"'>"+
                            "<input type='hidden' name='assetLocationId[]' value='"+value.assetlocationid+"'>"+
                            "<input type='hidden' name='assetKeeperKeyId[]' value='"+value.assetkeeperkeyid+"'>"+
                            "<input type='hidden' name='assetLocationName[]' value='"+value.assetlocationname+"'>"+
                            "<input type='hidden' name='assetEmployeeName[]' value='"+value.employeename+"'>"+
                            "<input type='hidden' name='assetDeprMethodName[]' value='"+value.assetdeprmethodname+"'>"+
                            "<input type='hidden' name='keyId[]' value='"+value.id+"'>"+
                            "<input type='hidden' name='count[]' value='"+value.countqty+"'>"+
                            "<input type='hidden' name='stusageYear[]' value='"+value.stusageyear+"'>"+
                            "<input type='hidden' name='originalUsageYear[]' value='"+value.originalusageyear+"'>"+
                            "<input type='hidden' name='originalStUsageYear[]' value='"+value.originalstusageyear+"'>"+
                            "<input type='hidden' name='diffstn[]' value='"+value.stusageyear+"'>"+
                            "<input type='hidden' name='costAccountId[]' value='"+value.costaccountid+"'>"+
                            "<input type='hidden' name='customerCode[]' value='"+value.customername+"'>"+
                        "</td>"+
                        "<td class='stretchInput text-center'>"+rowChecked+"</td>"+ 
                        "<td class='stretchInput text-center'><input type='text' name='dtlAccountName[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+value.accountname+"' title='"+value.accountname+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='assetCode[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+value.assetcode+"' title='"+value.assetcode+"'></td>"+
                        (IS_SHOW_CUSTOMER_DEPR === '1' ? "<td class='stretchInput text-center'><input type='text' name='customerName[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+value.customername+"' title='"+value.customername+"'></td>" : '')+
                        "<td class='stretchInput text-center'><input type='text' name='assetName[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+value.assetname+"' title='"+value.assetname+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='assetNumber[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+value.assetnumber+"' title='"+value.assetnumber+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='serialNumber[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+(value.serialnumber ? value.serialnumber : '')+"' title='"+value.serialnumber+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='measureCode[]' class='form-control form-control-sm stringInit' readonly='readonly' value='"+value.measurecode+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='usedDate[]' class='form-control form-control-sm stringInit text-center' readonly='readonly' value='"+date("Y-m-d", strtotime(value.disposeddate)) +"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='theClosestDeprDate[]' class='form-control form-control-sm stringInit text-center' readonly='readonly' value='"+date("Y-m-d", strtotime(value.actiondate))+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='countqty[]' class='form-control form-control-sm' readonly='readonly' value='"+value.countqty+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='inqty[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.inqty+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='incost[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.incost+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='incostamt[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.incostamt+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='originalcost[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.originalcost+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='calculatecost[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.calculatecost+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='salvageamt[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+(value.salvageamt ? value.salvageamt : '0')+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='FA_ASSET_DTL_DV.inDeprAmt[]' class='form-control form-control-sm bigdecimalInit text-right' value='"+value.indepramt+"'></td>"+
                        "<td class='stretchInput text-center stnDepr' "+isHide+"><input type='text' name='FA_ASSET_DTL_DV.stnDepr[]' class='form-control form-control-sm bigdecimalInit text-right stnDepr' value='"+value.standartindepramt+"'><input type='hidden' name='FA_ASSET_DTL_DV.diff[]'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='actualcost[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.actualcost+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='indepramt[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.outdepramt+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='intaxdepramt[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.intaxdepramt+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='usageyear[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.usageyear+"'></td>"+
                        "<td class='stretchInput text-center'><input type='text' name='stusageyear[]' class='form-control form-control-sm bigdecimalInit text-right' readonly='readonly' value='"+value.stusageyear+"'></td>"+
                        "<td class='text-center stretchInput middle'>"+
                            "<a href='javascript:;' class='btn btn-xs purple-plum' onclick='detailedTr(this);' title='Дэлгэрэнгүй'><i class='fa fa-external-link'></i></a>"+
                            "<a href='javascript:;' class='btn red btn-xs' onclick='assetDtlRemove(this);' title='Устгах'><i class='fa fa-trash'></i></a>"+
                        "</td>"+
                    "</tr>");
        }
    }

    return html.join('');
}
function cashierKeeperSelectableGrid(metaDataCode, chooseType, elem, rows) {
    
    var multi_idField = '', multi_codeField = '', multi_displayField = '';
    
    if (rows) {
        $.each(rows, function (key, row) {
            multi_idField += row.id + ',';
            multi_codeField += row.assetkeeperkeycode + ', ';
            multi_displayField += row.assetkeeperkeyname + ', ';
        });  
        
        multi_idField = rtrim(multi_idField, ',');
        multi_codeField = rtrim(multi_codeField, ', ');
        multi_displayField = rtrim(multi_displayField, ', ');
    }
    
    $("#cashierKeeperId_valueField", depreciationWindowId).val(multi_idField);
    $("#cashierKeeperCode_displayField", depreciationWindowId).val(multi_codeField).attr('title', multi_codeField);
    $("#cashierKeeperName_nameField", depreciationWindowId).val(multi_displayField).attr('title', multi_displayField);
    
    setDescriptionValue();
}
function departmentSelectableGrid(metaDataCode, chooseType, elem, rows){
    var row = rows[0];
    $("#departmentId_valueField", depreciationWindowId).val(row.id);
    $("#departmentCode_displayField", depreciationWindowId).val(row.code ? row.code : row.departmentcode);
    $("#departmentName_nameField", depreciationWindowId).val(row.departmentname);
    
    depreciationcashierKeeperEnable();
    setDescriptionValue();
}
function locationSelectableGrid(metaDataCode, chooseType, elem, rows){
    
    var multi_idField = '', multi_codeField = '', multi_displayField = '';
    
    if (rows) {
        $.each(rows, function (key, row) {
            multi_idField += row.id + ',';
            multi_codeField += row.assetlocationcode + ', ';
            multi_displayField += row.assetlocationname + ', ';
        });  
        
        multi_idField = rtrim(multi_idField, ',');
        multi_codeField = rtrim(multi_codeField, ', ');
        multi_displayField = rtrim(multi_displayField, ', ');
    }
    
    $("#locationId_valueField", depreciationWindowId).val(multi_idField);
    $("#locationCode_displayField", depreciationWindowId).val(multi_codeField).attr('title', multi_codeField);
    $("#locationName_nameField", depreciationWindowId).val(multi_displayField).attr('title', multi_displayField);
    
    setDescriptionValue();
}
function ownerSelectableGrid(metaDataCode, chooseType, elem, rows) {
    
    var multi_idField = '', multi_codeField = '', multi_displayField = '';
    
    if (rows) {
        $.each(rows, function (key, row) {
            multi_idField += row.id + ',';
            multi_codeField += row.employeecode + ', ';
            multi_displayField += row.employeename + ', ';
        });  
        
        multi_idField = rtrim(multi_idField, ',');
        multi_codeField = rtrim(multi_codeField, ', ');
        multi_displayField = rtrim(multi_displayField, ', ');
    }
    
    $("#assetOwnerId_valueField", depreciationWindowId).val(multi_idField);
    $("#assetOwnerCode_displayField", depreciationWindowId).val(multi_codeField).attr('title', multi_codeField);
    $("#assetOwnerName_nameField", depreciationWindowId).val(multi_displayField).attr('title', multi_displayField);
    
    setDescriptionValue();
}
function customerSelectableGrid(metaDataCode, chooseType, elem, rows) {
    
    var multi_idField = '', multi_codeField = '', multi_displayField = '';
    
    if (rows) {
        $.each(rows, function (key, row) {
            multi_idField += row.id + ',';
            multi_codeField += row.customercode + ', ';
            multi_displayField += row.customername + ', ';
        });  
        
        multi_idField = rtrim(multi_idField, ',');
        multi_codeField = rtrim(multi_codeField, ', ');
        multi_displayField = rtrim(multi_displayField, ', ');
    }
    
    $("#customerId_valueField", depreciationWindowId).val(multi_idField);
    $("#customerCode_displayField", depreciationWindowId).val(multi_codeField).attr('title', multi_codeField);
    $("#customerName_nameField", depreciationWindowId).val(multi_displayField).attr('title', multi_displayField);
    
    setDescriptionValue();
}
function groupSelectableGrid(metaDataCode, chooseType, elem, rows){
    
    var multi_idField = '', multi_codeField = '', multi_displayField = '';
    
    if (rows) {
        $.each(rows, function (key, row) {
            multi_idField += row.id + ',';
            multi_codeField += row.code + ', ';
            multi_displayField += row.assetgroupname + ', ';
        });  
        
        multi_idField = rtrim(multi_idField, ',');
        multi_codeField = rtrim(multi_codeField, ', ');
        multi_displayField = rtrim(multi_displayField, ', ');
    }
    
    $("#assetGroupId_valueField", depreciationWindowId).val(multi_idField);
    $("#assetGroupCode_displayField", depreciationWindowId).val(multi_codeField).attr('title', multi_codeField);
    $("#assetGroupName_nameField", depreciationWindowId).val(multi_displayField).attr('title', multi_displayField);
    
    setDescriptionValue();
}
function calculateFooter(){
    var amount1 = 0, amount2 = 0, amount3 = 0, amount4 = 0, amount5 = 0, amount6 = 0, amount7 = 0, amount8 = 0, amount9 = 0, amount10 = 0;
    
    if (isChecked_stn == false) {
        $(".standartindepramt_sum").hide();
    } else {
        $(".standartindepramt_sum").show();
    }
    
    var el = $("table#assetDtls > tbody > tr", depreciationWindowId);
    var len = el.length, i = 0;
    
    for (i; i < len; i++) { 
        var _thisRow = $(el[i]);
        var check = _thisRow.find('input[name="isChecked[]"]');
        if (check.is(':checked')) {
            amount1 = amount1 + parseFloat(_thisRow.find('input[name="inqty[]"]').autoNumeric('get'));
            amount2 = amount2 + parseFloat(_thisRow.find('input[name="FA_ASSET_DTL_DV.inDeprAmt[]"]').autoNumeric('get'));
            amount3 = amount3 + parseFloat(_thisRow.find('input[name="FA_ASSET_DTL_DV.stnDepr[]"]').autoNumeric('get'));
            amount4 = amount4 + parseFloat(_thisRow.find('input[name="incostamt[]"]').autoNumeric('get'));
            amount5 = amount5 + parseFloat(_thisRow.find('input[name="actualcost[]"]').autoNumeric('get'));
            amount6 = amount6 + parseFloat(_thisRow.find('input[name="indepramt[]"]').autoNumeric('get'));
            amount7 = amount7 + parseFloat(_thisRow.find('input[name="salvageamt[]"]').autoNumeric('get'));
            amount8 = amount8 + parseFloat(_thisRow.find('input[name="intaxdepramt[]"]').autoNumeric('get'));
            amount9 = amount9 + parseFloat(_thisRow.find('input[name="originalcost[]"]').autoNumeric('get'));
            amount10 = amount10 + parseFloat(_thisRow.find('input[name="calculatecost[]"]').autoNumeric('get'));
        }
    }
    
    $("#assetDtls > tfoot", depreciationWindowId).find("td.inqty_sum").autoNumeric('set', amount1);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.indepramt_sum").autoNumeric('set', amount2);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.standartindepramt_sum").autoNumeric('set', amount3);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.incostamt_sum").autoNumeric('set', amount4);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.originalcost_sum").autoNumeric('set', amount9);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.actualcost_sum").autoNumeric('set', amount5);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.indepramt_ssum").autoNumeric('set', amount6);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.salvageamt_sum").autoNumeric('set', amount7);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.intaxdepramt_sum").autoNumeric('set', amount8);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.calculatecost_sum").autoNumeric('set', amount10);
}
function assetDtlRemove(elem){
    var target_row = $(elem).closest("tr");
    rowRemovedChanged[target_row.find("input[name='keyId[]']").val()] = true;
    $(target_row).remove();
    /*calculateFooter();*/
    $('#fz-parent', depreciationWindowId).trigger('scroll');
    return false;
}
function detailedTr(elem){
    var $tr = $(elem).closest("tr");
    var $dialogName = 'dialog-assetDtl';
    
    if (!$("#" + $dialogName, $tr).length) {
        $("#" + $dialogName, $tr).dialog('destroy').remove();
    }
    if (!$("#" + $dialogName, $tr).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo($tr);
    }    
    var $dialog = $("#" + $dialogName, $tr);
    
    $.ajax({
        type: 'post',
        url: 'mdasset/detailedDeprAsset',
        data: {
            usageyear: $tr.find("input[name='usageYear[]']").val(),
            stusageyear: $tr.find("input[name='stusageYear[]']").val(),
            originalusageyear: $tr.find("input[name='originalusageyear[]']").val(),
            originalstusageyear: $tr.find("input[name='originalstusageyear[]']").val(),
            accountname: $tr.find("input[name='dtlAccountName[]']").val(),
            accountid: $tr.find("input[name='dtlAccountId[]']").val(),
            assetLocationName: $tr.find("input[name='assetLocationName[]']").val(),
            assetEmployeeName: $tr.find("input[name='assetEmployeeName[]']").val(),
            assetDeprMethodName: $tr.find("input[name='assetDeprMethodName[]']").val(), 
            customerCode: $tr.find("input[name='customerCode[]']").val()
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                appendTo: $tr,
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: "auto",
                modal: false,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                "closable": true,
                "maximizable": true,
                "minimizable": true,
                "collapsable": true,
                "dblclick": "maximize", "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initInputType($dialog);
    });
}
function calcCountQty(){
    var trLength = $("table#assetDtls > tbody > tr", depreciationWindowId).length;
    var loanMonth =  $(depreciationWindowId + " #depMonth").autoNumeric('get');   
    
    if (trLength > 0) {
        $("table#assetDtls > tbody > tr", depreciationWindowId).each(function(i) {
            var _thisRow = $(this);
            
            var this_check = _thisRow.find("input[name='isChecked[]']");
            var countQty = _thisRow.find("input[name='count[]']").val();
            var inCostAmt = Number($(_thisRow).find("input[name='incostamt[]']").autoNumeric("get"));
            var actualCost = $(_thisRow).find("input[name='actualcost[]']").autoNumeric("get");
            var salvageAmt = $(_thisRow).find("input[name='salvageamt[]']").autoNumeric("get");
            var inQty = $(_thisRow).find("input[name='inqty[]']").autoNumeric("get");
            var usageYear = $(_thisRow).find("input[name='usageYear[]']").val();
            var stusageYear = $(_thisRow).find("input[name='stusageYear[]']").val();
            var oldindepramt = $(_thisRow).find("input[name='indepramt[]']").autoNumeric("get");
            var clDeprDate = $(_thisRow).find("input[name='theClosestDeprDate[]']").val();
            var diff_stn = 0;
            var bookdate = $(depreciationWindowId+" #bookDate").val();
            var intaxdepramt = Number($(_thisRow).find("input[name='intaxdepramt[]']").autoNumeric("get"));
            
//                if(countQty > loanMonth*30){
//                    $(_thisRow).find("input[name='countqty[]']").val(loanMonth*30);
//                    var inDeprAmt = (inCostAmt - (salvageAmt * inQty))* deprPercent / 12 * loanMonth / 100;
//                    $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
//                }else{
//                    var inDeprAmt = (inCostAmt - (salvageAmt * inQty))* deprPercent / 12 / 30 * countQty / 100;
//                    $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
//                }
            if (this_check.is(":checked")) {
                var split_bookDate = bookdate.split("-");
                var split_clDeprDate = clDeprDate.split("-");
                var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                var firstDate = new Date(split_clDeprDate[0], split_clDeprDate[1],split_clDeprDate[2]);
                var secondDate = new Date(split_bookDate[0], split_bookDate[1],split_bookDate[2]);
                
                if (calcMethod == 1) {
                    if (loanMonth != 0 && loanMonth != '') {
                        var split_date = bookdate.split('-');
                        var n = parseInt(split_date[0]);

                        var dayOfYear = (n%4==0 ? 366:365);

                        // үлдэх өртөг * тоо ширхэг
                        var uldehCost = salvageAmt * inQty;
                        //Элэгдэл = Нийт өртөг - үлдэх өртөг
                        var q1 = inCostAmt - uldehCost;
                        // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                        var countqty = q1 / (usageYear * dayOfYear);
                        // q1 / (Татварын жил *365 )
                        var countqtys = q1 /(stusageYear*dayOfYear);
                        // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                        var inDeprAmt = countqty * loanMonth;
                        // Татварын элэгдэл 
                        var stnDepr =  countqtys * loanMonth;
                        
                        if (inDeprAmt > (actualCost - (salvageAmt * inQty))) {
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", (actualCost - (salvageAmt * inQty)));
                        } else {
                            if (inDeprAmt > actualCost) {
                                _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", actualCost);
                            } else {
                                _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                            }
                        }
                        
                        if (inCostAmt-oldindepramt-(salvageAmt*inQty) > 0) {
                            if (isChecked_stn == true) {
                                diff_stn =  inDeprAmt-stnDepr;
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                            }
                        } else {
                            $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                            if (isChecked_stn == true) {
                                diff_stn =  inDeprAmt-stnDepr;
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                            }
                        }
                    } else {
                        $(_thisRow).find("input[name='countqty[]']").val(countQty);
                        $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                        if (isChecked_stn == true) {
                            diff_stn =  inDeprAmt-stnDepr;
                            $(_thisRow).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                            $(_thisRow).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                        }
                    }
                }
                
                if (calcMethod == 0) {
                    var split_date = bookdate.split('-');
                    var n = parseInt(split_date[0]);
                    var dayOfYear = (n%4==0 ? 366:365);
                    var diffDays = countQty;
                    // үлдэх өртөг * тоо ширхэг
                    var uldehCost = salvageAmt * inQty;
                    //Элэгдэл = Нийт өртөг - үлдэх өртөг
                    var q1 = inCostAmt - uldehCost;
                    // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                    var countqty = q1 / (usageYear * dayOfYear);
                    // q1 / (Татварын жил *365 )
                    var countqtys = q1 /(stusageYear * dayOfYear);
                    // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                    var inDeprAmt = countqty * diffDays;
                    //console.log("inDeprAmt = countqty * diffDays: " +inDeprAmt+"="+countqty+"*"+diffDays);
                    // Татварын элэгдэл 
                    var stnDepr =  countqtys * diffDays;
                    
                    if (inDeprAmt > (actualCost - (salvageAmt * inQty))) {
                        _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", (actualCost - (salvageAmt * inQty)));
                        console.log('case 1 - ' + i);
                    } else {
                        if (inDeprAmt > actualCost) {
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", actualCost);
                            console.log('case 2 - ' + i);
                        } else {
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                            console.log('case 3 - ' + i);
                        }
                    }
                    if (inCostAmt-oldindepramt-(salvageAmt*inQty) > 0) {
                        if (isChecked_stn == true) {
                            diff_stn = inDeprAmt-stnDepr;
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                        }
                    } else {
                        $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                        console.log('case 4 - ' + i);
                        if (isChecked_stn == true) {
                            diff_stn = inDeprAmt-stnDepr;
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                        }
                    }
                }
                
                if (calcMethod == 2) {
                    if (loanMonth != 0 && loanMonth != '') {
                        var split_date = bookdate.split('-');
                        var n = parseInt(split_date[0]);

                        var dayOfYear = 12;

                        // үлдэх өртөг * тоо ширхэг
                        var uldehCost = salvageAmt * inQty;
                        //Элэгдэл = Нийт өртөг - үлдэх өртөг
                        var q1 = inCostAmt - uldehCost;
                        // нэг өдрийн элэгдэл = q1 / (Ашиглах жил *365 )
                        var countqty = q1 / (usageYear * dayOfYear);
                        // q1 / (Татварын жил *365 )
                        var countqtys = q1 /(stusageYear*dayOfYear);
                        // Санхүүгийн элэгдэл = элэгдэл * хугацаа
                        var inDeprAmt = countqty * loanMonth;
                        // Татварын элэгдэл 
                        var stnDepr =  countqtys * loanMonth;
                        
                        if (inDeprAmt > (actualCost - (salvageAmt * inQty))) {
                            _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", (actualCost - (salvageAmt * inQty)));
                        } else {
                            if (inDeprAmt>actualCost) {
                                _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", actualCost);
                            } else {
                                _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                            }
                        }
                        
                        if (inCostAmt-oldindepramt-(salvageAmt*inQty) > 0) {
                            if (isChecked_stn == true) {
                                diff_stn =  inDeprAmt-stnDepr;
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                            }
                        } else {
                            $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                            if (isChecked_stn == true) {
                                diff_stn = inDeprAmt-stnDepr;
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                                $(_thisRow).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                            }
                        }
                    } else {
                        $(_thisRow).find("input[name='countqty[]']").val(countQty);
                        $(_thisRow).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                        if (isChecked_stn == true) {
                            diff_stn =  inDeprAmt-stnDepr;
                            $(_thisRow).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                            $(_thisRow).find("input[name='FA_ASSET_DTL_DV.diff[]']").val(0);
                        }
                    }
                }
                
                diff_stn =  inDeprAmt-stnDepr;
                _thisRow.find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", stnDepr);
                _thisRow.find("input[name='FA_ASSET_DTL_DV.diff[]']").val(diff_stn);
                //_thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", inDeprAmt);
                if (inCostAmt == intaxdepramt || inCostAmt < intaxdepramt) {
                    _thisRow.find("input[name='FA_ASSET_DTL_DV.stnDepr[]']").autoNumeric("set", 0);
                }
                if (inCostAmt == oldindepramt) {
                    _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", 0);
                    console.log('case 5 - ' + i);
                }
                if(inCostAmt < oldindepramt){
                    _thisRow.find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']").autoNumeric("set", actualCost);
                    console.log('case 6 - ' + i);
                }
            }
        });
    }
    calculateFooter();
}
function depreciationToGl(){
    var isCheck = 0;   
    
    $('#assetDtls > tbody > tr', depreciationWindowId).each(function(){
        var _this = $(this);
        if (_this.find('input[name="isChecked[]"]').is(":checked")) {
            isCheck = 1;
            return false;
        }
    });
    
    PNotify.removeAll();
    
    if (isCheck === 0) {
        new PNotify({
            title: 'Анхааруулга',
            text: 'Жагсаалтаас сонгоно уу',
            type: 'error',
            sticker: false
        });
    } else {
        
        $(".stnDepr").attr('readonly','readonly');
        
        var cashierKeeperId = $(depreciationWindowId).find("#cashierKeeperId_valueField").val();
        if (!cashierKeeperId) {
            cashierKeeperId = '';
        }
        
        $.ajax({
            type: 'post',
            url: 'mdasset/createGlFromDepr',
            data: {
                uniqId: '<?php echo $this->uniqId; ?>', 
                bookDate: $(depreciationWindowId).find("#bookDate").val(), 
                cashierKeeperId: cashierKeeperId, 
                rowCheckChanged: rowCheckChanged, 
                rowValueChanged: rowValueChanged, 
                rowRemovedChanged: rowRemovedChanged, 
                calcstandardamt: $(depreciationWindowId).find("#isReconciliation:not([disabled])").is(':checked') ? 1 : 0,
                glDescription: $("#description", depreciationWindowId).val()
            }, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                 if (data.status == 'success') {
                    if (data.Html != '') {
                        if ($("#glTemplateSectionStatic").length > 0) {
                            $("#glTemplateSectionStatic").remove();
                        }
                        $(depreciationWindowId).find('li#glli').removeClass('hidden');
                        $(depreciationWindowId).find('div#gl').removeClass('hidden');
                        $(depreciationWindowId).find('li.nav-item > .nav-link.active').removeClass('active');
                        $(depreciationWindowId).find('div').removeClass('active');
                        $(depreciationWindowId).find('li#glli > .nav-link').addClass('active');
                        $(depreciationWindowId).find('div#gl').addClass('active');
                        $(depreciationWindowId).find('div#gl').append(data.Html);
                    }
                    
                    rowCheckChanged = {}, rowValueChanged = [], rowRemovedChanged = {};
                    
                 } else {
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                 }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }
}
function saveDepreciationBook(){
    
    if ($("table#assetDtls > tbody > tr", depreciationWindowId).length == 0) {
        new PNotify({
            title: 'Info',
            text: 'Тухайн огноогоор элэгдэл бодогдсон байна!',
            type: 'info',
            sticker: false
        });
        return false;
    }
    
    <?php
    if ($this->isNotUseGLAsset) {
    ?>
    var validGl = {'status': 'success'};
    <?php
    } else {
    ?>
    var validGl = validateGlBook($('#glTemplateSectionStatic', depreciationWindowId));
    <?php
    }
    ?>
    
    if (validGl.status == 'success') {
        
        $.ajax({
            type: 'post',
            url: 'mdasset/createAssetBook',
            data: $(depreciationWindowId).find("#saveDepreciation-form").serialize() + '&uniqId=<?php echo $this->uniqId; ?>&rowCheckChanged='+rowCheckChanged + '&rowValueChanged=' + rowValueChanged + '&rowRemovedChanged=' + rowRemovedChanged + '&calcstandardamt=' + (isChecked_stn ? 1 : 0), 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (data.status === 'success') {
                    
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    
                    rowCheckChanged = {}, rowValueChanged = [], rowRemovedChanged = {};
                    clearForm();
                    
                } else {
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
    } else {
        new PNotify({
            title: 'Error',
            text: validGl.text,
            type: 'error',
            sticker: false
        });
    } 
}
function resetHeader(){
    $("#headerFilterParam").find("input").val("");
    if ($("#isUseGl").is(':checked')) {
        $("#isUseGl").attr('checked', false);
        $("#isUseGl").parent().removeClass('checked');
    }
    $("#assetDtls > tbody", depreciationWindowId).empty();
    $("#assetDtls > tfoot", depreciationWindowId).find("td.inqty_sum").autoNumeric('set', 0);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.indepramt_sum").autoNumeric('set', 0);
}
function clearForm(){
    $("#headerParam").find("input").val('');
    if ($("#isUseGl").is(':checked')) {
        $("#isUseGl").attr('checked', false);
        $("#isUseGl").parent().removeClass('checked');
    }
    $("#assetDtls > tbody", depreciationWindowId).empty();
    $("#additional", depreciationWindowId).empty();
    
    $("#assetDtls > tfoot", depreciationWindowId).find("td.inqty_sum").autoNumeric('set', 0);
    $("#assetDtls > tfoot", depreciationWindowId).find("td.indepramt_sum").autoNumeric('set', 0);
    
    $(depreciationWindowId).find('input, select').attr("readonly", false);
    $(depreciationWindowId).find('button, input[type="radio"]').attr("disabled", false);
    
    if($(depreciationWindowId).find('div#gl').find("#glTemplateSectionStatic").length > 0){
        $(depreciationWindowId).find('div#gl').find("#glTemplateSectionStatic").remove();
        
        $(depreciationWindowId).find('li#glli').addClass('hidden');
        $(depreciationWindowId).find('div#gl').addClass('hidden');
        $(depreciationWindowId).find('li').removeClass('active');
        $(depreciationWindowId).find('div').removeClass('active');
        $(depreciationWindowId).find('li:first-child').addClass('active');
        $(depreciationWindowId).find('.tab-content').find('div:first-child').addClass('active');
    }
}
function depreciationAutoComplete(){
    $("body").on("focus", depreciationWindowId + ' input.assetdepr-code-ac:not(disabled, readonly)', function(e){
        deprlookupAutoComplete($(this), 'code');
    });
    $("body").on("focus", depreciationWindowId + ' input.assetdepr-name-ac:not(disabled, readonly)', function(e){
        deprlookupAutoComplete($(this), 'name');
    });   
    $("body").on("keydown", depreciationWindowId + ' input.assetdepr-code-ac:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13) {
            $(this).autocomplete("destroy");
            return false;
        }
    });
    $("body").on("keydown", depreciationWindowId + ' input.assetdepr-name-ac:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13) {
            $(this).autocomplete("destroy");
            return false;
        }
    });
    
    $("body").on("keydown", depreciationWindowId + ' input.assetdepr-code-ac:not(disabled, readonly)', function (e) {
        var isName = false;
        if ($(this).hasClass('assetdepr-name-ac')) {
            isName = true;
        }
        if (e.which === 13) {
            var _this = $(this);
            var _value = _this.val();
            var _parent = _this.closest("div.input-group");
            var _lookupCode = _parent.attr("data-section-path");
            var params = '';
            
            if (_lookupCode == 'FA_ASSET_KEEPER_KEY_LIST') {
                params = 'filterDepartmentId='+$('#departmentId_valueField', depreciationWindowId).val();
            }

            $.ajax({
                type: 'post',
                url: 'mdasset/deprAutoCompleteById',
                data: {
                    lookupCode: _lookupCode, 
                    code: _value,
                    isName: isName, 
                    params: params 
                },
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    
                    if (data.META_VALUE_ID !== '') {
                        
                        _parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).trigger("change");
                        _parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                        _parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                        
                    } else {
                        _parent.find("input[id*='_valueField']").val('').trigger("change");
                        _parent.find("input[id*='_nameField']").val('').attr('title', '');
                    }
                    
                    setDescriptionValue();
                   
                    _this.removeClass("spinner2");
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    });
}
function deprlookupAutoComplete(elem, type){
    var _this = elem;
    var isHoverSelect = false;
    var _parent = _this.closest("div.input-group");
    var lookupCode = _parent.attr("data-section-path");
    var params = '';
    
    if (lookupCode == 'FA_ASSET_KEEPER_KEY_LIST') {
        params = 'filterDepartmentId='+$('#departmentId_valueField', depreciationWindowId).val();
    }
    
    _this.autocomplete({
        minLength: 1,
        maxShowItems: 10,
        delay: 500,
        highlightClass: "lookup-ac-highlight", 
        appendTo: "body",
        position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
        autoSelect: false,
        source: function(request, response) {
            $.ajax({
                type: 'post',
                url: 'mdasset/deprLookupAutoComplete',
                dataType: "json",
                data: {
                    lookupCode: lookupCode, 
                    q: request.term, 
                    type: type, 
                    params: params 
                },
                success: function(data) {
                    if (type == 'code') {
                        response($.map(data, function(item) {
                            var code = item.codeName.split("|");
                            return {
                                value: code[1], 
                                label: code[1],
                                name: code[2],
                                row : item.row
                            };
                        }));
                    } else {
                        response($.map(data, function(item) {
                            var code = item.codeName.split("|");
                            return {
                                value: code[2], 
                                label: code[1],
                                name: code[2],
                                row : item.row
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
            //$(this).autocomplete('widget').style.zIndex = "99999999999999";
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
                } else {
                    _parent.find("input[id*='_nameField']").val(ui.item.name);
                }
            } else {
                if (type === 'code') {
                    if (ui.item.label === _this.val()) {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        _parent.find("input[id*='_displayField']").val(_this.val());
                        event.preventDefault();
                    }
                } else {
                   
                    if (ui.item.name === _this.val()) {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        _parent.find("input[id*='_nameField']").val(_this.val());
                        event.preventDefault();
                    }
                }
                
                if (lookupCode = 'Department11') {
                    depreciationcashierKeeperEnable();
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
function depreciationResizeDtlTable() {
    var $freezeParent = $('#fz-parent', depreciationWindowId);
    if ($freezeParent.length) {
        var dynamicHeight = $(window).height() - $freezeParent.offset().top - 65;
        var $assetDtls = $('table#assetDtls', depreciationWindowId);
        $freezeParent.css('height', dynamicHeight);
        $assetDtls.tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9}); 
        $freezeParent.trigger('scroll');
    }
}
function depreciationCalcFooter(tr, actionCode) {
    var inDeprAmtElement = $(tr).find("input[name='FA_ASSET_DTL_DV.inDeprAmt[]']");
    var stnDeprElement = $(tr).find("input[name='FA_ASSET_DTL_DV.stnDepr[]']");

    var inDeprAmtFooterElement = $("#assetDtls > tfoot", depreciationWindowId).find('td.indepramt_sum');
    var stnDeprFooterElement = $("#assetDtls > tfoot", depreciationWindowId).find('td.standartindepramt_sum');

    var inDeprAmt = Number(inDeprAmtElement.autoNumeric('get'));
    var stnDepr = Number(stnDeprElement.autoNumeric('get'));

    var inDeprAmtFooter = Number(inDeprAmtFooterElement.autoNumeric('get'));
    var stnDeprFooter = Number(stnDeprFooterElement.autoNumeric('get'));
    
    if (actionCode == 'minus') {
        
        if (inDeprAmtFooter > 0) {
            var inDeprAmtResult = inDeprAmtFooter - inDeprAmt;
            if (inDeprAmtResult < 0) {
                inDeprAmtFooterElement.autoNumeric('set', 0);
            } else {
                inDeprAmtFooterElement.autoNumeric('set', inDeprAmtResult);
            }
        }

        if (stnDeprFooter > 0) {
            var stnDeprResult = stnDeprFooter - stnDepr;
            if (stnDeprResult < 0) {
                stnDeprFooterElement.autoNumeric('set', 0);
            } else {
                stnDeprFooterElement.autoNumeric('set', stnDeprResult);
            }
        }

        inDeprAmtElement.autoNumeric('set', 0);
        stnDeprElement.autoNumeric('set', 0);
        
    } else {
        
        inDeprAmtFooterElement.autoNumeric('set', (inDeprAmtFooter + inDeprAmt));
        stnDeprFooterElement.autoNumeric('set', (stnDeprFooter + stnDepr));
    }
}
function setDescriptionValue() {
    var bookDate = '',
        department = '',
        cashier = '',
        asset = '',
        assetGroup = '',
        location = '';
    
    if ($("#bookDate", depreciationWindowId).val() !== '') {
        bookDate = $("#bookDate", depreciationWindowId).val() + ' ';
    }
    if ($("#departmentCode_displayField", depreciationWindowId).val() !== '') {
        department = $("#departmentCode_displayField", depreciationWindowId).val() + ' | ';
        department += $("#departmentName_nameField", depreciationWindowId).val() + ' ';
    }
    if ($("#cashierKeeperCode_displayField", depreciationWindowId).val() !== '') {
        cashier = $("#cashierKeeperCode_displayField", depreciationWindowId).val() + ' | ';
        cashier += $("#cashierKeeperName_nameField", depreciationWindowId).val() + ' ';
    }
    if ($("#assetOwnerCode_displayField", depreciationWindowId).val() !== '') {
        asset = $("#assetOwnerCode_displayField", depreciationWindowId).val() + ' | ';
        asset += $("#assetOwnerName_nameField", depreciationWindowId).val() + ' ';
    }
    if ($("#assetGroupCode_displayField", depreciationWindowId).val() !== '') {
        assetGroup = $("#assetGroupCode_displayField", depreciationWindowId).val() + ' | ';
        assetGroup += $("#assetGroupName_nameField", depreciationWindowId).val() + ' ';
    }
    if ($("#locationCode_displayField", depreciationWindowId).val() !== '') {
        location = $("#locationCode_displayField", depreciationWindowId).val() + ' | ';
        location += $("#locationName_nameField", depreciationWindowId).val() + ' ';
    }
    
    $("#description", depreciationWindowId).text(bookDate + department + cashier + asset + assetGroup + location);    
}
function depreciationcashierKeeperEnable() {
    $("#cashierKeeperCode_displayField", depreciationWindowId).removeAttr('readonly');
    $("#cashierKeeperName_nameField", depreciationWindowId).removeAttr('readonly');
    $("div[data-section-path='FA_ASSET_KEEPER_KEY_LIST']", depreciationWindowId).find('button').removeAttr('disabled'); 
}
</script>