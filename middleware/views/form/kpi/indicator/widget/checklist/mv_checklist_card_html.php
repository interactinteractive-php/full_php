            <?php 
            $relationTabList = Arr::groupByArrayByNullKey($this->relationList, 'TAB_NAME'); 
            $tabId = 1;
            foreach ($relationTabList as $tabName => $tabRow) {
                if ($tabName != 'яяяrow') { 
            ?>                    
                <div class="d-flex mv_checklist_card_html">
                    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md pr-2" style="width:100%">
                        <div class="sidebar-content">

                            <div class="card">
                                <div class="card-body mv-checklist-menu mv-checklist-card-menu" style="background: #F9F9F9;">
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
                                                            <div class="card" style="width:100%;background: #fff;">
                                                                <div class="card-body p-2 d-flex">
                                                                    <div class="p-1">
                                                                        <div class="w-100 pull-left position-1 ">
                                                                            <span class="badge badge-warning badge-icon" style="padding: 15px;border: 1px solid #e6e6e6;"><i style="color:#54565e !important;margin-right: 2px;" class="fa '.($row['ICON'] ? $row['ICON'] : 'fa-suitcase').'"></i></span>
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
                                                        echo '<ul class="nav nav-group-sub d-flex justify-content-center px-5" style="gap: 20px;">';
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
            <?php 
                $tabId ++;
                }
            }                    
            ?>        

<style>
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
            padding: 12px !important;
            
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
        max-height: 51px;
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