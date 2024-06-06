<div class="kpi-form-paper-portrait">
    <div class="kpi-form-paper-portrait-child">    
        <div class="d-flex justify-content-between">
            <?php 
                $logoImage = 'assets/custom/img/new_veritech_black_logo.png';

                if (isset($this->logoImage) && file_exists($this->logoImage)) {
                    $logoImage = $this->logoImage;
                }
            ?>            
            <img style="height: 24px" src="<?php echo $logoImage; ?>"/>
        </div>
        <div class="d-flex justify-content-center">
            <p class="mb-0 mt-1 ml-2" style="font-size: 20px;font-weight: bold;"><?php echo $this->title ?></p>
        </div>
        <div class="row mv-checklist-render-parent mv-checklist2-render-parent" id="mv-checklist-render-parent-<?php echo $this->uniqId; ?>">

            <?php 
            echo Form::hidden(array('data-path' => 'endToEndLogHdrId', 'value' => issetParam($this->endToEndLogData['headerLogId']))); 
            echo Form::hidden(array('data-path' => 'listIndicatorId', 'value' => $this->listIndicatorId)); 
            echo Form::hidden(array('data-path' => 'methodTypeCode', 'value' => $this->methodTypeCode)); 
            echo Form::hidden(array('data-path' => 'headerRecordId', 'value' => $this->recordId)); 
            echo Form::hidden(array('data-path' => 'headerParams', 'value' => htmlentities(str_replace('&quot;', '\\&quot;', json_encode($this->selectedRow, JSON_UNESCAPED_UNICODE)), ENT_QUOTES, 'UTF-8'))); 
            
            $relationTabList = Arr::groupByArrayByNullKey($this->relationList, 'TAB_NAME'); 
            ?>

            <div class="bp-tabs tabbable-line mv-main-tabs mv-checklist-tab w-100">
                <ul class="nav nav-tabs">
                    <?php
                    if (!$this->isIgnoreHeaderProcess) {
                    ?>
                    <li class="nav-item">
                        <a href="#structabcustom_<?php echo $this->uniqId; ?>" class="nav-link active" data-toggle="tab" aria-expanded="false">
                            <?php echo checkDefaultVal($this->shortDescription, 'Хүсэлт') ?>                
                        </a>
                    </li>                        
                    <?php
                    }
                    $tabId = 1;
                    foreach ($relationTabList as $groupName => $groupRow) {
                        if ($groupName != 'яяяrow') {
                            echo '<li class="nav-item">';
                                echo '<a href="#maintabcustom_'.$this->uniqId.'_'.$tabId.'" class="nav-link '.($this->isIgnoreHeaderProcess ? ($tabId == 1 ? 'active' : '') : '').' mv-checklist-tab-link" data-toggle="tab" aria-expanded="false">';
                                    echo $groupName;
                                echo '</a>';
                            echo '</li>';
                            $tabId ++;
                        }
                    }                    
                    ?>
                </ul>        
                <div class="tab-content" style="padding-top: 0px;padding-bottom: 0px;"> 
                    <?php
                    if (!$this->isIgnoreHeaderProcess) {
                    ?>
                    <div class="tab-pane active" id="structabcustom_<?php echo $this->uniqId; ?>">
                        <div class="mv-checklist-main-render" style="width: 100%; padding: 10px 50px;">
                            <form method="post" enctype="multipart/form-data">
                                <div class="meta-toolbar is-bp-open-">
                                    <div class="main-process-text">
                                        <div><?php echo $this->title; ?></div>                                        
                                    </div>
                                    <div class="ml-auto">
                                        <button type="button" class="hidden btn btn-sm btn-circle btn-success bp-btn-save" onclick="saveMvCheckListCheck(this);">
                                            <i class="icon-checkmark-circle2"></i> Шалгах
                                        </button>
                                        <button type="button" class="ml-1 btn btn-sm btn-circle btn-success bp-btn-save" onclick="saveKpiIndicatorHeaderForm(this);">
                                            <i class="icon-checkmark-circle2"></i> <?php echo $this->lang->line('save_btn'); ?>
                                        </button>
                                    </div>
                                </div>
                                <?php echo $this->headerProcess; ?>
                            </form>
                        </div>                
                    </div>
                    <?php
                    }
                    $tabId = 1;
                    foreach ($relationTabList as $tabName => $tabRow) {
                        if ($tabName != 'яяяrow') { 
                    ?>                    
                        <div class="tab-pane <?php echo ($this->isIgnoreHeaderProcess ? ($tabId == 1 ? 'active' : '') : ''); ?> p-1" id="maintabcustom_<?php echo $this->uniqId; ?>_<?php echo $tabId; ?>" style="padding-bottom: 0 !important;padding-top: 0 !important;padding-right: 0 !important;">
                            <div class="d-flex">
                                <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md pr-2" style="width:100%">
                                    <div class="sidebar-content">

                                        <div class="card">
                                            <div class="card-body mv-checklist-menu">
                                                <ul class="nav mv_card_status_widget_main nav-sidebar" style="margin-top:6px;" data-nav-type="accordion">
                                                    <?php
                                                    $n = 0;
                                                    $n2 = 0;
                                                    $relationList = Arr::groupByArrayByNullKey($tabRow['rows'], 'GROUP_NAME');    

                                                    foreach ($relationList as $groupName => $groupRow) {

                                                        $item = '';
                                                        $rows = $groupRow['rows'];
                                                        $relationWidget = Arr::groupByArrayByNullKey($rows, 'WIDGET_CODE');

                                                        if (isset($relationWidget['relation_card_widget'])) {
                                                            foreach ($rows as $row) {

                                                                if ($row['WIDGET_CODE'] != 'relation_card_widget') continue;

                                                                $kpiTypeId = $row['KPI_TYPE_ID'];
                                                                $mapLabelName = $row['MAP_LABEL_NAME'];
                                                                $class = $itemClass = '';

                                                                if ($mapLabelName != '') {
                                                                    $name = $this->lang->line($mapLabelName);
                                                                } else {
                                                                    if ($kpiTypeId == 2008) {
                                                                        $name = $row['STRUCTURE_NAME'];
                                                                    } elseif ($row['META_DATA_ID']) {
                                                                        $name = $this->lang->line($row['META_DATA_NAME']);
                                                                    } else {
                                                                        $name = $row['NAME'];
                                                                    }
                                                                }

                                                                if ($n == 0) {
                                                                    $class = ' ';
                                                                }

                                                                if (!$this->selectedRow && !$this->isIgnoreHeaderProcess) {
                                                                    $class = ' disabled';
                                                                }

                                                                $rowJson = json_encode(array(
                                                                    'mapId'          => $row['MAP_ID'], 
                                                                    'indicatorId'    => $row['ID'], 
                                                                    'strIndicatorId' => $row['STRUCTURE_INDICATOR_ID'],
                                                                    'kpiTypeId'      => $row['KPI_TYPE_ID'], 
                                                                    'metaDataId'     => $row['META_DATA_ID'], 
                                                                    'metaTypeId'     => $row['META_TYPE_ID'], 
                                                                ));
                                                                $rowJson = htmlentities($rowJson, ENT_QUOTES, 'UTF-8');

                                                                $hiddenParams = json_encode(array(
                                                                    'srcMapId'       => $row['MAP_ID'],
                                                                    'srcIndicatorId' => $this->strIndicatorId, 
                                                                    'srcRecordId'    => $this->recordId, 
                                                                    'trgIndicatorId' => $row['ID']
                                                                ));
                                                                $hiddenParams = htmlentities($hiddenParams, ENT_QUOTES, 'UTF-8');

                                                                $iconName = 'far fa-square';
                                                                $wfmStatus = '<div style="padding: 2px 10px 2px"></div>';

                                                                if (isset($this->endToEndLogData['detailData'][$row['ID']]) && $this->endToEndLogData['detailData'][$row['ID']]['WFM_STATUS_NAME']) {
                                                                    $wfmStatus = '<div data-statusid="'.$this->endToEndLogData['detailData'][$row['ID']]['WFM_STATUS_ID'].'" style="padding: 2px 10px 2px 10px;background-color:'.$this->endToEndLogData['detailData'][$row['ID']]['WFM_STATUS_COLOR'].'">'.$this->endToEndLogData['detailData'][$row['ID']]['WFM_STATUS_NAME'].'</div>';
                                                                }

                                                                if ($row['CRITERIA'] != '') {
                                                                    $itemClass = ' mv-checklist-criteria d-none';
                                                                }

                                                                $item .= '<li class="nav-item'.$itemClass.'" data-stepid="'.$row['ID'].'">
                                                                    <a style="width: 350px;" href="javascript:;" class="mv_card_status_widget mv_checklist_02_sub nav-link'.$class.'" data-indicatorid="'.$this->indicatorId.'" data-uniqid="'.$this->uniqId.'" data-json="'.$rowJson.'" data-hidden-params="'.$hiddenParams.'" data-iscomment="'.$row['IS_COMMENT'].'" data-stepid="'.$row['ID'].'">
                                                                        <div class="card" style="width:100%">
                                                                            <div class="card-body p-2 d-flex">
                                                                                <div class="p-1">
                                                                                    <div class="w-100 pull-left position-1 ">
                                                                                        <span class="badge badge-warning badge-icon" style="padding: 15px;border: 1px solid #e6e6e6;"><i style="color:#54565e !important" class="fa '.($row['ICON'] ? $row['ICON'] : 'fa-suitcase').'"></i></span>
                                                                                    </div>
                                                                                    <p class="w-100 pull-left text-left text-one-line position-2 mt8">
                                                                                        '.$name.'
                                                                                    </p>
                                                                                    <p class="w-100 pull-left text-left text-three-line position-3 mb5">
                                                                                        '.issetParam($row['DESCRIPTION']).'
                                                                                    </p>
                                                                                    <div class="pull-left text-left text-three-line position-4">                                                                                    
                                                                                        '.$wfmStatus.'
                                                                                    </div>
                                                                                </div>
                                                                                <div class="right-sidewidget pull-right ml-auto">
                                                                                    <button href="javascript:;" class="btn btn-outline h-100"><i class="icon-arrow-right15"></i></button>
                                                                                </div>                                                                            
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </li>';

                                                                $n ++;
                                                            }

                                                            if ($groupName != 'яяяrow') {
                                                                echo '<li class="nav-item nav-item-submenu '.(!$n2 ? 'nav-group-sub-mv-opened' : '').'">';
                                                                    echo '<a href="javascript:;" class="nav-link mv_checklist_02_groupname">'.$this->lang->line($groupName).'</a>';
                                                                    echo '<ul class="nav nav-group-sub justify-content-center px-5" style="gap: 20px;">';
                                                            }
                                                            $n2++;

                                                            echo $item;
                                                        } else {
                                                            foreach ($rows as $row) {

                                                                $kpiTypeId = $row['KPI_TYPE_ID'];
                                                                $mapLabelName = $row['MAP_LABEL_NAME'];
                                                                $class = $itemClass = '';

                                                                if ($mapLabelName != '') {
                                                                    $name = $this->lang->line($mapLabelName);
                                                                } else {
                                                                    if ($kpiTypeId == 2008) {
                                                                        $name = $row['STRUCTURE_NAME'];
                                                                    } elseif ($row['META_DATA_ID']) {
                                                                        $name = $this->lang->line($row['META_DATA_NAME']);
                                                                    } else {
                                                                        $name = $row['NAME'];
                                                                    }
                                                                }

                                                                if ($n == 0) {
                                                                    $class = ' active';
                                                                }

                                                                if (!$this->selectedRow && !$this->isIgnoreHeaderProcess) {
                                                                    $class = ' disabled';
                                                                }

                                                                $rowJson = json_encode(array(
                                                                    'mapId'          => $row['MAP_ID'], 
                                                                    'indicatorId'    => $row['ID'], 
                                                                    'strIndicatorId' => $row['STRUCTURE_INDICATOR_ID'],
                                                                    'kpiTypeId'      => $row['KPI_TYPE_ID'], 
                                                                    'metaDataId'     => $row['META_DATA_ID'], 
                                                                    'metaTypeId'     => $row['META_TYPE_ID'], 
                                                                ));
                                                                $rowJson = htmlentities($rowJson, ENT_QUOTES, 'UTF-8');

                                                                $hiddenParams = json_encode(array(
                                                                    'srcMapId'       => $row['MAP_ID'],
                                                                    'srcIndicatorId' => $this->strIndicatorId, 
                                                                    'srcRecordId'    => $this->recordId, 
                                                                    'trgIndicatorId' => $row['ID']
                                                                ));
                                                                $hiddenParams = htmlentities($hiddenParams, ENT_QUOTES, 'UTF-8');

                                                                $iconName = 'far fa-square';

                                                                if (isset($this->endToEndLogData['detailData'][$row['ID']]) && $this->endToEndLogData['detailData'][$row['ID']]['STATUS_CODE'] == 'done') {
                                                                    $iconName = 'fas fa-check-square';
                                                                }

                                                                if ($row['CRITERIA'] != '') {
                                                                    $itemClass = ' mv-checklist-criteria d-none';
                                                                }

                                                                $item .= '<li class="nav-item'.$itemClass.'" data-stepid="'.$row['ID'].'">
                                                                    <a href="javascript:;" class="mv_checklist_02_sub nav-link'.$class.'" data-indicatorid="'.$this->indicatorId.'" data-uniqid="'.$this->uniqId.'" data-json="'.$rowJson.'" data-hidden-params="'.$hiddenParams.'" data-iscomment="'.$row['IS_COMMENT'].'" data-stepid="'.$row['ID'].'">
                                                                        <i class="'.$iconName.'"></i> <span class="pt1">'.$name.'</span>
                                                                    </a>
                                                                </li>';

                                                                $n ++;
                                                            }

                                                            if ($groupName != 'яяяrow') {
                                                                echo '<li class="nav-item nav-item-submenu '.(!$n2 ? 'nav-group-sub-mv-opened' : '').'">';
                                                                    echo '<a href="javascript:;" class="nav-link mv_checklist_02_groupname">'.$this->lang->line($groupName).'</a>';
                                                                    echo '<ul class="nav nav-group-sub pl20 flex-column">';
                                                            }
                                                            $n2++;

                                                            echo $item;                                                                
                                                        }

                                                        if ($groupName != 'яяяrow') {
                                                                echo '</ul>';
                                                            echo '</li>';
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="w-100 prnt-content-wrapper">            
                                </div>                
                            </div>                
                        </div>
                    <?php 
                        $tabId ++;
                        }
                    }                    
                    ?>                    
                </div>
            </div>               
        </div>
    </div>
</div>
<?php 
    $bgImage = 'middleware/assets/img/process/background/khanbank_mv_bg_image.jpg';

    if (isset($this->bgImage) && file_exists($this->bgImage)) {
        $bgImage = $this->bgImage;
    }
?>

<style type="text/css">
/*.mv-checklist2-render-parent .mv-checklist-render {
    max-width: 900px;
}*/    
.mv-checklist2-render-parent .mv-checklist-render-comment .media-body .p-2.pb3 {
    background-color: #eee !important;
}   
.mv-checklist2-render-parent .mv-checklist-render-comment .media-list .border-gray {
    border-color: transparent !important;
}   
.mv-checklist2-render-parent .mv-checklist-render-comment .dialog-chat .avatar {
    display: none;
}   
.nav-link.mv_checklist_02_groupname {
    font-size: 13px;
    color: #333 !important;
    font-weight: bold !important;
    padding-top: 5px;
    padding-bottom: 5px;
    text-transform: none !important;
}    
.nav-link.mv_checklist_02_sub {
    padding-top: 2px;
    padding-bottom: 2px;
    font-size: 12px;
    /*font-weight: bold;*/
}    
.nav-link.mv_checklist_02_sub i {
    color: #1B84FF !important;
    margin-top: 2px;
    font-size: 18px;    
    margin-right: 2px;
}    
.mv-checklist2-render-parent .jeasyuiTheme3 {
    padding-bottom: 15px;
}
/*.mv-checklist2-render-parent .mv-checklist-render .input-group-btn>.btn {
    height: 20px;
    min-height: 20px;
}*/
/*.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row {
    position: absolute;
    margin-top: -36px;
    margin-left: 536px;
}*/
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-icon,
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:first-child {
    display: none;
}
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:last-child button {
    color: #252F4A;
    background-color: #eee;
    padding: 0px 5px 0px 5px !important;
}
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:last-child button i:before {
    content: "Сонгох";
    font-family: Arial, Helvetica, sans-serif;
}
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-htable .datagrid-header-row:not(.datagrid-filter-row) {
    height: 71px !important;
}
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-header .datagrid-cell span, 
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-view .datagrid-cell-group {
    font-size: 12px;
    font-weight: 700;
    color: #99A1B7;
}
.mv-checklist2-render-parent .datagrid-header td, 
.mv-checklist2-render-parent .datagrid-body td, 
.mv-checklist2-render-parent .datagrid-footer td {
    border-color: transparent;
}
.mv-checklist2-render-parent .panel-header-eui, 
.mv-checklist2-render-parent .panel-body-eui {
    border-color: transparent;
}
.mv-checklist2-render-parent .datagrid-pager {
    border-color: transparent;
}
/*.mv-checklist2-render-parent .datagrid .datagrid-pager {
    display: none;
}*/
.mv-checklist2-render-parent .datagrid-row-alt:not(.datagrid-row-over) {
    background: transparent;
}
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-header td {
    background: #eee !important;
    border-style: solid;
    border-color: transparent;
}
.mv-checklist2-render-parent .mv-checklist-render .meta-toolbar {
    border-bottom: none;
    margin-top: 0;
}
.mv-checklist2-render-parent .mv-checklist-render .meta-toolbar .main-process-text {
    font-size: 12px;
} 
.mv-checklist2-render-parent .mv-checklist-main-render .meta-toolbar {
    border-bottom: 0;
    padding-bottom: 0;
    margin-bottom: 0;
}
.mv-checklist2-render-parent .mv-checklist-main-render .meta-toolbar .main-process-text {
    display: none;
}    
.mv-checklist2-render-parent .bp-add-one-row-num {
    display: none;
}    
.mv-checklist2-render-parent .mv-checklist-render .mv-add-row-actions {
    margin-top: 0 !important;
}    
.mv-checklist2-render-parent .nav-group-sub-mv-opened .nav-group-sub {
    display: block;
}
.mv-checklist2-render-parent .nav-sidebar .nav-item:not(.nav-item-header):last-child {
    padding-bottom: 0 !important;
}
.mv-checklist2-render-parent .nav-item-submenu.nav-group-sub-mv-opened>.nav-link:after {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
}
.mv-checklist2-render-parent .nav-group-sub .nav-link {
    padding-left: 0;
}
.mv-checklist2-render-parent .mv-checklist-render button.bp-add-one-row {
    background-color: #eee;
    color: #252F4A;
    font-size: 12px;
    padding: 0px 5px 0px 5px;
}    
.mv-checklist2-render-parent .main-process-text-description {
    color: #99A1B7;
    text-transform: none;
    font-weight: normal;
    font-size: 11px;    
}    
.mv-checklist2-render-parent .mv-checklist-render button.bp-add-one-row:hover,
.mv-checklist2-render-parent .mv-checklist-render .bp-add-ac-row button:hover {
    background-color: #1B84FF !important;
    color: #fff !important;
}    
.nav-item-submenu>.nav-link.mv_checklist_02_groupname:after {
    margin-top: -6px;
    content:'';
}
.mv-checklist2-render-parent {
    margin: -20px -15px 0px -15px!important;
}
.mv-checklist2-render-parent button.bp-btn-save i {
    display: none;
}
.mv-checklist2-render-parent button.bp-btn-save, 
.mv-checklist2-render-parent button.bp-btn-saveadd {
    color: #fff!important;
    border-color: #1B84FF!important;
    padding-left: 18px!important;
    padding-right: 18px!important;
    background-color: #1B84FF!important;
    padding-bottom: 2px !important;
    font-size: 12px!important;
}
.mv-checklist2-render-parent button.bp-btn-save:hover, 
.mv-checklist2-render-parent button.bp-btn-saveadd:hover {
    background-color: #1B84FF!important;
}
.mv-checklist2-render-parent .mv-rows-title:not(.mv-rows-title-label) {
    display: none;
}
.mv-checklist2-render-parent .mv-rows-title-label {
    color: rgba(51,51,51,.8);
}
.mv-checklist2-render-parent > .sidebar {
    width: 16.875rem;
    padding: 0;
    background-color: rgb(243, 244, 246);
}
.mv-checklist2-render-parent > .sidebar .sidebar-content {
    padding: 15px 10px;
}
.mv-checklist2-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link {
    text-transform: none;
}
.mv-checklist2-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link.active {
    background-color: transparent;
}
.mv-checklist2-render-parent .mv-checklist-title {
    color: #3C3C3C;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 700;
}
.mv-checklist2-render-parent .mv-checklist-description {
    color: #67748E;
    margin-top: 10px;
}
.mv-checklist2-render-parent > .sidebar > .sidebar-content > .card > .card-body .step {
    background: #A0A0A0;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist2-render-parent > .sidebar > .sidebar-content > .card > .card-body .step.active {
    background: #468CE2;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist2-render-parent .mv-checklist-menu {
    /*height: 70vh;*/
    padding: 0;
/*    margin-left: -10px;
    margin-right: -10px;*/
    overflow: auto;
}
.mv-checklist2-render-parent .mv-checklist-menu li {
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link {
    display: flex;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    padding: 10px 22px 10px 10px;
    overflow: hidden;
    font-size: 12px;
    text-transform: none;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link:hover {
    background-color: #E8EBF0;
    color: #468CE2;
}
.mv-checklist2-render-parent .sidebar-light {
    border-right: none;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link i {
    font-size: 18px;
    margin-right: 10px;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link span {
    font-size: 12px;
    font-weight: 600;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link.active {
    background-color: #E8EBF0;
    color: #468CE2;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child {
    padding-right: 15px !important;
    padding-bottom: 0 !important;
}
.kpi-form-paper-portrait {
    background-image: url("<?php echo $bgImage; ?>"); 
    background-repeat: no-repeat; 
    background-position: top center;
    background-attachment: fixed;
    background-color: #ededed;
    margin: -10px -15px;
    padding-top: 11px;
    padding-bottom: 20px;
    background-size: cover;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child {
    position: relative;
    width: <?php echo $this->windowWidth ? $this->windowWidth: '1200px'; ?>;
    min-height: calc(100vh - 126px);
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;
    background: #FFF;
    padding: 20px;
    box-shadow: 0px 2px 6px 0 rgba(0,0,0,.5);
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-title {
    margin-bottom: -14px;
    /*margin-top: 10px;*/
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-logo {
    position: absolute;
    max-width: 150px;
    max-height: 70px;
    top: 20px;
    left: 20px;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-header-text {
    position: absolute;
    max-width: 180px;
    max-height: 70px;
    top: 20px;
    right: 20px;
    text-align: right;
    line-height: 14px;
}
.kpi-form-paper-portrait h1 {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    line-height: 28px;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
    max-width: 500px;
}
.kpi-form-paper-portrait .mv-main-tabs {
    margin-top: 1.25rem;
}
/*.kpi-form-paper-portrait .kpi-hdr-table .kpi-hdr-table-label {
    background-color: #fbd9a5 !important;
}*/
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-webkit-input-placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-ms-input-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait .bp-overflow-xy-auto {
    border: 0;
}
.kpi-form-paper-portrait table.kpi-dtl-table td, 
.kpi-form-paper-portrait table.kpi-dtl-table th {
    border: 1px solid transparent;
}
.kpi-form-paper-portrait table.kpi-dtl-table td {
    border-bottom: 1px #eee solid;
    border-right: 1px #eee solid;
    background-color: #fff;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr {
    height: 50px;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr th {
    border-top: 1px #eee solid !important;
    border-bottom: 1px #eee solid !important;
    background: #fff !important;
    font-weight: bold;
    font-size: 12px!important;
    background-color: #eee !important;
    color: #99A1B7;
}
.kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a.active:not(.nav-tabs-btn-filter, .nav-tabs-btn-search) {
    border-bottom: 3px solid #1B84FF !important;
    color: #1B84FF;
}
.mv-checklist2-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li.open, 
.mv-checklist2-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a:hover {
    border-bottom: 3px solid #1B84FF;
    color: #1B84FF;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link {
    background-color: transparent;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link:before {
    height: 2px;
    top: -1px;
    left: -1px;
    right: -1px;
    content: '';
    position: absolute;
}
.kpi-notfocus-readonly-input {
    cursor: text!important;
    background-color: inherit!important;
}
.kpi-notfocus-readonly-input:focus, 
table.table td.stretchInput input.kpi-notfocus-readonly-input:not(.select2-input):not(.error):focus {
    border: 1px solid transparent !important;
}
input.kpi-notfocus-readonly-input::-webkit-input-placeholder {
    color: transparent !important;
}
input.kpi-notfocus-readonly-input:-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input:-ms-input-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::placeholder {
    color: transparent !important;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice {
    background-color: #fff;
    background-image: none;
    border: 1px solid #fff;
    cursor: text;
    padding-left: 3px;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice .select2-arrow {
    display: none;
}
.kpi-form-paper-portrait table.table td.stretchInput input[type="text"]:not(.select2-input):not(.error):focus, 
.kpi-form-paper-portrait table.table td.stretchInput textarea:not(.error):focus {
    border: 1px solid #e9a22f !important;
}
.mv-rows-title .label-colon {
    display: none;
}
.mv-inline-field {
    display: inline-block;
    padding-left: 10px;
}
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control), 
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-row,
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label, 
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-input {
    display: block;
}
.mv-checklist2-render-parent .mv-hdr-label-control, 
.mv-checklist2-render-parent .mv-hdr-label-control-row,
.mv-checklist2-render-parent .mv-hdr-label-control-label, 
.mv-checklist2-render-parent .mv-hdr-label-control-input {
    border: none;
    padding-left: 0;
    padding-right: 0;
    background-color: transparent;
}
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label {
    width: 100%!important;
}
.mv-checklist2-render-parent .mv-hdr-label-control-label {
    text-align: left;
    font-weight: bold;
}
.mv-checklist2-render-parent .mv-rows-title-label {
    font-size: 14px;
    color: #555;
}
.mv-checklist2-render-parent .mv-hdr-label-control-label label {
    color: #666;
}
.mv-checklist2-render-parent .kpidv-data-filter-col .list-group {
    background-color:transparent;
}
.mv-checklist2-render-parent .mv-hdr-label-control-label label .label-colon {
    display: none;
}
.ui-dialog .mv-checklist2-render-parent .ws-area .ws-page-content-wrapper .ws-page-content {
    padding: 0px!important;
}
.mv-checklist2-render-parent .mv-hdr-label-control-input input.form-control {
    height: 32px!important;
    min-height: 32px!important;
    /*background-color: #fafafa;*/
    border-radius: 6px!important;
    border: 1px #eee solid;
    padding: 7px 10px!important;
}
.mv-checklist2-render-parent .mv-hdr-label-control-input textarea.form-control {
    /*background-color: #fafafa;*/
    border-radius: 6px!important;
    border: 1px #eee solid;
    padding: 7px 10px!important;
}
.mv-checklist2-render-parent .mv-hdr-label-control {
    margin-bottom: 10px;
}
.mv-checklist2-render-parent .nav-group-sub-mv-opened .nav-group-sub {
    display: flex;
}
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control .select2-choice,
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control.select2-container-active .select2-choice,
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control.select2-container-active .select2-choices {
    border: 1px #eee solid;
    height: 32px;
    padding-top: 2px;
    /*background-color: #fafafa;*/
}
.mv-checklist2-render-parent .mv-hdr-label-control-input .select2-container .select2-choice .select2-arrow {
    /*background-color: #fafafa;*/
}
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .table-scrollable>.table, 
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .tabbable-line>.tab-content {
    background-color: transparent;
}
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .tabbable-line>.nav-tabs {
    border: none;
    margin: 0px;
    background: transparent;
}
.mv-checklist2-render-parent .viewer-container > .center-sidebar > .row > .content-wrapper > .row, 
.mv-checklist2-render-parent .viewer-container > .center-sidebar > .row > .top-sidebar-content > .xs-form.row {
    margin: 0;
}
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .bp-header-param ul.bp-icon-selection {
    max-height: 360px;
}
.mv_card_status_widget {
    display: inline-block;
    width: 90px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}

.mv_card_status_widget:hover .no-dataview {
    display: block !important;
}
.mv_card_status_widget {
    .card {
       margin-bottom: 0;
        border-radius: 10px !important;
        padding: 0 !important;
    }
    .card-body .card-img {
        border-radius: 0;
        border-bottom: 1px #eee solid;
    }
}

.mv_card_status_widget h5 {
    display: block;
    padding: 0 10px;
    font-size: 12px;
    color: #333;
    line-height: 20px;
    text-align: center;
    overflow: hidden;
}

.mv_card_status_widget_main .nav-link:not(.disabled):hover {
    background-color: transparent !important;
}

.mv_card_status_widget_main {
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: center;

    .mv_card_status_widget.active > .card,
    .mv_card_status_widget:hover > .card {
        border-color: #2196f3;
    }

    .left-sidewidget {
        /* gap: 16px;
        display: inline-flex;
        flex-wrap: wrap;
        justify-content: center;
        width: 280px; */
        width: 270px;
        max-height: max-content;
        min-height: max-content;
        min-width: 200px;
    }

    .position-1 {
        margin: 0;
        max-height: 47px;
        margin-bottom: 16px;
        .badge {
            font-size: 20px;
            color: #282A30;
            background: #F9F8F9;
            border-radius: 4px;
            padding: 12px;
            
        }
    }

    .position-2 {
        font-size: 13px; 
        color: #282A30;
        margin: 0;
        max-height: 47px;
        margin-bottom: 10px;
    }
    .position-3 {
        font-size: 12px;
        color: #707579;
        margin: 0;
        max-height: 47px;
        margin-bottom: 16px;
    }
    .position-4 {
        font-size: 11px;
        margin: 0;
        max-height: 47px;
        color:#fff;
        border-radius: 10px;
    }

    .right-sidewidget {
        height: 180px !important;
    }
}
</style>

<?php require getBasePath() . 'middleware/views/form/kpi/indicator/checklist/cardscripts.php'; ?>

<script type="text/javascript">
</script>