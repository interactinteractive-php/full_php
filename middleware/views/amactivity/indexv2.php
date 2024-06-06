<?php $getUID = getUID(); ?>
<div class="col-md-12 pl0 amactivityMaindWindow<?php echo $getUID ?>" id="amactivityMaindWindow">
    <div class="card-body xs-form">
        <div class="col-md-12 pl0 pr0">
            <form id="activityInfoForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line" >
                                <ul class="nav nav-tabs bp-addon-tab d-none">
                                    <li>
                                        <a href="#bp_main_tab_1487128569927652__" class="active" data-toggle="tab">Үндсэн</a>
                                    </li>
                                    <li>
                                        <a href="#bp_relation_tab_1487128569927652__" data-toggle="tab">Бусад</a>
                                    </li>
                                    <li>
                                        <a href="#bp_wfmlog_tab_1487128569927652__" data-toggle="tab"><?php echo $this->lang->line('wfmlog') ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="bp_main_tab_1487128569927652__"><!-- banner -->
                                        <div class="row">
                                            <div class="col form-group fom-row ">
                                                <div class="next-generation-input-wrap">
                                                    <div class="next-generation-input-label blue">
                                                        Төлөвлөлт
                                                        <div class="next-generation-input-group">
                                                            <div class="meta-autocomplete-wrap" data-section-path="activityKeyId">
                                                                <div class="input-group">
                                                                    <input type="hidden" id="activityKeyId_valueField" class="activityKeyId_valueField" name="activityKeyId" value="<?php echo $this->activityKeyId; ?>" required="required">
                                                                    <input type="text" id="activityKeyId_displayField" name="calcCode" class="form-control form-control-sm lookup-code-autocomplete-activity activityKeyId_displayField" value="<?php echo $this->getRowActivityKey['ACTIVITY_KEY_CODE']; ?>" required="required" title="" placeholder="<?php echo $this->lang->line('code_search'); ?>" data-lookupid="<?php echo $this->lookUpCalc['META_DATA_ID']; ?>" data-lookuptypeid="<?php echo $this->lookUpCalc['META_TYPE_ID']; ?>">                                                                        
                                                                    <span class="input-group-btn">
                                                                        <button type="button" id="searchCalcButton" class="btn default btn-bordered form-control-sm mr0 searchCalcButton" onclick="dataViewCustomSelectableGrid('AM_ACTIVITY_KEY_List', 'single', 'activitySelectabledGrid', '', this);"><i class="fa fa-search"></i></button>
                                                                    </span>
                                                                </div>                                                                
                                                            </div>                                                                
                                                        </div>    
                                                    </div>
                                                    <div class="next-generation-input-body blue" id="activityKeyId_nameField">
                                                        <?php echo $this->getRowActivityKey['DESCRIPTION']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col form-group fom-row " id="activityPeriodSelect">                                         
                                                <div class="next-generation-input-wrap">
                                                    <div class="next-generation-input-label green">
                                                        Тайлант үе
                                                        <div class="next-generation-input-group">
                                                        </div>    
                                                    </div>
                                                    <div class="next-generation-input-body green" id="activityKeyId_nameField">
                                                        <select class="form-control form-control-sm" name="periodId" id="periodId">
                                                            <?php
                                                            foreach ($this->getAllActivityPeriod as $key => $comboVal) {
                                                                echo "<option value='" . $key . "'>" . $comboVal . "</option>";
                                                            }
                                                            ?>                                                        
                                                        </select>                    
                                                        <input type='hidden' id='maxDimension' value='<?php echo $this->getRowActivityKey['MAX_DIMENION']; ?>'>
                                                        <input type='hidden' id='minDimension' value='<?php echo $this->getRowActivityKey['MIN_DIMENION']; ?>'>                                                                              
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="bp_wfmlog_tab_1487128569927652__">
                                        <fieldset class="collapsible">
                                            <legend><?php echo $this->lang->line('wfmlog') ?></legend>
                                            <div class="col-md-12"><?php echo $this->wfmlog; ?></div>
                                            <style>
                                                #bp_wfmlog_tab_1487128569927652__ .mt-element-list .list-todo.mt-list-container {
                                                    border: none !important;
                                                }
                                            </style>
                                        </fieldset>
                                    </div>
                                    <div class="tab-pane" id="bp_relation_tab_1487128569927652__">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row fom-row">
                                                    <div class="col-md-4">
                                                        <?php echo Form::label(array('text' => 'Файл', 'for' => 'periodId', 'class' => 'customLabel col-form-label float-right', 'required' => 'required')); ?>
                                                    </div>
                                                    <div class="col-md-8 main-header-file-area">
                                                        <div class="clearfix w-100"></div>
                                                        <?php
                                                        if (!empty($this->attachFiles)) {
                                                            $fileIndex = 0;

                                                            foreach ($this->attachFiles as $attach) {
                                                                if (file_exists($attach['path'])) {
                                                                    ?>
                                                                    <div class="mb10">
                                                                        <input type="hidden" name="activity_file_edit[]" class="" value="<?php echo Arr::encode($attach); ?>">                                                                
                                                                        <input type="hidden" name="activity_file_action[]" class="">                                                                
                                                                        <?php
                                                                        echo $attach['attachname'];
                                                                        echo html_tag('a', array('href' => 'mdobject/downloadFile?file=' . $attach['path'], 'title' => 'Файл татах', 'class' => 'dg-custom-tooltip ml10', 'style' => 'margin-top:-23px; margin-right:4px;'), '<i class="fa fa-file text-success"></i>');
                                                                        echo html_tag('a', array('href' => 'javascript:;', 'onclick' => 'amactivityObj.editFileRemove(this)', 'title' => 'Файл устгах', 'class' => 'dg-custom-tooltip', 'style' => 'margin-top:-23px; margin-right:4px;'), '<i class="fa fa-lg fa-remove text-danger"></i>');
                                                                        echo '</div><div class="clearfix w-100"></div>';
                                                                    }
                                                                    $fileIndex++;
                                                                }
                                                            }
                                                            ?>
                                                            <div>
                                                                <input type="file" name="activity_file[]" class="float-left" onchange="hasFileExtension(this);">
                                                                <a href="javascript:;" class="btn btn-xs btn-success" title="Нэмэх" onclick="amactivityObj.addFileActivity(this);">
                                                                    <i class="icon-plus3 font-size-12"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row fom-row">
                                                        <div class="col-md-4">
<?php echo Form::label(array('text' => 'Эхлэх огноо', 'for' => 'startDate', 'class' => 'customLabel col-form-label float-right')); ?>
                                                        </div>
                                                        <div class="col-md-8 main-header-file-area">
                                                            <div class="clearfix w-100"></div>
                                                            <div>
                                                                <?php
                                                                echo Date::formatter($this->activityKey['START_DATE']);
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row fom-row">
                                                        <div class="col-md-4">
<?php echo Form::label(array('text' => 'Дуусах огноо', 'for' => 'endDate', 'class' => 'customLabel col-form-label float-right')); ?>
                                                        </div>
                                                        <div class="col-md-8 main-header-file-area">
                                                            <div class="clearfix w-100"></div>
                                                            <div>
                                                                <?php
                                                                echo Date::formatter($this->activityKey['END_DATE']);
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>             
                                                    <div class="form-group row fom-row">
                                                        <div class="col-md-4">
<?php echo Form::label(array('text' => 'Салбар нэгж', 'for' => 'depName', 'class' => 'customLabel col-form-label float-right')); ?>
                                                        </div>
                                                        <div class="col-md-8 main-header-file-area">
                                                            <div class="clearfix w-100"></div>
                                                            <div>
                                                                <?php
                                                                echo $this->activityKey['DEPARTMENT_NAME'];
                                                                ?>
                                                                <input type="hidden" name="departmentId" id="mainDepValue" value="<?php echo $this->activityKey['DEPARTMENT_ID']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row fom-row">
                                                        <div class="col-md-4">
<?php echo Form::label(array('text' => 'Бүхэлчлэх хэлбэр', 'for' => 'periodId', 'class' => 'customLabel col-form-label float-right')); ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select class="form-control form-control-sm" name="roundValue" id="roundValue">
                                                                <option value='1'>Нэгжээр</option>
                                                                <option value='1000'>Мянгаар</option>
                                                                <option value='1000000'>Саяаар</option>
                                                                <option value='1000000000'>Тэрбумаар</option>
                                                            </select>
                                                        </div>
                                                    </div>                                                                   
                                                </div>
                                                <div class="col-md-6">
                                                    <?php
                                                    if ($this->getCombo) {
                                                        foreach ($this->getCombo as $key => $val) {
                                                            ?>
                                                            <div class="form-group row fom-row">
                                                                <div class="col-md-4">
    <?php echo Form::label(array('text' => $this->getComboText[$key], 'for' => '', 'class' => 'customLabel col-form-label float-right')); ?>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input name="expressionComboKey[]" type="hidden" value="<?php echo $key; ?>" />
                                                                    <select class="form-control form-control-sm" name="expressionComboValue[]" id="roundValue">
                                                                        <?php
                                                                        if (isset($this->getCombo[$key]) && !empty($this->getCombo[$key])) {
                                                                            foreach ($this->getCombo[$key] as $kkey => $vval) {
                                                                                echo "<option value='" . $vval['activitykeyid'] . "'>" . $vval['name'] . "</option>";
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>   
<?php } ?>
<?php } ?>
                                                </div>                                                    
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="activityMainTabArea">
                <div class="row mt10 mb10">
                    <div class="btn-group btn-group-devided activityBtnSection">
                        <div class="btn-group d-none">
                            <button class="btn btn-success btn-circle btn-sm dropdown-toggle ml5" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-plus3 font-size-12"></i> Нэмэх
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="javascript:;" onclick="amactivityObj.loadButtons(0);"><i class="icon-plus3 font-size-12"></i> Нэмэх</a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="amactivityObj.loadButtons(1);"><i class="icon-plus3 font-size-12"></i> Нэмэх (Child)</a>
                                </li>
                            </ul>
                        </div>

                        <div class="btn-group d-none">
                            <button class="btn btn-danger btn-circle btn-sm dropdown-toggle ml5" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-trash"></i> Устгах
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="javascript:;" onclick="amactivityObj.deleteActivity('activity', 0);"><i class="fa fa-trash"></i> Устгах</a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="amactivityObj.deleteActivity('activity', 1);"><i class="fa fa-trash"></i> Устгах (Child)</a>
                                </li>
                            </ul>
                        </div>       

                        <?php
                        echo Html::anchor(
                                'javascript:;', '<i class="fa fa-save"></i> Хадгалах', array(
                            'class' => 'btn blue btn-circle btn-sm',
                            'title' => '',
                            'onclick' => 'amactivityObj.calculateExpression();'
                        ));
                        ?>
                        <button type="button" class="btn green-meadow btn-circle btn-sm d-none" id="addUmObjectBtn"><i class="icon-plus3 font-size-12"></i> Хамаарал нэмэх</button>

                        <?php
                        $singleMenuHtml = '';
                        if (isset($this->wfmStatusBtns) && isset($this->wfmStatusBtns['result'])) {
                            $singleMenuHtml .= '<span  class="workflowBtn-' . $this->methodId . '"></span >';
                            foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
                                $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetVar($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetVar($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\',  \'\', undefined, \'' . $this->methodId . '\', undefined , undefined , \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', \'' . $this->workspaceId . '\', \'1\');"';
                                $singleMenuHtml .= '<button data-dm-id="' . $this->dmMetaDataId . '" type="button" ' . $wfmMenuClick . ' class="btn btn-sm purple-plum btn-circle hidden-wfm-status-' . $wfmstatusRow['wfmstatusid'] . '" style="background-color:' . $wfmstatusRow['wfmstatuscolor'] . '"> ' . $wfmstatusRow['wfmstatusname'] . '</button> ';
                            }
                            echo $singleMenuHtml;
                        }
                        ?>
                    </div>
                    <?php
                    echo Html::anchor(
                            'javascript:;', '<i class="fa fa-file-excel-o"></i>', array(
                        'class' => 'btn btn-secondary btn-circle btn-sm default float-right',
                        'title' => $this->lang->line('excel_btn'),
                        'onclick' => 'amactivityObj.dataActivitySheetCtrlExcelExport();'
                            ), true
                    );
                    ?>
                </div>
                <div class="row">
                    <div class="mt10"></div>
                    <div class="col-md-12">
                        <div class="activity-expression-viewer" style="background-color: #FFDEA5; padding: 6px 10px;">
                            <i class="fa fa-calculator"></i> <span></span>
                        </div>
                    </div>
                    <div class="col-md-12 jeasyuiTheme3" id="dataGridDiv">
                        <table class="no-border mt0" id="objectdatagrid_<?php echo $this->activityKeyId ?>" style="width: 100%;"></table>
                    </div> 
                </div>
                <div class="form-actions mt15 form-actions-btn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <span id="fieldSpan" class="float-left" style="font-weight: bold !important;"></span><span id="fieldExpressionSpan" class="float-left"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loadAccount"></div>
    </div>
</div>
<style type="text/css">
    .activity-expression-viewer-class {
        z-index: 9999;
        position: fixed;
        top: 65px;        
        -webkit-box-shadow: 0 3px 9px -4px black;
        -moz-box-shadow: 0 3px 9px -4px black;
        box-shadow: 0 3px 9px -4px black;
    }
    .customLabel {
        color: #444;

        font-size: 12px !important;
        font-weight: 400;
    }
    .tooltip-inner {
        max-width: 550px;
    }
    .datagrid-htable td span {
        white-space: normal !important;
    }
    .datagrid-cell {
        white-space: nowrap !important;
    }
    /* Custom Card CSS Start */
    .next-generation-input-wrap {
        width: 100%;
        height: 80px;
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
    }
    .next-generation-input-label.green {
        background-color: #e1ebcb;
        border-left: 5px solid #abc66a;
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
</style>
<script type="text/javascript">
    
    
    var activityKeyId = '<?php echo $this->activityKeyId; ?>', amactivityObj;
    var $defaultOpen = '1';
    $.extend($.fn.datagrid.defaults.editors, {
        datebox: {
            init: function (container, options) {
                var input = $('<input type="text" data-options="formatter:activityDateFormatter,parser:dateParser,onSelect:onSelectDate">').appendTo(container);
                input.datebox(options);
                return input;
            },
            destroy: function (target) {
                $(target).datebox('destroy');
            },
            getValue: function (target) {
                return $(target).datebox('getValue');
            },
            setValue: function (target, value) {
                $(target).datebox('setValue', value);
            },
            resize: function (target, width) {
                $(target).datebox('resize', width);
            }
        }
    });

    $.getScript('assets/custom/addon/plugins/jquery-easyui/datagrid-filter.js', function () {
        $.getScript('middleware/assets/js/amactivity_oop.js', function () {
            amactivityObj = new Amactivity(activityKeyId);
            amactivityObj.initEventListener();

            if (activityKeyId !== '0000009') {
                amactivityObj.editModeLoadDataGrid();
            }
        });
    });

    var UM_OBJECT_DV_ID = '<?php echo Config::getFromCache('UM_OBJECT_DV_ID'); ?>';
    /* global umObject */
    $(function () {
        
        $('.amactivityMaindWindow<?php echo $getUID ?>').closest('.ws-area').find('.ws-menu').addClass('d-none');
        if (typeof umObject === 'undefined') {
            $.getScript(URL_APP + 'middleware/assets/js/addon/umObject.js', function () {
                $.getStylesheet(URL_APP + 'middleware/assets/css/addon/style.css');
                umObject.init(<?php echo $this->uniqId; ?>, '', <?php echo json_encode(array()); ?>);
            });
        } else {
            umObject.init(<?php echo $this->uniqId; ?>, '', <?php echo json_encode(array()); ?>);
        }
    });

    function selectedActivityFunction(metaDataCode, chooseType, elem, rows) {
        amactivityObj.insertActivityRow(metaDataCode, rows, elem);
    }
    ;

    function activitySelectabledGrid(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        amactivityObj.loadPeriod(row);
    }
    ;

    function dataGridFormatterGeneral(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            return '';
        return pureNumberFormat(value);
    }
    ;

    function dataGridFormatter_1000(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            return '';
        return pureNumberFormat(value / 1000);
    }
    ;

    function dataGridFormatter_1000000(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            return '';
        return pureNumberFormat(value / 1000000);
    }
    ;

    function dataGridFormatter_1000000000(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            return '';
        return pureNumberFormat(value / 1000000000);
    }
    ;

    function dataGridTextFormatterGeneral(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            return '';
        return '<span title="' + value + '" class="">' + value + '</span>';
    }
    ;

    function cellStyler(value, row, index) {
        if (row.haschild === '1')
//           (row['quantityformula'] !== undefined && row['quantityformula'] !== null && row['quantityformula'] !== '') ||
//           (row['unitpriceformula'] !== undefined && row['unitpriceformula'] !== null && row['unitpriceformula'] !== '') ||
//           (row['budgetamountformula'] !== undefined && row['budgetamountformula'] !== null && row['budgetamountformula'] !== ''))       
            return "background-color:#E5E5E5;";
    }
    ;

    function allCellStyler(value, row, index) {
        return "background-color:#FFDEA5;";
    }
    ;

    function dataGridFormatterDescription(value, row, index) {
        if (typeof value === 'undefined' || value === null)
        {
            return '';
        } else {
            var result = splitDesc(value);
            var spanClass = "", spanSpace = "&nbsp;&nbsp;&nbsp;";
            $.each($(amactivityObj.dataGridId).datagrid('getRows'), function (childKey, childRow) {
                if (childRow.parentid === row.id) {
                    spanClass = "tree-hit tree-collapsed";
                    spanSpace = '';
                    return false;
                }
            });
            var tmpEl = '<span>' + result.first + '<span title="' + value + '" class="' + spanClass + '"></span><span class="tree-title">' + spanSpace + result.last + '</span></span>';
            return tmpEl;
        }
    }
    ;

    function dataGridFormatterExpenseAccount(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            value = "";

        return '<div class=" quick-item" style="width: 102%">'
                + '<input type="text" name="expenseAccountQuickCode" class="expenseAccountQuickCode expenseAccountQuickCode_' + row.id + ' form-control accountCodeMask form-control-sm" value="' + value + '" style="padding-left:4px; border: none;" placeholder="Кодоор хайх" autocomplete="off">'
                + '</div>';
    }
    ;

    function dataGridFormatterRevenueAccount(value, row, index) {
        if (typeof value === 'undefined' || value === null)
            value = "";

        return '<div class=" quick-item" style="width: 102%">'
                + '<input type="text" name="revenueAccountQuickCode" class="revenueAccountQuickCode revenueAccountQuickCode_' + row.id + ' form-control accountCodeMask form-control-sm" value="' + value + '" style="padding-left:4px; border: none;" placeholder="Кодоор хайх" autocomplete="off">'
                + '</div>';
    }
    ;

    function onSelectDate(date) {
        amactivityObj.onSelectDate(date);
    }
    
    function activityDateFormatter(date) {
        if (date === "Y-m-d" || date == null) {
            return "";
        }
        var date = new Date(date);
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        var date = y + '/' + (m < 10 ? ('0' + m) : m) + '/' + (d < 10 ? ('0' + d) : d);
        return date;
    }
    
    function dateParser(s) {
        if (!s)
            return new Date();
        var ss = Date(s);
        return new Date(s);
    }

    var splitDesc = function (str) {
        var splited = str.split("");
        var first = "";
        var last = "";
        $.each(splited, function (key, val) {
            if (val === "#") {
                first += val;
            } else {
                last = str.substring(key, str.length);
                return false;
            }
        });
        first = first.replace(new RegExp("#", 'g'), "&nbsp;&nbsp;&nbsp;&nbsp");
        return {first: first, last: last};
    };

    function lookupAutoCompleteActivity(elem, type) {
        var _this = elem;
        var _lookupId = _this.attr("data-lookupid");
        var _metaDataId = _this.attr("data-metadataid");
        var _processId = _this.attr("data-processid");
        var bpElem = _this.parent().parent().find("input[type='hidden']");
        var _paramRealPath = bpElem.attr("data-path");
        var _parent = _this.closest("div.meta-autocomplete-wrap");
        var mainSelector = $("#bp-window-" + _processId + ":visible");
        var params = '';
        var isHoverSelect = false;

        if (typeof bpElem.attr("data-criteria-param") !== 'undefined') {
            var paramsPathArr = bpElem.attr("data-criteria-param").split("|");
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split("@");
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '';

                if ($("[data-path='" + fieldPath + "']", mainSelector).length > 0) {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                } else {
                    fieldValue = fieldPath;
                }

                params += inputPath + "=" + fieldValue + "&";
            }
        }

        if (typeof bpElem.attr("data-in-param") !== 'undefined' && typeof bpElem.attr('data-in-lookup-param') !== 'undefined') {
            var paramsPathArr = bpElem.attr('data-in-param').split('|');
            var lookupPathArr = bpElem.attr('data-in-lookup-param').split('|');
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPath = paramsPathArr[i];
                var inputPath = lookupPathArr[i];
                var fieldValue = '';

                if ($("[data-path='" + fieldPath + "']", mainSelector).length > 0) {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                } else {
                    fieldValue = fieldPath;
                }

                params += inputPath + "=" + fieldValue + "&";
            }
        }

        _this.autocomplete({
            minLength: 1,
            maxShowItems: 30,
            delay: 500,
            highlightClass: "lookup-ac-highlight",
            appendTo: "body",
            position: {my: "left top", at: "left bottom", collision: "flip flip"},
            autoSelect: false,
            source: function (request, response) {

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
                    success: function (data) {
                        if (type == 'code') {
                            response($.map(data, function (item) {
                                var code = item.split("|");
                                return {
                                    value: code[1],
                                    label: code[1],
                                    name: code[2],
                                    id: code[0]
                                };
                            }));
                        } else {
                            response($.map(data, function (item) {
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
            focus: function (event, ui) {
                if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                    isHoverSelect = false;
                } else {
                    if (event.keyCode == 38 || event.keyCode == 40) {
                        isHoverSelect = true;
                    }
                }
                return false;
            },
            open: function () {
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function () {
                $(this).autocomplete("option", "appendTo", "body");
            },
            select: function (event, ui) {
                var origEvent = event;

                if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                    if (type === 'code') {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_displayField']").attr('data-ac-id', ui.item.id);
                        _parent.find("input[id*='_valueField']").val(ui.item.id);
                        amactivityObj.loadPeriod(ui.item.id);
                    } else {
                        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                    }
                } else {
                    if (type === 'code') {
                        if (ui.item.label === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.find("input[id*='_valueField']").val(ui.item.id);
                            amactivityObj.loadPeriod(ui.item.id);
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

                while (origEvent.originalEvent !== undefined) {
                    origEvent = origEvent.originalEvent;
                }

                if (origEvent.type === 'click') {
                    var e = jQuery.Event("keydown");
                    e.keyCode = e.which = 13;
                    _this.trigger(e);
                }
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            ul.addClass('lookup-ac-render');

            if (type === 'code') {
                var re = new RegExp("(" + this.term + ")", "gi"),
                        cls = this.options.highlightClass,
                        template = "<span class='" + cls + "'>$1</span>",
                        label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
            } else {
                var re = new RegExp("(" + this.term + ")", "gi"),
                        cls = this.options.highlightClass,
                        template = "<span class='" + cls + "'>$1</span>",
                        name = item.name.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
            }
        };
    }
    
</script>
