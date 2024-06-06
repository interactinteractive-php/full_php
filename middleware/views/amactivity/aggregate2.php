<div class="col-md-12 pl0 pr0" id="amactivityAggregate2MaindWindow">
    <div class="card-body xs-form">
        
        <div class="row">
            <div class="tabbable-line tab-not-padding-top" >
                <ul class="nav nav-tabs bp-addon-tab">
                    <li>
                        <a href="#aggregate_main_tab_1487128569927652__" class="active" data-toggle="tab">Үндсэн</a>
                    </li>
                    <li>
                        <a href="#aggregate_relation_tab_1487128569927652__" data-toggle="tab">Бусад</a>
                    </li>
                    <li>
                        <a href="#aggregate_wfmlog_tab_1487128569927652__" data-toggle="tab"><?php echo $this->lang->line('wfmlog') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="aggregate_main_tab_1487128569927652__">
                        <div class="col pl0">
                            <div class="form-group row fom-row">
                                <div class="col-md-12 col-xs-12 pl0  pr0">
                                    <div class="next-generation-input-wrap">
                                        <div class="next-generation-input-label blue">
                                            Төлөвлөлт
                                            <div class="next-generation-input-group">
                                                <div class="meta-autocomplete-wrap" data-section-path="activityKeyId">
                                                    <input type="hidden" id="activityKeyId_valueField" class="activityKeyId_valueField" name="activityKeyId" value="<?php echo $this->activityKeyId; ?>" required="required">
                                                    <input type="text" disabled="disabled" id="activityKeyId_displayField" name="calcCode" class="form-control form-control-sm lookup-code-autocomplete-activity activityKeyId_displayField" value="<?php echo $this->getRowActivityKey['ACTIVITY_KEY_CODE']; ?>" required="required" title="" placeholder="<?php echo $this->lang->line('code_search'); ?>" data-lookupid="<?php echo $this->lookUpCalc['META_DATA_ID']; ?>" data-lookuptypeid="<?php echo $this->lookUpCalc['META_TYPE_ID']; ?>">                                                                        
                                                </div>                                                                
                                            </div>    
                                        </div>
                                        <div class="next-generation-input-body blue" id="activityKeyId_nameField">
                                            <?php echo $this->getRowActivityKey['DESCRIPTION']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>                                                    
                    </div>      
                    <div class="tab-pane" id="aggregate_relation_tab_1487128569927652__">
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group row fom-row">
                                            <div class="col-md-4">
                                                <?php echo Form::label(array( 'text' => 'Файл', 'for' => 'periodId', 'class' => 'customLabel col-form-label float-right')); ?>
                                            </div>
                                            <div class="col-md-8 main-header-file-area">
                                                <div class="clearfix w-100"></div>
                                                <?php
                                                if(!empty($this->attachFiles)) {
                                                    $fileIndex = 0;

                                                    foreach ($this->attachFiles as $attach) { 
                                                        if(file_exists($attach['path'])) { ?>
                                                            <div class="mb10">
                                                                <input type="hidden" name="activity_file_edit[]" class="" value="<?php echo Arr::encode($attach); ?>">                                                                
                                                                <input type="hidden" name="activity_file_action[]" class="">                                                                
                                                       <?php        
                                                                echo $attach['attachname'];
                                                                echo html_tag('a', array('href' => 'mdobject/downloadFile?file=' . $attach['path'], 'title' => 'Файл татах', 'class' => 'dg-custom-tooltip ml10', 'style' => 'margin-top:-23px; margin-right:4px;'), '<i class="fa fa-file text-success"></i>');
                                                                echo html_tag('a', array('href' => 'javascript:;', 'onclick' => 'amactivityObj.editFileRemove(this)', 'title' => 'Файл устгах', 'class' => 'dg-custom-tooltip', 'style' => 'margin-top:-23px; margin-right:4px;'), '<i class="fa fa-lg fa-remove text-danger"></i>');
                                                            ?> 
                                                            </div><div class="clearfix w-100"></div>
                                                        <?php 
                                                        }
                                                        $fileIndex++;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row fom-row">
                                            <div class="col-md-4">
                                                <?php echo Form::label(array( 'text' => 'Эхлэх огноо', 'for' => 'startDate', 'class' => 'customLabel col-form-label float-right')); ?>
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
                                                <?php echo Form::label(array( 'text' => 'Дуусах огноо', 'for' => 'endDate', 'class' => 'customLabel col-form-label float-right')); ?>
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
                                                <?php echo Form::label(array( 'text' => 'Салбар нэгж', 'for' => 'depName', 'class' => 'customLabel col-form-label float-right')); ?>
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
                                                <?php echo Form::label(array( 'text' => 'Бүхэлчлэх хэлбэр', 'for' => 'periodId', 'class' => 'customLabel col-form-label float-right')); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control form-control-sm" name="roundValue" disabled="disabled" id="roundValue">
                                                    <option value='1'>Нэгжээр</option>
                                                    <option value='1000'>Мянгаар</option>
                                                    <option value='1000000'>Саяаар</option>
                                                    <option value='1000000000'>Тэрбумаар</option>
                                                </select>
                                            </div>
                                        </div>                                                                   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="aggregate_wfmlog_tab_1487128569927652__">
                        <fieldset class="collapsible">
                            <legend><?php echo $this->lang->line('wfmlog') ?></legend>
                            <div class="col-md-12"><?php echo $this->wfmlog; ?></div>
                            <style>
                                #aggregate_wfmlog_tab_1487128569927652__ .mt-element-list .list-todo.mt-list-container {
                                    border: none !important;
                                }
                            </style>
                        </fieldset>
                    </div>
                </div>      
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 pl0 pr0">
                <div class="row">
                    <div class="col-md-12 mt5">
                        <div class="btn-group btn-group-devided">
                            <?php
                                $singleMenuHtml = '';
                                if (isset($this->wfmStatusBtns) &&isset($this->wfmStatusBtns['result'])) {
                                    $singleMenuHtml .= '<span  class="workflowBtn-'. $this->methodId .'"></span >';
                                    foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
                                        $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetVar($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetVar($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\',  \'\', undefined, \''. $this->methodId .'\', undefined , undefined , \'' . $wfmstatusRow['wfmstatusprocessid'] . '\' , \'' . $wfmstatusRow['wfmisdescrequired'] . '\', \''. $this->workspaceId .'\', \'1\');"';
                                        $singleMenuHtml .= '<button data-dm-id="'. $this->dmMetaDataId .'" type="button" ' . $wfmMenuClick . ' class="btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['wfmstatusname'] .'</button> ';
                                    } 
                                    echo $singleMenuHtml; 
                                } 
                            ?>
                        </div>
                        <div class="btn-group btn-group-devided text-right float-right">
                            <a onclick="amactivityObj.amactivityColumnConfig2()" title="Тохиргоо" class="btn float-right btn-sm btn-circle blue ml5" href="javascript:;"><i class="fa fa-cog"></i></a>
                            <?php
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-file-excel-o"></i>', array(
                                        'class' => 'btn btn-secondary btn-circle btn-sm default',
                                        'title' => $this->lang->line('excel_btn'),
                                        'onclick' => 'amactivityObj.dataExportToExcelAmactivity2();'
                                    ), true
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-file-pdf-o"></i>', array(
                                        'class' => 'btn btn-secondary btn-circle btn-sm default  ',
                                        'title' => $this->lang->line('pdf_btn'),
                                        'onclick' => 'amactivityObj.dataExportToPdfAmactivity();'
                                    ), true
                                );
                            ?>
                        </div>
                    </div>                        
                    <div class="col-md-12 jeasyuiTheme3 mt5" id="dataGridDiv">
                        <table class="no-border mt0" id="aggregate2datagrid_<?php echo $this->activityKeyId ?>" style="width: 100%; "></table>
                    </div> 
                </div>
                <div class="form-actions mt15 form-actions-btn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <span id="fieldSpan" class="float-left" style="font-weight: bold !important;"></span><span id="fieldExpressionSpan" class="float-left"></span>
                            </div>
                            <div class="col-md-4">
                                <?php
                                    //echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success float-right ml10 saveActivitySheetBtn hidden', 'value' => '<i class="fa fa-save"></i> ' . $this->lang->line('save_btn')));
                                if (Config::getFromCache('CONFIG_MULTI_TAB')) {
//                                        echo Form::button(array('class' => 'btn btn-circle btn-sm blue float-right backFromActivitySheet', 'onclick' => 'backFirstContent(this);', 'value' => '<i class="fa fa-reply"></i> ' . $this->lang->line('back_btn')));
                                } else {
                                    echo Form::button(array('class' => 'btn btn-circle btn-sm blue float-right backFromActivitySheet', 'onclick' => 'backWindowDataViewFilter();', 'value' => '<i class="fa fa-reply"></i> ' . $this->lang->line('back_btn')));
                                }
                                ?>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
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

    $.getScript('middleware/assets/js/amactivity_oop.js', function() {
        amactivityObj = new Amactivity(activityKeyId);

        if(activityKeyId !== '0000009'){
            amactivityObj.loadDataGridAggregate2();        
        }
    });
    
    function dataGridFormatterGeneral(value, row, index){
        if(typeof value === 'undefined' || value === null)
            return '';
        
        var nFormat = pureNumberFormat(value);
        return nFormat;
    };          
    
    function dataGridFormatterDescription(value, row, index){
        if(typeof value === 'undefined' || value === null)
            return '';        
        return replaceAll(value, "#", "&nbsp;&nbsp;&nbsp;&nbsp");
    };
    
    function replaceAll(str, find, replace) {
        var i = str.indexOf(find);
        if (i > -1) {
            str = str.replace(find, replace); 
            i = i + replace.length;
            var st2 = str.substring(i);
            if(st2.indexOf(find) > -1){
                str = str.substring(0 , i) + replaceAll(st2, find, replace);
            }       
        }
        return str;
    }      
</script>
