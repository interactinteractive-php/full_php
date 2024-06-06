<?php
$processsMainContentClassBegin = $processsMainContentClassEnd = $processsDialogContentClassBegin = $processsDialogContentClassEnd = $dialogProcessLeftBanner = $mainProcessLeftBanner = '';
$isBanner = false;

if ($this->isDialog == false && Input::isEmpty('workSpaceId') == true) {
    $mainProcessBtnBar = '<div class="meta-toolbar">';
    
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', ($this->dmMetaDataId ? true : false)
            );
            if ($this->dmMetaDataId && Input::postCheck('isBackBtnIgnore') == false) {
                $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
            }
        }
    } else {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-sm btn-secondary card-subject-btn-border mr10 bp-btn-back',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }
    
    $reportPrint = '';
    if ($this->isPrint) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 '.(($this->isEditMode == true) ? '' : 'disabled').'" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . $this->lang->line('printTemplate') . '</button>';
    }
    $mainProcessBtnBar .= '<div class="ml-auto">
            ' . Form::button(
                array(
                    'class' => 'btn btn-sm btn-circle purple-plum ml5',
                    'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                    'onclick' => 'printProcess(this);'
                ), isset($this->isPrintView) ? $this->isPrintView : false
            ) . $reportPrint .
            '
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
    <div class="clearfix w-100"></div>';

    if ($mainProcessLeftBanner != '') {
        $processsMainContentClassBegin = '<div class="processs-main-content">';
        $processsMainContentClassEnd = '</div>';
        $isBanner = true;
    }
    
} else {
    $mainProcessBtnBar = '';

    $mainProcessLeftBanner = '';
    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
        $isBanner = true;
    }
}
?>
<div class="xs-form bp-banner-container bp-view-process" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <?php 
    echo Form::create(array('id' => 'wsForm', 'method' => 'post', 'class' => ($isBanner ? 'bp-banner-content' : '')));
    
    $isCallNextFunction = '1';
    
    if (isset($this->selectedRowData) && isset($this->newStatusParams) && $this->newStatusParams) {
        $this->selectedRowsData = $this->selectedRowData;
                
        if (isset($this->selectedRowData[0])) {
            if (is_array($this->selectedRowData[0]))
                $this->selectedRowData = $this->selectedRowData[0];
            else
                $this->selectedRowsData = array($this->selectedRowsData);
        } else {
            $this->selectedRowsData = array($this->selectedRowsData);
        }
        $arrayToStrParam = Arr::encode($this->selectedRowsData);
        if (isset($arrayToStrParam) && isset($this->newStatusParams) && $this->newStatusParams && $arrayToStrParam) {
            $isCallNextFunction = '0';
        }
    }
    
    if (isset($this->wfmStatusParams['result']) && isset($this->selectedRowData) && isset($this->hasMainProcess) && $this->hasMainProcess) {
        
        $wfmStatusBtns = '';
        
        if (isset($this->wfmStatusBtns) && $this->wfmStatusBtns && isset($this->wfmStatusBtns['result']) && $this->wfmStatusBtns['result']) {
            foreach ($this->wfmStatusBtns['result'] as $wfmstatusRow) {
                $wfmMenuClick = 'onclick="changeWfmStatusId(this, \'' . (isset($wfmstatusRow['wfmstatusid']) ? $wfmstatusRow['wfmstatusid'] : '') . '\', \'' . $this->dmMetaDataId . '\', \'' . $this->refStructureId . '\', \'' . trim(issetParam($this->selectedRowData['wfmstatuscolor'])) . '\', \'' . issetParam($wfmstatusRow['wfmstatusname']) . '\', \'\', \'changeHardAssign\', \'\', \''. $this->uniqId .'\', \''. $this->methodId .'\', undefined, undefined, \'' . $wfmstatusRow['wfmstatusprocessid'] . '\', \'' . $wfmstatusRow['wfmisdescrequired'] . '\', undefined, undefined, undefined, \'' . $isCallNextFunction .'\', \'' . $wfmstatusRow['isformnotsubmit'] . '\', \'' . $wfmstatusRow['usedescriptionwindow'] . '\');"';
                $wfmStatusBtns .= '<button type="button" ' . $wfmMenuClick . ' class="btn btn-sm purple-plum btn-circle" style="background-color:'. $wfmstatusRow['wfmstatuscolor'] .'"><i class="fa fa-location-arrow"></i> '. $wfmstatusRow['wfmstatusname'] .'</button> ';
            }
        }
        
        $wfmPanelViewer = (new Mdworkflow())->wfmPanelViewer($this->refStructureId, $this->sourceId, $this->selectedRowData['wfmstatusid']);
        
        $statusStep = $wfmPanelViewer['statusStep'];
        $wfmAssignmentUsers = $wfmPanelViewer['assignmentUsers'];
        
        echo '<div class="row bp-file-view-header"><div class="col-md-9">'.$statusStep.'</div><div class="col-md-3 text-right pt8">'.$wfmStatusBtns.'</div></div>';
    }
    
    //echo $mainProcessBtnBar;

    echo $this->bpTab['tabStart'];

    echo $dialogProcessLeftBanner;
    echo $processsDialogContentClassBegin;
    ?>
    <div class="row">
        <div class="col-md-12 center-sidebar">  
            <?php 
            echo $mainProcessLeftBanner;
            echo $processsMainContentClassBegin; 
            ?>
            <div class="bp-template-wrap mt10">
                <div class="bp-template-table">
                    <div class="bp-template-table-row">
                        <div class="bp-template-table-cell-left">
                            
                            <?php
                            if ($this->fileExtension == 'pdf') {
                                echo '<iframe src="api/pdf/web/viewer.html?file='.URL.$this->filePath.'" style="border: 0; width: 100%; height: 600px"></iframe>';
                            } elseif ($this->fileExtension == 'doc' || $this->fileExtension == 'docx') {
                                echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$this->filePath.'" style="border: 0; width: 100%; height: 600px"></iframe>';
                            } elseif ($this->fileExtension == 'xls' || $this->fileExtension == 'xlsx') {
                                echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL.$this->filePath.'" style="border: 0; width: 100%; height: 600px"></iframe>';
                            }
                            ?>
                            
                        </div>
                        <div class="bp-template-table-cell-right pl10" style="background-color: transparent">
                            <?php
                            if (isset($wfmAssignmentUsers)) {
                                echo $wfmAssignmentUsers;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>    
            
            <div id="bprocessCoreParam">
                <?php
                echo Form::hidden(array('name' => 'methodId', 'value' => $this->methodId)); 
                echo Form::hidden(array('name' => 'processSubType', 'value' => $this->processSubType));
                echo Form::hidden(array('name' => 'create', 'value' => ($this->processActionType == 'insert' ? '1' : '0')));
                echo Form::hidden(array('name' => 'responseType', 'value' => $this->responseType));
                echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => isset($this->newStatusParams) ? $this->newStatusParams : '')); 
                echo Form::hidden(array('name' => 'wfmStringRowParams', 'value' => isset($arrayToStrParam) ? $arrayToStrParam : '')); 
                echo Form::hidden(array('id' => 'openParams', 'value' => $this->openParams));
                echo Form::hidden(array('name' => 'isSystemProcess', 'value' => $this->isSystemProcess));
                echo Form::hidden(array('name' => 'dmMetaDataId', 'value' => $this->dmMetaDataId));
                echo Form::hidden(array('name' => 'cyphertext', 'value' => $this->cyphertext));
                echo Form::hidden(array('name' => 'plainText', 'value' => $this->plainText));
                echo Form::hidden(array('id' => 'saveAddEventInput'));
                echo Form::hidden(array('name' => 'windowSessionId', 'value' => $this->uniqId));
                ?>    
            </div>
            <div id="responseMethod"></div>      
            <?php echo $processsMainContentClassEnd; ?>     
        </div>    
    </div>
    <?php
    echo $processsDialogContentClassEnd;
    echo $this->bpTab['tabEnd'];
    ?>
    <div class="clearfix w-100"></div>
    
    <?php echo Form::close(); ?>        
</div>

<script type="text/javascript">
var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");    
$(function(){
    setTimeout(function(){
        bp_window_<?php echo $this->methodId; ?>.find('iframe').css('height', ($(window).height() - bp_window_<?php echo $this->methodId; ?>.offset().top - 60)+'px');
    }, 1);
});    
function processBeforeSave_<?php echo $this->methodId; ?>(thisButton) {
    return true;
}
function processAfterSave_<?php echo $this->methodId; ?>(thisButton, responseStatus) {
    return true;
}
</script>
