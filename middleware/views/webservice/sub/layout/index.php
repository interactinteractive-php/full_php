<?php

$isDtlTbl = (isset($this->isDtlTbl) && $this->isDtlTbl) ? true : false;

if (issetParam($this->isBpOpenParam) == '1') {
    $jsGlobal = '<script type="text/javascript">'; 
    $jsGlobal .= 'var isBpOpenParam'.$this->uniqId.' = "is-bp-open-'.issetParam($this->isBpOpenParam).'";'; 
    $jsGlobal .= '</script>';
    echo $jsGlobal;
}

if ($this->isDialog == false && Mdwebservice::$isLogViewMode == false) {
    $mainProcessBtnBar = '<div class="meta-toolbar is-bp-open-'.issetParam($this->isBpOpenParam).'">';

    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-sm btn-circle btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left22"></i>', true
            );
            $mainProcessBtnBar .= ' <div class="main-process-text">';
                $mainProcessBtnBar .= ' <span class="bp-text">' . $this->lang->line('business_process') . ' - </span>';
                $mainProcessBtnBar .= '<span>' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            $mainProcessBtnBar .= '</div>';
        } else {
            if (issetParam($this->isBpOpenParam) != '1') {
                $mainProcessBtnBar .= html_tag('a', array(
                    'href' => 'javascript:;',
                    'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                    'onclick' => 'backFirstContent(this);',
                    'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="icon-arrow-left22"></i>', ($this->dmMetaDataId ? true : false)
                );
            }
            if (Input::postCheck('isBackBtnIgnore') == false) {
                $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            }
        }
    } else {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left22"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            if (issetParam($this->isBpOpenParam) != '1') {
                $mainProcessBtnBar .= html_tag('a', array(
                    'href' => 'javascript:;',
                    'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                    'onclick' => 'backFirstContent(this);',
                    'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="icon-arrow-left22"></i>', true
                );
            }
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }

    $reportPrint = '';
    
    if ($this->isPrint && $this->isEditMode) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 bp-btn-print ' . (($this->isEditMode ==
                true) ? '' : 'disabled') . '" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode ==
                true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . ($this->lang->line('printTemplate'.$this->methodId) == 'printTemplate'.$this->methodId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->methodId)) . '</button>';
    }

    $mainProcessBtnBar .= '<div class="ml-auto">';
    
    if ($this->processActionType == 'view') {
        
        $mainProcessBtnBar .= Form::button(
                array(
                    'class' => 'btn btn-sm btn-circle purple-plum ml5',
                    'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                    'onclick' => 'printProcess(this);'
                ), isset($this->isPrintView) ? $this->isPrintView : false
            ) . $reportPrint;
        
    } else {
        
        $previewReportTemplateCode = issetParam($this->methodRow['PREVIEW_REPORT_TEMPLATE_CODE']);
        
        $mainProcessBtnBar .= Form::button(
            array(
                'class' => 'btn btn-info btn-circle btn-sm mr-1 bp-btn-help',
                'value' => $this->lang->line('menu_system_guide'),
                'onclick' => "redirectHelpContent(this, '".$this->helpContentId."', '".$this->methodId."', 'meta_process');"
            ), ($this->helpContentId ? true : false)
            ) . Form::button(
            array(
                'class' => 'btn btn-sm btn-circle btn-success mr5',
                'value' => '<i class="fa fa-arrow-left"></i> ' . $this->lang->line('prev'),
                'onclick' => 'selectPrevNext(\'' . $this->dmMetaDataId . '\', \'' . $this->methodId . '\', this, null, null, \'' . 'transferProcessCriteria' . '\', \'' . $this->batchNumber . '\', -1, -1)'
            ), ($this->isShowPrevNext == '1') ? true : false
            ) . Form::button(
                    array(
                'class' => 'btn btn-sm btn-circle btn-success mr5',
                'value' => '<i class="fa fa-arrow-right"></i> ' . $this->lang->line('next'),
                'onclick' => 'selectPrevNext(\'' . $this->dmMetaDataId . '\', \'' . $this->methodId . '\', this, null, null, \'' . 'transferProcessCriteria' . '\', \'' . $this->batchNumber . '\', 1, 1)'
                    ), ($this->isShowPrevNext == '1') ? true : false
            ) . html_tag('button', array(
                    'type' => 'button',
                    'class' => 'btn btn-sm btn-circle btn-success mr5 bp-btn-preview',
                    'onclick' => 'previewReportTemplateFromBp(this, \'' . $this->methodId . '\', \'' . $this->uniqId . '\', \'' . $previewReportTemplateCode . '\');'
                ), 
                '<i class="far fa-file-search"></i> Preview', ($previewReportTemplateCode) ? true : false
            ) . html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success mr5 bp-btn-saveadd',
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', \'saveadd\');',
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="icon-checkmark-circle2"></i> ' . $this->runMode, (!$this->isEditMode) ? ($this->runMode ? true : false) : false
            ) . html_tag('button', array(
                    'type' => 'button', 
                    'class' => 'btn btn-sm btn-circle hide btn-success mr5 bp-btn-saveedit',
                    'onclick' => 'runAutoEditBusinessProcess(this, \''.$this->dmMetaDataId.'\', \''.$this->uniqId.'\', '.json_encode($this->isEditMode).');', 
                    'data-dm-id' => $this->dmMetaDataId 
                ), 
                '<i class="fa fa-pencil"></i> ' . $this->lang->line('save_btn_edit')
            ) . html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save ' . $this->runMode,
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ');',
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="icon-checkmark-circle2"></i> ' . $this->processActionBtn, (isset($this->isIgnoreActionBtn) ? false : true)
            ) . ((Config::getFromCache('IS_TEST_SERVER') && !isset($this->isIgnoreActionBtn)) ? html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle btn-success bpTestCaseSaveButton ml5 bp-btn-testcase',
                'onclick' => "saveBusinessProcessTestCase($(this), $(this).closest('form'));",
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="fa fa-save"></i> Тест кэйс хадгалах'
            ) : '') . Form::button(
                    array(
                'class' => 'btn btn-sm btn-circle purple-plum ml5 bp-btn-print',
                'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                'onclick' => 'printProcess(this);'
                    ), isset($this->isPrintView) ? $this->isPrintView : false
            ) . html_tag('button', array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-circle purple-plum ml5 bp-btn-saveprint',
                'onclick' => 'runBusinessProcess(this, \'' . $this->dmMetaDataId . '\', \'' . $this->uniqId . '\', ' . json_encode($this->isEditMode) . ', \'saveprint\');',
                'data-dm-id' => $this->dmMetaDataId
                    ), '<i class="fa fa-print"></i> ' . Lang::line('saveandprint'), $this->isSavePrint 
            ). $reportPrint;
    }
    
    $mainProcessBtnBar .= '</div>
        </div>
        <div class="hide mt10" id="boot-fileinput-error-wrap"></div>';

} else {
    $mainProcessBtnBar = '';
}
?>

<div class="xs-form is-bp-open-<?php echo issetParam($this->isBpOpenParam); ?> main-action-meta bp-banner-container <?php echo $this->processActionType == 'view' ? 'bp-view-process ' : ''; ?>bp-layout <?php echo $this->methodRow['SKIN'].' '.$this->methodRow['THEME']; ?>" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>" data-isgroup="1">
    
    <?php
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'enctype' => 'multipart/form-data'));
    $isCallNextFunction = '1';
    
    $this->postSelectedRowData = isset($this->selectedRowData) ? $this->selectedRowData : array();
    
    if (isset($this->selectedRowData) && isset($this->newStatusParams) && $this->newStatusParams) {
        $this->selectedRowsData = $this->selectedRowData;
        
        if (isset($this->selectedRowData[0])) {
            if (is_array($this->selectedRowData[0]))
                $this->selectedRowData = $this->selectedRowData[0];
            else
                $this->selectedRowsData = array($this->selectedRowsData);
        } else {
            if (isset($this->selectedRowsData['pfnextstatuscolumn'])) {
                unset($this->selectedRowsData['pfnextstatuscolumn']);
            }
            $this->selectedRowsData = array($this->selectedRowsData);
        }
        $arrayToStrParam = Arr::encode($this->selectedRowsData);
        
        if (isset($arrayToStrParam) && isset($this->newStatusParams) && $this->newStatusParams && $arrayToStrParam) {
            $isCallNextFunction = '0';
        }
    }
    
    if (isset($this->wfmStatusParams['result']) && isset($this->selectedRowData) 
        && isset($this->hasMainProcess) && $this->hasMainProcess 
        && isset($this->wfmStatusBtns) && $this->wfmStatusBtns 
        && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) {
            
        $singleMenuHtml = '<span class="workflowBtn-'. $this->methodId .' bp-wfmstatus-btns"></span>';
        $rowJson = htmlentities(json_encode($this->postSelectedRowData), ENT_QUOTES, 'UTF-8'); 
        
        foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
            $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['processname']) . '\', \'\', \'changeHardAssign\', \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined, undefined, \'' . $wfmstatusRow['wfmstatusprocessid'] . '\', \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined, undefined, undefined, \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\', \'' . $wfmstatusRow['usedescriptionwindow'] . '\');"';
            $singleMenuHtml .= '<button type="button" ' . $wfmMenuClick . ' data-rowdata="'. $rowJson .'" class="hidden btn btn-sm purple-plum btn-circle hidden-wfm-status-'. $wfmstatusRow['wfmstatusid']  .'" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"> '. $wfmstatusRow['processname'] .'</button> ';
        }

        echo $singleMenuHtml; 
        echo '<hr class="bp-top-hr"/>';
    } 
    
    echo $mainProcessBtnBar;
    echo $this->bpTab['tabStart'];
    
    echo '<div class="position-relative text-right">';
        echo Mduser::processToolsButton($this->methodId, (issetParam($this->methodRow['IS_TOOLS_BTN']) ? false : true), $this->runMode, $this->bpTab['tabStart'], true);
    echo '</div>';
    
    if (Mdwebservice::$isLogViewMode) {
        echo '<div class="text-right">
            <input type="checkbox" data-off-color="warning" data-on-color="info" data-on-text="Шинэ" data-size="small" data-off-text="Хуучин" class="form-check-input-switch-bplog_'.$this->methodId.' notuniform" checked>
        </div>';
    } 
       
    echo $this->layout; 
    ?>
    
    <div class="bp-header-param d-none">
        <?php echo $this->hiddenParam; ?>
    </div>
    <div class="bp-detail-param d-none">
        <?php echo $this->hiddenDetail; ?>
    </div>
    
    <div id="bprocessCoreParam">
        <?php 
        echo Form::hidden(array('name' => 'methodId', 'value' => $this->methodId)); 
        echo Form::hidden(array('name' => 'processSubType', 'value' => $this->processSubType));
        echo Form::hidden(array('name' => 'create', 'value' => ($this->processActionType == 'insert' ? '1' : '0')));
        echo Form::hidden(array('name' => 'responseType', 'value' => $this->responseType));
        echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => isset($this->newStatusParams) ? $this->newStatusParams : ''));
        echo Form::hidden(array('name' => 'wfmStringRowParams', 'value' => isset($arrayToStrParam) ? $arrayToStrParam : ''));
        echo Form::hidden(array('name' => 'openParams', 'id' => 'openParams', 'value' => $this->openParams));
        echo Form::hidden(array('name' => 'isSystemProcess', 'value' => $this->isSystemProcess));
        echo Form::hidden(array('name' => 'dmMetaDataId', 'value' => $this->dmMetaDataId));
        echo Form::hidden(array('name' => 'cyphertext', 'value' => $this->cyphertext));
        echo Form::hidden(array('name' => 'plainText', 'value' => $this->plainText));
        echo Form::hidden(array('id' => 'saveAddEventInput'));
        echo Form::hidden(array('name' => 'cacheId', 'value' => $this->cacheId));
        echo Form::hidden(array('name' => 'windowSessionId', 'value' => $this->uniqId));

        if (isset($this->realSourceIdAutoMap)) {

            echo Form::hidden(array('name' => 'realSourceIdAutoMap', 'value' => $this->realSourceIdAutoMap . '_' . $this->dmMetaDataId));

            if (isset($this->srcAutoMapPattern)) {
                echo Form::textArea(array('name' => 'srcAutoMapPattern', 'class' => 'd-none', 'value' => $this->srcAutoMapPattern));
            }
        }
        
        if (isset($this->fillParamData['_taskflowinfo']) && $this->fillParamData['_taskflowinfo']) {
            echo Form::hidden(array('name' => 'taskFlowInfo', 'value' => Arr::encode($this->fillParamData['_taskflowinfo'])));
        }
        ?>    
    </div>
    
    <div id="responseMethod"></div>
    
    <?php 
    echo $this->bpTab['tabEnd'];
    echo Mdlanguage::translateBtnByMetaId($this->methodId);
    echo Form::close(); 
    ?>    
</div>

<script type="text/javascript">
$(function() {
    bp_window_<?php echo $this->methodId; ?>.find('[data-section-code]').each(function() {
        var $this = $(this), $childs = $this.find('> *'), childrenLength = $childs.length;
        if (childrenLength == 0) {
            $this.closest('.bl-section').hide().attr('data-layout-section-hide', 1);
        } 
    }); 
    bp_window_<?php echo $this->methodId; ?>.find('.process-layout-sidebar').each(function() {
        var $thisSidebar = $(this), 
            totalSection = $thisSidebar.find('[data-bl-col]').length, 
            totalHideSection = $thisSidebar.find('[data-layout-section-hide]').length;
        
        if (totalSection == totalHideSection) {
            $thisSidebar.hide();
        }
    });
});
</script>

<?php 
if ($this->processActionType == 'view') {
    require getBasePath() . 'middleware/views/webservice/sub/script/view.php'; 
} else {
    require getBasePath() . 'middleware/views/webservice/sub/script/main.php'; 
}
?>

<script type="text/javascript">
$(function() {
    
    setTimeout(function() {
        bp_window_<?php echo $this->methodId; ?>.find('[data-section-code]').each(function() {
            var $this = $(this), $childs = $this.find('> *'), childrenLength = $childs.length;
            if (childrenLength) {
                var noneCount = $childs.filter(function() { return $(this).css('display') == 'none'; }).length;
                if (childrenLength == noneCount) {
                    $this.closest('.bl-section').hide();
                }
            }
        }); 
        
        function layoutAdjustBoxHeights() {
            var $layoutSidebars = bp_window_<?php echo $this->methodId; ?>.find('.process-layout-sidebar:visible, .process-layout-main-part:visible');
        
            if ($layoutSidebars.length > 1) {

                var maxHeight = 0;

                $layoutSidebars.each(function() {
                    var $this = $(this).find('> .row');
                    if (maxHeight < $this.height()) { 
                        maxHeight = $this.height(); 
                    }
                });

                if (maxHeight > 0) {
                    $layoutSidebars.each(function() {
                        var $this = $(this).find('> .row');
                        var $lastVisibleSection = $this.find('.bl-section:visible:last');
                        if ($lastVisibleSection.length) {
                            var thisHeight = $this.height();
                            $lastVisibleSection.css('min-height', $lastVisibleSection.height() + 17 + (maxHeight - thisHeight));
                        }
                    });
                }
            }
        }
        
        layoutAdjustBoxHeights();
    
    }, 0);
});
</script>

<style type="text/css">
.bp-layout .col-form {
    padding-top: 5px;
    padding-bottom: 13px;
}
.bp-layout .card {
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    border-radius: 6px;
}
.bp-layout .card {
    margin-bottom: 0;
}
/*.bp-layout .form-group:last-child */
.bp-layout .col-form-label {
    text-align: right;
}
.bp-layout .form-group-percent {
    margin-right: 0;
}
.bp-layout .form-group-percent > .col-form-label {
    padding-right: 10px !important;
}
.bp-layout .col-form > .card > .card-body > .tabbable-line > .nav-tabs > li > a {
    padding-top: 0 !important;
    line-height: 14px;
}
.bp-layout .card > .card-body > .row > .col-md-12 > .table-toolbar {
    margin-top: 0;
}
.bp-layout .card > .card-header:not(.invisible) {
    margin-top: -14px;
    margin-bottom: 13px;
    border-bottom: 1px #ddd solid;
    padding-bottom: 5px;
    height: auto;
}
.bp-layout .card > .card-header > .card-title {
    color: #333;
}
.bp-layout .card > .card-body > div[data-section-path]:last-of-type {
    margin-bottom: 0!important;
}
.bp-layout .card.bg-warning .col-form-label, 
.bp-layout .card.bg-dark .col-form-label, 
.bp-layout .card.bg-primary .col-form-label, 
.bp-layout .card.bg-secondary .col-form-label, 
.bp-layout .card.bg-danger .col-form-label, 
.bp-layout .card.bg-success .col-form-label, 
.bp-layout .card.bg-info .col-form-label, 
.bp-layout .card[class*=bg-purple] .col-form-label, 
.bp-layout .card[class*=bg-grey] .col-form-label, 

.bp-layout .card.bg-warning .card-title, 
.bp-layout .card.bg-dark .card-title, 
.bp-layout .card.bg-primary .card-title, 
.bp-layout .card.bg-secondary .card-title, 
.bp-layout .card.bg-danger .card-title, 
.bp-layout .card.bg-success .card-title, 
.bp-layout .card.bg-info .card-title, 
.bp-layout .card[class*=bg-purple] .card-title, 
.bp-layout .card[class*=bg-grey] .card-title {
    color: #fff;
}
.bp-layout .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .card-header {
    border-bottom-color: rgba(255,255,255,.4);
}
.bp-layout .card[class*=bg-] .nav-tabs .nav-item.show .nav-link, 
.bp-layout .card[class*=bg-] .nav-tabs .nav-link.active {
    background-color: transparent;
}
.bp-layout .card[class*=bg-] .tabbable-line > .tab-content {
    background-color: transparent;
}
.bp-layout .bl-labelposition-top .col-form-label, 
.bp-layout .bl-labelposition-top .col-form-control {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
}
.bp-layout .bl-labelposition-top .col-form-label {
    text-align: left !important;
    margin-bottom: 5px;
}
.bp-layout .card.title-font-size-small .card-header .card-title {
    font-size: 13px;
}
.bp-layout .bl-section-no-padding {
    padding: 0!important;
}
.bp-layout .bl-section-no-padding > .card-body {
    padding: 5px!important;
}
<?php echo $this->gridHeaderClass; ?>
</style>